<?php

return [
  //'bind' => [],

  'singleton' => [
    Sys\Router::class => function(){ return Sys\Router::getInstance(); },
    Sys\Config::class => function(){ return Sys\Config::getInstance(); },
    Sys\Database::class => function(){ return Sys\Database::getInstance(); },
    App\Account::class => function(){ return App\Account::getInstance(); },
    App\Role::class => function(){ return App\Role::getInstance(); },
  ],

  'alias' => [
    'route' => Sys\Router::class,
    'config' => Sys\Config::class,
    'database' => Sys\Database::class
  ]
];