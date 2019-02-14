<?php

namespace Sys;

class Config implements \ArrayAccess
{
  /**
   * @var Config
   */
  private static $instance = null;

  public static function getInstance()
  {
    if(!self::$instance)
      self::$instance = new self;
    return static::$instance;
  }

  private function __construct(){}

  protected $cache;

  public function get($category)
  {
    if(!isset($this->cache[$category])){
      $filePath = ROOT . '/config/' . $category . '.php';
      if(!file_exists($filePath))
        throw new \Exception('Config file `' . $filePath . '` not found');
      $this->cache[$category] = require_once $filePath;
    }

    return $this->cache[$category];
  }

  public function offsetExists($offset)
  {
    throw new \Exception('Not Implemented');
  }

  public function offsetGet($offset)
  {
    return $this->get($offset);
  }

  public function offsetSet($offset, $value)
  {
    throw new \Exception('Not Implemented');
  }

  public function offsetUnset($offset)
  {
    throw new \Exception('Not Implemented');
  }
}