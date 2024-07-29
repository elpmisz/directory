<?php

namespace App\Classes;

use PDO;

class Group
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "GROUP CLASS";
  }

  public function group_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.group WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function group_id($data)
  {
    $sql = "SELECT id FROM directory.group WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['id']) ? $row['id'] : "");
  }

  public function group_insert($data)
  {
    $sql = "INSERT INTO directory.group(`uuid`, `name`) VALUES(uuid(),?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }
}
