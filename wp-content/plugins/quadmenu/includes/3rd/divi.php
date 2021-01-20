<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Divi_Module {

  public function __construct() {
    add_action('divi_extensions_init', array($this, 'includes'));
  }

  function includes() {
    include_once 'divi/QuadmenuDiviModule.php';
  }

}

new QuadMenu_Divi_Module();
