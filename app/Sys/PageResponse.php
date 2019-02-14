<?php

namespace Sys;

class PageResponse extends Template implements Interfaces\ResponseInterface
{
  private $statusCode = 200;

  private $tpl = false;

  private $title = false;

  private $keywords = false;

  private $description = false;

  private $icon = false;

  private $styles = array();

  private $scripts = array();

  private $redirect = false;

  public function __construct(Config $config, $template = false)
  {
    parent::__construct();
    if ($template)
      $this->setTemplate($template);
    $this->setTitle($config['common']['title']);
    $this->setKeywords($config['common']['keywords']);
    $this->setDescription($config['common']['description']);
    $this->setIcon($config['common']['icon']);
  }

  public function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;
    return $this;
  }

  public function getStatusCode()
  {
    return $this->statusCode;
  }

  public function setRedirect($redirect)
  {
    $this->redirect = $redirect;
    return $this;
  }

  public function getRedirect()
  {
    return $this->redirect;
  }

  public function setTemplate($tpl)
  {
    $this->tpl = $tpl;
    return $this;
  }

  public function getTemplate($tpl)
  {
    return $this->tpl;
  }

  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setKeywords($keywords)
  {
    $this->keywords = $keywords;
    return $this;
  }

  public function getKeywords()
  {
    return $this->keywords;
  }

  public function setDescription($desc)
  {
    $this->description = $desc;
    return $this;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setIcon($icon_url)
  {
    $this->icon = $icon_url;
    return $this;
  }

  public function getIcon()
  {
    return $this->icon;
  }

  public function insertStyle($url)
  {
    $this->styles[] = $url;
    return $this;
  }

  /**
   * Вывод стилей
   */
  public function getStyles()
  {
    return $this->styles;
  }

  public function insertScript($url)
  {
    $this->scripts[] = $url;
    return $this;
  }

  public function getScripts()
  {
    return $this->scripts;
  }

  public function assign($tpl_var, $value = null, $nocache = false)
  {
    parent::assign($tpl_var, $value, $nocache);
    return $this;
  }

  public function send()
  {
    if ($this->statusCode !== 200)
      header('HTTP/1.0 ' . $this->statusCode, true, $this->statusCode);
    $this
      ->assign('page', [
        'title' => $this->getTitle(),
        'keywords' => $this->getKeywords(),
        'description' => $this->getDescription(),
        'icon' => $this->getIcon(),
        'styles' => $this->getStyles(),
        'scripts' => $this->getScripts(),
      ])
      ->assign('route', Router::getInstance());
    $this->display($this->tpl . '.tpl');
  }
}