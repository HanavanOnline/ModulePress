<?php

  addHook('admin_body_load', 'renderAdminMedia');

  function renderAdminMedia($data) {

    global $route;

    if($route == 'mp-admin/media/view') {
      $medii = getMedia();
      for($x = 0; $x < sizeof($medii); $x++) {
        $img = $medii[$x];
        echo '<a href="/mp-admin/media/view/', $img, '"><img src="/media/', $img, '" /></a>','<br />';
      }
    }

    $route_paths = explode('/', $route);

    if(sizeof($route_paths) > 3 && $route_paths[0] == 'mp-admin' && $route_paths[1] == 'media' && $route_paths[2] == 'view') {
      global $database;
      if($database->getMediaExists($route_paths[3])) {
        echo '<h2>',$database->getMediaTitle($route_paths[3]),'</h2><br />';
        echo '<img src="/media/', $route_paths[3], '" />','<br />';
      }
    }

  }
