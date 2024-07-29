<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\User;
use App\Classes\Subject;
use App\Classes\Validation;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

$USER = new User();
$SUBJECT = new Subject();
$VALIDATION = new Validation();

$user = $USER->user_view_email([$email]);

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "create") {
  try {
    $code = (isset($_POST['code']) ? $VALIDATION->input($_POST['code']) : "");
    $name = (isset($_POST['name']) ? $VALIDATION->input($_POST['name']) : "");
    $type = (isset($_POST['type']) ? $VALIDATION->input($_POST['type']) : "");

    $count = $SUBJECT->subject_count([$code]);
    if (intval($count) === 0 && !empty($code) && !empty($name)) {
      $SUBJECT->subject_insert([$code, $name, $type]);
    }
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/subject");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "update") {
  try {
    $id = (isset($_POST['id']) ? $VALIDATION->input($_POST['id']) : "");
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $code = (isset($_POST['code']) ? $VALIDATION->input($_POST['code']) : "");
    $name = (isset($_POST['name']) ? $VALIDATION->input($_POST['name']) : "");
    $type = (isset($_POST['type']) ? $VALIDATION->input($_POST['type']) : "");
    $status = (isset($_POST['status']) ? $VALIDATION->input($_POST['status']) : "");

    $SUBJECT->subject_update([$code, $name, $type, $status, $uuid]);

    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/subject/edit/{$uuid}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "delete") {
  try {
    $data = json_decode(file_get_contents("php://input"), true);
    $uuid = $data['uuid'];

    if (!empty($uuid)) {
      $SUBJECT->subject_delete([$uuid]);
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

if ($action === "data") {
  try {
    $result = $SUBJECT->subject_data();
    echo json_encode($result);
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
    $VALIDATION->alert("danger", "ONLY XLS XLSX CSV!", "/subject");
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

  $data = [];
  foreach ($result as $value) {
    $data[] = array_map("trim", $value);
  }

  foreach ($data as $key => $value) {
    if (!in_array($key, [0])) {
      $code = (isset($value[0]) ? $value[0] : "");
      $name = (isset($value[1]) ? $value[1] : "");
      $type = (isset($value[2]) ? $value[2] : "");
      $type = (!empty($type) && $type === "สมรรถนะ" ? 1 : 2);

      $count = $SUBJECT->subject_count([$code]);
      if (intval($count) === 0 && !empty($code) && !empty($name)) {
        $SUBJECT->subject_insert([$code, $name, $type]);
      } else {
        $SUBJECT->subject_import([$name, $type, $code]);
      }
    }
  }

  $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/subject");
}
