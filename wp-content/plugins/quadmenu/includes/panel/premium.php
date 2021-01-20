<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Premium extends QuadMenu_Panel {

  static $status = array();

  function __construct() {
    add_action('admin_menu', array($this, 'add_menu'));
  }

  function add_menu() {
    add_submenu_page(self::$panel_slug, esc_html__('Premium', 'quadmenu'), sprintf('%s <i class="dashicons dashicons-awards"></i>', esc_html__('Premium', 'quadmenu')), 'edit_posts', self::$panel_slug . '_premium', array($this, 'add_panel'));
  }

  function add_panel() {
    global $submenu;
    include (QUADMENU_PLUGIN_DIR . '/includes/panel/pages/parts/header.php');
    include (QUADMENU_PLUGIN_DIR . '/includes/panel/pages/premium.php');
  }

}

new QuadMenu_Premium();
