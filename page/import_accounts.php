<?php

DB::query("TRUNCATE accounts");

$file = fopen(BASE_DIR.'/dev/accounts.txt', 'r');
while(!feof($file)) {
  $str = fgets($file);
  if (empty($str)) continue;
  $str = str_replace("\r\n", "", $str);
  $row = explode(",", $str);
  $display_name = explode(" ", $row[0]);

  $surname    = !empty($display_name[0]) ? $display_name[0] : "";
  $name       = !empty($display_name[1]) ? $display_name[1] : "";
  $patronymic = !empty($display_name[2]) ? $display_name[2] : "";

  $row[1]     = mb_strtolower($row[1]);
  $account    = str_replace("@kms.scac.ru", "", $row[1]);
  $mail       = $row[1];

  DB::query("INSERT INTO accounts (surname,name,patronymic,account,mail) VALUES (?,?,?,?,?)",
  $surname, $name, $patronymic, $account, $mail);
}
fclose($file);
echo "complete";