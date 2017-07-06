<?php

$file = fopen(BASE_DIR.'/dev/1c_moscow.txt', 'r');
while(!feof($file)) {
  $str = fgets($file);
  if (empty($str)) continue;
  $str = str_replace("\r\n", "", $str);
  $row = explode(";", $str);

  $fio        = $row[0];
  $tab        = $row[1];
  $position   = $row[2];

  $sp1        = $row[3];
  $sp1_name   = $row[4];
  $sp2        = $row[5];
  $sp2_name   = $row[6];

  $fio        = explode(" ", $fio);
  $surname    = $fio[0];
  $name       = $fio[1];
  $patronymic = $fio[2];

  // -----------------------------------------------------------------------
  $ac = DB::singleRow("SELECT id, tab FROM accounts WHERE surname=? AND name=? AND patronymic=?", $surname, $name, $patronymic);
  if (!empty($ac) && empty($ac['tab'])) {
    DB::query("UPDATE accounts SET sp1=?, sp2=?, tab=?, position=? WHERE surname=? AND name=? AND patronymic=?",
      $sp1, $sp2, $tab, $position, $surname, $name, $patronymic);



    // -----------------------------------------------------------------------
    if (!empty($sp1)) {
      $sp = DB::scalarSelect("SELECT code FROM sp WHERE code=?", $sp1);
      if (empty($sp))
        DB::query("INSERT INTO sp (code,name,level) VALUES (?,?,1)", $sp1, $sp1_name);
    }
    // -----------------------------------------------------------------------
    if (!empty($sp2)) {
      $sp = DB::scalarSelect("SELECT code FROM sp WHERE code=?", $sp2);
      if (empty($sp))
        DB::query("INSERT INTO sp (code,name,level) VALUES (?,?,2)", $sp2, $sp2_name);
    }
    // -----------------------------------------------------------------------
  }
}
fclose($file);
echo "complete";