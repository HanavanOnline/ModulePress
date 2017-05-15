<?php

  class Addon {
    private $name = null;
    private $slug = null;
    private $version = null;
    private $path = null;

    function __construct($slug, $name, $version, $path) {
      $this->slug = $slug;
      $this->name = $name;
      $this->version = $version;
      $this->path = $path;
    }

    public function getSlug() {
      return $this->slug;
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
