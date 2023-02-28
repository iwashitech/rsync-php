<?php

require_once('settings/env.php');
require_once('functions/mkdir.php');

// サイト選択して変数を設定
require_once('scripts/select_site.php');

$CONFIRM_DELETE=<<<EOT
######################################################

リモートのファイルを削除しますか？(y/n): 

######################################################
EOT;

$CONFIRM_DELETE_FINAL=<<<EOT
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

リモートのファイルを削除しようとしています
本当に削除してもよろしいですか？(y/n): 

@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
EOT;

echo $CONFIRM_DELETE . "\n";
$DELETE_OK = rtrim(fgets(STDIN), "\n");

switch ($DELETE_OK){
  case 'y':
    echo $CONFIRM_DELETE_FINAL . "\n";
    $DELETE_FINAL_OK = rtrim(fgets(STDIN), "\n");
    switch ($DELETE_FINAL_OK){
      case 'y':
        // ログ用ディレクトリ作成
        make_directory('del');
        // コマンド用ディレクトリ作成
        make_directory('cmd');
        // バックアップ用ディレクトリ作成
        make_directory('backup');
        break;
      case 'n':
        exit;
      default:
        exit;
    }
    break;
  case 'n':
    exit;
  default:
    exit;
}

// バックアップコマンド用ファイル作成
file_put_contents("$DIR_COMMAND/{$WORK_DATE}_backup.txt", "cd $SITEDIR\n");
$files = explode("\n", file_get_contents('files/delete_list.txt'));
foreach ($files as $file) {
  file_put_contents("$DIR_COMMAND/{$WORK_DATE}_backup.txt", "get -r $file backup_delete_$WORK_DATE/$file >> $DIR_COMMAND/{$WORK_DATE}_backup.txt\n", FILE_APPEND);
}

$command_backup =<<<EOT
result_backup=$(sftp -o "batchmode no"  -b $DIR_COMMAND/{$WORK_DATE}_backup.txt -i $PRIVATE_KEY $USER@$HOST << EOF 2>&1
EOF
)
echo "\$result_backup" > logs/delete/{$WORK_DATE}_backup.log
EOT;

# バックアップコマンド実行
exec($command_backup);

# バックアップコマンドでエラーが発生した場合に作業を中止する
$sftp_status_backup='$?';

# bash - SFTP suppress all messages except errors - Unix & Linux Stack Exchange
# https://unix.stackexchange.com/questions/279427/sftp-suppress-all-messages-except-errors
if ($sftp_status_backup != 0) {
  echo "バックアップ作業中にエラーが発生しました";
  echo "作業を中止します";
  exit;
}

// 削除コマンド用ファイル作成
file_put_contents("$DIR_COMMAND/{$WORK_DATE}_delete.txt", "cd $SITEDIR\n");
$files = explode("\n", file_get_contents('files/delete_list.txt'));
foreach ($files as $file) {
  file_put_contents("$DIR_COMMAND/{$WORK_DATE}_delete.txt", "rm $file >> $DIR_COMMAND/{$WORK_DATE}_delete.txt\n", FILE_APPEND);
}

$command_delete =<<<EOT
result_delete=$(sftp -o "batchmode no"  -b $DIR_COMMAND/{$WORK_DATE}_delete.txt -i $PRIVATE_KEY $USER@$HOST << EOF 2>&1
EOF
)
echo "\$result_delete" > logs/delete/{$WORK_DATE}_delete.log
EOT;

# 削除コマンド実行
exec($command_delete);