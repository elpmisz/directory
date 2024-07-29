<?php

namespace App\Classes;

use PDO;

class Subject
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "SUBJECT CLASS";
  }

  public function subject_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.subject WHERE code = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function subject_code($data)
  {
    $sql = "SELECT code FROM directory.subject WHERE name = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['code']) ? $row['code'] : "");
  }

  public function subject_view($data)
  {
    $sql = "SELECT * FROM directory.subject WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function subject_insert($data)
  {
    $sql = "INSERT INTO directory.subject(`uuid`, `code`, `name`, `type`) VALUES(uuid(),?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_update($data)
  {
    $sql = "UPDATE directory.subject SET
    code = ?, 
    name = ?,
    type = ?,
    status = ?,
    updated = NOW()
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_delete($data)
  {
    $sql = "UPDATE directory.subject SET 
    status = 0,
    updated = NOW()
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_export()
  {
    $sql = "SELECT a.code,a.name,
    (
      CASE
        WHEN a.type = 1 THEN 'สมรรถนะ'
        WHEN a.type = 2 THEN 'รายวิชา'
        ELSE NULL
      END
    ) type 
    FROM directory.subject a
    WHERE status = 1
    ORDER BY a.status ASC, a.type ASC, a.code ASC";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_NUM);
  }

  public function subject_import($data)
  {
    $sql = "UPDATE directory.subject SET
    name = ?,
    type = ?,
    updated = NOW()
    WHERE code = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_data()
  {
    $sql = "SELECT COUNT(*) FROM directory.subject WHERE type = 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $column = ["a.id", "a.email", "a.position", "a.department", "a.area", "a.field", "a.group", "b.text", "c.text"];

    $keyword = (isset($_POST['search']['value']) ? trim($_POST['search']['value']) : "");
    $filter_order = (isset($_POST['order']) ? $_POST['order'] : "");
    $order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
    $order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
    $limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
    $limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
    $draw = (isset($_REQUEST['draw']) ? $_REQUEST['draw'] : "");

    $sql = "SELECT a.id,a.uuid,a.code,a.name,a.type,
    (
      CASE
        WHEN a.type = 1 THEN 'สมรรถนะ'
        WHEN a.type = 2 THEN 'รายวิชา'
        ELSE NULL
      END
    ) type_name,
    (
      CASE
        WHEN a.type = 1 THEN 'primary'
        WHEN a.type = 2 THEN 'warning'
        ELSE NULL
      END
    ) type_color,
    (
      CASE
        WHEN a.status = 1 THEN 'ใช้งาน'
        WHEN a.status = 2 THEN 'ระงับการใช้งาน'
        WHEN a.status = 0 THEN 'ลบ' 
        ELSE NULL
      END
    ) status_name,
    (
      CASE
        WHEN a.status = 1 THEN 'success'
        WHEN a.status = 2 THEN 'danger'
        WHEN a.status = 0 THEN 'warning' 
        ELSE NULL
      END
    ) status_color
    FROM directory.subject a
    WHERE a.status IN (1,2)
    AND a.`type` IN (1,2) ";

    if (!empty($keyword)) {
      $sql .= " AND (a.code LIKE '%{$keyword}%' OR a.name LIKE '%{$keyword}%') ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.status ASC, a.type ASC, a.code ASC ";
    }

    $sql2 = "";
    if ($limit_length) {
      $sql2 .= "LIMIT {$limit_start}, {$limit_length}";
    }

    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $filter = $stmt->rowCount();
    $stmt = $this->dbcon->prepare($sql . $sql2);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($result as $row) {
      $action = "<a href='/subject/edit/{$row['uuid']}' class='badge badge-{$row['status_color']} font-weight-light'>{$row['status_name']}</a> <a href='javascript:void(0)' class='badge badge-danger font-weight-light btn-delete' id='{$row['uuid']}'>ลบ</a>";
      $subject = "<a href='javascript:void(0)' class='badge badge-{$row['type_color']} font-weight-light'>{$row['type_name']}</a>";

      if (!empty($row['name'])) {
        $data[] = [
          $action,
          $row['code'],
          $row['name'],
          $subject,
        ];
      }
    }

    $output = [
      "draw"    => $draw,
      "recordsTotal"  =>  $total,
      "recordsFiltered" => $filter,
      "data"    => $data
    ];
    return $output;
  }
}
