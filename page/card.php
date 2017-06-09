<?php

$table = false;
if (!empty($_GET['o'])) {
  if ($_GET['o'] == "computername") {
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
      echo '<br/>';
      echo '<span class="gr">Сетевоя имя компьютера:</span> ' . getLinkComputer($rows['name'], false, false) . '<br/>';
      echo '<span class="gr">IP-адрес:</span> ' . $rows['ip'] . '<br/>';
      echo '<span class="gr">Операционная система:</span> ' . $rows['system'] . '<br/>';
      echo '<span class="gr">Разрядность:</span> ' . $rows['arch'] . '<br/>';

      break;
    case "accounts":
      $fio = !empty($rows['account']) ? getDisplayName($rows['account']) : $rows['surname']." ".$rows['name']." ".$rows['patronymic'];

      echo '<br/>';
      echo '<span class="gr">Фамилия Имя Отчество:</span> ' . $fio . '<br/>';
      echo '<span class="gr">Учетная запись:</span> ' . getLinkAccounts($rows['account'], false, false) . '<br/>';
      echo '<span class="gr">Почтовый ящик:</span> <a href="mailto:'.$rows['mail'].'" class="ml">' . $rows['mail'] . '</a><br/>';
      echo '<span class="gr">Рабочий телефон:</span> ' . $rows['phone'] . '<br/>';
      echo '<span class="gr">Табельный номер:</span> ' . $rows['tab'] . '<br/>';
      echo '<span class="gr">Структурное подразделение:</span> ' . getSpTree($rows['sp1'],$rows['sp2'],$rows['sp3']) . '<br/>';
      echo '<span class="gr">Должность: </span>' . $rows['position'] . '<br/>';

      break;
  }
  $note = DB::singleRow("SELECT * FROM notes WHERE alias=?", $value);
  echo "<br/>Заметка";
  echo '<div id="note">'.nl2br($note['note']).'</div>';
  echo '<form id="note-edit"><textarea data-alias="'.$value.'">'.$note['note'].'</textarea><br/><input type="button" value="Сохранить"> <span class="cancel">Отменить</span></form>';

  else :
    echo "<br/>Данных не обнаружено";
  endif;
}

?>


<script>
  $(document).ready(function () {
    var $note = $("#note"),
        $note_edit = $("#note-edit"),
        $button = $note_edit.find('input[type="button"]'),
        $text = $note_edit.find('textarea'),
        $cancel = $note_edit.find('.cancel');

    $note.dblclick(function(){
      $(this).hide();
      $note_edit.show();
    });

    $cancel.click(function() {
      $note.show();
      $note_edit.hide();
    });

    $button.click(function(){
      var note_text = $text.val(),
          alias = $text.data('alias');

      $.ajax({
        url: '/ajax/save-note.php/',
        type: 'POST',
        data: {'note_text' : note_text, 'alias' : alias},
        success: function(data) {
          $note.html(nl2br(note_text)).show();
          $note_edit.hide();
          return false;
        },
        error: function() { alert('Ошибка выполнения запроса'); return false; }
      });
    });

    function nl2br (str, is_xhtml) {
      var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
      return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
    }
  });
</script>