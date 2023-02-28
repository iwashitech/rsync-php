<?php

function make_directory ($dir_type) {
  require_once(dirname(__FILE__) . '/../settings/env.php');
  global $DIR_UP;
  global $DIR_DELETE;
  global $DIR_COMMAND;
  global $DIR_BACKUP;

  if(!file_exists($DIR_UP) && $dir_type == "up") {
    mkdir($DIR_UP, 0755, true);
  }

  if(!file_exists($DIR_DELETE) && $dir_type == "del") {
    mkdir($DIR_DELETE, 0755, true);
  }

  if(!file_exists($DIR_COMMAND) && $dir_type == "cmd") {
    mkdir($DIR_COMMAND, 0755, true);
  }

  if(!file_exists($DIR_BACKUP) && $dir_type == "backup") {
    $files = explode("\n", file_get_contents('files/delete_list.txt'));
    foreach ($files as $file) {
      if(preg_match('/(.+\/).+/', $file)) {
        mkdir($DIR_BACKUP . '/' . preg_replace('/(.+\/).+/', '$1', $file), 0755, true);
      }
    }
  }
}