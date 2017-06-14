<?php
/*
function to1251($str) {
  if (!is_utf8($str)) return $str;

  return iconv('UTF-8', 'Windows-1251//IGNORE//TRANSLIT', $str);
}

function is_utf8($str) {
  return $str === @iconv('UTF-8', 'UTF-8', $str);
}

function from1251($str) {
  if (is_utf8($str)) return $str;

  // ¬ременный поиск бага
  set_error_handler(function($errno, $errstr) {
    $str = "iconv error (see from1251)\n";
    $str .= "iconv_str={$GLOBALS['iconv_str']}\n";
    $str .= "{$_SERVER['REQUEST_URI']}\n";
    $str .= 'POST=' . var2str($_POST) . "\n";
    $str .= "==================================================";
  });
  $GLOBALS['iconv_str'] = $str;
  $result = iconv('Windows-1251', 'UTF-8', $str);
  restore_error_handler();
  return $result;
}

function var2str($var) {
  $str = trim(var_export($var, true));
  $str = str_replace(array("\r", "\n"), '', $str);
  $length = strlen($str);
  $new_str = '';
  $last_char = '';
  $in_quotes = false;
  for ($i = 0; $i < $length; $last_char = $str{$i++}) {
    if (!$in_quotes && $str{$i} == ' ' && $last_char != ',') continue;
    if (!$in_quotes && $str{$i} == "'") $in_quotes = true;
    elseif ($in_quotes && $str{$i} == "'" && $last_char != '\\') $in_quotes = false;
    $new_str .= $str{$i};
  }

  return $new_str;
}
*/




function print_array($array, $exit=false) {
  echo "<pre>".print_r($array, true)."</pre>";
  if ($exit) exit();
}

function getDisplayName($account) {
  $account = DB::singleRow("SELECT * FROM accounts WHERE account=?", $account);
  $fio = $account['surname'].' '.$account['name'].' '.$account['patronymic'];
  return $fio;
}

function getVersion($computer) {
  $computer = DB::singleRow("SELECT ip, system, arch FROM computers WHERE name=?", $computer);
  $version = !empty($computer['ip']) ? "(".$computer['ip'].") " : "";
  $version .= $computer['system'] . ' ' . $computer['arch'];
  return $version;
}

function getLinkAccounts($account_name, $fio=true, $card=true) {
  if (empty($account_name)) return false;

  $link = '<a href="/statistics/?o=account&v='.$account_name.'">'.$account_name.'</a> ';
  if ($fio)
    $link .= getDisplayName($account_name) . ' ';
  if ($card)
    $link .= '[<a href="/card/?o=account&v='.$account_name.'">инф.</a>]';

  return $link;
}

function getLinkComputer($computer_name, $ver=true, $card=true, $id="") {
  if (empty($computer_name)) return false;

  $id = empty($id) ? "" : ' id="'.$id.'"';
  $link = '<a href="/statistics/?o=computer&v='.$computer_name.'"'.$id.'>'.$computer_name.'</a> ';
  if ($ver)
    $link .= getVersion($computer_name) . ' ';
  if ($card)
    $link .= '[<a href="/card/?o=computer&v='.$computer_name.'">инф.</a>]';
  return $link;
}

function modifier_plural_form($n, $form1, $form2, $form5='') {
  if(!$form5) $form5=$form2;
  $n = abs($n) % 100;
  $n1 = $n % 10;
  if ($n > 10 && $n < 20) return $form5;
  if ($n1 > 1 && $n1 < 5) return $form2;
  if ($n1 == 1) return $form1;
  return $form5;
}

function getSpTree($sp1, $sp2, $sp3) {
  $tree = "";
  if (!empty($sp1)) {
    $sp = DB::singleRow("SELECT * FROM sp WHERE code=?", $sp1);
    $tree .= '<a href="/unit/?sp='.$sp['id'].'&level=1">'.$sp['name'].'</a>';
  }

  if (!empty($sp2)) {
    $sp = DB::singleRow("SELECT * FROM sp WHERE code=?", $sp2);
    $tree .= ' - <a href="/unit/?sp='.$sp['id'].'&level=2">'.$sp['name'].'</a>';
  }

  if (!empty($sp3)) {
    $sp = DB::singleRow("SELECT * FROM sp WHERE code=?", $sp3);
    $tree .= ' - <a href="/unit/?sp='.$sp['id'].'&level=3">'.$sp['name'].'</a>';
  }

  return $tree;
}

function getLinkSp($sp) {
  if (empty($sp)) return false;
  $link = '<a href="/unit/?sp='.$sp['id'].'&level='.$sp['level'].'">'.$sp['name'].' ('.$sp['code'].')</a>';

  return $link;
}