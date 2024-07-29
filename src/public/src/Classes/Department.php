<?php

namespace App\Classes;

use PDO;

class Department
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "DEPARTMENT CLASS";
  }

  public function department_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.department WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function department_id($data)
  {
    $sql = "SELECT id FROM directory.department WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['id']) ? $row['id'] : "");
  }

  public function department_insert($data)
  {
    $sql = "INSERT INTO directory.department(`uuid`, `name`) VALUES(uuid(),?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }
}
