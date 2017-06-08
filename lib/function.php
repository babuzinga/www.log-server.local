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

function getLinkAccounts($account_name, $card=true) {
  $link = '<a href="/statistics/?o=account&v='.$account_name.'">'.$account_name.'</a> ';
  if ($card)
    $link .= '[<a href="/card/?o=account&v='.$account_name.'" target="_blank">инф.</a>]';
  else
    $link .= getDisplayName($account_name);
  return $link;
}

function getLinkComputer($computer_name, $card=true) {
  $link = '<a href="/statistics/?o=computername&v='.$computer_name.'">'.$computer_name.'</a> ';
  if ($card)
    $link .= '[<a href="/card/?o=computername&v='.$computer_name.'" target="_blank">инф.</a>]';
  else
    $link .= getVersion($computer_name);
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