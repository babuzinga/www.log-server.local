<?php

$folder = BASE_DIR.'/public/photo/';
$f = scandir($folder);
foreach ($f as $file) {
  $str = from1251($file);
  echo $folder.'/'.$str.'<br/>';
}
echo "<br/>complete";