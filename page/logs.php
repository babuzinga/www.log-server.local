<?php
  $dt_current = date("Y-m-d");
  $dt_start = !empty($_GET['dt1']) ? $_GET['dt1'] : $dt_current;
  $dt_end = !empty($_GET['dt2']) ? $_GET['dt2'] : $dt_current;

  $dts = DB::getArray("SELECT dt FROM logs GROUP BY dt");
  if (empty($dts)) $dts = array($dt_current);

  $rows = DB::getRows("SELECT * FROM logs WHERE dt>=? AND dt<=? ORDER BY id DESC", $dt_start, $dt_end);
  $period = ($dt_start==$dt_end) ? " за ".$dt_start : " за период с {$dt_start} по {$dt_end}";
  $period .= " (".count($rows)." ".modifier_plural_form(count($rows),"запись","записи","записей").")";
?>

  <div class="statistics">
    <form action="/logs/" method="get">
      Логи <?= $period ?>
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
      <input type="submit" value="Показать">
    </form>
  </div>

<?php
echo "
  <table>
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
    <td>".getLinkComputer($row['computer'])."</td>
    <td>".getLinkAccounts($row['account'])."</td>
    <td>{$row['action']}</td>
    </tr>
    ";
}
echo "</table>";