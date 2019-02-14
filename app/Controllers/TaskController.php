<?php

namespace Controllers;


use Models\TaskModel;
use Sys\Data;

class TaskController
{
  public function viewAdd($error = false)
  {
    return view('form')
      ->setTitle('Create')
      ->assign('task', new TaskModel())
      ->assign('error', $error);
  }

  public function add()
  {
    $description = Data::post('description', 's');
    if (empty($description))
      return redirect('/add?error=Description is empty');

    $task = new TaskModel();
    $task->description = Data::post('description', 's');
    $task->create();

    return redirect('/');
  }

  public function viewEdit(TaskModel $task, $error = false)
  {
    return view('form')
      ->setTitle('Edit')
      ->assign('task', $task)
      ->assign('error', $error);
  }

  public function edit(TaskModel $task)
  {
    $description = Data::post('description', 's');
    if (empty($description))
      return redirect('/edit?task=' . $task->id . '&error=Description is empty');

    $task->description = Data::post('description', 's');
    $task->save();

    return redirect('/');
  }

  public function delete(TaskModel $task)
  {
    $task->delete();
    return redirect('/');
  }

  public function toggle(TaskModel $task)
  {
    $task->completed = !$task->completed;
    $task->save();

    return redirect('/');
  }
}