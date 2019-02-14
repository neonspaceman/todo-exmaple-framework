<?php

namespace Models;


use Sys\Database;
use Sys\Model;

class TaskModel extends Model
{
  protected $table = 'tasks';

  protected $id = 0;

  protected $columns = [
    'description',
    'completed'
  ];

  public function __construct($data = [])
  {
    foreach($data as $key => $attr)
      $this->setAttribute($key, $attr);
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  public function setCompleted($value)
  {
    $this->attributes['completed'] = (int)((bool)$value);
    return $this;
  }

  /**
   * @param $key
   * @return TaskModel
   */
  public static function find($key)
  {
    $db = Database::getInstance();

    $instance = new static();

    $columns = $instance->columns;
    $columns[] = 'id';

    $q = 'SELECT `' . implode('`,`', $columns) . '` FROM `' . $instance->table . '` WHERE `id`=? LIMIT 1';
    $stmt = $db->prepare($q);
    $stmt->execute([$key]);
    $data = $stmt->fetch();

    return $data ? new static($data) : new static();
  }

  /**
   * @return TaskModel[]
   */
  public static function all()
  {
    $db = Database::getInstance();

    $instance = new static();

    $columns = $instance->columns;
    $columns[] = 'id';

    $tasks = [];
    $q = 'SELECT `' . implode('`,`', $columns) . '` FROM `' . $instance->table . '` ORDER BY `id` DESC';
    $stmt = $db->query($q);
    while($data = $stmt->fetch())
      $tasks[] = new static($data);

    return $tasks;
  }

  public function create()
  {
    $db = Database::getInstance();

    $attributes = $this->getAttributes();
    $columns = array_keys($attributes);
    $attributes = array_values($attributes);

    $q = 'INSERT INTO `' . $this->table . '` (`' . implode('`,`', $columns) . '`) VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ')';
    $stmt = $db->prepare($q);
    $stmt->execute($attributes);

    $this->setAttribute('id', $db->lastInsertId());

    return $this;
  }

  public function save()
  {
    $db = Database::getInstance();

    $attributes = $this->getAttributes();
    $columns = array_keys($attributes);
    $attributes = array_values($attributes);
    $attributes[] = $this->id;

    $q = 'UPDATE `' . $this->table . '` SET `' . implode('` = ?,`', $columns) . '` = ? WHERE `id` = ? LIMIT 1';
    $stmt = $db->prepare($q);
    $stmt->execute($attributes);

    return $this;
  }

  public function delete()
  {
    $db = Database::getInstance();

    $q = 'DELETE FROM `' . $this->table . '` WHERE `id` = ? LIMIT 1';
    $stmt = $db->prepare($q);
    $stmt->execute([$this->id]);
  }
}