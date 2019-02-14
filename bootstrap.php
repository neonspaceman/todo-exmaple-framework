<?php

define('ROOT', dirname(__FILE__));

session_start(['name' => 'sid']);

mb_internal_encoding("utf-8");
date_default_timezone_set('UTC');
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'help.php';

if (defined('ACTION_FILE'))
  return;

\Sys\Container::getInstance()
  ->boot(require_once ROOT . '/config/containers.php');
