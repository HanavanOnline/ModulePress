<?php

  $time_start = microtime(true);

  include_once('root/mp-config.php');
  include_once('root/database.php');
  include_once('root/hook.php');
  include_once('root/filter.php');
  include_once('root/theme.php');
  include_once('root/addon.php');
  include_once('root/module.php');
  include_once('root/resource.php');

  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  $route = $_GET['route'];

  if(!isset($route) || empty($route))
    $route = '';

  $database = new Database(DB_URI, DB_DATABASE, DB_USERNAME, DB_PASSWORD);

  $database->doPrepareDatabase();

  $hooks = array();
  $filters = array();
  $addons = array();
  $modules = array();
  $resources = array();

  function getRoute() {
    global $route;
    return $route;
  }

  // Add a hook that may be called for future use. It is possible that the hook may never be called. $key is the name of the hook. $func is the callback function
  function addHook($key, $func) {
    global $hooks;
    $hooks[] = new Hook($key, $func);
  }

  function doHook($key, &$data = null) {
    global $hooks;
    foreach($hooks as $hook) {
      if($hook->getKey() == $key) {
        $hook->call($data);
      }
    }
  }

  // Add a filter that may be called for future use. It is possible that the filter may never be called. $key is the name of the filter. $func is the callback function
  function addFilter($key, $func) {
    global $filters;
    $filters[] = new Filter($key, $func);
  }

  function doFilter($key, &$data = null) {
    global $filters;
    foreach($filters as $filter) {
      if($filter->getKey() == $key) {
        $filter->call($data);
      }
    }
  }

  function loadAddon($slug, $name, $version, $path) {
    global $addons;
    $cur = new Addon($slug, $name, $version, $path);
    $addons[] = $cur;
    return $cur;
  }

  function mlog($message) {
    echo '<br />',$message,'<br />';
  }

  // Get a list of addons as an array. $key is used to match addon slug to the $key provided. This array can be empty.
  function getAddons($key = null) {
    global $addons;
    if($key == null)
      return $addons;
    $ret = array();
    foreach($addons as $addon) {
      if (strpos($addon->getSlug(), $key) !== false) {
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
      if($details != null) {
        foreach($details as $det_key => $det_val) {
          foreach($modules as $module) {
            $add = true;
            $local_details = $details;
            if($module->getDetails() != null) {
              foreach($module->getDetails() as $mod_det_key => $mod_det_val) {
                if($mod_det_key == $det_key) {
                  if($mod_det_val != $det_val)
                    $add = false;
                  unset($local_details[$mod_det_key]);
                }
              }
            }
            if($add && sizeof($local_details) == 0) {
              $cur[] = $module;
            }
          }
        }
      }
    }
    foreach($cur as $module) {
      $module->getOpen();
      $module->getClose();
    }
  }

  function addResourceLazy($url, $name, $content_type = 'text/css') {
    global $resources;
    $resources[] = new Resource($url, $name, $content_type);
  }

  function addResource($resource) {
    global $resources;
    $resources[] = $resource;
  }

  function getResourcesByName($key) {
    global $resources;
    $ret = array();
    foreach($resources as $resource) {
      if(strpos($resource->getName(), $key) !== false) {
        $ret[] = $resource;
      }
    }
    return $ret;
  }

  function getResourcesByURL($url) {
    global $resources;
    $ret = array();
    if($url == '' || !isset($url) || empty($url))
      return $ret;
    foreach($resources as $resource) {
      if(strpos($resource->getURL(), $url) !== false) {
        $ret[] = $resource;
      }
    }
    return $ret;
  }

  function getCurrentThemeFolder() {
    return 'Hanavan Online Test Theme';
  }

  // ======= END CORE FUNCTIONS  ======= //

  $addon_files = scandir('addons');
  $theme_files = scandir('theme');

  for($x = 0; $x < sizeof($addon_files); $x++) {
    $file = $addon_files[$x];
    $addon_files[$x] = getcwd() . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . 'addon.php';
  }

  clearstatcache();

  include_once getcwd() . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR . getCurrentThemeFolder() . DIRECTORY_SEPARATOR . 'theme.php';

  // ====== START ADMIN ROUTE ======= //

  $clean_route = str_replace('/', '', $route);

  $is_admin = false;

  if(strpos($clean_route, 'mp-admin') === 0) {
    $is_admin = true;
    foreach(scandir('admin') as $addon) {
      if(strpos($addon, 'addon') !== false) {
        $addon_files[] = getcwd() . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $addon;
      }
    }
  }

  // ====== END ADMIN ROUTE ======= //

  doHook('pre_addons_loaded');

  foreach($addon_files as $file) {
    $fullFile = $file;

    if(file_exists($fullFile)) {
      include_once $fullFile;
      $data = array('addon' => $addons[sizeof($addons)-1]);
      doHook('addon_loaded', $data);
    }
  }

  doHook('post_addons_loaded');

  doHook('add_resources');

  $route_resources[] = getResourcesByURL($route);

  if(sizeof($route_resources) > 0) {
    $obj = $route_resources[0];
    if(sizeof($obj) > 0) {
      $obj[0]->render();
    }
  }

  doHook('pre_page_load');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array();
    $data[0] = $_POST;
    $data[1] = array();
    $data[2] = $_FILES;
    doHook('handle_post_data', $data);
    $res = $data[1];
    if(sizeof($res) == 0) {
      $res['mp'] = true;
    }
    $data[1] = $res;
    echo json_encode($data);
    exit();
  }

  doHook('page_load');

  echo '<!DOCTYPE html><html><head>';

  doHook('head_load');

  doHook('post_head_load');

  echo '</head>';

  $body = '<body class="';

  $body_classes = '';

  if(!$is_admin) {

    doHook('body_load');

    echo $database->getPageContents($route);

    doHook('post_body_load');

  } else {

    doHook('admin_body_load');

    doHook('admin_post_body_load');

  }

  $time_end = microtime(true);

  for($x = 0; $x < 10; $x++) {
    echo '<br />';
  }

  mlog('It took ' . ($time_end - $time_start) . ' microseconds to load this page.<br />Start: ' . $time_start . '; End: ' . $time_end);
  if($is_admin) mlog('Admin Mode Enabled'); else mlog('Admin Mode Disabled');
  mlog('User: ' . exec('whoami'));

  echo 'Route is: ', $route;

  echo '</body>';

  ?>
