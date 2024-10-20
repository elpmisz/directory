<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
set_time_limit(0);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\Branch;
use App\Classes\Department;
use App\Classes\Directory;
use App\Classes\Field;
use App\Classes\Group;
use App\Classes\Position;
use App\Classes\User;
use App\Classes\Subject;
use App\Classes\Validation;
use App\Classes\Zone;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$USER = new User();
$SUBJECT = new Subject();
$GROUP = new Group();
$FIELD = new Field();
$DEPARTMENT = new Department();
$ZONE = new Zone();
$BRANCH = new Branch();
$POSITION = new Position();
$DIRECTORY = new Directory();
$VALIDATION = new Validation();

try {
  define("JWT_SECRET", "SECRET-KEY");
  define("JWT_ALGO", "HS512");
  $jwt = (isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : "");
  if (empty($jwt)) {
    die(header("Location: /"));
  }
  $decode = JWT::decode($jwt, new Key(JWT_SECRET, JWT_ALGO));
  $email = (isset($decode->data) ? $decode->data : "");
} catch (Exception $e) {
  $msg = $e->getMessage();
  if ($msg === "Expired token") {
    die(header("Location: /logout"));
  }
}

$user = $USER->user_view_email([$email]);

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "create") {
  try {
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $group_id = (isset($_POST['group_id']) ? $VALIDATION->input($_POST['group_id']) : "");
    $field_id = (isset($_POST['field_id']) ? $VALIDATION->input($_POST['field_id']) : "");
    $department_id = (isset($_POST['department_id']) ? $VALIDATION->input($_POST['department_id']) : "");
    $zone_id = (isset($_POST['zone_id']) ? $VALIDATION->input($_POST['zone_id']) : "");
    $branch_id = (isset($_POST['branch_id']) ? $VALIDATION->input($_POST['branch_id']) : "");
    $position_id = (isset($_POST['position_id']) ? $VALIDATION->input($_POST['position_id']) : "");

    $directory_count = $DIRECTORY->directory_count([$email, $position_id]);
    if (intval($directory_count) > 0) {
      $VALIDATION->alert("danger", "ข้อมูลซ้ำในระบบ!", "/directory");
    }

    $DIRECTORY->directory_insert([$email, $group_id, $field_id, $department_id, $zone_id, $branch_id, $position_id]);

    foreach ($_POST['item_primary'] as $key => $value) {
      $item_key = $DIRECTORY->directory_key([$group_id]);
      $item_key = (intval($item_key));

      $item_primary = (isset($_POST['item_primary'][$key]) ? $VALIDATION->input($_POST['item_primary'][$key]) : "");
      $item_subject = (isset($_POST['item_subject'][$key]) ? $_POST['item_subject'][$key] : "");

      $primary_count = $DIRECTORY->primary_count([$group_id, $item_key, $item_primary]);
      if (intval($primary_count) === 0) {
        $DIRECTORY->primary_insert([$group_id, $item_key, $item_primary]);
      }

      if (!empty($item_subject)) {
        foreach ($item_subject as $subject) {
          $subject_count = $DIRECTORY->subject_count([$branch_id, $position_id, $item_key, $subject]);
          if (intval($subject_count) === 0) {
            $DIRECTORY->subject_insert([$branch_id, $position_id, $item_key, $subject]);
          }
        }
      }
    }

    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/directory");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "update") {
  try {
    $request_id = (isset($_POST['id']) ? $VALIDATION->input($_POST['id']) : "");
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $group_id = (isset($_POST['group_id']) ? $VALIDATION->input($_POST['group_id']) : "");
    $field_id = (isset($_POST['field_id']) ? $VALIDATION->input($_POST['field_id']) : "");
    $department_id = (isset($_POST['department_id']) ? $VALIDATION->input($_POST['department_id']) : "");
    $zone_id = (isset($_POST['zone_id']) ? $VALIDATION->input($_POST['zone_id']) : "");
    $branch_id = (isset($_POST['branch_id']) ? $VALIDATION->input($_POST['branch_id']) : "");
    $position_id = (isset($_POST['position_id']) ? $VALIDATION->input($_POST['position_id']) : "");
    $status = (isset($_POST['status']) ? $VALIDATION->input($_POST['status']) : "");

    $DIRECTORY->directory_update([$email, $group_id, $field_id, $department_id, $zone_id, $branch_id, $position_id, $status, $uuid]);

    if (!empty($_POST['item__primary'])) {
      foreach ($_POST['item__primary'] as $key => $value) {
        $item__key = $DIRECTORY->directory_key([$group_id]);
        $item__primary = (isset($_POST['item__primary'][$key]) ? $VALIDATION->input($_POST['item__primary'][$key]) : "");
        $item__subject = (isset($_POST['item__subject'][$key]) ? $_POST['item__subject'][$key] : "");

        $primary_count = $DIRECTORY->primary_count([$group_id, $item__key, $item__primary]);
        if (intval($primary_count) === 0) {
          $DIRECTORY->primary_insert([$group_id, $item__key, $item__primary]);
        }

        if (!empty($item__subject)) {
          foreach ($item__subject as $subject) {
            $subject_count = $DIRECTORY->subject_count([$branch_id, $position_id, $item__key, $subject]);
            if (intval($subject_count) === 0) {
              $DIRECTORY->subject_insert([$branch_id, $position_id, $item__key, $subject]);
            }
          }
        }
      }
    }

    foreach ($_POST['item_primary'] as $key => $value) {
      $item_key = (isset($_POST['item_key'][$key]) ? $VALIDATION->input($_POST['item_key'][$key]) : "");
      $item_primary = (isset($_POST['item_primary'][$key]) ? $VALIDATION->input($_POST['item_primary'][$key]) : "");
      $item_subject = (isset($_POST['item_subject'][$key]) ? array_filter($_POST['item_subject'][$key]) : "");

      if (!empty($item_subject)) {
        foreach ($item_subject as $subject) {
          $subject_count = $DIRECTORY->subject_count([$branch_id, $position_id, $item_key, $subject]);
          if (intval($subject_count) === 0) {
            $DIRECTORY->subject_insert([$branch_id, $position_id, $item_key, $subject]);
          }
        }
      }

      $DIRECTORY->subject_inactive([$branch_id, $position_id, $item_key]);
      if (!empty($item_subject)) {
        foreach ($item_subject as $subject) {
          $DIRECTORY->subject_active([$branch_id, $position_id, $item_key, $subject]);
        }
      }
    }

    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/directory");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "primary-delete") {
  try {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    if (!empty($id)) {
      $DIRECTORY->primary_delete([$id]);
      $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!");
      echo json_encode(200);
    } else {
      $VALIDATION->alert("danger", "ระบบมีปัญหา ลองใหม่อีกครั้ง!");
      echo json_encode(500);
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "import") {
  $file_name = (isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '');
  $file_tmp = (isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '');
  $file_allow = ["xls", "xlsx", "csv"];
  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

  if (!in_array($file_extension, $file_allow)) :
    $VALIDATION->alert("danger", "เฉพาะเอกสารนามสกุล XLS XLSX CSV!", "/directory");
  endif;

  if ($file_extension === "xls") {
    $READER = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
  } elseif ($file_extension === "xlsx") {
    $READER = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  } else {
    $READER = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
  }

  $READ = $READER->load($file_tmp);
  $result = $READ->getActiveSheet()->toArray();
  $columns = $READ->getActiveSheet()->getHighestColumn();
  $columnsIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columns);

  $results = [];
  foreach ($result as $value) {
    $results[] = array_map("trim", $value);
  }

  $data = [];
  $last = null;
  foreach ($results as $key => $current) {
    if (!empty($current[0]) || !empty($current[1])) {
      $last = $current;
      $data[] = $current;
    } else {
      if ($last !== null) {
        $data[] = array_merge(array_slice($last, 0, 7), array_slice($current, 7));
      }
    }
  }

  // echo "<pre>";
  // print_r($data);
  // die();

  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $group = (isset($value[0]) ? $VALIDATION->input($value[0]) : "");
      $group_count = $GROUP->group_count([$group]);
      if (intval($group_count) === 0 && !empty($group)) {
        $GROUP->group_insert([$group]);
      }
    }
  }
  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $field = (isset($value[1]) ? $VALIDATION->input($value[1]) : "");
      $field_count = $FIELD->field_count([$field]);
      if (intval($field_count) === 0 && !empty($field)) {
        $FIELD->field_insert([$field]);
      }
    }
  }
  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $department = (isset($value[2]) ? $VALIDATION->input($value[2]) : "");
      $department_count = $DEPARTMENT->department_count([$department]);
      if (intval($department_count) === 0 && !empty($department)) {
        $DEPARTMENT->department_insert([$department]);
      }
    }
  }
  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $zone = (isset($value[3]) ? $VALIDATION->input($value[3]) : "");
      $zone_count = $ZONE->zone_count([$zone]);
      if (intval($zone_count) === 0 && !empty($zone)) {
        $ZONE->zone_insert([$zone]);
      }
    }
  }
  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $branch = (isset($value[4]) ? $VALIDATION->input($value[4]) : "");
      $branch_count = $BRANCH->branch_count([$branch]);
      if (intval($branch_count) === 0 && !empty($branch)) {
        $BRANCH->branch_insert([$branch]);
      }
    }
  }
  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $position = (isset($value[5]) ? $VALIDATION->input($value[5]) : "");
      $position_count = $POSITION->position_count([$position]);
      if (intval($position_count) === 0 && !empty($position)) {
        $POSITION->position_insert([$position]);
      }
    }
  }

  $primary_data = [];
  foreach ($data as $key => $value) {
    if (in_array($key, [0])) {
      for ($i = 7; $i <= $columnsIndex; $i++) {
        $primary = (!empty($value[$i]) ? $VALIDATION->input($value[$i]) : "");
        if (preg_match('/^([A-Z]+\d+)(?:\s+)?(.*)$/', $primary, $matches)) {
          $primary_code = (!empty($matches[1]) ? $matches[1] : "");
          $primary_name = (!empty($matches[2]) ? $matches[2] : "");
          $count = $SUBJECT->subject_count([$primary_code]);
          if (intval(($count)) === 0 && !empty($primary_code)) {
            $SUBJECT->subject_insert([$primary_code, $primary_name, 1]);
          }

          if (!empty($primary_code)) {
            $primary_data[] = $i . "," . $primary_code;
          }
        }
      }
    }
  }

  $subject_data = [];
  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $branch = (isset($value[4]) ? $VALIDATION->input($value[4]) : "");
      $branch_id = (!empty($branch) ? $BRANCH->branch_id([$branch]) : "");
      $position = (isset($value[5]) ? $VALIDATION->input($value[5]) : "");
      $position_id = (!empty($position) ? $POSITION->position_id([$position]) : "");
      for ($i = 7; $i <= $columnsIndex; $i++) {
        $subject = (!empty($value[$i]) ? $VALIDATION->input($value[$i]) : "");
        if (preg_match('/^([A-Z]+\d+)(?:\s+)?(.*)$/', $subject, $matches)) {
          $subject_code = (!empty($matches[1]) ? $matches[1] : "");
          $subject_name = (!empty($matches[2]) ? $matches[2] : "");
          $count = $SUBJECT->subject_count([$subject_code]);
          if (intval(($count)) === 0 && !empty($subject_code)) {
            $SUBJECT->subject_insert([$subject_code, $subject_name, 2]);
          }

          if (!empty($subject_code)) {
            $subject_data[] =  $branch_id . "," . $position_id . "," . $i . "," . $subject_code;
          }
        }
      }
    }
  }

  $last = $DIRECTORY->directory_last();

  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $group = (isset($value[0]) ? $VALIDATION->input($value[0]) : "");
      $group_id = (!empty($group) ? $GROUP->group_id([$group]) : "");
      $field = (isset($value[1]) ? $VALIDATION->input($value[1]) : "");
      $field_id = (!empty($field) ? $FIELD->field_id([$field]) : "");
      $department = (isset($value[2]) ? $VALIDATION->input($value[2]) : "");
      $department_id = (!empty($department) ? $DEPARTMENT->department_id([$department]) : "");
      $zone = (isset($value[3]) ? $VALIDATION->input($value[3]) : "");
      $zone_id = (!empty($zone) ? $ZONE->zone_id([$zone]) : "");
      $branch = (isset($value[4]) ? $VALIDATION->input($value[4]) : "");
      $branch_id = (!empty($branch) ? $BRANCH->branch_id([$branch]) : "");
      $position = (isset($value[5]) ? $VALIDATION->input($value[5]) : "");
      $position_id = (!empty($position) ? $POSITION->position_id([$position]) : "");
      $email = (isset($value[6]) ? $VALIDATION->input($value[6]) : "");

      $directory_count = $DIRECTORY->directory_count([$email, $position_id]);
      if (intval($directory_count) === 0) {
        $DIRECTORY->directory_insert([$email, $group_id, $last, $field_id, $department_id, $zone_id, $branch_id, $position_id]);

        foreach ($primary_data as $subject) {
          $sub = explode(",", $subject);
          $primary_count = $DIRECTORY->primary_count([$group_id, $last, $sub[0], $sub[1]]);
          if (intval($primary_count) === 0 && !empty($sub[0]) && !empty($sub[1])) {
            $DIRECTORY->primary_insert([$group_id, $last, $sub[0], $sub[1]]);
          }
        }

        foreach ($subject_data as $subject) {
          $sub = explode(",", $subject);
          $subject_count = $DIRECTORY->subject_count([$sub[0], $sub[1], $sub[2], $sub[3]]);
          if (intval($subject_count) === 0 && !empty($sub[2]) && !empty($sub[3])) {
            $DIRECTORY->subject_insert([$sub[0], $sub[1], $sub[2], $sub[3]]);
          }
        }
      }
    }
  }

  $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/directory");
}

if ($action === "data") {
  try {
    $group = (isset($_POST['group']) ? $VALIDATION->input($_POST['group']) : "");
    $primary = (isset($_POST['primary']) ? $VALIDATION->input($_POST['primary']) : "");
    $subject = (isset($_POST['subject']) ? $VALIDATION->input($_POST['subject']) : "");
    $result = $DIRECTORY->directory_data($group, $primary, $subject);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "group-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->group_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "field-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->field_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "department-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->department_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "zone-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->zone_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "branch-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->branch_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "position-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->position_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "primary-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->primary_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "subject-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->subject_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
