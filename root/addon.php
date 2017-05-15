<?php

  class Addon {
    private $name = null;
    private $version = null;

    function __construct($name, $version) {
      $this->name = $name;
      $this->version = $version;
    }

    public function getName() {
      return $this->name;
    }

    public function getVersion() {
      return $this->version;
    }

  }
