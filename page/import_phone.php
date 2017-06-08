<?php

$file = fopen(BASE_DIR.'/dev/phone.txt', 'r');
while(!feof($file)) {
  $str = fgets($file);
  if (empty($str)) continue;
  $str = str_replace("\r\n", "", $str);
  $str = str_replace(" ", "", $str);
  $row = explode(";", $str);

  $surname    = $row[0];
  $name       = $row[1];
  $patronymic = $row[2];
  $phone      = str_replace("-", "", $row[3]);

  //echo $surname.' '.$name.' '.$patronymic.' '.$phone.'<br/>';
  DB::query("UPDATE accounts SET phone=? WHERE surname=? AND name=? AND patronymic=?", $phone, $surname, $name, $patronymic);
}
fclose($file);
echo "complete";