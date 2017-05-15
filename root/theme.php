<?php

  class Theme {
    private $slug = null;
    private $name = null;
    function __construct($slug, $name) {
      $this->slug = $slug;
      $this->name = $name;
    }

    public function getName() {
      return $this->name;
    }

    public function getSlug() {
      return $this->slug;
    }

    public function fail($reason) {
      echo "$this->getName() failed because: $reason";
    }

  }
