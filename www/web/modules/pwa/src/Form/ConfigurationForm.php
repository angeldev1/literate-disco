<?php

namespace Drupal\pwa\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\file\Entity\File;
use Drupal\pwa\ManifestInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * Class ConfigurationForm.
 *
 * @package Drupal\pwa\Form
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * The manifest service.
   *
   * @var use Drupal\pwa\ManifestInterface
   */
  protected $manifest;

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ManifestInterface $manifest) {
    parent::__construct($config_factory);

    $this->manifest = $manifest;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('pwa.manifest')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pwa_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $host = $this->getRequest()->server->get('HTTP_HOST');
    $files_path = file_create_url("public://pwa") . '/';
    if (substr($files_path, 0, 7) == 'http://') {
      $files_path = str_replace('http://', '', $files_path);
    }
    elseif (substr($files_path, 0, 8) == 'https://') {
      $files_path = str_replace('https://', '', $files_path);
    }
    if (substr($files_path, 0, 4) == 'www.') {
      $files_path = str_replace('www.', '', $files_path);
    }
    $host = $this->getRequest()->server->get('HTTP_HOST');
    if (substr($files_path, 0, strlen($host)) == $host) {
      $files_path = str_replace($host, '', $files_path);
    }
    $wrapper = \Drupal::service('stream_wrapper_manager')->getViaScheme(file_default_scheme());
    $realpath = \Drupal::service('file_system')->realpath(file_default_scheme() . "://");

    $config = $this->config('pwa.config');

    $form['manifest'] = [
      '#type' => 'details',
      '#title' => $this->t('Manifest'),
      '#open' => TRUE,
    ];

    $form['manifest']['name'] = [
      "#type" => 'textfield',
      '#title' => $this->t('Web app name'),
      '#description' => $this->t("The name for the application that needs to be displayed to the user."),
      '#default_value' => $config->get('site_name'),
      '#required' => TRUE,
      "#maxlength" => 55,
      '#size' => 60,
    ];

    $form['manifest']['short_name'] = [
      "#type" => 'textfield',
      "#title" => $this->t('Short name'),
      "#description" => $this->t("A short application name, this one gets displayed on the user's homescreen."),
      '#default_value' => $config->get('short_name'),
      '#required' => TRUE,
      '#maxlength' => 25,
      '#size' => 30,
    ];
	
    $form['manifest']['lang'] = [
      "#type" => 'textfield',
      "#title" => $this->t('Lang'),
      "#description" => $this->t('The default language of the manifest.'),
      '#default_value' => $config->get('lang'),
      '#required' => TRUE,
      '#maxlength' => 25,
      '#size' => 30,
    ];

    $form['manifest']['description'] = [
      "#type" => 'textfield',
      "#title" => $this->t('Description'),
      "#description" => $this->t('The description of your PWA.'),
      '#default_value' => $config->get('description'),
      '#maxlength' => 255,
      '#size' => 60,
    ];
  
    $form['manifest']['start_url'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Start URL'),
      '#description' => $this->t('Start URL.'),
      '#default_value' => $config->get('start_url'),
      '#rows' => 1
    ];

    $form['manifest']['theme_color'] = [
      "#type" => 'color',
      "#title" => $this->t('Theme color'),
      "#description" => $this->t('This color sometimes affects how the application is displayed by the OS.'),
      '#default_value' => $config->get('theme_color'),
      '#required' => TRUE,
    ];

    $form['manifest']['background_color'] = [
      "#type" => 'color',
      "#title" => $this->t('Background color'),
      "#description" => $this->t('This color gets shown as the background when the application is launched'),
      '#default_value' => $config->get('background_color'),
      '#required' => TRUE,
    ];

    $id = $this->getDisplayValue($config->get('display'), TRUE);

    $form['manifest']['display'] = [
      "#type" => 'select',
      "#title" => $this->t('Display type'),
      "#description" => $this->t('This determines which UI elements from the OS are displayed.'),
      "#options" => [
        '1' => $this->t('fullscreen'),
        '2' => $this->t('standalone'),
        '3' => $this->t('minimal-ui'),
        '4' => $this->t('browser'),
      ],
      '#default_value' => $id,
      '#required' => TRUE,
    ];

    $validators = [
      'file_validate_extensions' => ['png'],
      'file_validate_image_resolution' => ['512x512', '512x512'],
    ];

    $form['manifest']['default_image'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use the theme image'),
      "#description" => $this->t('This depends on the logo that the theme generates'),
      "#default_value" => $config->get('default_image'),
    ];

    $form['manifest']['images'] = [
      '#type' => 'fieldset',
      '#states' => [
        'invisible' => [
          ':input[name="default_image"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['manifest']['images']['image'] = [
      '#type' => 'managed_file',
      '#name' => 'image',
      '#title' => $this->t('Image'),
      '#size' => 20,
      '#description' => $this->t('This image is your application icon. (png files only, format: (512x512)'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://pwa/',
    ];

    $bobTheHTMLBuilder = '<label>Current Image:</label> <br/> <img src="' . $config->get('image') . '" width="200"/>';
    if ($config->get('default_image') == 0) {
      $form['manifest']['images']['current_image'] = [
        '#markup' => $bobTheHTMLBuilder,
        '#name' => 'current image',
        '#id' => 'current_image',
      ];
    }

    $form['service_worker'] = [
      '#type' => 'details',
      '#title' => $this->t('Service worker'),
      '#open' => TRUE,
    ];

    $form['service_worker']['urls_to_cache'] = [
      '#type' => 'textarea',
      '#title' => $this->t('URLs to cache on install'),
      '#description' => $this->t('These will serve the page offline even if they have not been visited, try to limit the ammount of URLs here so the user is not downloading too much on their first visit. Make sure the URL is not a 404. Make sure are these are relative URLs tokens not supported.'),
      '#default_value' => $config->get('urls_to_cache'),
      '#rows' => 7
    ];

    $form['service_worker']['urls_to_exclude'] = [
      '#type' => 'textarea',
      '#title' => $this->t('URLs to exclude'),
      '#description' => $this->t('Takes a regex, these URLs will use network-only, default config should be, admin/.* and user/reset/.*.'),
      '#default_value' => $config->get('urls_to_exclude'),
      '#rows' => 7
    ];

    $form['service_worker']['cache_version'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cache version'),
      '#description' => $this->t('Changing this number will invalidate all Service Worker caches. Use it when assets have significantly changed or if you want to force a cache refresh for all clients.'),
      '#size' => 5,
      '#default_value' => $config->get('cache_version') ?: 1,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   *
   * function converts an id to a display string or a string to an id
   *
   * @param $value
   * @param boolean $needId
   *
   * @return int|string
   */
  private function getDisplayValue($value, $needId) {
    if ($needId) {
      $id = 1;
      switch ($value) {
        case 'standalone':
          $id = 2;
          break;
        case 'minimal-ui':
          $id = 3;
          break;
        case 'browser':
          $id = 4;
          break;
      }
      return $id;
    }
    else {
      $display = '';
      switch ($value) {
        case 1:
          $display = 'fullscreen';
          break;
        case 2:
          $display = 'standalone';
          break;
        case 3:
          $display = 'minimal-ui';
          break;
        case 4:
          $display = 'browser';
          break;
      }
    }
    return $display;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $default_image = $form_state->getValue('default_image');
    $img = $form_state->getValue(['image', 0]);
    $config = $this->config('pwa.config');

    if ($config->get('default_image') && !$default_image && !isset($img)) {
      $form_state->setErrorByName('image', $this->t('Upload a image, or chose the theme image.'));
    }

    // Check urls format
    $urls_to_cache = pwa_str_to_list($form_state->getValue('urls_to_cache'));
    foreach ($urls_to_cache as $page) {
      // If link is internal.
      try {
         $url = Url::fromUserInput($page);
       }
       catch(\Exception $e) {
         $form_state->setErrorByName('urls_to_cache', $this->t("The user-entered URL '{$page}' must begin with a '/', '?', or '#'."));
       }
       // If link does not exist.
       if (isset($url) && !$url->isRouted()) {
         $form_state->setErrorByName('urls_to_cache', $this->t('Error "' . $page . '" URL to Cache is a 404.'));
       }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('pwa.config');

    $display = $this->getDisplayValue($form_state->getValue('display'), FALSE);

    $fid = $form_state->getValue(['image', 0]);
    $default_image = $form_state->getValue('default_image');

    if ($config->get('default_image') == 0) {
      if (isset($fid) || $default_image == 1) {
        $this->manifest->deleteImage();
      }
    }

    $configTheme = $this->config('system.theme');
    $nameOfDefaultTheme = $configTheme->get('default');

    // Get image from theme
    if ($default_image) {
      $theme_image = theme_get_setting('logo.path', $nameOfDefaultTheme);
      if (substr($theme_image, strlen($theme_image) - 3, 3) != 'png') {
        $this->messenger()
          ->addWarning($this->t('The theme image is not a .png file, your users may not be able to add this website to the homescreen.'));
      }
      $image_size = getimagesize(getcwd() . $theme_image);
      if ($image_size[0] == $image_size[1]) {
        $this->messenger()
          ->addWarning($this->t('The theme image is not a square, your application image maybe altered (recommended size: 512x512).'));
      }
    }

    // Save new config data
    $config
      ->set('site_name', $form_state->getValue('name'))
      ->set('short_name', $form_state->getValue('short_name'))
      ->set('theme_color', $form_state->getValue('theme_color'))
      ->set('background_color', $form_state->getValue('background_color'))
      ->set('description', $form_state->getValue('description'))
      ->set('lang', $form_state->getValue('lang'))
      ->set('display', $display)
      ->set('default_image', $default_image)
      ->set('start_url', $form_state->getValue('start_url'))
      ->set('urls_to_cache', $form_state->getValue('urls_to_cache'))
      ->set('urls_to_exclude', $form_state->getValue('urls_to_exclude'))
      ->set('cache_version', $form_state->getValue('cache_version'))
      ->save();

    // Save image if exists
    if (!empty($fid)) {
      $file = File::load($fid);

      $file_usage = \Drupal::service('file.usage');
      $file->setPermanent();
      $file->save();

      $file_usage->add($file, 'PWA', 'PWA', $this->currentUser()->id());

      // Save new image.
      $wrapper = \Drupal::service('stream_wrapper_manager')->getViaScheme(file_default_scheme());
      $files_path = '/' . $wrapper->basePath() . '/pwa/';
      $file_uri = $files_path . $file->getFilename();

      $file_path = $wrapper->realpath() . '/pwa/' . $file->getFilename();

      $config->set('image', $file_uri)->save();

      // for image_small
      $newSize = 192;
      $oldSize = 512;

      $src = imagecreatefrompng($file_path);
      $dst = imagecreatetruecolor($newSize, $newSize);

      // Make transparent background.
      $color = imagecolorallocatealpha($dst, 0, 0, 0, 127);
      imagefill($dst, 0, 0, $color);
      imagesavealpha($dst, TRUE);

      imagecopyresampled($dst, $src, 0, 0, 0, 0, $newSize, $newSize, $oldSize, $oldSize);
      $path_to_copy = $file_path . 'copy.png';
      $stream = fopen($path_to_copy, 'w+');
      if ($stream == TRUE) {
        imagepng($dst, $stream);
        $config->set('image_small', $file_uri . 'copy.png')
          ->save();
      }

      // for image_very_small
      $newSize = 144;
      $oldSize = 512;

      $src = imagecreatefrompng($file_path);
      $dst = imagecreatetruecolor($newSize, $newSize);

      // Make transparent background.
      $color = imagecolorallocatealpha($dst, 0, 0, 0, 127);
      imagefill($dst, 0, 0, $color);
      imagesavealpha($dst, TRUE);

      imagecopyresampled($dst, $src, 0, 0, 0, 0, $newSize, $newSize, $oldSize, $oldSize);
      $path_to_copy = $file_path . 'copy2.png';
      if ($stream = fopen($path_to_copy, 'w+')) {
        imagepng($dst, $stream);
        $config->set('image_very_small', $file_uri . 'copy2.png')
          ->save();
      }
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * @return config settings.
   */
  protected function getEditableConfigNames() {
    return ['pwa.config'];
  }
}
