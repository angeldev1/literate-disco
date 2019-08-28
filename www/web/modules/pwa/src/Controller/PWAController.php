<?php
/**
 * @file
 * Replace values in serviceworker.js
 */

namespace Drupal\pwa\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\State\StateInterface;
use Drupal\pwa\ManifestInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Url;

/**
 * Default controller for the pwa module.
 */
class PWAController implements ContainerInjectionInterface {

  /**
   * The manifest service.
   *
   * @var \Drupal\pwa\ManifestInterface
   */
  private $manifest;

  /**
   * The state.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  private $state;

  /**
   * Constructor.
   *
   * @param \Drupal\pwa\ManifestInterface $manifest
   *   The manifest service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The system state.
   */
  public function __construct(ManifestInterface $manifest, StateInterface $state) {
    $this->manifest = $manifest;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('pwa.manifest'),
      $container->get('state')
    );
  }

  /**
   * Fetch the manifest content.
   */
  public function pwa_manifest() {
    return new Response($this->manifest->getOutput(), 200, [
      'Content-Type' => 'application/json',
    ]);
  }

  // Fetch all resources.

  public function _pwa_fetch_offline_page_resources($pages) {

    // For each Drupal path, request the HTML response and parse any CSS/JS found
    // within the HTML. Since this is the pure HTML response, any DOM modifications
    // that trigger new requests cannot be accounted for. An example would be an
    // asynchronously-loaded webfont.

    $resources = [];

    foreach ($pages as $page) {
      try {
        // URL is validated as internal in ConfigurationForm.php.
        $url = Url::fromUserInput($page, ['absolute' => TRUE])->toString();
        $response = \Drupal::httpClient()->get($url, array('headers' => array('Accept' => 'text/plain')));
        $data = $response->getBody();
        if (empty($data)) {
          continue;
        }
      }
      catch (\Exception $e) {
        continue;
      }

      // Get all DOM data.
      $dom = new \DOMDocument();
      @$dom->loadHTML($data);

      $xpath = new \DOMXPath($dom);
      foreach ($xpath->query('//script[@src]') as $script) {
        $resources[] = $script->getAttribute('src');
      }
      foreach ($xpath->query('//link[@rel="stylesheet"][@href]') as $stylesheet) {
        $resources[] = $stylesheet->getAttribute('href');
      }
      foreach ($xpath->query('//style[@media="all" or @media="screen"]') as $stylesheets) {
        preg_match_all(
          "#(/(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
          ' ' . $stylesheets->textContent,
          $matches
        );
        $resources = array_merge($resources, $matches[0]);
      }
      foreach ($xpath->query('//img[@src]') as $image) {
        $resources[] = $image->getAttribute('src');
      }
    }

    $dedupe = array_unique($resources);
    $dedupe = array_values($dedupe);
    return $dedupe;
  }

  /**
   * Replace the serviceworker file with variables from Drupal config.
   */
  public function pwa_serviceworker_file_data() {
    $path = drupal_get_path('module', 'pwa');
	
    $sw = file_get_contents($path . '/js/serviceworker.js');

    // Get urls from config
    $cacheUrls = pwa_str_to_list(\Drupal::config('pwa.config')->get('urls_to_cache'));
    $exclude_cache_url = pwa_str_to_list(\Drupal::config('pwa.config')->get('urls_to_exclude'));

    // Get icons list and convert into array of sources.
    $manifest = Json::decode($this->manifest->getOutput());
    $cacheIcons = [];
    if (!empty($manifest['icons'])) {
      foreach($manifest['icons'] as $icon) {
        $cacheIcons[] = $icon['src'];
      }
    }

    // Combine URLs from admin UI with manifest icons.
    $cacheWhitelist = array_merge($cacheUrls, $cacheIcons);

    // Look up module release from package info.
    $pwa_module_info = system_get_info('module', 'pwa');
    $pwa_module_version = $pwa_module_info['version'];

    // Packaging script will always provide the published module version. Checking
    // for NULL is only so maintainers have something predictable to test against.
    if ($pwa_module_version == null) {
      $pwa_module_version = '8.x-1.x-dev';
    }

    // Set up placeholders.
    $replace = [
      '[/*cacheUrls*/]' => Json::encode($cacheWhitelist),
      '[/*exclude_cache_url*/]' => Json::encode($exclude_cache_url),
      '[/*modulePath*/]' => '/'. drupal_get_path('module', 'pwa'),
      '1/*cacheVersion*/' => '\'' . $pwa_module_version . '-v' . (\Drupal::config('pwa.config')->get('cache_version') ?: 1) . '\'',
    ];
    if (!empty($cacheUrls)) {
      $replace['[/*cacheUrlsAssets*/]'] = Json::encode($this->_pwa_fetch_offline_page_resources($cacheUrls));
    }

    // Fill placeholders and return final file.
    $data = str_replace(array_keys($replace), array_values($replace), $sw);

    return new Response($data, 200, [
      'Content-Type' => 'application/javascript',
      'Service-Worker-Allowed' => '/',
    ]);
  }
  
  
  /**
   * Phone home uninstall
   * Applied from patch https://www.drupal.org/project/pwa/issues/2913023#comment-12819311 
   */
    public function pwa_module_active_page() {
      return [
        '#tag' => 'h1',
        '#value' => 'PWA module is installed.',
        '#attributes' => [
          'data-drupal-pwa-active' => TRUE,
        ],
      ];
    }

/**
 * Provide a render array for offline pages.
 *
 * @return array
 *   The render array.
 */
  public function pwa_offline_page() {
    return [
      '#theme' => 'offline',
    ];
  }
}
