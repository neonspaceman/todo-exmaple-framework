<?php

namespace Sys;

class Model implements \ArrayAccess
{
  protected $table;

  protected $columns;

  protected $attributes = [];

  /**
   * Get all attributes by columns
   * @return array
   */
  protected function getAttributes()
  {
    $res = [];
    foreach($this->attributes as $key => $attr)
      $res[Str::toCamelCase($key)] = $attr;
    return $res;
  }

  public function setAttribute($key, $value)
  {
    $method = 'set' . Str::toStudlyCase($key);
    if(method_exists($this, $method))
      return $this->{$method}($value);

    $this->attributes[Str::toCamelCase($key)] = $value;

    return $this;
  }

  public function getAttribute($key)
  {
    $method = 'get' . Str::toStudlyCase($key);
    if(method_exists($this, $method))
      return $this->{$method}();

    return $this->attributes[$key] ?? null;
  }

  public function __set($name, $value)
  {
    return $this->setAttribute($name, $value);
  }

  public function __get($key)
  {
    return $this->getAttribute($key);
  }

  public static function find($key)
  {
    throw new \Exception('Method is not implemented');
  }

  public function offsetExists($offset)
  {
    return isset($this->attributes[$offset]);
  }

  public function offsetGet($offset)
  {
    return $this->getAttribute($offset);
  }

  public function offsetSet($offset, $value)
  {
    $this->setAttribute($offset, $value);
  }

  public function offsetUnset($offset)
  {
    throw new \Exception('Not Implemented');
  }
}