<?php

namespace App;

use Sys\Str;
use Sys\Config;

class Role implements \ArrayAccess
{
  /**
   * @var Role
   */
  private static $instance = null;

  public static function getInstance()
  {
    if(!static::$instance)
      static::$instance = new static;
    return static::$instance;
  }

  /**
   * @var Account
   */
  protected $account;

  /**
   * @var Config;
   */
  protected $config;

  private function __construct()
  {
    $this->account = Account::getInstance();
    $this->config = Config::getInstance();
  }

  public function isGuest()
  {
    return !$this->account->getAttribute('logged');
  }

  public function isUser()
  {
    return $this->account->getAttribute('logged');
  }

  public function isAdmin()
  {
    return $this->isUser()
      && (bool)($this->account->getAttribute('role') & $this->config['roles']['admin']);
  }

  public function offsetExists($offset)
  {
    return method_exists($this, 'is' . Str::toStudlyCase($offset));
  }

  public function offsetGet($offset)
  {
    $method = 'is' . Str::toStudlyCase($offset);

    if (method_exists($this, $method))
      return $this->{$method}();

    return null;
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