<?php

namespace Sys;

/**
 * Caller functions or methods width resolver dependency
 * @package Sys
 */
class Caller
{
  protected static function isMethod($callback)
  {
    return is_string($callback) && (strpos($callback, '@') !== false || strpos($callback, '::') !== false);
  }

  /**
   * @param string $callback
   * @param array $parameters
   * @return mixed
   */
  protected static function resolveMethod($callback, $parameters)
  {
    if (count($segments = explode('@', $callback)) == 2)
      return static::resolve([Container::getInstance()->make($segments[0]), $segments[1]], $parameters);
    if (count($segments = explode('::', $callback)) == 2)
      return static::resolve([$segments[0], $segments[1]], $parameters);
  }

  /**
   * @param $callback
   * @param $parameters
   * @return mixed
   */
  protected static function resolve($callback, $parameters)
  {
    if (static::isMethod($callback))
      return static::resolveMethod($callback, $parameters);

    $dependencies = (is_array($callback)
      ? new \ReflectionMethod($callback[0], $callback[1])
      : new \ReflectionFunction($callback)
    )->getParameters();

    return call_user_func_array($callback, DependencyResolver::resolve($dependencies, $parameters));
  }

  /**
   * @param $callback
   * @param array $parameters
   * @return mixed
   */
  public static function call($callback, $parameters = [])
  {
    return static::resolve($callback, $parameters);
  }
}
