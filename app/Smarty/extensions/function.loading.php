<?php

function smarty_function_loading($params, $template)
{
  static $n = 0;

  // diameter
  $d = $params['d'] ?? 32;
  $stoke = $params['stoke'] ?? 4;
  $color = $params['color'] ?? 'default';

  $n++;
  $unique = $n . time();
  $r = $d * .5 - $stoke * .5;
  $l = 2 * $r * M_PI;
  $a = .2;
  $part = $a + (1 - $a) * .3;
  return '<div class="loading_wrap ' . $color . '" style="width: ' . $d . 'px; height: ' . $d . 'px;"><svg class="progress-arc" viewport="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><defs><linearGradient id="gradIntermediate' . $unique . '" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" class="stop0"></stop><stop offset="60%" class="stop60"></stop><stop offset="100%" class="stop100"></stop></linearGradient></defs><circle class="progress-arc-bar" cx="' . ($d * .5) . '" cy="' . ($d * .5) . '" r="' . $r . '" style="stroke-width: ' . $stoke . '; stroke-dasharray: ' . round($l * $part, 4) . ', ' . round((1 - $part) * $l, 4) . '; stroke: url(#gradIntermediate' . $unique . ');"></circle></svg></div>';
}