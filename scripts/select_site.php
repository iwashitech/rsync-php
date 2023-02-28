<?php
require 'vendor/autoload.php';

$CONFIRM_CODE=<<<EOT
######################################################

サイトコードを入力してください: 
foo) FOO
bar) BAR

######################################################
EOT;

echo $CONFIRM_CODE . "\n";
$CODE = rtrim(fgets(STDIN), "\n");

switch ($CODE){
  case 'foo':
    Dotenv\Dotenv::createImmutable(__DIR__ . '/../config', 'foo')->load();
    break;
  case 'bar':
    Dotenv\Dotenv::createImmutable(__DIR__ . '/../config', 'bar')->load();
    break;
  default:
    echo "そのサイトは登録されていません";
    exit;
}

$USER=$_ENV["USER_NAME"];
$HOST=$_ENV["HOST"];
$PRIVATE_KEY=$_ENV["PRIVATE_KEY"];
$SITEDIR=$_ENV["SITEDIR"];