<?php

$file = fopen(BASE_DIR.'/dev/dhcp.txt', 'r');
while(!feof($file)) {
  $str = fgets($file);
  if (empty($str)) continue;
  $str = str_replace("\r\n", "", $str);
  $row = explode(" -", $str);



  $ip           = str_replace(" ", "", $row[0]);
  $ip_type      = "";
  $mac          = str_replace(" ", "", $row[2]);
  $computername = strtoupper($row[4]);

  if (stristr($computername, 'D- ') !== false)
    $ip_type = "Dynamic";

  if (stristr($computername, 'U- ') !== false)
    $ip_type = "Reserve";

  if (stristr($computername, 'N- ') !== false)
    $ip_type = "Inactive";

  $computername = str_replace("D- ", "", $computername);
  $computername = str_replace("U- ", "", $computername);
  $computername = str_replace("N- ", "", $computername);
  $computername = str_replace(" ", "", $computername);
  $computername = str_replace(".KMS.GSS.LOCAL", "", $computername);

  if (empty($computername))
    continue;

  //echo $ip.' --- '.$mac.' --- '.$computername.' --- '.$ip_type.'<br/>';

  $ac = DB::scalarSelect("SELECT id FROM computers WHERE name=?", $computername);
  if (empty($ac)) {
    DB::query("INSERT INTO computers (name,ip,ip_type,mac) VALUES (?,?,?,?)",
      $computername, $ip, $ip_type, $mac);
  } else {
    DB::query("UPDATE computers SET ip=?, ip_type=?, mac=? WHERE name=?", $ip, $ip_type, $mac, $computername);
  }

}
fclose($file);
echo "complete";