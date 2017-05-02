<?php

  include_once('root/mp-config.php');
  include_once('root/database.php');

  $route = $_GET['route'];

  $database = new Database(DB_URI, DB_DATABASE, DB_USERNAME, DB_PASSWORD);

  echo 'heey';

  $hooks = array();

  function addHook($key, $func) {
    $hooks[] = new Hook($key, $func);
  }

  function doHook($key, $data = null) {
    foreach($hooks as $hook) {
      if($hook->getKey() == $key) {
        $hook->call($data);
      }
    }
  }

  function callHooks($key, $data = null) {
    foreach($hooks as $hook) {
      if($hooks->getKey() == $key) {
        $hook->call($data);
      }
    }
  }

  $dir    = 'addons';
  $files  = scandir($dir);

  clearstatcache();

  foreach($files as $file) {
    $fullFile = DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . 'addon.php';
    echo $fullFile;
    if(file_exists($fullFile)) {
      include_once $fullFile;
      echo 'EXISTS';
    }
    echo '<br />';
  }

  callHooks('init');

  callHooks('page_load');
