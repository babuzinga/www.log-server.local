<?php

$object = false;
if (!empty($_GET['o'])) {
  if ($_GET['o'] == "computer") $object = $_GET['o'];
  if ($_GET['o'] == "account") $object = $_GET['o'];
}

$value = !empty($_GET['v']) ? $_GET['v'] : false;

if (empty($object) || empty($value)) {
  echo "Ошибка получения данных";
} else {
  $type = $object == 'computer' ? "компьютера" : "пользователя";
  $dt_current = date("Y-m-d");
  $dt_start = !empty($_GET['dt1']) ? $_GET['dt1'] : date("Y-m-d",strtotime("-1 day"));
  $dt_end = !empty($_GET['dt2']) ? $_GET['dt2'] : $dt_current;

  $dts = DB::getArray("SELECT dt FROM logs GROUP BY dt");
  if (empty($dts)) $dts = array($dt_current);

  $rows = DB::getRows("SELECT * FROM logs WHERE dt>=? AND dt<=? AND {$object}=? ORDER BY id DESC", $dt_start, $dt_end, $value);
  $period = " за период с {$dt_start} по {$dt_end}";
  $period .= " (".count($rows)." ".modifier_plural_form(count($rows),"запись","зиписи","записей").")";
?>

<div class="statistics">
  <form action="/statistics/" method="get">
    Результаты поиска <b><?= $type ?> "<?= $value ?>"</b> <?= $period ?>
    <br/>
    <br/>
    Выбрать период времени: с
    <select name="dt1">
      <option disabled>Начало</option>
      <?php
        foreach ($dts as $dt) {
          echo '<option value="' . $dt . '"';
          if ($dt==$dt_start) echo ' selected';
          echo '>' . $dt . '</option>';
        }
      ?>
    </select>
    по
    <select name="dt2">
      <option disabled>Конец</option>
      <?php
        foreach ($dts as $dt) {
          echo '<option value="' . $dt . '"';
          if ($dt==$dt_end) echo ' selected';
          echo '>' . $dt . '</option>';
        }
      ?>
    </select>

    <input type="hidden" name="o" value="<?=$object?>">
    <input type="hidden" name="v" value="<?=$value?>">
    <input type="submit" value="Поиск">
  </form>
</div>

<?php
  echo "
  <table class='".$object."'>
    <tr>
      <th>Дата</th>
      <th>Время</th>
      <th>Имя компьютера</th>
      <th>Пользователь</th>
      <th>Действие</th>
    </tr>
  ";
  foreach ($rows as $row) {
    echo "
    <tr>
    <td>{$row['dt']}</td>
    <td>{$row['tm']}</td>
    <td class='c'>".getLinkComputer($row['computer'])."</td>
    <td class='u'>".getLinkAccounts($row['account'])."</td>
    <td>{$row['action']}</td>
    </tr>
    ";
  }
  echo "</table>";
}