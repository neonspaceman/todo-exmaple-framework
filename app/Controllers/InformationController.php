<?php

namespace Controllers;

class InformationController
{
  public function page404()
  {
    return view('404')
      ->setTitle('Not Found');
  }
}