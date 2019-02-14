<?php

namespace Sys;

class Str
{
  public static $studlyCaseCache = [];

  public static $camelCaseCache = [];

  public static $snakeCaseCache = [];

  /**
   * @param string $tpl Templates
   * @param array $params Parameters
   * @return string
   */
  public static function template($tpl, $params)
  {
    $keys = array_keys($params);
    foreach($keys as &$key)
      $key = '{' . $key . '}';
    return str_replace($keys, array_values($params), $tpl);
  }

  public static function slug($value)
  {
    //$value = mb_strtolower($value);
    $value = preg_replace('/[^a-z0-9\s\-]/i', '', $value);
    $value = preg_replace('/[\s]/', '-', $value);
    return $value;
  }

  public static function toCamelCase($value)
  {
    if(isset(static::$camelCaseCache[$value]))
      return static::$camelCaseCache[$value];

    return static::$camelCaseCache[$value] = lcfirst(static::toStudlyCase($value));
  }

  public static function toStudlyCase($value)
  {
    $key = $value;

    if(isset(static::$studlyCaseCache[$key]))
      return static::$studlyCaseCache[$key];

    $value = ucwords($value, '-_');

    return static::$studlyCaseCache[$key] = str_replace(['-', '_'], '', $value);
  }

  public static function toSnakeCase($value, $delimiter = '_')
  {
    $key = $value;

    if(isset(static::$snakeCaseCache[$key][$delimiter]))
      return static::$snakeCaseCache[$key][$delimiter];

    if(!ctype_lower($value)){
      $value = preg_replace('/\s+/u', '', ucwords($value));
      $value = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
    }

    return static::$snakeCaseCache[$key][$delimiter] = $value;
  }

  /**
   * @param int $number
   * @param array $ends
   * @return mixed
   */
  public static function numEnding($number, $ends)
  {
    $ret = $ends[2];
    $number %= 100;
    if(!($number >= 11 && $number <= 19)){
      switch($number % 10){
        case 1:
          $ret = $ends[0];
          break;
        case 2:
        case 3:
        case 4:
          $ret = $ends[1];
          break;
      }
    }
    return $ret;
  }
}