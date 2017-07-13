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
        $this->pdo = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
      } catch(Exception $e) {
      }
    }

    public function getTableExists($table) {
      $stmt = $this->pdo->prepare('SHOW TABLES LIKE :table');
      $stmt->bindParam(':table', $table);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result === FALSE;
    }

    public function doPrepareDatabase() {

      if($this->getTableExists('pages')) {

        $stmt = $this->pdo->prepare("CREATE TABLE pages (id INT(10) NOT NULL AUTO_INCREMENT, title TEXT(255) NOT NULL, url TEXT(255) NOT NULL, content TEXT(65535) NOT NULL, PRIMARY KEY (id))");

        $stmt->execute();

        $this->addTestPage();

      }

      if($this->getTableExists('users')) {

        $stmt = $this->pdo->prepare("CREATE TABLE users (id INT(10) NOT NULL AUTO_INCREMENT, username TEXT(65535) NOT NULL, password TEXT(65535) NOT NULL, slug TEXT(255) NOT NULL, PRIMARY KEY (id))");

        $stmt->execute();

      }

      if($this->getTableExists('media')) {

        $stmt = $this->pdo->prepare("CREATE TABLE media (id INT(10) NOT NULL AUTO_INCREMENT, filename TEXT(65535) NOT NULL, title TEXT(65535) NULL DEFAULT NULL, slug TEXT(255) NULL DEFAULT NULL, PRIMARY KEY (id))");

        $stmt->execute();

      }

      if($this->getTableExists('options')) {

        $stmt = $this->pdo->prepare("CREATE TABLE options (id INT(10) NOT NULL AUTO_INCREMENT, data TEXT(255) NOT NULL, value TEXT(65535) NOT NULL, PRIMARY KEY (id))");

        $stmt->execute();

      }

    }

    public function addPage($title, $url, $content) {

      $stmt = $this->pdo->prepare('INSERT INTO pages (title, url, content) VALUES (:title, :url, :content)');
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':url', $url);
      $stmt->bindParam(':content', $content);

      return $stmt->execute();

    }

    public function addMedia($title, $filename, $slug) {

      $stmt = $this->pdo->prepare('INSERT INTO media (title, filename, slug) VALUES (:title, :filename, :slug)');
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':filename', $filename);
      $stmt->bindParam(':slug', $slug);

      return $stmt->execute();

    }

    public function getPageExists($title = null, $url = null) {

      if($url != null) {

        $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE url = :url');
        $stmt->bindParam(':url', $url);

        $result = $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);

        return sizeof($result) > 0;

      }

    }

    public function getMediaExists($filename) {

      $stmt = $this->pdo->prepare('SELECT * FROM media WHERE filename = :filename');
      $stmt->bindParam(':filename', $filename);

      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);

      return sizeof($result) > 0;

    }

    public function getMediaTitle($filename) {
      $stmt = $this->pdo->prepare('SELECT title FROM media WHERE filename = :filename');
      $stmt->bindParam(':filename', $filename);

      $stmt->execute();

      $result = $stmt->fetch()[0];

      return $result;
    }

    public function getPageContents($url) {

      if(!$this->getPageExists(null, $url))
        return '';

      $stmt = $this->pdo->prepare('SELECT content FROM pages WHERE url = :url');
      $stmt->bindParam(':url', $url);

      $stmt->execute();

      $result = $stmt->fetchColumn();

      return $result;

    }

    function addTestPage() {

      $title = 'Test Page - Delete';
      $url = 'test/page';
      $content = 'On a good day, john enjoys a cold brew.';

      return $this->addPage($title, $url, $content);

    }

  }
