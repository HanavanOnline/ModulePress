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
      try {

      } catch(Exception $e) {
        $this->pdo = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
      }
    }

    public function getTableExists($table) {
      $stmt = $this->pdo->prepare("SHOW TABLES LIKE ':table'");
      $stmt->bindParam(':table', $table);

      $result = $stmt->execute();

      return $result;

    }

    public function doPrepareDatabase() {
      if(getTableExists('pages'))
        return;
      $stmt = $this->pdo->prepare("CREATE TABLE pages (id INT(10) NOT NULL AUTO_INCREMENT, title TEXT(255) NOT NULL, url TEXT(255) NOT NULL, content TEXT(65535) NOT NULL)");

      addTestPage();

    }

    public function addPage($title, $url, $content) {

      $stmt = $this->pdo->prepare('INSERT INTO pages (title, url, content) (:title, :url, :content)');
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':url', $url);
      $stmt->bindParam(':content', $content);

      return $stmt->execute();

    }

    public function getPageExists($title = null, $url = null) {

      $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE title = "*:title*"');
      $stmt->bindParam(':title', $title);

      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);

      var_dump($result);

      return sizeof($result) > 0;

    }

    function addTestPage() {

      $title = 'Test Page - Delete';
      $url = 'test/page';
      $content = 'On a good day, john enjoys a cold brew.';

      return $this->addPage($title, $url, $content);

    }

  }
