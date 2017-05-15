<?php

  $time_start = microtime(true);

  include_once('root/mp-config.php');
  include_once('root/database.php');
  include_once('root/hook.php');
  include_once('root/theme.php');
  include_once('root/addon.php');
  include_once('root/module.php');

  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  $route = $_GET['route'];

  $database = new Database(DB_URI, DB_DATABASE, DB_USERNAME, DB_PASSWORD);

  $hooks = array();
  $addons = array();
  $modules = array();

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

  function addModule($module) {
    global $modules;
    $modules[] = $module;
  }

  function loadModule($key, $details = null) {
    global $modules;
    $cur = array();
    if($key != null) {
      foreach($modules as $module) {
        if(strpos($module->getKey(), $key) !== false) {
          $cur[] = $module;
        }
      }
    } else {
      foreach($details as $det_key => $det_val) {
        foreach($modules as $module) {
          $add = true;
          $local_details = $details;
          foreach($module->getDetails() as $mod_det_key => $mod_det_val) {
            if($mod_det_key == $det_key) {
              if($mod_det_val != $det_val)
                $add = false;
              unset($local_details[$mod_det_key]);
            }
          }
          if($add && sizeof($local_details) == 0) {
            $cur[] = $module;
          }
        }
      }
    }
    foreach($cur as $module) {
      $module->getOpen();
      $module->getClose();
    }
  }

  function getCurrentTheme() {
    return 'hanavanonlinetesttheme';
  }

  // ======= END CORE FUNCTIONS  ======= //

  $dir    = 'addons';
  $files  = scandir($dir);

  clearstatcache();

  doHook('pre_addons_loaded');

  foreach($files as $file) {
    $fullFile = getcwd() . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . 'addon.php';
    echo $fullFile;
    if(file_exists($fullFile)) {
      include_once $fullFile;
      echo '<br />Size of ', sizeof($addons), '<br />';
      doHook('addon_loaded', array('name' => 'test', 'addon' => $addons[sizeof($addons)-1]));
    }
    echo '<br />';
  }

  doHook('post_addons_loaded');

  doHook('page_load');

  $time_end = microtime(true);

  echo 'It took ', ($time_end - $time_start), ' microseconds to load this page.<br />Start: ', $time_start, '; End: ', $time_end;

  ?>
