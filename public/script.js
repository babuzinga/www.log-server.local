$(document).ready(function () {
  var $data_search = $("#data-search"),
    $result_search = $("#result-search");

  $data_search.keyup(function() {
    var val = $(this).val();

    if (val.length >= 3) {
      $.ajax({
        url: '/ajax/search.php/',
        type: 'POST',
        data: {'val' : val},
        success: function(data) {
          $result_search.html(data);
          return false;
        },
        error: function() { alert('Ошибка выполнения запроса'); return false; }
      });
    } else {
      $result_search.html("");
    }
  });
  // --------------------------------------------------------------------------
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
  // --------------------------------------------------------------------------
  var $form_edit = $('#edit_data'),
      $submit = $form_edit.find('input[name=save]'),
      $success = $form_edit.find('.success'),
      fd = new FormData;

  $submit.click(function(){
    $.each($form_edit.serializeArray(), function(key, input){
      fd.append(input.name, input.value);
    });

    $.ajax({
      url: '/ajax/save-edit-object.php/',
      type: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      success: function(data) {
        $success.html(data).show();
        return false;
      },
      error: function() { alert('Ошибка выполнения запроса'); return false; }
    });
  });
  // --------------------------------------------------------------------------
});

function CopyToClipboard(containerid) {
  var range;
  if (document.selection) {
    range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("Copy");

  } else if (window.getSelection) {
    range = document.createRange();
    range.selectNode(document.getElementById(containerid));
    window.getSelection().addRange(range);
    document.execCommand("Copy");
    //alert("text copied");
  }
}