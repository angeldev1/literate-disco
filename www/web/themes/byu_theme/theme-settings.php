<?php

function byu_theme_form_system_theme_settings_alter(&$form, Drupal\Core\Form\FormStateInterface $form_state) {


  // ----------- GENERAL -----------
  $form['options']['general'] = [
    '#type' => 'fieldset',
    '#title' => t('General'),
  ];
  // Loader Setting
  $form['options']['general']['loader'] = [
    '#type' => 'checkbox',
    '#title' => 'On/Off Loader',
    '#default_value' => theme_get_setting('loader'),
  ];

  // ----------- DESIGN -----------
  $form['options']['design'] = [
    '#type' => 'vertical_tabs',
    '#title' => 'BYU Style Options: Design Your Site',
    '#open' => FALSE,
  ];
  // component versions
  $form['byu_components'] = [
    '#type' => 'details',
    '#title' => t('BYU Component Settings'),
    '#group' => 'design',
  ];
  $form['byu_components']['components_version'] = [
    '#type' => 'select',
    '#title' => t('Which version of BYU Web components do you want to load?'),
    '#description' => t('1.x.x. is recommended because it will incorporate all new features and bug fixes without big changes that may require manual adjustments.'),
    '#options' => [
      '1.x.x' => t('1.x.x - Get new features & bug fixes'),
      '1.2.x' => t('1.2.x - Stay on version 1.2 and only get bug fixes, no features'),
      'latest' => t('Latest - every update, including major version changes.'),
      'master' => t('Master - Latest development, Use for Development & Testing.'),
      'none' => t('Don\'t load components. This is primarily for testing purposes.'),
    ],
    '#default_value' => theme_get_setting('components_version'),
  ];

  // Fonts
  $form['fonts'] = [
    '#type' => 'details',
    '#title' => t('BYU Font Settings'),
    '#group' => 'design',
  ];
  $form['fonts']['google_info'] = [
    '#markup' => '<p> These are the google font alternative for Sentinel, Vitesse, Gotham, and Ringside. They may load faster, and the BYU Paid fonts from Hoeffler may be phased out in the future.</p>',
  ];
  $form['fonts']['font_package'] = [
    '#type' => 'select',
    '#title' => t('Which font package do you want to load?'),
    '#description' => t('If you want Sentinel to show as an option below, select the FULL font package. Save this page and return to set the other settings.'),
    '#options' => [
      'fonts-basic' => t('Basic: Vitesse, Gotham, Ringside'),
      'fonts-full' => t('Full: Vitesse, Gotham, Sentinel & a few others'),
    ],
    '#default_value' => theme_get_setting('font_package'),
  ];
  $form['fonts']['fontawesome_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Load FontAwesome 4 library'),
    '#default_value' => theme_get_setting('fontawesome_use'),
  ];
  $form['fonts']['libreberville_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Load Libre Baskerville font'),
    '#default_value' => theme_get_setting('libreberville_use'),
    '#description' => t('This serif font is a google font alternative, and may load faster. Click Save, and then this font will be available in the options below.'),
  ];
  $form['fonts']['sourcesans_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Load Source Sans font'),
    '#default_value' => theme_get_setting('sourcesans_use'),
    '#description' => t('This sans-serif font is a google font alternative, and may load faster. Click Save, and then this font will be available in the options below.'),
  ];
  $form['fonts']['domine_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Use the google alternatives to Sentinel (Domine).'),
    '#default_value' => theme_get_setting('domine_use'),
    '#description' => t('See <a href="https://byu.box.com/s/5rjf18yczhzku8cxy4g1kn28812eriow">here</a> for example.'),
  ];
  $form['fonts']['montserrat_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Use the google alternatives to Gotham (Montserrat).'),
    '#default_value' => theme_get_setting('montserrat_use'),
    '#description' => t('See <a href="https://byu.box.com/s/x7by6y123p68dpnfwegqo08llf1o1ulw">here</a> for example.'),
  ];
  $form['fonts']['zilla_slab_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Use the google alternatives to Vitesse (Zilla Slab).'),
    '#default_value' => theme_get_setting('zilla_slab_use'),
    '#description' => t('See <a href="https://byu.box.com/s/jsuskgf2f3ghc2qa8idj3az3dk8r7me0">here</a> for example.'),
  ];
  $form['fonts']['roboto_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Use the google alternatives to Ringside (Roboto).'),
    '#default_value' => theme_get_setting('roboto_use'),
    '#description' => t('See <a href="https://byu.box.com/s/kk3iv6hzn7xwagaiv1jdiyytbj0n3i99">here</a> for example.'),
  ];
  $sentinel_en = (theme_get_setting('font_package') == 'fonts-full');
  $libre_en = theme_get_setting('libreberville_use');
  $sourcesans_en = theme_get_setting('sourcesans_use');
  $domine_use = theme_get_setting('domine_use');
  $montserrat_use = theme_get_setting('montserrat_use');
  $zilla_slab_use = theme_get_setting('zilla_slab_use');
  $roboto_use = theme_get_setting('roboto_use');

  $fontOptions = [
    "vitesse" => "Vitesse",
    "gotham" => "Gotham",
    "ringside" => "Ringside",
  ];
  if ($sentinel_en == TRUE) {
    $fontOptions['sentinel'] = 'Sentinel';
  }
  if ($libre_en == TRUE) {
    $fontOptions['libreb'] = 'Libre Baskerville';
  }
  if ($sourcesans_en == TRUE) {
    $fontOptions['sourcesans'] = 'Source Sans';
  }
  if ($domine_use == TRUE) {
    $fontOptions['domine'] = 'Domine';
  }
  if ($montserrat_use == TRUE) {
    $fontOptions['montserrat'] = 'Montserrat';
  }
  if ($zilla_slab_use == TRUE) {
    $fontOptions['zilla_slab'] = 'Zilla Slab';
  }
  if ($roboto_use == TRUE) {
    $fontOptions['roboto'] = 'Roboto';
  }

  $form['fonts']['font_one'] = [
    '#type' => 'select',
    '#title' => t('H1 Font'),
    '#options' => $fontOptions,
    '#default_value' => theme_get_setting('font_one'),
  ];
  $form['fonts']['font_one_color'] = [
    '#type' => 'select',
    '#title' => t('H1 Color'),
    '#options' => [
      'h1-navy' => t('Navy'),
      'h1-gray' => t('Gray'),
      'h1-black' => t('Black'),
    ],
    '#default_value' => theme_get_setting('font_one_color'),
  ];
  $form['fonts']['font_two'] = [
    '#type' => 'select',
    '#title' => t('H2 Font'),
    '#options' => $fontOptions,
    '#default_value' => theme_get_setting('font_two'),
  ];
  $form['fonts']['font_two_color'] = [
    '#type' => 'select',
    '#title' => t('H2 Color'),
    '#options' => [
      'h2-navy' => t('Navy'),
      'h2-gray' => t('Gray'),
      'h2-black' => t('Black'),
    ],
    '#default_value' => theme_get_setting('font_two_color'),
  ];
  $form['fonts']['font_three'] = [
    '#type' => 'select',
    '#title' => t('H3 Font'),
    '#options' => $fontOptions,
    '#default_value' => theme_get_setting('font_three'),
  ];
  $form['fonts']['font_three_color'] = [
    '#type' => 'select',
    '#title' => t('H3 Color'),
    '#options' => [
      'h3-navy' => t('Navy'),
      'h3-gray' => t('Gray'),
      'h3-black' => t('Black'),
    ],
    '#default_value' => theme_get_setting('font_three_color'),
  ];

  $form['fonts']['font_four'] = [
    '#type' => 'select',
    '#title' => t('H4 Font'),
    '#options' => $fontOptions,
    '#default_value' => theme_get_setting('font_four'),
  ];
  $form['fonts']['font_four_color'] = [
    '#type' => 'select',
    '#title' => t('H4 Color'),
    '#options' => [
      'h4-navy' => t('Navy'),
      'h4-gray' => t('Gray'),
      'h4-black' => t('Black'),
    ],
    '#default_value' => theme_get_setting('font_four_color'),
  ];
  $form['fonts']['font_five'] = [
    '#type' => 'select',
    '#title' => t('H5 Font'),
    '#options' => $fontOptions,
    '#default_value' => theme_get_setting('font_five'),
  ];
  $form['fonts']['font_five_color'] = [
    '#type' => 'select',
    '#title' => t('H5 Color'),
    '#options' => [
      'h5-navy' => t('Navy'),
      'h5-gray' => t('Gray'),
      'h5-black' => t('Black'),
    ],
    '#default_value' => theme_get_setting('font_five_color'),
  ];

  $pFontOptions = [
    'default' => t('Default'),
    'ringside' => t('Ringside (sans-serif)'),
    'gotham' => t('Gotham (san-serif)'),
  ];
  if ($libre_en == TRUE) {
    $pFontOptions['libreb'] = 'Libre Baskerville';
  }
  if ($sourcesans_en == TRUE) {
    $pFontOptions['sourcesans'] = 'Source Sans';
  }
  if ($domine_use == TRUE) {
    $pFontOptions['domine'] = 'Domine';
  }
  if ($montserrat_use == TRUE) {
    $pFontOptions['montserrat'] = 'Montserrat';
  }
  if ($zilla_slab_use == TRUE) {
    $pFontOptions['zilla_slab'] = 'Zilla Slab';
  }
  if ($roboto_use == TRUE) {
    $pFontOptions['roboto'] = 'Roboto';
  }
  $form['fonts']['p_font'] = [
    '#type' => 'select',
    '#title' => t('Paragraph Font'),
    '#options' => $pFontOptions,
    '#default_value' => theme_get_setting('p_font'),
  ];

  // Header Option
  $form['header_style'] = [
    '#type' => 'details',
    '#title' => 'BYU Header',
    '#group' => 'design',
  ];
  $form['header_style']['subtitle'] = [
    '#type' => 'fieldset',
    '#title' => 'Subtitle Settings',
    '#open' => FALSE,
  ];
  $form['header_style']['subtitle']['subtitle_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Use Subtitle'),
    '#default_value' => theme_get_setting('subtitle_use'),
    '#description' => t("Add Sub Title to the site header."),
  ];
  $form['header_style']['subtitle']['subtitle_above'] = [
    '#type' => 'checkbox',
    '#title' => t('Subtitle Above'),
    '#default_value' => theme_get_setting('subtitle_above'),
    '#description' => t("Place the subtitle above the Main Title."),
  ];
  $form['header_style']['subtitle']['subtitle_italic'] = [
    '#type' => 'checkbox',
    '#title' => t('Subtitle Italic'),
    '#default_value' => theme_get_setting('subtitle_italic'),
    '#description' => t("Italicize the subtitle."),
  ];
  $form['header_style']['subtitle']['subtitle_text'] = [
    '#type' => 'textfield',
    '#title' => t('Site Subtitle'),
    '#default_value' => theme_get_setting('subtitle_text'),
    '#description' => t("The subtitle appears below (or above) the Main Title."),
  ];


  $form['header_style']['user_info'] = [
    '#type' => 'fieldset',
    '#title' => 'User Information & SIgn In Settings',
    '#open' => TRUE,
  ];
  $form['header_style']['user_info']['login_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Display Sign In/Out block'),
    '#default_value' => theme_get_setting('login_use'),
    '#description' => t("Choose to display a Sign In link."),
  ];

  $form['header_style']['user_info']['login_url'] = [
    '#type' => 'textfield',
    '#title' => t('Login Url'),
    '#default_value' => theme_get_setting('login_url'),
    '#description' => t("The subtitle appears below (or above) the Main Title. Default value if blank is '../user'. If you are using the CAS module, use '../cas'."),
  ];
  $form['header_style']['user_info']['logout_url'] = [
    '#type' => 'textfield',
    '#title' => t('Logout Url'),
    '#default_value' => theme_get_setting('logout_url'),
    '#description' => t("The subtitle appears below (or above) the Main Title.  Default value if blank is '../user/logout'. If you are using the CAS module, use '../caslogout'."),
  ];
  $form['header_style']['user_info']['myaccount_use'] = [
    '#type' => 'checkbox',
    '#title' => t('My Account'),
    '#default_value' => theme_get_setting('myaccount_use'),
    '#description' => t("Link the username / name display to a user account page."),
  ];
  $form['header_style']['user_info']['myaccount_url'] = [
    '#type' => 'textfield',
    '#title' => t('My Account Url'),
    '#default_value' => theme_get_setting('myaccount_url'),
    //        '#default_value' => variable_get('myaccount_url', '../user/'),
    //        '#default_value' => '../user',
    '#description' => t("Provide the relative url for my account. Default value if blank is '../user'."),
  ];


  /* ------ Search Settings ------ */
  $form['header_style']['search'] = [
    '#type' => 'fieldset',
    '#title' => 'Search Settings',
    '#open' => FALSE,
  ];
  $form['header_style']['search']['search_use'] = [
    '#type' => 'checkbox',
    '#title' => t('Display a Search bar in the header'),
    '#default_value' => theme_get_setting('search_use'),
    //        '#description'   => t("Choose to display a Sign In link."),
  ];
  $form['header_style']['search']['search_input'] = [
    '#type' => 'textfield',
    '#title' => t('Alternate Search Input'),
    '#default_value' => theme_get_setting('search_input'),
    '#description' => t("If you're using a different search module and the component is having difficulty finding the search input, enter the class, id, or attribute name (include the period, hashtag, or brackets) of your search input. This will tell the component exactly what content to search. For example: [data-drupal-selector=\'edit-keys\'] or #my-search-input. Please note that, in Drupal, some IDs change, so make sure you're picking a permanent one. Also note that you should use single quotes instead of double quotes, as this will be wrapped in double quotes inside the component."),
  ];
  $form['header_style']['search']['search_submit'] = [
    '#type' => 'textfield',
    '#title' => t('Alternate Search Submit'),
    '#default_value' => theme_get_setting('search_submit'),
    '#description' => t("If you're using a different search module and the component is having difficulty finding the submit button, enter the class, id, or attribute name (include the period, hashtag, or brackets) of your submit button. This will tell the component exactly what button to trigger. For example: [data-drupal-selector=\'edit-submit\'] or #my-submit-button. Please note that, in Drupal, some IDs change, so make sure you're picking a permanent one. Also note that you should use single quotes instead of double quotes, as this will be wrapped in double quotes inside the component."),
  ];
  $form['header_style']['search']['search_info'] = [
    '#markup' => '<p>For more information on how the search component works, go to <a href="http://2017-components-demo.cdn.byu.edu/byu-search.html">http://2017-components-demo.cdn.byu.edu/byu-search.html</a>.</p>',
  ];

  //Header Actions Settings
  $form['header_style']['header_actions'] = [
    '#type' => 'fieldset',
    '#title' => t('Action Links'),
    '#open' => FALSE,
  ];
  $form['header_style']['header_actions']['actions_info'] = [
    '#markup' => '<p>Action Links are very short links for a unique user action. For instance, this could be an "Apply" link or "Cart".
There should only ever be 2 one-word links or one 2-word link, as this space is minimal. To place these links, go to
the <a href="../admin/structure/block" target="_blank">blocks page</a> and place a block into the Header Actions region.</p><p>Action links
are placed at the bottom of the menu in mobile views. Please make sure your content fits at various breakpoints.</p>',
  ];
  $form['header_style']['header_actions']['actions_bg'] = [
    '#type' => 'select',
    '#title' => t('Action Link Button Style'),
    '#description' => t('If enabled, the site name and main menu will appear in a bar along the top of the page. You will want to make sure that the menu background is set to transparent.'),
    '#options' => [
      'none' => t("No button style"),
      'royal' => t('Royal Blue button'),
    ],
    '#default_value' => theme_get_setting('actions_bg'),
  ];

  //Menu Settings
  $form['header_style']['menu'] = [
    '#type' => 'fieldset',
    '#title' => t('Menu Settings'),
    '#open' => FALSE,
  ];
  $form['header_style']['menu']['menu_disable'] = [
    '#type' => 'checkbox',
    '#title' => t('Disable the menu.'),
    '#default_value' => theme_get_setting('menu_disable'),
    //        '#description'   => t("This allows part of the hero to show through behind the menu."),
  ];
  $form['header_style']['menu']['transparent'] = [
    '#type' => 'checkbox',
    '#title' => t('Make menu background transparent'),
    '#default_value' => theme_get_setting('transparent'),
    '#description' => t("This allows part of the hero to show through behind the menu."),
  ];

  // Hero
  $form['header_style']['hero'] = [
    '#type' => 'fieldset',
    '#title' => t('Hero Settings'),
    '#open' => FALSE,
  ];

  $form['header_style']['hero']['hero_width'] = [
    '#type' => 'select',
    '#title' => t('Hero Space Width'),
    '#options' => [
      0 => t("Full Width (default)"),
      1 => t('Custom Width'),
    ],
    '#description' => t('The custom page width setting is under BYU General Page. See the next section of settings.'),
    '#default_value' => theme_get_setting('hero_width'),
  ];

  $form['header_style']['hero']['hero_vs_menu'] = [
    '#type' => 'select',
    '#title' => t('How do you want the Hero space & Menu to be?'),
    '#default_value' => theme_get_setting('hero_vs_menu'),
    '#description' => t('If enabled, the site name and main menu will appear in a bar along the top of the page. You will want to make sure that the menu background is set to transparent.'),
    '#options' => [
      0 => t("Show hero below the menu (default)"),
      1 => t('Show hero behind menu'),
    ],
  ];

  $form['header_style']['hero']['hero_image_width'] = [
    '#type' => 'checkbox',
    '#title' => t('Make images stretch full width'),
    '#default_value' => theme_get_setting('hero_image_width'),
    '#description' => t("Whether you are using a full width or constrained width hero, use this setting to tell images to expand to the full width of the hero space."),
  ];

  /* ---- General Page settings -- */
  $form['general_page'] = [
    '#type' => 'details',
    '#title' => t('BYU General Page'),
    //        '#collapsible' => TRUE,
    '#group' => 'design',
  ];
  $form['general_page']['full_width'] = [
    '#type' => 'checkbox',
    '#title' => t('Full Width instead of Constrained Width'),
    '#default_value' => theme_get_setting('full_width'),
    '#description' => t("Choose to have all pages extend full width. This applies to BYU Header, page content, and BYU Footer. The hero space has it's own setting for width, and this will not override that."),
  ];

  $form['general_page']['custom_width'] = [
    '#type' => 'textfield',
    '#title' => t('Custom Page Width'),
    '#default_value' => theme_get_setting('custom_width'),
    '#description' => t("Enter the number of pixels you would like. i.e. '1200' fof 1200px. Defaults to 1000px."),
  ];

  $form['general_page']['min_page_height'] = [
    '#type' => 'select',
    '#title' => 'Custom Page Height',
    '#default_value' => theme_get_setting('min_page_height'),
    '#options' => [
      'none' => 'None',
      '300' => '300px',
      '500' => '500px',
    ],
  ];

  $form['general_page']['byu_styles'] = [
    '#type' => 'fieldset',
    '#title' => t('Extra BYU Styles'),
    '#open' => FALSE,
  ];

  $form['general_page']['byu_styles']['byu_styles_info'] = [
    '#markup' => '<p>Enabling these styles doesn\'t necessarily mean the styles will immediately take effect. Most of these styles make byu classes available for use as you choose to apply them.</p>',
  ];

  $form['general_page']['byu_styles']['byu_buttons'] = [
    '#type' => 'checkbox',
    '#title' => t('BYU Button Styles'),
    '#default_value' => theme_get_setting('byu_buttons'),
  ];

  $form['general_page']['byu_styles']['byu_tables'] = [
    '#type' => 'checkbox',
    '#title' => t('BYU Table Styles'),
    '#default_value' => theme_get_setting('byu_tables'),
  ];

  $form['general_page']['byu_styles']['byu_box_shadows'] = [
    '#type' => 'checkbox',
    '#title' => t('BYU Box Shadow Styles'),
    '#default_value' => theme_get_setting('byu_box_shadows'),
  ];

  $form['general_page']['your_css'] = [
    '#type' => 'textarea',
    '#title' => 'Add Your CSS',
    '#default_value' => theme_get_setting('your_css'),
  ];

  // Footer Option
  $form['footer_style'] = [
    '#type' => 'details',
    '#title' => 'BYU Footer',
    '#group' => 'design',
  ];

  // Footer Option
  $form['footer_style']['sticky_footer'] = [
    '#type' => 'fieldset',
    '#title' => t('Sticky Footer Settings'),
    '#open' => FALSE,
  ];
  $form['footer_style']['sticky_footer']['footer_sticky'] = [
    '#type' => 'checkbox',
    '#title' => t('Make the footer sticky'),
    '#default_value' => theme_get_setting('footer_sticky'),
    '#description' => t("A sticky footer will automatically stick to the bottom of the page until there is enough content to force it farther down."),
  ];
  $form['footer_style']['footer_option'] = [
    '#type' => 'select',
    '#title' => 'Select a footer style option:',
    '#default_value' => theme_get_setting('footer_option'),
    '#options' => [
      'normal' => 'Normal: 4 Even Columns',
      'one_two_merged' => 'Double wide, normal, normal (2:1:1 columns) - leave Footer 4 empty.',
      'two_three_merged' => 'Normal, double wide, normal (1:2:1 columns) - leave Footer 4 empty.',
      'three_four_merged' => 'Normal, normal, double wide (1:1:2 columns) - leave Footer 4 empty.',
    ],
    '#description' => 'If you select any footer layout besides normal, do not place content in the Footer 4 region. It will not be used.',
  ];
  $form['footer_style']['footer_info'] = [
    '#markup' => '<p>Note: If you are selecting a double wide column layout, you are responsible for formatting your content inside that wide column. That means if you want it to contain two columns of links, you need to add a class to do that.</p><p>The class "two-columns" is available if you would like to use that.</p>',
  ];
  $form['footer_style']['footer_regions'] = [
    '#markup' => '<p>To place content in the footer:<br>1.Make sure you have the module <a 
href="https://www.drupal.org/project/block_class">block class</a> downloaded and enabled. <br>2. Go to 
the <a href="../admin/structure/block" target="_blank">blocks page</a> 
and place blocks into one of the footer regions. Each time you place a block, add the class "byu-footer" to each block. Footer 1, Footer 2, Footer 3, and Footer 4 correspond to the 4 footer columns.</p><p>The header for the footer column will be the block title of the first block in the region.</p>',
  ];

  // Contact
  $form['contact'] = [
    '#type' => 'details',
    '#title' => 'Contact',
    '#description' => 'Show in header : Default + Language Dropdown',
    '#group' => 'design',
  ];
  $form['contact']['contact_option'] = [
    '#type' => 'textfield',
    '#default_value' => theme_get_setting('contact_option'),
    '#description' => 'Show in header : Default + Language Dropdown',
  ];
  $form['contact']['contact_about_link'] = [
    '#type' => 'textfield',
    '#title' => 'Link',
    '#default_value' => theme_get_setting('contact_about_link'),
    '#description' => 'Show in header : Default + Language Dropdown',
  ];
  $form['contact']['contact_about'] = [
    '#type' => 'textfield',
    '#title' => 'Title',
    '#default_value' => theme_get_setting('contact_about'),
    '#description' => 'Show in header : Default + Language Dropdown',
  ];
  $form['contact']['contact_us_link'] = [
    '#type' => 'textfield',
    '#title' => 'Link',
    '#default_value' => theme_get_setting('contact_us_link'),
    '#description' => 'Show in header : Default + Language Dropdown',
  ];
  $form['contact']['contact_us'] = [
    '#type' => 'textfield',
    '#title' => 'Title',
    '#default_value' => theme_get_setting('contact_us'),
  ];

}
