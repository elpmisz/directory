<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");
$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$group = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Directory;
use App\Classes\Validation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$DIRECTORY = new Directory();
$VALIDATION = new Validation();
$SPREADSHEET = new Spreadsheet();
$WRITER = new Xlsx($SPREADSHEET);

$SPREADSHEET->setActiveSheetIndex(0);
$ACTIVESHEET = $SPREADSHEET->getActiveSheet();

$result = $DIRECTORY->directory_export([$group]);
$topic = $DIRECTORY->directory_group([$group]);
$topic = explode(",", $topic['subject']);
$columns = ["กลุ่มงาน", "สายงาน", "ฝ่าย/ภาค", "ส่วน/เขต", "หน่วย/สาขา", "ตำแหน่ง", "E-Mail"];
$columns = array_merge($columns, $topic);

$data_count = null;

$info_data = [];
$subjects_data = [];
foreach ($result as $row) {
  $info_data[] = [
    $row['group_name'],
    $row['field_name'],
    $row['department_name'],
    $row['zone_name'],
    $row['branch_name'],
    $row['position_name'],
    $row['email'],
  ];

  $data_count = $DIRECTORY->data_count([$row['branch_id'], $row['position_id']]);

  $subject_data = [];
  $topics = explode(",", $row['primary']);
  foreach ($topics as $value) {
    $subject = $DIRECTORY->directory_subject([$row['branch_id'], $row['position_id'], $value]);
    $subjects = [];
    foreach ($subject as $sub) {
      $subjects[] = $sub['subject'];
    }
    $subject_data[] = $subjects;
  }
  $subjects_data[] = $subject_data;
}

$datas = [];
foreach ($subjects_data as $sub) {
  $subjects = [];
  for ($i = 0; $i < $data_count; $i++) {
    $subjects[$i] = [];
    foreach ($sub as $arr) {
      $subjects[$i][] = (isset($arr[$i]) ? $arr[$i] : "");
    }
  }
  $datas[] = $subjects;
}

$data = [];
foreach ($info_data as $key => $info) {
  foreach ($datas[$key] as $value) {
    $data[] = array_merge($info, $value);
  }
}

ob_start();
$date = date('Ymd');
$filename = "{$date}_directory.csv";
header("Content-Encoding: UTF-8");
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename={$filename}");
ob_end_clean();

$output = fopen("php://output", "w");
fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
fputcsv($output, $columns);

foreach ($data as $value) {
  fputcsv($output, $value);
}

fclose($output);
die();
