<?php

$dt_current = date("Y-m-d");
DB::query("DELETE FROM logs WHERE dt=?", $dt_current);



$folder = '//log-server/log$';
$f = scandir($folder);
foreach ($f as $file) {
  if (pathinfo($file, PATHINFO_EXTENSION) == 'log'){
    if (!file_exists($folder.'/'.$file)) continue;
    $file_log = fopen($folder.'/'.$file, 'r');

    while(!feof($file_log)) {
      $str = fgets($file_log);
      if (empty($str)) continue;
      $str = str_replace("\r\n", "", $str);
      $row = explode(";", $str);

      $dt           = $row[0];
      $tm           = $row[1];
      $computername = strtoupper($row[2]);
      $account      = mb_strtolower($row[3]);
      $domain       = $row[4];
      $system       = $row[5];
      $arch         = $row[6];
      $action       = $row[7];
      $ip           = !empty($row[8]) ? $row[8] : "";
      $ip           = str_replace(" ", "", $ip);

      DB::query("INSERT INTO logs (dt,tm,computername,account,domain,action) VALUES (?,?,?,?,?,?)",
      $dt, $tm, $computername, $account, $domain, $action);

      if (empty($computername))
        continue;

      $ac = DB::scalarSelect("SELECT id FROM computers WHERE name=?", $computername);
      if (empty($ac)) {
        DB::query("INSERT INTO computers (name,ip,system,arch) VALUES (?,?,?,?)",
        $computername, $ip, $system, $arch);
      } else {
        DB::query("UPDATE computers SET ip=?, system=?, arch=? WHERE name=?", $ip, $system, $arch, $computername);
      }
    }

    fclose($file_log);

    if ($file!=$dt_current.'.log')
      rename ($folder.'/'.$file, $folder.'/'.$file.'.bak');
  }
}
echo "complete";