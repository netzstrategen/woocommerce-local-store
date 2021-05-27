<?php

namespace Netzstrategen\WooCommerceLocalStore;

/**
 * Plugin settings functionality.
 */
class Config {

  /**
   * Config JSON file.
   *
   * @var string
   */
  const CONFIG_FILE = '.woocommerce-local-store.json';

  /**
   * Static cache of configuration.
   *
   * @var array
   */
  private static $cache;

  /**
   * Static cache of configured stores.
   *
   * @var array
   */
  private static $stores;

  /**
   * Returns configuration of stores.
   */
  public static function get(?string $store_id = NULL): array {
    if (!isset(static::$cache)) {
      try {
        $data = static::readDataFile(self::CONFIG_FILE);
        if (!static::$cache = json_decode($data, TRUE)) {
          throw new \Exception('Unable to decode the configuration.');
        }
        foreach (static::$cache as $location_name => $location) {
          foreach ($location['stores'] as $id => $store_config) {
            $store_config['id'] = $id;
            $store_config['name'] = $location['name'];
            static::$stores[$id] = $store_config;
          }
        }
      }
      catch (\Throwable $e) {
        trigger_error($e, E_USER_ERROR);
      }
    }
    if ($store_id !== NULL) {
      return static::$stores[$store_id];
    }
    return static::$stores;
  }

  public static function getAllStoreIds(): array {
    return array_keys(Config::get());
  }

  public static function getStoreIdsByType(string $type): array {
    $ids = [];
    foreach (Config::get() as $id => $store_config) {
      if ($store_config['type'] === $type) {
        $ids[] = $id;
      }
    }
    return $ids;
  }

  public static function getStoreIdsByNameAndType(string $name, string $type): array {
    $ids = [];
    foreach (Config::get() as $id => $store_config) {
      if ($store_config['name'] === $name && $store_config['type'] === $type) {
        $ids[] = $id;
      }
    }
    return $ids;
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
  private static function readDataFile(string $path): string {
    $path = ABSPATH . $path;
    if (!file_exists($path) || !$str = file_get_contents($path)) {
      throw new \Exception("Unable to read file '$path'.");
    }
    return $str;
  }

}
