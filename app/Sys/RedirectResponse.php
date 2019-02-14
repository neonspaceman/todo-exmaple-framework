<?php

namespace Sys;

class RedirectResponse implements Interfaces\ResponseInterface
{
  private $location;

  public function __construct($location = "")
  {
    if ($location)
      $this->setLocation($location);
  }

  public function setLocation($location)
  {
    $this->location = $location;
    return $this;
  }

  public function getLocation()
  {
    return $this->location;
  }

  public function send()
  {
    header('Location: ' . $this->location);
  }
}