<?php

function smarty_modifier_json($data)
{
  $json = json_encode($data, JSON_HEX_APOS | JSON_HEX_QUOT);
  $json = preg_replace('/"(\w+)"/', '$1', $json);
  $json = str_replace('"', '\'', $json);
  return $json;
}