<?php

require_once('settings/env.php');
// サイト選択して変数を設定
require_once('scripts/select_site.php');
// アップロードするか確認
require_once('scripts/select_upload.php');

$RSH = $PRIVATE_KEY == "NONE" ? "" : "-e \"ssh -i $PRIVATE_KEY\"";

$CONFIRM_DRY_RUN=<<<EOT
######################################################

実際にアップロードする前に
動作確認(dry-run)を行いますか？(y/n): 

######################################################
EOT;

echo $CONFIRM_DRY_RUN . "\n";
$DRY_RUN_OK = rtrim(fgets(STDIN), "\n");

switch ($DRY_RUN_OK){
  case 'y':
    $OPTION_DRY_RUN="--dry-run";
    break;
  case 'n':
    $OPTION_DRY_RUN="";
    break;
  default:
    echo "どちらかを選んでください";
    break;
}

$command_up =<<<EOT
rsync -avu $OPTION_DRY_RUN $RSH upload/ $USER@$HOST:$SITEDIR > logs/upload/$WORK_DATE.log
EOT;

// アップロード実行
if ($UPLOAD_OK == "y" && $ACCEPTED_UPLOAD == "OK") {
  exec($command_up);
} else {
  exit;
}