<?php

require_once('functions/mkdir.php');

$ACCEPTED_UPLOAD="NO ACCEPTED";
$CONFIRM_FINAL= <<<EOT
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

アップロードしようとしています！！！
本当にアップロードしてもよろしいですか？(y/n): 

@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
EOT;

echo $CONFIRM_FINAL . "\n";
$UPLOAD_OK = rtrim(fgets(STDIN), "\n");
switch ($UPLOAD_OK){
  case 'y':
    $ACCEPTED_UPLOAD="OK";
    make_directory('up');
    break;
  case 'n':
    break;
  default:
    echo "どちらかを選んでください";
    break;
}