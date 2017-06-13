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





$d = !empty($_POST['val']) ? $_POST['val'] : false;

if (empty($d)) return false;

$result = "";
$computers = DB::getRows('SELECT name FROM computers WHERE name!="" AND name LIKE "%'.$d.'%"');
foreach ($computers as $computer) {
  if (empty($computer['name'])) continue;
  $result .= getLinkComputer($computer['name']).'<br/>';
}

$accounts = DB::getRows('SELECT account FROM accounts WHERE (surname!="" AND surname LIKE "%'.$d.'%") OR (account!="" AND account LIKE "%'.$d.'%")');
if (!empty($result) && !empty($accounts)) $result .= '<br/>';
foreach ($accounts as $account) {
  if (empty($account['account'])) continue;
  $result .= getLinkAccounts($account['account']).'<br/>';
}

$sps = DB::getRows('SELECT * FROM sp WHERE (name!="" AND name LIKE "%'.$d.'%") OR (code!="" AND code LIKE "%'.$d.'%")');
if (!empty($result) && !empty($sps)) $result .= '<br/>';
foreach ($sps as $sp) {
  if (empty($sp['name'])) continue;
  $result .= getLinkSp($sp).'<br/>';
}

echo "<br/>".$result;
exit();