<?php

namespace Sys;

class Log
{
  private static function write($message, $postfix = false)
  {
    $config = Config::getInstance();
    $trace = debug_backtrace();
    $functionName = isset($trace[2]) ? $trace[2]['function'] : '-';
    $mark = date("H:i:s") . ' [' . $functionName . ']';
    $logName = ROOT . $config['log']['dir'] . '/log_' . date("j.n.Y") . ($postfix ? '_' . $postfix : '') . '.txt';
    file_put_contents($logName, $mark . " : " . $message . PHP_EOL, FILE_APPEND);
  }

  public static function message($message, $postfix = false)
  {
    if(is_array($message))
      $message = json_encode($message);
    static::write('[INFO] ' . $message, $postfix);
  }

  public static function error($message, $postfix = false)
  {
    if(is_array($message))
      $message = json_encode($message);
    static::write('[ERROR] ' . $message, $postfix);
  }
}