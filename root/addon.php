<?php

  class Addon {
    private $name = null;
    private $version = null;
    private $path = null;

    function __construct($name, $version, $path) {
      $this->name = $name;
      $this->version = $version;
      $this->path = $path;
    }

    public function getName() {
      return $this->name;
    }

    public function getVersion() {
      return $this->version;
    }

    public function getPath() {
      return $this->path;
    }

  }
