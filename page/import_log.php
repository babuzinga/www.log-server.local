<?php

DB::query("TRUNCATE logs");

$folder = '//kms-file-1/log$';
$f = scandir($folder);
foreach ($f as $file){
  if(preg_match('/\.(log)/', $file)){
    $file = fopen($folder.'/'.$file, 'r');

    while(!feof($file)) {
      $str = fgets($file);
      if (empty($str)) continue;
      $str = str_replace("\r\n", "", $str);
      $row = explode(";", $str);

      $dt = $row[0];
      $tm = $row[1];
      $computername = $row[2];
      $account = mb_strtolower($row[3]);
      $domain = $row[4];
      $system = $row[5];
      $arch = $row[6];
      $action = $row[7];
      $ip = !empty($row[8]) ? $row[8] : "";
      $ip = str_replace(" ", "", $ip);

      DB::query("INSERT INTO logs (dt,tm,computername,account,domain,action) VALUES (?,?,?,?,?,?)",
      $dt, $tm, $computername, $account, $domain, $action);

      $ac = DB::scalarSelect("SELECT id FROM computers WHERE name=?", $computername);
      if (empty($ac)) {
        DB::query("INSERT INTO computers (name,ip,system,arch) VALUES (?,?,?,?)",
        $computername, $ip, $system, $arch);
      } else {
        DB::query("UPDATE computers SET ip=?, system=?, arch=? WHERE name=?", $ip, $system, $arch, $computername);
      }
    }
    fclose($file);
  }
}
echo "complete";