<?php

namespace App\Classes;

use PDO;

class Directory
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "DIRECTORY CLASS";
  }

  public function directory_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.directory_request WHERE email = ? AND status = 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function directory_insert($data)
  {
    $sql = "INSERT INTO directory.directory_request(`uuid`, `email`, `group_id`, `field_id`, `department_id`, `zone_id`, `branch_id`, `position_id`) VALUES(uuid(),?,?,?,?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function directory_view($data)
  {
    $sql = "SELECT a.id,a.`uuid`,a.email,a.`status`,
    a.group_id,b.`name` group_name,a.field_id,c.`name` field_name,a.department_id,d.`name` department_name,
    a.zone_id,e.`name` zone_name,a.branch_id,f.`name` branch_name,a.position_id,g.`name` position_name
    FROM directory.directory_request a
    LEFT JOIN directory.`group` b
    ON a.group_id = b.id
    LEFT JOIN directory.`field` c
    ON a.field_id = c.id
    LEFT JOIN directory.department d
    ON a.department_id = d.id
    LEFT JOIN directory.`zone` e
    ON a.zone_id = e.id
    LEFT JOIN directory.branch f
    ON a.branch_id = f.id
    LEFT JOIN directory.`position` g
    ON a.position_id = g.id
    WHERE a.`uuid` = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function directory_export($data)
  {
    $sql = "SELECT a.uuid,a.email,b.`name` group_name,c.`name` field_name,d.`name` department_name,
    e.`name` zone_name,f.`name` branch_name,g.`name` position_name,
    GROUP_CONCAT(h.id ORDER BY h.id ASC) `primary`
    FROM directory.directory_request a
    LEFT JOIN directory.`group` b
    ON a.group_id = b.id
    LEFT JOIN directory.`field` c
    ON a.field_id = c.id
    LEFT JOIN directory.department d
    ON a.department_id = d.id
    LEFT JOIN directory.`zone` e
    ON a.zone_id = e.id
    LEFT JOIN directory.branch f
    ON a.branch_id = f.id
    LEFT JOIN directory.`position` g
    ON a.position_id = g.id
    LEFT JOIN directory.directory_primary h
    ON a.id = h.request_id
    WHERE a.status = 1
    AND a.group_id = ?
    GROUP BY a.id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll();
  }

  public function directory_group($data)
  {
    $sql = "SELECT GROUP_CONCAT(DISTINCT '[',b.subject_code,'] ',c.`name` ORDER BY b.id ASC) `subject`
    FROM directory.directory_request a
    LEFT JOIN directory.directory_primary b
    ON a.id = b.request_id
    LEFT JOIN directory.`subject` c
    ON b.subject_code = c.`code`
    WHERE a.group_id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch();
  }

  public function directory_subject($data)
  {
    $sql = "SELECT CONCAT('[',a.subject_code,'] ',b.`name`) `subject`
    FROM directory.directory_subject a
    LEFT JOIN directory.`subject` b
    ON a.subject_code = b.`code`
    WHERE a.primary_id = ?
    GROUP BY a.subject_code
    ORDER BY a.id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll();
  }

  public function group_count($data)
  {
    $sql = "SELECT COUNT(b.id) total
    FROM directory.directory_request a
    LEFT JOIN directory.directory_primary b
    ON a.id = b.request_id
    LEFT JOIN directory.directory_subject c
    ON b.id = c.primary_id
    WHERE a.group_id = ?
    GROUP BY b.id
    ORDER BY COUNT(b.id) DESC
    LIMIT 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function primary_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.directory_primary WHERE request_id = ? AND subject_code = ? AND status = 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function primary_id($data)
  {
    $sql = "SELECT a.id FROM directory.directory_primary a WHERE a.request_id = ? AND a.key = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    return (isset($row['id']) ? $row['id'] : "");
  }

  public function primary_insert($data)
  {
    $sql = "INSERT INTO directory.directory_primary(`request_id`, `key`, `subject_code`) VALUES(?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function primary_view($data)
  {
    $sql = "SELECT b.id, b.subject_code,CONCAT('[',b.subject_code,'] ',c.`name`) subject_name
    FROM directory.directory_request a
    LEFT JOIN directory.directory_primary b
    ON a.id = b.request_id
    LEFT JOIN directory.`subject` c
    ON b.subject_code = c.`code`
    WHERE a.`uuid` = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll();
  }

  public function subject_count($data)
  {
    $sql = "SELECT COUNT(*) FROM directory.directory_subject WHERE primary_id = ? AND subject_code = ? AND status = 1";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function subject_insert($data)
  {
    $sql = "INSERT INTO directory.directory_subject(`primary_id`, `subject_code`) VALUES(?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_view($data)
  {
    $sql = "SELECT b.id, b.subject_code,CONCAT('[',b.subject_code,'] ',c.`name`) subject_name
    FROM directory.directory_primary a
    LEFT JOIN directory.directory_subject b
    ON a.id = b.primary_id
    LEFT JOIN directory.`subject` c
    ON b.subject_code = c.`code`
    WHERE a.id = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll();
  }

  public function directory_data($group = null)
  {
    $sql = "SELECT COUNT(*) FROM directory.directory_request WHERE status = 1";
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

    $sql = "SELECT a.id,a.`uuid`,a.email,
    b.`name` group_name,c.`name` field_name,d.`name` department_name,
    e.`name` zone_name,f.`name` branch_name,g.`name` position_name,
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
    FROM directory.directory_request a
    LEFT JOIN directory.`group` b
    ON a.group_id = b.id
    LEFT JOIN directory.`field` c
    ON a.field_id = c.id
    LEFT JOIN directory.department d
    ON a.department_id = d.id
    LEFT JOIN directory.`zone` e
    ON a.zone_id = e.id
    LEFT JOIN directory.branch f
    ON a.branch_id = f.id
    LEFT JOIN directory.`position` g
    ON a.position_id = g.id
    WHERE a.`status` IN (1,2) ";

    if (!empty($keyword)) {
      $sql .= " AND (a.code LIKE '%{$keyword}%' OR a.name LIKE '%{$keyword}%') ";
    }
    if (!empty($group)) {
      $sql .= " AND a.group_id = '{$group}' ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.status ASC, b.id ASC,c.id ASC,d.id ASC,e.id,f.id ASC,g.id ASC ";
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
      $action = "<a href='/directory/edit/{$row['uuid']}' class='badge badge-{$row['status_color']} font-weight-light'>{$row['status_name']}</a> <a href='javascript:void(0)' class='badge badge-danger font-weight-light btn-delete' id='{$row['uuid']}'>ลบ</a>";

      if (!empty($row['email'])) {
        $data[] = [
          $action,
          $row['email'],
          $row['group_name'],
          $row['field_name'],
          $row['department_name'],
          $row['zone_name'],
          $row['branch_name'],
          $row['position_name'],
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

  public function group_select($keyword)
  {
    $sql = "SELECT id, name `text`
    FROM directory.group
    WHERE status = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND name LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY name ORDER BY name ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function field_select($keyword)
  {
    $sql = "SELECT id, name `text`
    FROM directory.field
    WHERE status = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND name LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY name ORDER BY name ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function department_select($keyword)
  {
    $sql = "SELECT id, name `text`
    FROM directory.department
    WHERE status = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND name LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY name ORDER BY name ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function zone_select($keyword)
  {
    $sql = "SELECT id, name `text`
    FROM directory.zone
    WHERE status = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND name LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY name ORDER BY name ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function branch_select($keyword)
  {
    $sql = "SELECT id, name `text`
    FROM directory.branch
    WHERE status = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND name LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY name ORDER BY name ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function position_select($keyword)
  {
    $sql = "SELECT id, name `text`
    FROM directory.position
    WHERE status = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND name LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY name ORDER BY name ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function primary_select($keyword)
  {
    $sql = "SELECT a.`code` `id`,CONCAT('[',a.`code`,'] ',a.`name`) `text`
    FROM directory.`subject` a
    WHERE a.`type` = 1
    AND a.`status` = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND a.code LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY a.code ORDER BY a.code ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function subject_select($keyword)
  {
    $sql = "SELECT a.`code` `id`,CONCAT('[',a.`code`,'] ',a.`name`) `text`
    FROM directory.`subject` a
    WHERE a.`type` = 2
    AND a.`status` = 1 ";
    if (!empty($keyword)) {
      $sql .= " AND a.code LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY a.code ORDER BY a.code ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }


  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}