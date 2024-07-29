<?php

namespace App\Classes;

use PDO;

class Zone
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "ZONE CLASS";
  }

  public function zone_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.zone WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function zone_id($data)
  {
    $sql = "SELECT id FROM directory.zone WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['id']) ? $row['id'] : "");
  }

  public function zone_insert($data)
  {
    $sql = "INSERT INTO directory.zone(`uuid`, `name`) VALUES(uuid(),?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }
}
