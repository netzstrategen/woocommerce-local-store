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
  const CONFIG_FILE = '.woocommerce-multi-stock.json';

  /**
   * Static cache of configuration.
   *
   * @var array
   */
  private static $cache;

  /**
   * Returns the plugin configurations.
   *
   * @param string $key
   *   The configuration JSON key to return.
   *
   * @return array
   *   The plugin configurations.
   */
  public static function get($key = NULL) {
    if (!isset(static::$cache)) {
      try {
        $data = static::readDataFile(self::CONFIG_FILE);
        if (!static::$cache = json_decode($data, TRUE)) {
          throw new \Exception('Unable to decode the configuration.');
        }
      }
      catch (\Throwable $e) {
        trigger_error($e, E_USER_ERROR);
      }
    }
    if ($key !== NULL) {
      return static::$cache[$key];
    }
    return static::$cache;
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
    $path = ABSPATH . $path;
    if (!file_exists($path) || !$str = file_get_contents($path)) {
      throw new \Exception("Unable to read file '$path'.");
    }
    return $str;
  }

}
