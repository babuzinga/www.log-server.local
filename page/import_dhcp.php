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
  $mac          = str_replace("-", "", $mac);
  $mac          = str_replace("-", "", $mac);
  $computer     = strtoupper($row[4]);

  if (stristr($computer, 'D- ') !== false)
    $ip_type = "Dynamic";

  if (stristr($computer, 'U- ') !== false)
    $ip_type = "Reserve";

  if (stristr($computer, 'N- ') !== false)
    $ip_type = "Inactive";

  $computer = str_replace("D- ", "", $computer);
  $computer = str_replace("U- ", "", $computer);
  $computer = str_replace("N- ", "", $computer);
  $computer = str_replace(" ", "", $computer);
  $computer = str_replace(".KMS.GSS.LOCAL", "", $computer);

  if (empty($computer))
    continue;

  //echo $ip.' --- '.$mac.' --- '.$computer.' --- '.$ip_type.'<br/>';

  $ac = DB::scalarSelect("SELECT id FROM computers WHERE name=?", $computer);
  if (empty($ac)) {
    DB::query("INSERT INTO computers (name,ip,ip_type,mac) VALUES (?,?,?,?)",
      $computer, $ip, $ip_type, $mac);
  } else {
    DB::query("UPDATE computers SET ip=?, ip_type=?, mac=? WHERE name=?", $ip, $ip_type, $mac, $computer);
  }

}
fclose($file);
echo "complete";