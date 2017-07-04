<?php

  addHook('admin_body_load', 'admin_test');

  function admin_test() {
    global $clean_route;
    if($clean_route == 'mp-admin') {
      admin_home_page();
    }
  }

  function admin_home_page() {
      echo '<img src="/theme/Hanavan Online Test Theme/res/logo-long.png" />';
      echo '<ul id="hott-menu">';
      $tabs = array('Admin Home' => '/mp-admin',
                    'View Site' => '/',
                    'Add Media' => '/mp-admin/media');
      foreach($tabs as $tab => $url) {
        echo '<li><a href="',$url,'">',$tab,'</a></li>';
      }
      echo '</ul>';
      exit();
  }

  ?>
