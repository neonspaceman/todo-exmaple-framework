<?php

namespace Sys;

class Data
{
  static public function toInt($num)
  {
    return (int)$num;
  }

  static public function toUInt($num)
  {
    $num = (int)$num;
    return $num < 0 ? 0 : $num;
  }

  static public function toFloat($num)
  {
    return (float)$num;
  }

  static public function toString($str)
  {
    return trim($str);
  }

  static public function toBool($bool)
  {
    return (bool)static::toInt($bool);
  }

  /**
   * Получение данных
   * @param $arr - массив
   * @param $name - имя
   * @param $modify - модификаторы i, u, f, s, i[], u[], f[], s[]
   * @return array|bool|float|int|string
   */
  static public function getFromArr($arr, $name, $modify = false)
  {
    $var = $arr[$name] ?? null;
    switch($modify){
      case 'i':
        $var = static::toInt($var);
        break;
      case 'u':
        $var = static::toUInt($var);
        break;
      case 'f':
        $var = static::toFloat($var);
        break;
      case 's':
        $var = static::toString($var);
        break;
      case 'b':
        $var = static::toBool($var);
        break;
      case 'i[]':
        $tmp = $var;
        $var = array();
        if(is_array($tmp))
          foreach($tmp as $value)
            $var[] = static::toInt($value);
        break;
      case 'u[]':
        $tmp = $var;
        $var = array();
        if(is_array($tmp))
          foreach($tmp as $value)
            $var[] = static::toUInt($value);
        break;
      case 'f[]':
        $tmp = $var;
        $var = array();
        if(is_array($tmp))
          foreach($tmp as $value)
            $var[] = static::toFloat($value);
        break;
      case 's[]':
        $tmp = $var;
        $var = array();
        if(is_array($tmp))
          foreach($tmp as $value)
            $var[] = static::toString($value);
        break;
      case 'b[]':
        $tmp = $var;
        $var = array();
        if(is_array($tmp))
          foreach($tmp as $value)
            $var[] = static::toBool($value);
        break;
      case 'mask':
        $tmp = $var;
        $var = 0;
        if(is_array($tmp)){
          foreach($tmp as $value)
            $var |= static::toUInt($value);
        }
        break;
      case 'date':
        $var = static::toString($var);
        if(strtotime($var) <= 0)
          $var = date('Ymd', 0);
        break;
    }
    return $var;
  }

  static public function get($name, $modify = false)
  {
    return static::getFromArr($_GET, $name, $modify);
  }

  static public function post($name, $modify = false)
  {
    return static::getFromArr($_POST, $name, $modify);
  }

  static public function session($name, $modify = false)
  {
    return static::getFromArr($_SESSION, $name, $modify);
  }

  static public function cookie($name, $modify = false)
  {
    return static::getFromArr($_COOKIE, $name, $modify);
  }
}