<?php

require_once 'bootstrap.php';

use Sys\Router;

//var_dump(\App\Account::getInstance());

$route = Router::getInstance();
$route->get('', 'Welcome@viewMain');

$route->get('sign_in', 'User@viewSignIn');
$route->post('sign_in', 'User@signIn');
$route->get('sign_out', 'User@signOut');

$route->get('add', 'Task@viewAdd');
$route->post('add', 'Task@add');

$route->get('edit', 'Task@viewEdit');
$route->post('edit', 'Task@edit');

$route->get('delete', 'Task@delete');

$route->get('toggle', 'Task@toggle');

$response = $route->resolve();

$response->send();