(function ($, Drupal, drupalSettings, navigator, window) {
  'use strict';

  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker .register("/serviceworker-pwa", { scope: drupalSettings.path.baseUrl })
        .then(registration => {
          console.log(`Service Worker registered! Scope: ${registration.scope}`);
        })
        .catch(err => {
          console.log(`Service Worker registration failed: ${err}`);
        });
    });
  }

})(jQuery, Drupal, drupalSettings, navigator, window);