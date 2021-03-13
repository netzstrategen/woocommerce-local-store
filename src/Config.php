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
   * Returns configuration of stores.
   */
  public static function get(?string $store_id = NULL): array {
    if (!isset(static::$cache)) {
      try {
        $data = static::readDataFile(self::CONFIG_FILE);
        if (!static::$cache = json_decode($data, TRUE)) {
          throw new \Exception('Unable to decode the configuration.');
        }
        foreach (static::$cache as $id => $store_config) {
          static::$cache[$id]['id'] = $id;
        }
      }
      catch (\Throwable $e) {
        trigger_error($e, E_USER_ERROR);
      }
    }
    if ($store_id !== NULL) {
      return static::$cache[$store_id];
    }
    return static::$cache;
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

  public static function getStoreIdsByName(string $name): array {
    $ids = [];
    foreach (Config::get() as $id => $store_config) {
      if ($store_config['name'] === $name) {
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
