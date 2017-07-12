<?php

set_time_limit(0);
//gnore_user_abort(true);

define('DB_DATABASE', 'log-session');
define('DB_USER',     'root');
define('DB_PASSWORD', '');
define('DB_HOST',     '127.0.0.1');
define('BASE_DIR',    dirname(dirname(__FILE__)) );

ini_set('display_errors', 'on');
ini_set("memory_limit", "-1");

include BASE_DIR.'/lib/exceptions.php';
include BASE_DIR.'/lib/db.php';
include BASE_DIR.'/lib/function.php';

DB::connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);





if (!empty($_POST)) {
  $table  = !empty($_POST['table']) ? $_POST['table'] : false;
  $value  = !empty($_POST['value']) ? $_POST['value'] : false;
  $sid    = !empty($_POST['sid']) ? $_POST['sid'] : false;
  $code   = !empty($_POST['code']) ? $_POST['code'] : false;
  $object = ($table == "computers") ? "computer" : "account";

  unset($_POST['table']);
  unset($_POST['value']);
  unset($_POST['sid']);
  unset($_POST['code']);
  unset($_POST['save']);

  if (empty($code) || $code != '300572') {
    echoStop('Неверный код');
  }

  $id = getKeyOnSid($sid);
  $hash = array();
  foreach ($_POST as $key=>$value)
    $hash[$key] = $value;

  if (empty($table) || empty($id) || empty($table)) {
    echoStop('Ошибка сохранения данных');
  }

  DB::update($table, $id, $hash);

  echoStop('Данные успешно сохранены');
}
echoStop('Ошибка сохранения данных');