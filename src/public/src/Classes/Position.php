<?php

namespace App\Classes;

use PDO;

class Position
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "POSITIN CLASS";
  }

  public function position_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.position WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function position_id($data)
  {
    $sql = "SELECT id FROM directory.position WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['id']) ? $row['id'] : "");
  }

  public function position_insert($data)
  {
    $sql = "INSERT INTO directory.position(`uuid`, `name`) VALUES(uuid(),?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }
}