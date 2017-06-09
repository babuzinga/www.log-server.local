<?php

$sp = !empty($_GET['sp']) ? $_GET['sp'] : false;
$level = !empty($_GET['level']) ? $_GET['level'] : false;

if (empty($sp) || empty($level)) {
  echo "Ошибка получения данных";
} else {
  $filed = "sp".$level;
  $sp = DB::singleRow("SELECT * FROM sp WHERE id=?", $sp);

  echo $sp['name']." (".$sp['code'].")<br/><br/>";

  $groups = DB::getRows('SELECT * FROM sp WHERE code LIKE "%'.$sp['code'].'%" AND code!=?', $sp['code']);
  if (!empty($groups)) {
    echo "Список групп:";
    foreach ($groups as $group) {
      echo '<br/><a href="/unit/?sp='.$group['id'].'&level='.$group['level'].'">'.$group['name'].' ('.$group['code'].')</a>';
    }
    echo "<br/><br/>";
  }

  $employees = DB::getRows("SELECT * FROM accounts WHERE {$filed}=?", $sp['code']);
  echo "Список сотрудников:<br/>";

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

  $alias = $sp['code'];
  $note = DB::singleRow("SELECT * FROM notes WHERE alias=?", $alias);
  echo "<br/>Заметка";
  echo '<div id="note">'.nl2br($note['note']).'</div>';
  echo '<form id="note-edit"><textarea data-alias="'.$alias.'">'.$note['note'].'</textarea><br/><input type="button" value="Сохранить"> <span class="cancel">Отменить</span></form>';
}