<?php

  include_once('root/mp-config.php');
  include_once('root/database.php');
  include_once('root/hook.php');

  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  $route = $_GET['route'];

  $database = new Database(DB_URI, DB_DATABASE, DB_USERNAME, DB_PASSWORD);

  $hooks = array();

  function addHook($key, $func) {
    global $hooks;
    $hooks[] = new Hook($key, $func);
  }

  function doHook($key, $data = null) {
    global $hooks;
    foreach($hooks as $hook) {
      if($hook->getKey() == $key) {
        $hook->call($data);
      }
    }
  }

  function callHooks($key, $data = null) {
    global $hooks;
    foreach($hooks as $hook) {
      if($hook->getKey() == $key) {
        $hook->call($data);
      }
    }
  }

  $dir    = 'addons';
  $files  = scandir($dir);

  clearstatcache();

  foreach($files as $file) {
    $fullFile = getcwd() . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . 'addon.php';
    echo $fullFile;
    if(file_exists($fullFile)) {
      include_once $fullFile;
    }
    echo '<br />';
  }

  callHooks('init');

  callHooks('page_load');

  ?>
