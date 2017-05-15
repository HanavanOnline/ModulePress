<?php

  abstract class Module {
    private $key = null;
    private $details = null;
    private $parent = null;

    function __construct($key, $details = null, $parent = null) {
      $this->key = $key;
      $this->details = $details;
      $this->parent = $parent;
    }

    public function getKey() {
      return $this->key;
    }

    public function getDetails() {
      return $this->details;
    }

    public function getDetail($key) {
      return $this->$details[$key];
    }

    public function getParent() {
      return $this->parent;
    }

    public function getOpen(){}
    public function getClose(){}
  }
