<?php

namespace Sys;

class Hashes
{
  private static $instance = null;

  public static function getInstance()
  {
    if(static::$instance === null)
      static::$instance = new static;

    return static::$instance;
  }

  private function genHash()
  {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
    $counter = mb_strlen($pattern) - 1;
    for($i = 0; $i < 10; ++$i)
      $key .= $pattern{rand(0, $counter)};

    return md5($key);
  }

  public function setHash($type, $id, $data = null)
  {
    $db = Database::getInstance();

    $hash = $this->genHash();
    $json_data = json_encode($data);

    $q = 'insert into `hashes` (`type`, `id`, `hash`, `data`) values (?, ?, ?, ?) on duplicate key update `hash` = ?, `data` = ?';
    $stmt = $db->prepare($q) or die($db->error);
    $stmt->bind_param('sissss', $type, $id, $hash, $json_data, $hash, $json_data);
    $stmt->execute() or die($db->error);
    $stmt->close();

    return $hash;
  }

  public function checkHash($type, $id, $checkHash, &$data = null)
  {
    $db = Database::getInstance();

    $hash = null;
    $q = 'select `hash`, `data` from `hashes` where `type` = ? and `id` = ? limit 1';
    $stmt = $db->prepare($q) or die($db->error);
    $stmt->bind_param('si', $type, $id);
    $stmt->execute() or die($db->error);
    $stmt->bind_result($hash, $data);
    $stmt->fetch();
    $stmt->close();
    $data = json_decode($data, true);

    $q = 'delete from `hashes` where `type` = ? and `id` = ? limit 1';
    $stmt = $db->prepare($q) or die($db->error);
    $stmt->bind_param('si', $type, $id);
    $stmt->execute() or die($db->error);
    $stmt->close();

    return $hash === $checkHash;
  }
}