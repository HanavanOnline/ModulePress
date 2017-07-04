<?php

  class Resource {
    private $url = null;
    private $name = null;
    private $content_type = null;

    function __construct($url, $name, $content_type = 'text/css') {
      $this->url = $url;
      $this->name = $name;
      $this->content_type = $content_type;
    }

    public function getURL() {
      return $this->url;
    }

    public function getName() {
      return $this->name;
    }

    public function getContentType() {
      return $this->content_type;
    }

    public function getData() {
      return file_get_contents($this->url);
    }

    public function render() {
      header('Content-Type:' . $this->content_type);
      echo $this->getData();
      exit();
    }

  }
