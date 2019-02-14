<?php

namespace Sys;

class Router implements \ArrayAccess
{
  /**
   * @var Router
   */
  private static $instance = null;

  public static function getInstance()
  {
    if(!self::$instance)
      self::$instance = new self;
    return static::$instance;
  }

  private function __construct(){}

  protected $get = [];

  protected $post = [];

  protected $controllers = [];

  protected $default = 'Information@page404';

  protected $url = null;

  protected $action = null;

  protected $parameters = [];

  protected function setUrl($url)
  {
    $this->url = $url;
    return $this;
  }

  protected function setAction($action)
  {
    $action = explode('@', $action);
    $this->action = [$action[0], $action[1]];
    return $this;
  }

  protected function setParameter($key, $value)
  {
    $this->parameters[$key] = $value;
    return $this;
  }

  protected function addRule(&$arr, $rule, $action)
  {
    $regexp = preg_replace('/{\w+}/', '(\w+)', $rule, -1, $countReplace);
    if ($countReplace)
      $arr['regexp'][$rule] = compact('action', 'regexp');
    else
      $arr['simple'][$rule] = $action;
    return $this;
  }

  public function get($rule, $controller)
  {
    $this->addRule($this->get, $rule, $controller);
    return $this;
  }

  public function post($rule, $controller)
  {
    $this->addRule($this->post, $rule, $controller);
    return $this;
  }

  public function default($action)
  {
    $this->default = $action;
    return $this;
  }

  /**
   * Resolve models and variables
   * @return array
   */
  protected function resolveDependencies()
  {
    $instances = [];

    foreach((new \ReflectionMethod('\Controllers\\' . $this->getController() . 'Controller', $this->getMethod()))->getParameters() as $dependency){
      $key = $dependency->getName();
      $value = $this->getParameter($key);
      if (is_null($value))
        continue;
      if ($dependency->getClass()){
        if ($dependency->getClass()->isSubclassOf(Model::class))
          $instances[$key] = Container::getInstance()->call($dependency->getClass()->getName() . '::find', ['key' => $value]);
      } else {
        $instances[$key] = $value;
      }
    }

    return $instances;
  }

  /**
   * @return Interfaces\ResponseInterface;
   */
  public function resolve()
  {
    // prepare url
    $url = ltrim(urldecode($_SERVER['REQUEST_URI']), '/');
    $posQuery = mb_strpos($url, '?');
    if($posQuery !== false)
      $url = mb_substr($url, 0, $posQuery);

    $this
      ->setUrl($url)
      ->setAction($this->default);

    $rules = null;
    switch($_SERVER['REQUEST_METHOD']){
      case 'GET':
        $rules = &$this->get;
        break;
      case 'POST':
        $rules = &$this->post;
        break;
    }

    if($rules){
      if (isset($rules['simple'][$url])){
        $this
          ->setAction($rules['simple'][$url]);
      } elseif (isset($rules['regexp'])) {
        foreach($rules['regexp'] as $rule => $item){
          if (preg_match('#^' . $item['regexp'] . '$#', $url, $matches)){
            $this->setAction($item['action']);
            preg_match_all('/\{(\w+)\}/', $rule, $names);
            $names = $names[1];
            for($i = 0, $c = count($names); $i < $c; ++$i)
              $this->setParameter($names[$i], $matches[$i + 1]); // offset, cos $matches[0] consist all string match
            break;
          }
        }
      }
    }

    $instances = $this->resolveDependencies();

    // check policies: before, controller & after
    $policy = '\Policies\\' . $this->getController() . 'Policy';
    if (class_exists($policy)){
      foreach(['before', $this->getMethod(), 'after'] as $policyMethod){
        if(method_exists($policy, $policyMethod)){
          $policyResponse = Container::getInstance()->call($policy . '@' . $policyMethod, $instances);
          if($policyResponse !== true)
            return $policyResponse;
        }
      }
    }

    return Container::getInstance()->call(
      '\Controllers\\' . $this->getController() . 'Controller@' . $this->getMethod(),
      $instances
    );
  }

  public function getController()
  {
    if(is_null($this->action))
      throw new \Exception('Current rule is null');

    return $this->action[0];
  }

  public function getMethod()
  {
    if(is_null($this->action))
      throw new \Exception('Current rule is null');

    return $this->action[1];
  }

  public function getAction()
  {
    if(is_null($this->action))
      throw new \Exception('Current rule is null');

    return $this->action[0] . '@' . $this->action[1];
  }

  public function getParameters()
  {
    if(is_null($this->parameters))
      throw new \Exception('Current rules is null');

    return $this->parameters;
  }

  public function getParameter($key)
  {
    return $this->parameters[$key] ?? Data::get($key);
  }

  public function getUrl()
  {
    return $this->url;
  }

  public function offsetExists($offset)
  {
    throw new \Exception('Method is not implemented');
  }

  public function offsetGet($offset)
  {
    $method = 'get' . Str::toStudlyCase($offset);

    if(method_exists($this, $method))
      return $this->{$method}();

    return null;
  }

  public function offsetSet($offset, $value)
  {
    throw new \Exception('Method is not implemented');
  }

  public function offsetUnset($offset)
  {
    throw new \Exception('Method is not implemented');
  }
}