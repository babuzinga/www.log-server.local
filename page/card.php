<?php

$table = false;
if (!empty($_GET['o'])) {
  if ($_GET['o'] == "computername") {
    $table = "computers";
    $filed = "name";
  }
  if ($_GET['o'] == "account") {
    $table = "accounts";
    $filed = "account";
  }
}

$value = !empty($_GET['v']) ? $_GET['v'] : false;

if (empty($table) || empty($value)) {
  echo "Ошибка получения данных";
} else {
  $rows = DB::singleRow("SELECT * FROM {$table} WHERE {$filed}=?", $value);
  echo "<pre>".print_r($rows,true)."</pre>";
}