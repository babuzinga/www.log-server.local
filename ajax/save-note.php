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





$note = !empty($_POST['note_text']) ? $_POST['note_text'] : "";
$alias = !empty($_POST['alias']) ? $_POST['alias'] : false;

if (empty($alias)) return false;

if (empty($note) && !empty($alias)) {
  DB::query("DELETE FROM notes WHERE alias=?", $alias);
  echo true;
  exit();
}

$id = DB::singleRow("SELECT * FROM notes WHERE alias=?", $alias);
if (empty($id)) {
  DB::query("INSERT INTO notes (note,alias) VALUES (?,?)", $note, $alias);
} else {
  DB::query("UPDATE notes SET note=? WHERE alias=?", $note, $alias);
}

echo true;
exit();