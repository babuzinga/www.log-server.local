<?php
set_time_limit(0);
//gnore_user_abort(true);

include '/lib/exceptions.php';
include '/lib/db.php';
include '/lib/function.php';

define('DB_DATABASE', 'log-session');
define('DB_USER',     'root');
define('DB_PASSWORD', '');
define('DB_HOST',     '127.0.0.1');
define('BASE_DIR',    dirname(__FILE__));

DB::connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
ini_set('display_errors', 'on');
ini_set("memory_limit", "-1");

?>

<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
<title>Log statistics</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<script src="/public/jquery-1.11.2.min.js"></script>

<style>
  * {
    font: normal 12px "Courier New";
    line-height: 1.6;
  }
  b {
    font-weight: bold;
  }
  div.menu,
  div.statistics,
  div.search {
    background: #f5f5f5;
    padding: 10px;
    margin-bottom: 10px;
  }
  table th {
    padding: 6px 20px 6px 0;
    text-align: left;
    background: #ffa9a9;
  }
  table td {
    padding: 5px 30px 5px 0;
    border-top: 1px dotted black;
    vertical-align: top;
  }
  table.computername td.c {
    background: #ffe1e6;
  }
  table.account td.u {
    background: #ffe1e6;
  }
  a {
    color: #c90000;
  }
  a:hover {
    text-decoration: none;
  }
  select {
    padding: 4px;
  }
</style>

<script>
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
  });
</script>
</head>

<body>
  <div class="search">
    <a href="/">Главная</a>
    &mdash; <a href="/logs/">Логи</a>
    &mdash; Поиск: <input value="" placeholder="Логин, фамилия или имя компьютера" type="text" id="data-search" style="width:300px">
    <div id="result-search"></div>
  </div>

  <?php
    $url = parse_url($_SERVER['REQUEST_URI']);
    $request = $url['path'];

    if ($request == "/") {
      $page = 'main';
    } else {
      $page = str_replace("/", "", $request);
    }

    $folder = BASE_DIR."/page/";
    if (file_exists($folder."{$page}.php"))
      include $folder."{$page}.php";
    else
      include $folder."error404.php";
  ?>
</body>
</html>

