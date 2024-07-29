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
