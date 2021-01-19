<?php

namespace Netzstrategen\WooCommerceLocalStore;

/**
 * A store.
 */
class Store {

  private $id;
  private $type;
  private $name;

  public static function fromConfig($store_id): self {
    $config = Config::get();
    if (isset($config[$store_id])) {
      $store_config = $config[$store_id];
    }
    else {
      throw new \InvalidArgumentException("Unknown store ID '$store_id'");
    }
    return new static($store_config);
  }

  public function __construct(array $store_config) {
    $this->id = $store_config['id'];
    $this->name = $store_config['name'];
    $this->type = $store_config['type'];
  }

  public function getStock($product_id): int {
    if ($product_id instanceof \WC_Product) {
      $product_id = $product_id->get_id();
    }
    $value = get_post_meta($product_id, $this->getStockMetaKey(), TRUE);
    return $value;
  }

  public function setStock($product_id, int $value): self {
    if ($product_id instanceof \WC_Product) {
      $product_id = $product_id->get_id();
    }
    update_post_meta($product_id, $this->getStockMetaKey(), $value);
    return $this;
  }

  public function deleteStock($product_id): self {
    if ($product_id instanceof \WC_Product) {
      $product_id = $product_id->get_id();
    }
    delete_post_meta($product_id, $this->getStockMetaKey());
    return $this;
  }

  private function getStockMetaKey() {
    return '_local_store_' . $this->getId() . '_stock';
  }

  public function getId(): string {
    return $this->id;
  }

  public function getType(): string {
    return $this->type;
  }

  public function getName(): string {
    return $this->name;
  }

}
