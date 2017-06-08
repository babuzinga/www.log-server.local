<?php

$sp = !empty($_GET['sp']) ? $_GET['sp'] : false;
$level = !empty($_GET['level']) ? $_GET['level'] : false;

if (empty($sp) || empty($level)) {
  echo "Ошибка получения данных";
} else {
  $filed = "sp".$level;
  $sp = DB::singleRow("SELECT * FROM sp WHERE id=?", $sp);
  $employees = DB::getRows("SELECT * FROM accounts WHERE {$filed}=?", $sp['code']);

  echo "Список сотрудников, относящихся к СП &#171;" . $sp['name'] . "&#187;<br/><br/>";

  $i=1;
  echo '<table><tr><th></th><th>ФИО</th><th>Телефон</th><th>Должность</th></tr>';
  foreach ($employees as $employ) {
    echo "<tr>";
    echo "<td>" . $i++ . '.</td>';

    $fio = !empty($employ['account']) ? getLinkAccounts($employ['account']) : $employ['surname']." ".$employ['name']." ".$employ['patronymic'];
    echo "<td>" . $fio . '</td>';
    echo "<td>" . $employ['phone'] . '</td>';
    echo "<td>" . $employ['position'] . '</td>';
    echo "</tr>";
  }
  echo "</table>";
}
