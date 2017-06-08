<?php

DB::query("TRUNCATE sp");

$file = fopen(BASE_DIR.'/dev/1c.txt', 'r');
while(!feof($file)) {
  $str = fgets($file);
  if (empty($str)) continue;
  $str = str_replace("\r\n", "", $str);
  $row = explode(";", $str);

  $sp1        = $row[0];
  $sp1_name   = $row[1];
  $sp2        = $row[2];
  $sp2_name   = $row[3];
  $sp3        = $row[4];
  $sp3_name   = $row[5];

  $surname    = $row[6];
  $tab        = $row[7];
  $name       = $row[8];
  $patronymic = $row[9];
  $position   = $row[10];

  // -----------------------------------------------------------------------
  $ac = DB::scalarSelect("SELECT id FROM accounts WHERE surname=? AND name=? AND patronymic=?", $surname, $name, $patronymic);
  if (empty($ac)) {
    DB::query("INSERT INTO accounts (surname,name,patronymic,sp1,sp2,sp3,tab,position) VALUES (?,?,?,?,?,?,?,?)",
      $surname, $name, $patronymic, $sp1, $sp2, $sp3, $tab, $position);
  } else {
    DB::query("UPDATE accounts SET sp1=?, sp2=?, sp3=?, tab=?, position=? WHERE surname=? AND name=? AND patronymic=?",
      $sp1, $sp2, $sp3, $tab, $position, $surname, $name, $patronymic);
  }
  // -----------------------------------------------------------------------
  if (!empty($sp1)) {
    $sp = DB::scalarSelect("SELECT code FROM sp WHERE code=?", $sp1);
    if (empty($sp)) {
      DB::query("INSERT INTO sp (code,name,level) VALUES (?,?,1)", $sp1, $sp1_name);
    } else {
      DB::query("UPDATE sp SET name=? WHERE code=?", $sp1_name, $sp);
    }
  }
  // -----------------------------------------------------------------------
  if (!empty($sp2)) {
    $sp = DB::scalarSelect("SELECT code FROM sp WHERE code=?", $sp2);
    if (empty($sp)) {
      DB::query("INSERT INTO sp (code,name,level) VALUES (?,?,2)", $sp2, $sp2_name);
    } else {
      DB::query("UPDATE sp SET name=? WHERE code=?", $sp2_name, $sp);
    }
  }
  // -----------------------------------------------------------------------
  if (!empty($sp3)) {
    $sp = DB::scalarSelect("SELECT code FROM sp WHERE code=?", $sp3);
    if (empty($sp)) {
      DB::query("INSERT INTO sp (code,name,level) VALUES (?,?,3)", $sp3, $sp3_name);
    } else {
      DB::query("UPDATE sp SET name=? WHERE code=?", $sp3_name, $sp);
    }
  }
  // -----------------------------------------------------------------------
}
fclose($file);
echo "complete";