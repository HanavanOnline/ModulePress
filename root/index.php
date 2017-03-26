<?php

  include_once('mp-config.php');

  $route = $_GET['route'];

  $pdo = new PDO('mysql:host=' . DB_URI . ';dbname=' . DB_DATABASE, DB_USERNAME, DB_PASSWORD);

  /*$sql = 'SELECT id FROM users WHERE (username = :username)';
  $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $sth->execute(array(':username' => $name));
  $id = $sth->fetchColumn();*/
