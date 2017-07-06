<?php

$table = false;
if (!empty($_GET['o'])) {
  if ($_GET['o'] == "computer") {
    $table = "computers";
    $filed = "name";
  }
  if ($_GET['o'] == "account") {
    $table = "accounts";
    $filed = "account";
  }
}

$value = !empty($_GET['v']) ? $_GET['v'] : false;

if (empty($table) || empty($value)) {
  echo "Ошибка получения данных";
} else {
  $rows = DB::singleRow("SELECT * FROM {$table} WHERE {$filed}=?", $value);
  if (!empty($rows)) :
  switch ($table) {
    case "computers":
      echo "<h1>Карточка компьютера:</h1>";
      echo '
        <br/>
        <table>
        <tr>
          <td class="gr">Сетевое имя компьютера:</td>
          <td>' . getLinkComputer($rows['name'], false, false, "name") . ' <button class="copy" onclick=CopyToClipboard("name")>copy</button></td>
        </tr>
        <tr>
          <td class="gr">IP-адрес:</td>
          <td><span id="ip">' . $rows['ip'] . '</span> <button class="copy" onclick=CopyToClipboard("ip")>copy</button></td>
        </tr>
        <tr>
          <td class="gr">Тип IP-адреса:</td>
          <td>' . $rows['ip_type'] . '</td>
        </tr>
        <tr>
          <td class="gr">MAC-адрес:</td>
          <td>' . $rows['mac'] . '</td>
        </tr>
        <tr>
          <td class="gr">Операционная система:</td>
          <td>' . $rows['system'] . '</td>
        </tr>
        <tr>
          <td class="gr">Разрядность:</td>
          <td>' . $rows['arch'] . '</td>
        </tr>
        </table>
      ';

      break;
    case "accounts":
      $fio = !empty($rows['account']) ? getDisplayName($rows['account']) : $rows['surname']." ".$rows['name']." ".$rows['patronymic'];

      echo "<h1>Карточка пользователя:</h1>";
      echo '
        <br/>
        <table>
        <tr>
          <td class="gr">Фамилия Имя Отчество:</td>
          <td>' . $fio . '</td>
        </tr>
        <tr>
          <td class="gr">Учетная запись:</td>
          <td>' . getLinkAccounts($rows['account'], false, false) . '</td>
        </tr>
        <tr>
          <td class="gr">Почтовый ящик:</td>
          <td><a href="mailto:'.$rows['mail'].'" class="ml">' . $rows['mail'] . '</a></td>
        </tr>
        <tr>
          <td class="gr">Рабочий телефон:</td>
          <td>' . $rows['phone'] . '</td>
        </tr>
        <tr>
          <td class="gr">Табельный номер:</td>
          <td>' . $rows['tab'] . '</td>
        </tr>
        <tr>
          <td class="gr">Структурное подразделение:</td>
          <td>' . getSpTree($rows['sp1'],$rows['sp2'],$rows['sp3']) . '</td>
        </tr>
        <tr>
          <td class="gr">Должность:</td>
          <td>' . $rows['position'] . '</td>
        </tr>
        </table>
      ';

      break;
  }

  if (!empty($_GET['sdb'])) {
    print_array($rows);
  } else {
    echo '<br/><a href="'.$_SERVER['REQUEST_URI'].'&sdb=true">show_db</a><br/>';
  }

  else :
    echo "<br/>Данных не обнаружено<br/>";
  endif;

  if (!empty($value)) {
    $alias = $value;
    $note = DB::singleRow("SELECT * FROM notes WHERE alias=?", $alias);
    echo "<br/>Заметка:";
    echo '<div id="note">'.nl2br($note['note']).'</div>';
    echo '<form id="note-edit"><textarea data-alias="'.$alias.'">'.$note['note'].'</textarea><br/><br/><input type="button" value="Сохранить"> <span class="cancel">Отменить</span></form>';
  }
}