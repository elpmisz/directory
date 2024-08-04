<?php
require_once(__DIR__ . "/vendor/autoload.php");

$ROUTER = new AltoRouter();

##################### SERVICE #####################
################### DIRECTORY ###################
$ROUTER->map("GET", "/directory", function () {
  require(__DIR__ . "/src/Views/directory/index.php");
});
$ROUTER->map("GET", "/directory/create", function () {
  require(__DIR__ . "/src/Views/directory/create.php");
});
$ROUTER->map("GET", "/directory/export/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/directory/export.php");
});
$ROUTER->map("GET", "/directory/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/directory/edit.php");
});
$ROUTER->map("POST", "/directory/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/directory/action.php");
});


##################### SETTING #####################
################### SUBJECT ###################
$ROUTER->map("GET", "/subject", function () {
  require(__DIR__ . "/src/Views/subject/index.php");
});
$ROUTER->map("GET", "/subject/create", function () {
  require(__DIR__ . "/src/Views/subject/create.php");
});
$ROUTER->map("GET", "/subject/export", function () {
  require(__DIR__ . "/src/Views/subject/export.php");
});
$ROUTER->map("GET", "/subject/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/subject/edit.php");
});
$ROUTER->map("POST", "/subject/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/subject/action.php");
});

################### GROUP ###################
$ROUTER->map("GET", "/group", function () {
  require(__DIR__ . "/src/Views/group/index.php");
});
$ROUTER->map("GET", "/group/create", function () {
  require(__DIR__ . "/src/Views/group/create.php");
});
$ROUTER->map("GET", "/group/export", function () {
  require(__DIR__ . "/src/Views/group/export.php");
});
$ROUTER->map("GET", "/group/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/group/edit.php");
});
$ROUTER->map("POST", "/group/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/group/action.php");
});

################### FIELD ###################
$ROUTER->map("GET", "/field", function () {
  require(__DIR__ . "/src/Views/field/index.php");
});
$ROUTER->map("GET", "/field/create", function () {
  require(__DIR__ . "/src/Views/field/create.php");
});
$ROUTER->map("GET", "/field/export", function () {
  require(__DIR__ . "/src/Views/field/export.php");
});
$ROUTER->map("GET", "/field/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/field/edit.php");
});
$ROUTER->map("POST", "/field/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/field/action.php");
});

################### DEPARTMENT ###################
$ROUTER->map("GET", "/department", function () {
  require(__DIR__ . "/src/Views/department/index.php");
});
$ROUTER->map("GET", "/department/create", function () {
  require(__DIR__ . "/src/Views/department/create.php");
});
$ROUTER->map("GET", "/department/export", function () {
  require(__DIR__ . "/src/Views/department/export.php");
});
$ROUTER->map("GET", "/department/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/department/edit.php");
});
$ROUTER->map("POST", "/department/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/department/action.php");
});

################### ZONE ###################
$ROUTER->map("GET", "/zone", function () {
  require(__DIR__ . "/src/Views/zone/index.php");
});
$ROUTER->map("GET", "/zone/create", function () {
  require(__DIR__ . "/src/Views/zone/create.php");
});
$ROUTER->map("GET", "/zone/export", function () {
  require(__DIR__ . "/src/Views/zone/export.php");
});
$ROUTER->map("GET", "/zone/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/zone/edit.php");
});
$ROUTER->map("POST", "/zone/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/zone/action.php");
});

################### BRANCH ###################
$ROUTER->map("GET", "/branch", function () {
  require(__DIR__ . "/src/Views/branch/index.php");
});
$ROUTER->map("GET", "/branch/create", function () {
  require(__DIR__ . "/src/Views/branch/create.php");
});
$ROUTER->map("GET", "/branch/export", function () {
  require(__DIR__ . "/src/Views/branch/export.php");
});
$ROUTER->map("GET", "/branch/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/branch/edit.php");
});
$ROUTER->map("POST", "/branch/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/branch/action.php");
});

################### POSITION ###################
$ROUTER->map("GET", "/position", function () {
  require(__DIR__ . "/src/Views/position/index.php");
});
$ROUTER->map("GET", "/position/create", function () {
  require(__DIR__ . "/src/Views/position/create.php");
});
$ROUTER->map("GET", "/position/export", function () {
  require(__DIR__ . "/src/Views/position/export.php");
});
$ROUTER->map("GET", "/position/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/position/edit.php");
});
$ROUTER->map("POST", "/position/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/position/action.php");
});


##################### SYETEM #####################
$ROUTER->map("GET", "/system", function () {
  require(__DIR__ . "/src/Views/system/index.php");
});
$ROUTER->map("POST", "/system/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/system/action.php");
});

##################### USER #####################
$ROUTER->map("GET", "/user", function () {
  require(__DIR__ . "/src/Views/user/index.php");
});
$ROUTER->map("GET", "/user/create", function () {
  require(__DIR__ . "/src/Views/user/create.php");
});
$ROUTER->map("GET", "/user/profile", function () {
  require(__DIR__ . "/src/Views/user/profile.php");
});
$ROUTER->map("GET", "/user/change", function () {
  require(__DIR__ . "/src/Views/user/change.php");
});
$ROUTER->map("GET", "/user/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/user/edit.php");
});
$ROUTER->map("POST", "/user/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/user/action.php");
});

##################### AUTH #####################
$ROUTER->map("GET", "/", function () {
  require(__DIR__ . "/src/Views/home/login.php");
});
$ROUTER->map("GET", "/home", function () {
  require(__DIR__ . "/src/Views/home/index.php");
});
$ROUTER->map("GET", "/info", function () {
  require(__DIR__ . "/src/Views/home/info.php");
});
$ROUTER->map("GET", "/error", function () {
  require(__DIR__ . "/src/Views/home/error.php");
});
$ROUTER->map("POST", "/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/home/action.php");
});
$ROUTER->map("GET", "/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/home/action.php");
});


$MATCH = $ROUTER->match();

if (is_array($MATCH) && is_callable($MATCH["target"])) {
  call_user_func_array($MATCH["target"], $MATCH["params"]);
} else {
  header("HTTP/1.1 404 Not Found");
  require_once(__DIR__ . "/src/Views/home/error.php");
}
