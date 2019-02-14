<?php

namespace Policies;

use App\Role;
use Models\TaskModel;

class TaskPolicy
{
  public function viewAdd(TaskModel $task, Role $role)
  {
    if (!$task->id)
      return redirect('/');

    if (!$role->isAdmin())
      return redirect('/');

    return true;
  }

  public function add(TaskModel $task, Role $role)
  {
    if (!$task->id)
      return redirect('/');

    if (!$role->isAdmin())
      return redirect('/');

    return true;
  }

  public function viewEdit(TaskModel $task, Role $role)
  {
    if (!$task->id)
      return redirect('/');

    if (!$role->isAdmin())
      return redirect('/');

    return true;
  }

  public function edit(Role $role)
  {
    if (!$role->isAdmin())
      return redirect('/');

    return true;
  }

  public function delete(TaskModel $task, Role $role)
  {
    if (!$task->id)
      return redirect('/');

    if (!$role->isAdmin())
      return redirect('/');

    return true;
  }

  public function toggle(TaskModel $task, Role $role)
  {
    if (!$task->id)
      return redirect('/');

    if (!$role->isUser())
      return redirect('/');

    return true;
  }
}