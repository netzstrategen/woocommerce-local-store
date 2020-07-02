<?php

namespace Netzstrategen\MultiStock;

/**
 * Plugin settings functionality.
 */
class Config {

  /**
   * Config JSON file.
   *
   * @var string
   */
  const CONFIG_FILE = '/config.json';

  /**
   * Cache expiration time.
   *
   * @var int
   */
  const CACHE_DURATION = 43200;

  /**
   * Returns the plugin configurations.
   *
   * @param string $key
   *   The configuration JSON key to return.
   *
   * @return array
   *   The plugin configurations.
   */
  public static function get($key = '') {
    try {
      $transient_id = Plugin::PREFIX . '_config';
      if ($transient = get_transient($transient_id)) {
        $json_data = $transient;
      }
      else {
        $data = static::readDataFile(self::CONFIG_FILE);
        $json_data = json_decode($data, TRUE);
        if (is_null($json_data)) {
          throw new \Exception("Error: could not decode the config file.");
        }
        set_transient($transient_id, $json_data, static::CACHE_DURATION);
      }

      if (!$key) {
        $config = $json_data;
      }
      elseif (isset($json_data[$key])) {
        $config = $json_data[$key];
      }
      else {
        $config = [];
      }

      return $config;
    }
    catch (\Exception $error) {
      trigger_error($error, E_USER_ERROR);
    }
  }

  /**
   * Returns the content of a file, given its path.
   *
   * @param string $path
   *   Path to the file to be read.
   *
   * @return string
   *   Data file contents
   */
  protected static function readDataFile(string $path): string {
    $str = @file_get_contents(Plugin::getBasePath() . $path);
    if ($str === FALSE) {
      throw new \Exception("Error: could not read file '$path'.");
    }
    else {
      return $str;
    }
  }

}
