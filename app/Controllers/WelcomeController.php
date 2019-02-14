<?php

namespace Controllers;

use Models\TaskModel;

class WelcomeController
{
  public function viewMain()
  {
    $tasks = TaskModel::all();

    return view('welcome')
      ->setTitle('Welcome')
      ->assign('tasks', $tasks);
  }
}