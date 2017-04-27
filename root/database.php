<?php

  class Database {

    private $host = null;
    private $database = null;
    private $username = null;
    private $password = null;

    private $pdo = null;

    function __construct($host, $database, $username, $password) {
      $this->host = $host;
      $this->database = $database;
      $this->username = $username;
      $this->password = $password;
      $this->pdo = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
    }

    public function getTableExists($table) {
      $result = mysql_query("SHOW TABLES LIKE '$table'");
      $stmt = $this->pdo->prepare("SHOW TABLES LIKE ':table'");
      $stmt->bindParam(':table', $table);

      $result = $stmt->execute();

      return $result;

    }

  }
