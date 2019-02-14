<?php

namespace Sys;

class DependencyResolver
{
  /**
   * @param \ReflectionParameter $needed
   * @param array $search
   * @return bool
   */
  protected static function hasParameterOverride($needed, $search)
  {
    return array_key_exists($needed->getName(), $search);
  }

  /**
   * @param \ReflectionParameter $needed
   * @param array $search
   * @return mixed
   */
  protected static function getParameterOverride($needed, $search)
  {
    return $search[$needed->getName()];
  }

  /**
   * @param \ReflectionParameter $parameter
   * @return mixed
   * @throws \Exception
   */
  protected static function resolvePrimitive($parameter)
  {
    if ($parameter->isDefaultValueAvailable())
      return $parameter->getDefaultValue();
    throw new \Exception('Parameter `' . $parameter->getName() . '` is not resolved');
  }

  /**
   * @param \ReflectionParameter $parameter
   * @return mixed
   * @throws \Exception
   */
  protected static function resolveClass($parameter)
  {
    try {
      return Container::getInstance()->make($parameter->getClass()->getName());
    } catch(\Exception $err){
      if ($parameter->isDefaultValueAvailable())
        return $parameter->getDefaultValue();
      throw new \Exception('Parameter `' . $parameter->getName() . '` is not resolved');
    }
  }

  /**
   * @param \ReflectionParameter[] $dependencies
   * @param array $parameters
   * @return array
   */
  public static function resolve($dependencies, $parameters)
  {
    $instances = [];
    foreach($dependencies as $dependency){
      if (static::hasParameterOverride($dependency, $parameters)){
        $instances[] = static::getParameterOverride($dependency, $parameters);
        continue;
      }

      $instances[] = is_null($dependency->getClass())
        ? static::resolvePrimitive($dependency)
        : static::resolveClass($dependency);
    }
    return $instances;
  }
}