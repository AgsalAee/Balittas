<?php

class QuadMenu_Suggestions extends QuadMenu_Panel {

  function __construct() {
    add_action('admin_menu', array($this, 'add_menu'));
    add_action('admin_init', array($this, 'add_redirect'));
    add_filter('network_admin_url', array($this, 'network_admin_url'), 10, 2);
  }

  function add_menu() {
    add_submenu_page(self::$panel_slug, esc_html__('Suggestions', 'quadmenu'), sprintf('%s', esc_html__('Suggestions', 'quadmenu')), 'edit_posts', self::$panel_slug . '_suggestions', array($this, 'add_panel'));
  }

  function add_panel() {
    global $submenu;
    include_once(QUADMENU_PLUGIN_DIR . 'includes/suggestions.php');
    $wp_list_table = new QuadMenu_Suggestions_List_Table();
    $wp_list_table->prepare_items();
    include (QUADMENU_PLUGIN_DIR . '/includes/panel/pages/parts/header.php');
    include (QUADMENU_PLUGIN_DIR . '/includes/panel/pages/suggestions.php');
  }

  // fix for activateUrl on install now button
  public function network_admin_url($url, $path) {

    if (wp_doing_ajax() && !is_network_admin()) {
      if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'install-plugin') {
        if (strpos($url, 'plugins.php') !== false) {
          $url = self_admin_url($path);
        }
      }
    }

    return $url;
  }

  public function add_redirect() {

    if (isset($_REQUEST['activate']) && $_REQUEST['activate'] == 'true') {
      if (wp_get_referer() == admin_url('admin.php?page=' . self::$panel_slug . '_suggestions')) {
        wp_redirect(admin_url('admin.php?page=' . self::$panel_slug . '_suggestions'));
      }
    }
  }

}

new QuadMenu_Suggestions();
