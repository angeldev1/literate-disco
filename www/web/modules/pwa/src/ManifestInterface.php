<?php

namespace Drupal\pwa;

/**
 * Manifest JSON building service.
 */
interface ManifestInterface {

  /**
   * Build the manifest json string based on the configuration.
   *
   * @return string
   *   Manifest JSON string.
   */
  public function getOutput();

  /**
   * Deletes the images that are used for the manifest file.
   */
  public function deleteImage();

}
