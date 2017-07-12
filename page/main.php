<?php

$dt = date("Y-m-d");
$limit = 30;
$computers = DB::getRows("SELECT computer, COUNT(logs.id) AS cnt FROM logs WHERE dt=? GROUP BY computer ORDER BY cnt DESC LIMIT ?i", $dt, $limit);
$accounts = DB::getRows("SELECT account, COUNT(id) AS cnt FROM logs WHERE dt=? GROUP BY account ORDER BY cnt DESC LIMIT ?i", $dt, $limit);



echo "<br/>Топ {$limit} активностей за ".$dt."<br/><br/>";
echo "
  <table>
    <tr>
      <th>Компьютеры</th>
      <th></th>
      <th>Пользователи</th>
    </tr>
  ";

for ($i = 0; $i <= $limit; $i++) {

  if (empty($computers[$i]) && empty($accounts[$i])) break;
  echo "<tr>";
  echo "<td>";
  echo !empty($computers[$i]) ? '('.$computers[$i]['cnt'].') '.getLinkComputer($computers[$i]['computer'], false) : "";
  echo "</td>";
  echo "<td>&nbsp;&nbsp;&nbsp;</td>";
  echo "<td>";
  echo !empty($accounts[$i]) ? '('.$accounts[$i]['cnt'].') '.getLinkAccounts($accounts[$i]['account']) : "";
  echo "</td>";
  echo "</tr>";
}

echo "</table>";