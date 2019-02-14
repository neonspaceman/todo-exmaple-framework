<?php

function smarty_function_date($params, $template)
{
  $workbook = [
    'am' => 'дп', 'pm' => 'пп', 'AM' => 'ДП', 'PM' => 'ПП',
    'Monday' => 'Понедельник', 'Mon' => 'Пн',
    'Tuesday' => 'Вторник', 'Tue' => 'Вт',
    'Wednesday' => 'Среда', 'Wed' => 'Ср',
    'Thursday' => 'Четверг', 'Thu' => 'Чт',
    'Friday' => 'Пятница', 'Fri' => 'Пт',
    'Saturday' => 'Суббота', 'Sat' => 'Сб',
    'Sunday' => 'Воскресенье', 'Sun' => 'Вс',
    'Dec' => 'Дек', 'Jan' => 'Янв', 'Feb' => 'Фев',
    'Mar' => 'Мар', 'Apr' => 'Апр',
    'Jun' => 'Июн', 'Jul' => 'Июл', 'Aug' => 'Авг',
    'Sep' => 'Сен', 'Oct' => 'Окт', 'Nov' => 'Ноя',
    'st' => 'ое', 'nd' => 'ое', 'rd' => 'е', 'th' => 'ое'
  ];

  $format = $params['format'];
  $timestamp = $params['ts'] ?? time();
  $case = $params['case'] ?? 'G';

  switch($case){
    case 'N': // nominate
      $workbook['January'] = 'Январь';
      $workbook['February'] = 'Февраль';
      $workbook['March'] = 'Март';
      $workbook['April'] = 'Апрель';
      $workbook['May'] = 'Май';
      $workbook['June'] = 'Июнь';
      $workbook['July'] = 'Июль';
      $workbook['August'] = 'Август';
      $workbook['September'] = 'Сентябрь';
      $workbook['October'] = 'Октябрь';
      $workbook['November'] = 'Ноябрь';
      $workbook['December'] = 'Декабрь';
      break;
    case 'G': // genitive
      $workbook['January'] = 'Января';
      $workbook['February'] = 'Февраля';
      $workbook['March'] = 'Марта';
      $workbook['April'] = 'Апреля';
      $workbook['May'] = 'Мая';
      $workbook['June'] = 'Июня';
      $workbook['July'] = 'Июля';
      $workbook['August'] = 'Августа';
      $workbook['September'] = 'Сентября';
      $workbook['October'] = 'Октября';
      $workbook['November'] = 'Ноября';
      $workbook['December'] = 'Декабря';
      break;
  }

  return strtr(date($format, $timestamp), $workbook);
}