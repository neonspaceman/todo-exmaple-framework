<?php

namespace Sys;

class Container implements \ArrayAccess
{
  /**
   * @var Container
   */
  private static $instance = null;

  public static function getInstance()
  {
    if(self::$instance === null)
      self::$instance = new self;
    return self::$instance;
  }

  private function __construct()
  {
    $this->instance(static::class, $this);
  }

  protected $bindings = [];

  protected $aliases = [];

  protected $instances = [];

  protected $withStack = [];

  /**
   * @var DependencyResolver
   */
  protected $di;

  /**
   * @var Caller
   */
  protected $caller;

  protected function getAlias($abstract)
  {
    while(true){
      if(!isset($this->aliases[$abstract]))
        return $abstract;
      $abstract = $this->aliases[$abstract];
    }
  }

  protected function getParameters()
  {
    return count($this->withStack) ? end($this->withStack) : [];
  }

  protected function getConcrete($abstract)
  {
    if(isset($this->bindings[$abstract]))
      return $this->bindings[$abstract]['concrete'];

    return $abstract;
  }

  protected function isShared($abstract)
  {
    return isset($this->instances[$abstract]) || (isset($this->bindings[$abstract]['shared']) && $this->bindings[$abstract]['shared'] === true);
  }

  protected function buildClosure($closure)
  {
    $reflector = new \ReflectionFunction($closure);

    $instances = DependencyResolver::resolve($reflector->getParameters(), $this->getParameters());

    return $closure(...$instances);
  }

  protected function build($concrete)
  {
    if($concrete instanceof \Closure)
      return $this->buildClosure($concrete);

    $reflector = new \ReflectionClass($concrete);

    if(!$reflector->isInstantiable())
      throw new \Exception('Class is not instantiable');

    $construct = $reflector->getConstructor();

    if(is_null($construct))
      return new $concrete;

    $instances = DependencyResolver::resolve($construct->getParameters(), $this->getParameters());

    return $reflector->newInstanceArgs($instances);
  }

  protected function resolve($abstract, $parameters = [])
  {
    $abstract = $this->getAlias($abstract);

    $needRebuild = !empty($parameters);

    if(isset($this->instances[$abstract]) && !$needRebuild)
      return $this->instances[$abstract];

    $this->withStack[] = $parameters;

    $object = $this->build($this->getConcrete($abstract));

    if($this->isShared($abstract) && !$needRebuild)
      $this->instances[$abstract] = $object;

    array_pop($this->withStack);

    return $object;
  }

  public function boot($config)
  {
    if(isset($config['bind'])){
      foreach($config['bind'] as $abstract => $concrete)
        $this->bind($abstract, $concrete);
    }
    if(isset($config['singleton'])){
      foreach($config['singleton'] as $abstract => $concrete)
        $this->singleton($abstract, $concrete);
    }
    if(isset($config['alias'])){
      foreach($config['alias'] as $alias => $abstract)
        $this->alias($abstract, $alias);
    }
  }

  public function alias($abstract, $alias)
  {
    $this->aliases[$alias] = $abstract;
  }

  public function isAlias($abstract)
  {
    return isset($this->aliases[$abstract]);
  }

  public function bind($abstract, $concrete = null, $shared = false)
  {
    if(is_null($concrete))
      $concrete = $abstract;
    $this->bindings[$abstract] = compact('concrete', 'shared');
    unset($this->instances[$abstract]);
  }

  public function isBound($abstract)
  {
    return isset($this->bindings[$abstract])
      || isset($this->instances[$abstract])
      || $this->isAlias($abstract);
  }

  public function singleton($abstract, $concrete = null)
  {
    $this->bind($abstract, $concrete, true);
  }

  public function instance($abstract, $instance)
  {
    $this->instances[$abstract] = $instance;
  }

  /**
   * @param $abstract
   * @param array $parameters
   * @return mixed
   */
  public function make($abstract, $parameters = [])
  {
    return $this->resolve($abstract, $parameters);
  }

  public function call($callback, $parameters = [])
  {
    return Caller::call($callback, $parameters);
  }

  public function offsetExists($offset)
  {
    return $this->isBound($offset);
  }

  public function offsetGet($offset)
  {
    return $this->make($offset);
  }

  public function offsetSet($offset, $value)
  {
    $this->bind($offset, $value);
  }

  public function offsetUnset($offset)
  {
    throw new \Exception('Not Implemented');
  }
}

;