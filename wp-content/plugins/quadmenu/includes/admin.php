<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Admin {

  public function __construct() {

    add_action('wp_ajax_quadmenu_dismiss_notice', array($this, 'ajax_dismiss_notice'));

    add_action('admin_enqueue_scripts', array($this, 'register'), -1);

    add_action('admin_notices', array($this, 'notices'));

    add_action('admin_notices', array($this, 'add_rating_notice'));

    add_action('admin_footer', array($this, 'icons'));

    add_filter('plugin_action_links_' . QUADMENU_PLUGIN_BASENAME, array($this, 'add_action_links'));
  }

  function add_action_links($links) {

    $links[] = '<a target="_blank" href="' . QUADMENU_DEMO_URL . '">' . esc_html__('Premium', 'quadmenu') . '</a>';

    $links[] = '<a href="' . admin_url('admin.php?page=' . QUADMENU_PANEL) . '">' . esc_html__('Settings', 'quadmenu') . '</a>';

    return $links;
  }

  public function register() {

    wp_register_style('quadmenu-modal', QUADMENU_PLUGIN_URL . 'assets/backend/css/modal' . QuadMenu::isMin() . '.css', array(), QUADMENU_PLUGIN_VERSION, 'all');

    wp_register_style('quadmenu-admin', QUADMENU_PLUGIN_URL . 'assets/backend/css/quadmenu-admin' . QuadMenu::isMin() . '.css', array('quadmenu-modal', _QuadMenu()->selected_icons()->ID), QUADMENU_PLUGIN_VERSION, 'all');

    wp_register_script('quadmenu-admin', QUADMENU_PLUGIN_URL . 'assets/backend/js/quadmenu-admin' . QuadMenu::isMin() . '.js', array('jquery', 'jquery-ui-sortable'), QUADMENU_PLUGIN_VERSION, false);
  }

  public function icons() {

    global $pagenow, $quadmenu_active_locations;

    if ($pagenow != 'nav-menus.php')
      return;

    if (!is_array($quadmenu_active_locations))
      return;

    if (!count($quadmenu_active_locations))
      return;
    ?>
    <script>
      jQuery(document).ready(function () {
        var $list = jQuery('.menu-theme-locations'),
                locations = <?php echo json_encode(array_keys($quadmenu_active_locations)); ?>,
                icon = '<img src="<?php echo esc_url(QUADMENU_PLUGIN_URL . 'assets/backend/images/icond.png'); ?>" style="width: 1em; height: auto; margin: 1px 0 -1px 0; "/>';

        jQuery.each(locations, function (index, item) {
          $list.find('input#locations-' + item).after(icon);
        });

      });
    </script>
    <?php
  }

  public function notices() {

    if ($notices = get_option('quadmenu_admin_notices', false)) {
      foreach ($notices as $notice) {

        if (empty($notice['class']) || empty($notice['notice']))
          continue;
        ?>
        <div class="<?php echo esc_attr($notice['class']); ?>">
          <p><?php esc_html_e($notice['notice']); ?></p>
        </div>
        <?php
      }
      delete_option('quadmenu_admin_notices');
    }
  }

  static function add_notification($class = 'updated', $notice = false) {

    if (!$notice)
      return;

    $notices = get_option('quadmenu_admin_notices', array());

    $notices[] = array(
        'class' => $class,
        'notice' => $notice,
    );

    update_option('quadmenu_admin_notices', $notices);
  }

  function ajax_dismiss_notice() {

    if ($notice_id = ( isset($_POST['notice_id']) ) ? sanitize_key($_POST['notice_id']) : '') {

      update_user_meta(get_current_user_id(), $notice_id, true);

      wp_send_json($notice_id);
    }

    wp_die();
  }

  function add_rating_notice() {

    if (!get_transient('_quadmenu_first_rating') && !get_user_meta(get_current_user_id(), 'quadmenu-user-rating', true)) {
      ?>
      <div id="quadmenu-admin-rating" class="quadmenu-notice notice is-dismissible" data-notice_id="quadmenu-user-rating">
        <div class="notice-container" style="padding-top: 10px; padding-bottom: 10px; display: flex; justify-content: left; align-items: center;">
          <div class="notice-image">
            <img style="border-radius:50%;max-width: 90px;" src="<?php echo QUADMENU_PLUGIN_URL; ?>assets/backend/images/logo.jpg" alt="<?php echo esc_html(QUADMENU_PLUGIN_NAME); ?>>">
          </div>
          <div class="notice-content" style="margin-left: 15px;">
            <p>
              <?php printf(esc_html__('Hello! Thank you for choosing the %s plugin!', 'quadmenu'), QUADMENU_PLUGIN_NAME); ?>
              <br/>
              <?php esc_html_e('Could you please give it a 5-star rating on WordPress? We know its a big favor, but we\'ve worked very much and very hard to release this great product and this will boost our motivation and help us promote and continue to improving this product.', 'quadmenu'); ?>
            </p>
            <a href="<?php echo esc_url(QUADMENU_REVIEW_URL); ?>" class="button-primary" target="_blank">
              <?php esc_html_e('Yes, of course!', 'quadmenu'); ?>
            </a>
            <a href="<?php echo esc_url(QUADMENU_SUPPORT_URL); ?>" class="button-secondary" target="_blank">
              <?php esc_html_e('Report a bug', 'quadmenu'); ?>
            </a>
          </div>				
        </div>
      </div>
      <script>
        (function ($) {
          $('.quadmenu-notice').on('click', '.notice-dismiss', function (e) {
            e.preventDefault();

            var notice_id = $(e.delegateTarget).data('notice_id');

            $.ajax({
              type: 'POST',
              url: ajaxurl,
              data: {
                notice_id: notice_id,
                action: 'quadmenu_dismiss_notice',
              },
              success: function (response) {
                console.log(response);
              },
            });
          });
        })(jQuery);
      </script>
      <?php
    }
  }

}

new QuadMenu_Admin();
