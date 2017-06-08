<?php

$dt = date("Y-m-d");
$limit = 30;
echo "<br/>Топ {$limit} активностей за ".$dt."<br/><br/>";
echo "<table><tr><td>";



$computers = DB::getRows("SELECT computername, COUNT(logs.id) AS cnt FROM logs WHERE dt=? GROUP BY computername ORDER BY cnt DESC LIMIT ?i", $dt, $limit);
foreach ($computers as $computer) {
  echo '('.$computer['cnt'].') '.getLinkComputer($computer['computername']).'<br/>';
}



echo "</td><td style='vertical-align: top;'>";



$accounts = DB::getRows("SELECT account, COUNT(id) AS cnt FROM logs WHERE dt=? GROUP BY account ORDER BY cnt DESC LIMIT ?i", $dt, $limit);
foreach ($accounts as $account) {
  echo '('.$account['cnt'].') '.getLinkAccounts($account['account']).'<br/>';
}



echo "</td></tr></table>";