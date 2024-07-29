<?php

namespace App\Classes;

use PDO;

class Field
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "FIELD CLASS";
  }

  public function field_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.field WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function field_id($data)
  {
    $sql = "SELECT id FROM directory.field WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['id']) ? $row['id'] : "");
  }

  public function field_insert($data)
  {
    $sql = "INSERT INTO directory.field(`uuid`, `name`) VALUES(uuid(),?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }
}
