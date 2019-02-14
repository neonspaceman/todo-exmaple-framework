<?php

function smarty_function_source($params, $template)
{
  static $cache = null;
  if (is_null($cache)){
    $config = \Sys\Config::getInstance();
    $cache = [
      'url' => $config['common']['url'],
      'version' => $config['common']['version'],
    ];
  }
  $visibleVersion = $params['visibleVersion'] ?? true;
  return $cache['url'] . $params['path'] . ($visibleVersion ? '?' . $cache['version'] : '');
}
