<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Panel_Redux extends QuadMenu_Panel {

  function __construct() {
    add_action('redux/' . QUADMENU_DB_OPTIONS . '/panel/before', array($this, 'header'));
    add_filter('quadmenu_redux_args', array($this, 'args'), -10);
  }

  function args($args) {
    
    $args['menu_type'] = 'submenu';
    $args['page_parent'] = self::$panel_slug;
    $args['page_slug'] = QUADMENU_PANEL;
    $args['allow_sub_menu'] = true;
    $args['page'] = $args['display_name'] = $args['menu_title'] = esc_html__('Options', 'quadmenu');

    if (!is_admin()) {
      $args['page'] = $args['display_name'] = $args['menu_title'] = QUADMENU_PLUGIN_NAME;
    }

    return $args;
  }

  function header() {

    global $submenu;

    require_once QUADMENU_PLUGIN_DIR . 'includes/panel/pages/parts/header.php';
  }

}

new QuadMenu_Panel_Redux();
