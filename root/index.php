<?php

  include_once('mp-config.php');

  $route = $_GET['route'];

  $database = new Database(DB_URI, DB_DATABASE, DB_USERNAME, DB_PASSWORD);

  echo 'hey';

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
