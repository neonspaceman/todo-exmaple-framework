<?php

function smarty_function_num_ending($params, $template)
{
  return Sys\Str::numEnding((int)$params['num'], $params['ends']);
}
