<?php

namespace App\Classes;

use PDO;

class Branch
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "BRANCH CLASS";
  }

  public function branch_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.branch WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function branch_id($data)
  {
    $sql = "SELECT id FROM directory.branch WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['id']) ? $row['id'] : "");
  }

  public function branch_insert($data)
  {
    $sql = "INSERT INTO directory.branch(`uuid`, `name`) VALUES(uuid(),?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }
}
