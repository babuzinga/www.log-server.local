<?php
if (empty($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] != 'greend@y') {
  header('HTTP/1.0 401 Unauthorized');
  header('WWW-Authenticate: Basic realm="Login"');
  echo <<<HD
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>401 Authorization Required</title>
</head><body>
<h1>Authorization Required</h1>
<p>This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required.</p>
</body></html>
HD;
  exit;
}

date_default_timezone_set('Asia/Vladivostok');
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
define('HTTP_HOST',   $_SERVER['HTTP_HOST']);

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
  <script src="/public/script.js"></script>
  <link rel="stylesheet" type="text/css" href="/public/style.css">
</head>

<body>
  <div id="main">
    <div class="search">
      <a href="/">Главная</a>
      &mdash; <a href="/logs/">Логи</a>
      &mdash; <a href="/import_log/">Импорт</a>
      &mdash; Поиск: <input value="" placeholder="СП, логин, фамилия, имя или mac-адрес компьютера" type="text" id="data-search" style="width:400px">
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
  </div>
</body>
</html>

