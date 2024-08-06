<?php

namespace App\Classes;

use PDO;

class Dashboard
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "Dashboard CLASS";
  }

  public function dashboard_card()
  {
    $sql = "SELECT COUNT(*) request,
    (SELECT COUNT(*) FROM directory.`subject` WHERE `type` = 1) `primary`,
    (SELECT COUNT(*) FROM directory.`subject` WHERE `type` = 2) subject,
    (SELECT COUNT(*) FROM directory.`group`) `group`,
    (SELECT COUNT(*) FROM directory.`field`) `field`,
    (SELECT COUNT(*) FROM directory.`department`) `department`,
    (SELECT COUNT(*) FROM directory.`zone`) `zone`,
    (SELECT COUNT(*) FROM directory.`branch`) `branch`,
    (SELECT COUNT(*) FROM directory.`position`) `position`
    FROM directory.directory_request";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetch();
  }
}
