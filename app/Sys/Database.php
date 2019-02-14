<?php

namespace Sys;

class Database extends \PDO
{
  /**
   * @var Database
   */
  private static $instance = null;

  public static function getInstance()
  {
    if(!static::$instance){
      $config = Config::getInstance()['database'];
      static::$instance = new static(
        'mysql:host=' . $config['host'] . ';dbname=' . $config['name'] . ';charset=utf8',
        $config['user'],
        $config['password'],
        [
          static::ATTR_ERRMODE => static::ERRMODE_EXCEPTION,
          static::ATTR_DEFAULT_FETCH_MODE => static::FETCH_ASSOC,
          static::ATTR_EMULATE_PREPARES => false,
        ]
      );
    }
    return static::$instance;
  }
}