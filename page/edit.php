<?php

$table = false;
if (!empty($_GET['o'])) {
  if ($_GET['o'] == "computer") {
    $object = "computer";
    $table = "computers";
    $filed = "name";
  }
  if ($_GET['o'] == "account") {
    $object = "account";
    $table = "accounts";
    $filed = "account";
  }
}

$value = !empty($_GET['v']) ? $_GET['v'] : false;

if (empty($table) || empty($value)) {
  echo "Ошибка получения данных";
} else {
  $rows = DB::singleRow("SELECT * FROM {$table} WHERE {$filed}=?", $value);
  $sid = getSidByKey($rows['id']);
  if (!empty($rows)) :
    echo "
      <form id='edit_data'>
      <input type='hidden' name='table' value='{$table}'>
      <input type='hidden' name='value' value='{$value}'>
      <input type='hidden' name='sid' value='{$sid}'>
      <table>
        <tr>
          <th>Поле</th>
          <th></th>
          <th>Значение</th>
        </tr>
      ";
    foreach ($rows as $key => $row) {
      $disabled = "";
      if (
        ($table == "computers" && in_array($key, array("id"))) ||
        ($table == "accounts" && in_array($key, array("id")))
      ) {
        $disabled = "disabled=disabled";
      }

      echo "
        <tr>
          <td>{$key}</td>
          <td>=</td>
          <td><input type='text' name='{$key}' value='{$row}' {$disabled}></td>
        </tr>
      ";
    }
    echo "
      </table>
      <br/><br/>
      Код : <input type='password' name='code' value=''>
      <input type='button' name='save' value='Сохранить'>
      <a href='/card/?o={$object}&v={$value}' class='cancel'>Отменить</a>
      <br/><br/>
      <span class='success'></span>
      </form>
    ";
  else :
    echo "<br/>Данных не обнаружено<br/>";
  endif;
}