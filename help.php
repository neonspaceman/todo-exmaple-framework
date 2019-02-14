<?php

function redirect($location)
{
  return new Sys\RedirectResponse($location);
}

function view($template)
{
  return new Sys\PageResponse(\Sys\Config::getInstance(), $template);
}

function json()
{
  return new Sys\JsonResponse();
}

function make($abstract, $parameters = [])
{
  return Sys\Container::getInstance()->make($abstract, $parameters);
}

function call($callback, $parameters = [])
{
  return Sys\Caller::call($callback, $parameters);
}