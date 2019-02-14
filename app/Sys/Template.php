<?php

namespace Sys;

use App\Account;
use App\Role;

require_once ROOT . '/app/Smarty/Smarty.class.php';

class Template extends \Smarty
{
  function __construct()
  {
    parent::__construct();
    $this
      ->setTemplateDir(ROOT . '/templates/')
      ->setCompileDir(ROOT . '/cache/smarty/compiled')
      ->setCacheDir(ROOT . '/cache/smarty/cache')
      ->setConfigDir(ROOT . '/app/Smarty/config/')
      ->addPluginsDir(ROOT . '/app/Smarty/extensions/')
      ->assign('config', Config::getInstance())
      ->assign('account', Account::getInstance())
      ->assign('role', Role::getInstance())
      ->loadFilter('output', 'trimwhitespace');
  }
}