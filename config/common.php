<?php

$config = [
  'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'On' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'],
  'dev' => true,
  'version' => '3.1.1',
  'title' => 'New page',
  'keywords' => false,
  'description' => 'Task list',
  'icon' => '/public/favicon.ico',
];

if ($config['dev'])
  $config['version'] = time();

return $config;