<?php

namespace App;

use Sys\Config;
use Sys\Data;
use Sys\Database;
use Sys\JsonResponse;
use Sys\Model;
use Sys\Template;
use App\SMTP\SMTP;
use App\SMTP\Message as SMTPMessage;
use App\Tranzzo\Tranzzo;
use App\Telegram\Bot as TelegramBot;
use App\Telegram\Message as TelegramMessage;
use App\Telegram\Keyboard as TelegramKeyboard;

class Account extends Model
{
  // FIXME: This is only for demo
  const COOKIE_LOGIN = 'id';
  const COOKIE_PASSWORD = 'password';

  /**
   * @var Account
   */
  private static $instance = null;

  public static function getInstance()
  {
    if(!static::$instance)
      static::$instance = new static;
    return static::$instance;
  }

  protected $table = 'users';

  protected $logged = false;

  protected $columns = [
    'login',
    'password',
    'role',
  ];

  private function __construct()
  {
    $db = Database::getInstance();
    $login = Data::cookie(static::COOKIE_LOGIN);
    $password = Data::cookie(static::COOKIE_PASSWORD);

    if ($login && $password){
      $q = 'SELECT `' . implode('`,`', $this->columns) . '` FROM `' . $this->table . '` WHERE `login` = ? and `password` = ? LIMIT 1';
      $stmt = $db->prepare($q);
      $stmt->execute([$login, $password]);
      $data = $stmt->fetch();

      $this->setAttribute('logged', (bool)$data);
      if($data){
        foreach($data as $key => $value)
          $this->setAttribute($key, $value);
      }
    }
  }

  /**
   * Sign in
   * @param $login string Login
   * @param $password string Password
   * @return bool
   */
  public function signIn($login, $password)
  {
    $db = Database::getInstance();

    $q = 'SELECT COUNT(*) FROM `users` WHERE `login` = ? AND `password` = ? LIMIT 1';
    $stmt = $db->prepare($q);
    $stmt->execute([$login, $password]);
    $count = $stmt->fetchColumn();

    if ($count){
      setcookie(static::COOKIE_LOGIN, $login, 0, '/');
      setcookie(static::COOKIE_PASSWORD, $password, 0, '/');
      return true;
    }

    return false;
  }

  public function signOut()
  {
    setcookie(static::COOKIE_LOGIN, '', time() - 3600, '/');
    setcookie(static::COOKIE_PASSWORD, '', time() - 3600, '/');
  }
}