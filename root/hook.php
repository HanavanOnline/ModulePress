<?php

  class Hook {
    private $key = null;
    private $func = null;

    function __construct($key, $func) {
      $this->key = $key;
      $this->func = $func;
    }

    public function getKey() {
      return $this->key;
    }

    public function call(&$data = null) {
      if($data == null) {
        call_user_func($this->func, null);
        return;
      }
      call_user_func_array($this->func, array(&$data));
    }

  }
