<?php

  include_once('root/mp-config.php');
  include_once('root/database.php');
  include_once('root/hook.php');
  include_once('root/addon.php');
  include_once('root/module.php');

  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  $route = $_GET['route'];

  $database = new Database(DB_URI, DB_DATABASE, DB_USERNAME, DB_PASSWORD);

  $hooks = array();
  $addons = array();

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

  function loadAddon($name, $version, $path) {
    global $addons;
    $cur = new Addon($name, $version, $path);
    $addons[] = $cur;
    return $cur;
  }

  function mlog($message) {
    echo '<br />',$message,'<br />';
  }

  function getAddons($key = null) {
    global $addons;
    if($key == null)
      return $addons;
    $ret = array();
    foreach($addons as $addon) {
      if (strpos($addon, $key) !== false) {
        $ret[] = $addon;
      }
    }
    return $ret;
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

  callHooks('addon_load');

  callHooks('page_load');

  ?>
