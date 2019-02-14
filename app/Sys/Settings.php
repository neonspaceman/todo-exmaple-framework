<?php

namespace Sys;

class Settings implements \ArrayAccess
{
  /**
   * @var Settings
   */
  private static $instance = null;

  private static $cache = null;

  public static function getInstance()
  {
    if(!self::$instance)
      self::$instance = new self;
    return static::$instance;
  }

  private function __construct(){}

  public function getValue($var){
    if (is_null(static::$cache)){
      static::$cache = [];

      $db = Database::getInstance();
      $q = 'SELECT `param`, `value` FROM `settings`';
      $stmt = $db->prepare($q);
      $stmt->execute();
      while($data = $stmt->fetch())
        static::$cache[$data['param']] = $data['value'];
    }

    return static::$cache[$var] ?? null;
  }

  public function setValue($var, $value){
    static::$cache[$var] = $value;

    $db = Database::getInstance();
    $q = 'INSERT INTO `settings` (`param`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?';
    $stmt = $db->prepare($q);

    $stmt->execute([$var, $value, $value]);

    return $this;
  }

  public function offsetExists($offset)
  {
    throw new \Exception('Not Implemented');
  }

  public function offsetGet($offset)
  {
    return $this->getValue($offset);
  }

  public function offsetSet($offset, $value)
  {
    $this->setValue($offset, $value);
  }

  public function offsetUnset($offset)
  {
    throw new \Exception('Not Implemented');
  }
}