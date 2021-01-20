<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Configuration {

  public function __construct() {

    // Ajax
    // ---------------------------------------------------------------------

    add_filter('quadmenu_setup_nav_menu_item', array($this, 'default_values_nav_menu_items'), 15);

    add_filter('quadmenu_nav_menu_item_field_default', array($this, 'custom_default_values_nav_menu_items'), 10, 3);

    add_filter('quadmenu_compiler_files', array($this, 'files'));

    add_filter('quadmenu_register_icons', array($this, 'icons'), 1);

    add_filter('quadmenu_default_options', array($this, 'configuration'), 1);

    add_filter('quadmenu_default_options', array($this, 'responsive'), 1);

    add_filter('quadmenu_default_options', array($this, 'css'), 1);

    add_filter('quadmenu_default_options_themes', array($this, 'themes_options'), 1);

    add_filter('quadmenu_default_options_locations', array($this, 'locations_options'), 1);
  }

  static function custom_nav_menu_items() {

    $items = array();

    // QuadMenu
    // ---------------------------------------------------------------------

    $items['mega'] = array(
        'label' => esc_html__('QuadMenu Mega', 'quadmenu'),
        'title' => esc_html__('Mega', 'quadmenu'),
        'panels' => array(
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('subtitle', 'badge', 'float', 'hidden'),
            ),
            'icon' => array(
                'title' => esc_html__('Icon', 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
            'background' => array(
                'title' => esc_html__('Background', 'quadmenu'),
                'icon' => 'dashicons dashicons-format-image',
                'settings' => array('background'),
            ),
            'width' => array(
                'title' => esc_html__('Width', 'quadmenu'),
                'icon' => 'dashicons dashicons-align-left',
                'settings' => array('dropdown', 'stretch', 'width'),
            ),
        ),
        'desc' => esc_html__('A menu which can wrap any type of widget.', 'quadmenu'),
        'parent' => 'main',
        'depth' => 0,
    );

    $items['icon'] = array(
        'label' => esc_html__('QuadMenu Icon', 'quadmenu'),
        'title' => esc_html__('Icon', 'quadmenu'),
        'panels' => array(
            'icon' => array(
                'title' => esc_html__('Icon', 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('float', 'hidden', 'dropdown'),
            ),
        ),
        'desc' => esc_html__('Just an icon, no title.', 'quadmenu'),
        'depth' => 0,
    );
    $items['cart'] = array(
        'label' => esc_html__('QuadMenu Cart', 'quadmenu'),
        'title' => esc_html__('Cart', 'quadmenu'),
        'panels' => array(
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('float', 'hidden'),
            ),
            'icon' => array(
                'title' => esc_html__('Icon', 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
            'cart' => array(
                'title' => esc_html__('Cart', 'quadmenu'),
                'icon' => 'dashicons dashicons-cart',
                'settings' => array('dropdown', 'title', 'cart', 'cart_text'),
            ),
        ),
        'desc' => esc_html__('A cart widget for Woocommerce.', 'quadmenu'),
        'parent' => 'main',
        'depth' => 0,
    );

    $items['search'] = array(
        'label' => esc_html__('QuadMenu Search', 'quadmenu'),
        'title' => esc_html__('Search', 'quadmenu'),
        'panels' => array(
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('placeholder', 'search', 'float', 'hidden'),
            ),
            'icon' => array(
                'title' => esc_html__('Icon', 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
        ),
        'desc' => esc_html__('A search form for the site.', 'quadmenu'),
        'depth' => 0,
    );
    $items['column'] = array(
        'label' => esc_html__('Column', 'quadmenu'),
        'title' => esc_html__('Column', 'quadmenu'),
        'settings' => array('columns'),
        'desc' => esc_html__('Column to organize the content.', 'quadmenu'),
        'depth' => 1,
        'parent' => array('panel', 'tab', 'mega'),
    );
    $items['widget'] = array(
        'label' => esc_html__('QuadMenu Widget', 'quadmenu'),
        'title' => esc_html__('Widget', 'quadmenu'),
        'panels' => array(
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-align-left',
                'settings' => array('hidden'),
            ),
        ),
        'desc' => esc_html__('Include a widget inside column.', 'quadmenu'),
        'parent' => 'column',
    );

    // WordPress
    // ---------------------------------------------------------------------

    $items['custom'] = array(
        'panels' => array(
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
            ),
            'icon' => array(
                'title' => esc_html__('Icon', 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
        ),
        'parent' => array('main', 'column', 'login', 'icon', 'button', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
    );
    $items['taxonomy'] = array(
        'panels' => array(
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
            ),
            'icon' => array(
                'title' => esc_html__('Icon', 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
        ),
        'parent' => array('main', 'column', 'login', 'icon', 'button', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
    );
    $items['post_type'] = array(
        'panels' => array(
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
            ),
            'icon' => array(
                'title' => esc_html__('Icon', 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
            'content' => array(
                'title' => esc_html__('Content', 'quadmenu'),
                'icon' => 'dashicons dashicons-format-aside',
                'settings' => array('thumb', 'excerpt'),
            ),
        ),
        'parent' => array('main', 'column', 'login', 'icon', 'button', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
    );

    $items['post_type_archive'] = array(
        'panels' => array(
            /* 'default' => array(
              'title' => esc_html__('Default', 'quadmenu'),
              'icon' => 'dashicons dashicons-menu',
              'settings' => array('url', 'title', 'attr-title', 'classes', 'xfn', 'description'),
              ), */
            'general' => array(
                'title' => esc_html__('General', 'quadmenu'),
                'icon' => 'dashicons dashicons-admin-settings',
                'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
            ),
            'icon' => array(
                'title' => esc_html__(esc_html__('Icon', 'quadmenu'), 'quadmenu'),
                'icon' => 'dashicons dashicons-art',
                'settings' => array('icon'),
            ),
        ),
        'parent' => array('main', 'column', 'login', 'icon', 'button', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
    );

    return json_decode(json_encode(apply_filters('quadmenu_custom_nav_menu_items', $items)));
  }

  public function nav_menu_item_fields($menu_obj = false) {

    $settings = array();

    $settings['id'] = array(
        'id' => 'id',
        'db' => 'id',
        'type' => 'id',
    );

    $settings['url'] = array(
        'id' => 'url',
        'db' => 'url',
        'title' => esc_html__('URL'),
        'placeholder' => esc_html__('URL'),
        'type' => 'text',
        'default' => '',
    );

    $settings['title'] = array(
        'id' => 'title',
        'db' => 'title',
        'title' => esc_html__('Title'),
        'placeholder' => esc_html__('Navigation Label'),
        'type' => 'text',
        'default' => '',
    );

    $settings['attr-title'] = array(
        'id' => 'attr-title',
        'db' => 'post_excerpt',
        'title' => esc_html__('Title Attribute'),
        'placeholder' => esc_html__('Title Attribute'),
        'type' => 'text',
        'default' => '',
    );

    $settings['classes'] = array(
        'id' => 'classes',
        'db' => 'classes',
        'title' => esc_html__('CSS Classes (optional)'),
        'placeholder' => esc_html__('CSS Classes (optional)'),
        'type' => 'text',
        'default' => array(),
    );

    $settings['target'] = array(
        'id' => 'target',
        'db' => 'target',
        'target' => 'target',
        'title' => esc_html__('Target'),
        'placeholder' => esc_html__('Open link in a new tab'),
        'type' => 'checkbox',
        'ops' => '_blank',
        'default' => '',
    );

    $settings['xfn'] = array(
        'id' => 'xfn',
        'db' => 'xfn',
        'title' => esc_html__('Link Relationship (XFN)'),
        'placeholder' => esc_html__('Link Relationship (XFN)'),
        'type' => 'text',
        'default' => '',
    );

    $settings['description'] = array(
        'id' => 'description',
        'db' => 'description',
        'desc' => esc_html__('The description will be displayed in the menu if the current theme supports it.'),
        'type' => 'textarea',
        'default' => '',
    );

    $settings['icon'] = array(
        'id' => 'quadmenu-settings[icon]',
        'db' => 'icon',
        'type' => 'icon',
        'placeholder' => esc_html__('Search', 'quadmenu'),
        'default' => '',
    );

    $settings['subtitle'] = array(
        'id' => 'quadmenu-settings[subtitle]',
        'db' => 'subtitle',
        'title' => esc_html__('Subtitle', 'quadmenu'),
        'placeholder' => esc_html__('Enter item subtitle', 'quadmenu'),
        'type' => 'text',
        'default' => '',
    );

    $settings['placeholder'] = array(
        'id' => 'quadmenu-settings[placeholder]',
        'db' => 'subtitle',
        'title' => esc_html__('Placeholder', 'quadmenu'),
        'placeholder' => esc_html__('Enter item placeholder', 'quadmenu'),
        'type' => 'text',
        'default' => '',
    );

    $settings['badge'] = array(
        'id' => 'quadmenu-settings[badge]',
        'db' => 'badge',
        'title' => esc_html__('Badge', 'quadmenu'),
        'placeholder' => esc_html__('Item badge title', 'quadmenu'),
        'type' => 'text',
        'default' => '',
    );

    $settings['float'] = array(
        'id' => 'quadmenu-settings[float]',
        'db' => 'float',
        'title' => esc_html__('Float', 'quadmenu'),
        'placeholder' => esc_html__('Float item to left or right', 'quadmenu'),
        'type' => 'select',
        'default' => '',
        'depth' => 0,
        'ops' => array(
            '' => esc_html__('Default item position', 'quadmenu'),
            'opposite' => esc_html__('Float item opposite to default', 'quadmenu')
        )
    );

    $settings['dropdown'] = array(
        'id' => 'quadmenu-settings[dropdown]',
        'db' => 'dropdown',
        'title' => esc_html__('Dropdown Float', 'quadmenu'),
        'placeholder' => esc_html__('Float dropdown to left o right', 'quadmenu'),
        'type' => 'select',
        'default' => 'right',
        'ops' => array(
            'right' => esc_html__('Float dropdown right', 'quadmenu'),
            'left' => esc_html__('Float dropdown left', 'quadmenu')
        )
    );

    $settings['hidden'] = array(
        'id' => 'quadmenu-settings[hidden]',
        'db' => 'hidden',
        'title' => esc_html__('Hide on screen sizes', 'quadmenu'),
        'type' => 'multicheck',
        'default' => '',
        'ops' => array(
            'hidden-xs' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'XS'),
            'hidden-sm' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'SM'),
            'hidden-md' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'MD'),
            'hidden-lg' => sprintf(esc_html__('Hidden %1$s', 'quadmenu'), 'LG'),
        )
    );

    $settings['thumb'] = array(
        'id' => 'quadmenu-settings[thumb]',
        'db' => 'thumb',
        'title' => esc_html__('Show featured image', 'quadmenu'),
        'type' => 'select',
        'default' => '',
        'depth' => array(1, 2, 3, 4),
        'ops' => array(
            '' => esc_html__('Hide featured image', 'quadmenu'),
            'thumbnail' => esc_html__('Show featured image in thumbnail size', 'quadmenu'),
            'large' => esc_html__('Show featured image in wide size', 'quadmenu'),
        ),
    );

    $settings['excerpt'] = array(
        'id' => 'quadmenu-settings[excerpt]',
        'db' => 'excerpt',
        'type' => 'checkbox',
        'depth' => array(1, 2, 3, 4),
        'title' => esc_html__('Excerpt', 'quadmenu'),
        'placeholder' => esc_html__('Show items excerpt', 'quadmenu'),
        'default' => 'off',
    );

    $settings['background'] = array(
        'id' => 'quadmenu-settings[background]',
        'db' => 'background',
        'type' => 'background',
        'default' => array(
            'thumbnail-id' => 0,
            'size' => '',
            'position' => '',
            'repeat' => '',
            'origin' => 'border-box',
            'opacity' => 1,
        ),
    );

    $settings['stretch'] = array(
        'id' => 'quadmenu-settings[stretch]',
        'db' => 'stretch',
        'title' => esc_html__('Dropdown Width', 'quadmenu'),
        'desc' => esc_html__('This controls the width of the dropdown and contents.', 'quadmenu'),
        'type' => 'select',
        'default' => 'boxed',
        'ops' => array(
            'boxed' => esc_html__('Boxed dropdown', 'quadmenu'),
            'dropdown' => esc_html__('Stretch dropdown', 'quadmenu'),
            //'content' => esc_html__('Stretch dropdown and content', 'quadmenu'),
            '' => esc_html__('Custom dropdown width', 'quadmenu'),
        ),
    );

    $settings['width'] = array(
        'id' => 'quadmenu-settings[columns]',
        'db' => 'columns',
        'type' => 'width',
        'default' => array(),
        'ops' => array(
            'icons' => array(
                'md',
                'lg'
            ),
            'columns' => array(
                'md',
                'lg'
            ),
        ),
    );

    $settings['columns'] = array(
        'id' => 'quadmenu-settings[columns]',
        'db' => 'columns',
        'type' => 'width',
        'default' => array(),
        'ops' => array(
            'icons' => array(
                'xs',
                'sm',
                'md',
                'lg'
            ),
            'columns' => array(
                '',
                'sm',
                'md',
                'lg'
            ),
            'hidden' => array(
                'xs',
                'sm',
                'md',
                'lg'
            ),
        ),
    );

    $settings['cart'] = array(
        'id' => 'quadmenu-settings[cart]',
        'db' => 'cart',
        'title' => esc_html__('Cart', 'quadmenu'),
        'type' => 'select',
        'default' => 'woo',
        'ops' => array(
            'woo' => esc_html__('WooCommerce Cart', 'quadmenu'),
            'edd' => esc_html__('Easy Digital Downloads Cart', 'quadmenu'),
        )
    );

    $settings['cart_text'] = array(
        'id' => 'quadmenu-settings[cart_text]',
        'db' => 'cart_text',
        'title' => esc_html__('Text', 'quadmenu'),
        'type' => 'textarea',
        'default' => '',
    );

    $settings['social'] = array(
        'id' => 'quadmenu-settings[social]',
        'db' => 'social',
        'title' => esc_html__('Social', 'quadmenu'),
        'type' => 'select',
        'default' => 'toggle',
        'ops' => array(
            'embed' => esc_html__('Embeded', 'quadmenu'),
            'toggle' => esc_html__('Toggle', 'quadmenu'),
        )
    );

    $settings['search'] = array(
        'id' => 'quadmenu-settings[search]',
        'db' => 'search',
        'title' => esc_html__('Search', 'quadmenu'),
        'type' => 'multicheck',
        'default' => 'post',
        'ops' => $this->post_types()
    );

    return apply_filters('quadmenu_nav_menu_item_fields', $settings, $menu_obj);
  }

  function post_types() {

    $ops = array();

    $post_types = get_post_types(array('show_in_nav_menus' => true), 'object');

    foreach ($post_types as $post_type) {

      $ops[$post_type->name] = $post_type->label;
    }

    return $ops;
  }

  function nav_menu_item_fields_defaults() {

    $defaults = array();

    $fields = $this->nav_menu_item_fields();

    foreach ($fields as $id => $field) {
      $defaults[$id] = isset($field['default']) ? $field['default'] : esc_html__('Undefined default', 'quadmenu');
    }

    return $defaults;
  }

  function default_values_nav_menu_items($item) {

    $defaults = $this->nav_menu_item_fields_defaults();

    foreach ($defaults as $key => $value) {

      if (property_exists($item, $key))
        continue;

      $item->{$key} = apply_filters('quadmenu_nav_menu_item_field_default', $value, $key, $item);
    }

    return $item;
  }

  function custom_default_values_nav_menu_items($value, $key, $item) {

    if ($key == 'icon') {

      if ($item->quadmenu == 'social') {
        $value = 'dashicons dashicons-share';
      }

      if ($item->quadmenu == 'cart') {
        $value = 'dashicons dashicons-cart';
      }

      if ($item->quadmenu == 'icon') {
        $value = 'dashicons dashicons-info';
      }
    }

    if ($key == 'columns') {

      if ($item->quadmenu == 'column') {
        $value = array(
            'col-12',
            'col-sm-4'
        );
      }
    }

    return $value;
  }

  function files($files) {

    $files[] = QUADMENU_PLUGIN_URL . 'assets/frontend/less/quadmenu-locations.less';
    $files[] = QUADMENU_PLUGIN_URL . 'assets/frontend/less/quadmenu-widgets.less';

    return $files;
  }

  function icons() {

    $register_icons = array(
        'dashicons' => array(
            'name' => 'Dashicons',
            'url' => false,
            'prefix' => '',
            'iconmap' => 'dashicons dashicons-menu,dashicons dashicons-admin-site,dashicons dashicons-dashboard,dashicons dashicons-admin-post,dashicons dashicons-admin-media,dashicons dashicons-admin-links,dashicons dashicons-admin-page,dashicons dashicons-admin-comments,dashicons dashicons-admin-appearance,dashicons dashicons-admin-plugins,dashicons dashicons-admin-users,dashicons dashicons-admin-tools,dashicons dashicons-admin-settings,dashicons dashicons-admin-network,dashicons dashicons-admin-home,dashicons dashicons-admin-generic,dashicons dashicons-admin-collapse,dashicons dashicons-filter,dashicons dashicons-admin-customizer,dashicons dashicons-admin-multisite,dashicons dashicons-welcome-write-blog,dashicons dashicons-welcome-add-page,dashicons dashicons-welcome-view-site,dashicons dashicons-welcome-widgets-menus,dashicons dashicons-welcome-comments,dashicons dashicons-welcome-learn-more,dashicons dashicons-format-aside,dashicons dashicons-format-image,dashicons dashicons-format-gallery,dashicons dashicons-format-video,dashicons dashicons-format-status,dashicons dashicons-format-quote,dashicons dashicons-format-chat,dashicons dashicons-format-audio,dashicons dashicons-camera,dashicons dashicons-images-alt,dashicons dashicons-images-alt2,dashicons dashicons-video-alt,dashicons dashicons-video-alt2,dashicons dashicons-video-alt3,dashicons dashicons-media-archive,dashicons dashicons-media-audio,dashicons dashicons-media-code,dashicons dashicons-media-default,dashicons dashicons-media-document,dashicons dashicons-media-interactive,dashicons dashicons-media-spreadsheet,dashicons dashicons-media-text,dashicons dashicons-media-video,dashicons dashicons-playlist-audio,dashicons dashicons-playlist-video,dashicons dashicons-controls-play,dashicons dashicons-controls-pause,dashicons dashicons-controls-forward,dashicons dashicons-controls-skipforward,dashicons dashicons-controls-back,dashicons dashicons-controls-skipback,dashicons dashicons-controls-repeat,dashicons dashicons-controls-volumeon,dashicons dashicons-controls-volumeoff,dashicons dashicons-image-crop,dashicons dashicons-image-rotate,dashicons dashicons-image-rotate-left,dashicons dashicons-image-rotate-right,dashicons dashicons-image-flip-vertical,dashicons dashicons-image-flip-horizontal,dashicons dashicons-image-filter,dashicons dashicons-undo,dashicons dashicons-redo,dashicons dashicons-editor-bold,dashicons dashicons-editor-italic,dashicons dashicons-editor-ul,dashicons dashicons-editor-ol,dashicons dashicons-editor-quote,dashicons dashicons-editor-alignleft,dashicons dashicons-editor-aligncenter,dashicons dashicons-editor-alignright,dashicons dashicons-editor-insertmore,dashicons dashicons-editor-spellcheck,dashicons dashicons-editor-expand,dashicons dashicons-editor-contract,dashicons dashicons-editor-kitchensink,dashicons dashicons-editor-underline,dashicons dashicons-editor-justify,dashicons dashicons-editor-textcolor,dashicons dashicons-editor-paste-word,dashicons dashicons-editor-paste-text,dashicons dashicons-editor-removeformatting,dashicons dashicons-editor-video,dashicons dashicons-editor-customchar,dashicons dashicons-editor-outdent,dashicons dashicons-editor-indent,dashicons dashicons-editor-help,dashicons dashicons-editor-strikethrough,dashicons dashicons-editor-unlink,dashicons dashicons-editor-rtl,dashicons dashicons-editor-break,dashicons dashicons-editor-code,dashicons dashicons-editor-paragraph,dashicons dashicons-editor-table,dashicons dashicons-align-left,dashicons dashicons-align-right,dashicons dashicons-align-center,dashicons dashicons-align-none,dashicons dashicons-lock,dashicons dashicons-unlock,dashicons dashicons-calendar,dashicons dashicons-calendar-alt,dashicons dashicons-visibility,dashicons dashicons-hidden,dashicons dashicons-post-status,dashicons dashicons-edit,dashicons dashicons-trash,dashicons dashicons-sticky,dashicons dashicons-external,dashicons dashicons-arrow-up,dashicons dashicons-arrow-down,dashicons dashicons-arrow-right,dashicons dashicons-arrow-left,dashicons dashicons-arrow-up-alt,dashicons dashicons-arrow-down-alt,dashicons dashicons-arrow-right-alt,dashicons dashicons-arrow-left-alt,dashicons dashicons-arrow-up-alt2,dashicons dashicons-arrow-down-alt2,dashicons dashicons-arrow-right-alt2,dashicons dashicons-arrow-left-alt2,dashicons dashicons-sort,dashicons dashicons-leftright,dashicons dashicons-randomize,dashicons dashicons-list-view,dashicons dashicons-exerpt-view,dashicons dashicons-grid-view,dashicons dashicons-share,dashicons dashicons-share-alt,dashicons dashicons-share-alt2,dashicons dashicons-twitter,dashicons dashicons-rss,dashicons dashicons-email,dashicons dashicons-email-alt,dashicons dashicons-facebook,dashicons dashicons-facebook-alt,dashicons dashicons-googleplus,dashicons dashicons-networking,dashicons dashicons-hammer,dashicons dashicons-art,dashicons dashicons-migrate,dashicons dashicons-performance,dashicons dashicons-universal-access,dashicons dashicons-universal-access-alt,dashicons dashicons-tickets,dashicons dashicons-nametag,dashicons dashicons-clipboard,dashicons dashicons-heart,dashicons dashicons-megaphone,dashicons dashicons-schedule,dashicons dashicons-wordpress,dashicons dashicons-wordpress-alt,dashicons dashicons-pressthis,dashicons dashicons-update,dashicons dashicons-screenoptions,dashicons dashicons-info,dashicons dashicons-cart,dashicons dashicons-feedback,dashicons dashicons-cloud,dashicons dashicons-translation,dashicons dashicons-tag,dashicons dashicons-category,dashicons dashicons-archive,dashicons dashicons-tagcloud,dashicons dashicons-text,dashicons dashicons-yes,dashicons dashicons-no,dashicons dashicons-no-alt,dashicons dashicons-plus,dashicons dashicons-plus-alt,dashicons dashicons-minus,dashicons dashicons-dismiss,dashicons dashicons-marker,dashicons dashicons-star-filled,dashicons dashicons-star-half,dashicons dashicons-star-empty,dashicons dashicons-flag,dashicons dashicons-warning,dashicons dashicons-location,dashicons dashicons-location-alt,dashicons dashicons-vault,dashicons dashicons-shield,dashicons dashicons-shield-alt,dashicons dashicons-sos,dashicons dashicons-search,dashicons dashicons-slides,dashicons dashicons-analytics,dashicons dashicons-chart-pie,dashicons dashicons-chart-bar,dashicons dashicons-chart-line,dashicons dashicons-chart-area,dashicons dashicons-groups,dashicons dashicons-businessman,dashicons dashicons-id,dashicons dashicons-id-alt,dashicons dashicons-products,dashicons dashicons-awards,dashicons dashicons-forms,dashicons dashicons-testimonial,dashicons dashicons-portfolio,dashicons dashicons-book,dashicons dashicons-book-alt,dashicons dashicons-download,dashicons dashicons-upload,dashicons dashicons-backup,dashicons dashicons-clock,dashicons dashicons-lightbulb,dashicons dashicons-microphone,dashicons dashicons-desktop,dashicons dashicons-tablet,dashicons dashicons-smartphone,dashicons dashicons-phone,dashicons dashicons-index-card,dashicons dashicons-carrot,dashicons dashicons-building,dashicons dashicons-store,dashicons dashicons-album,dashicons dashicons-palmtree,dashicons dashicons-tickets-alt,dashicons dashicons-money,dashicons dashicons-smiley,dashicons dashicons-thumbs-up,dashicons dashicons-thumbs-down,dashicons dashicons-layout'
        ),
        'eleganticons' => array(
            'name' => 'Elegant Icons',
            'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/icons/eleganticons/style.min.css',
            'prefix' => '',
            'iconmap' => 'arrow_up,arrow_down,arrow_left,arrow_right,arrow_left-up,arrow_right-up,arrow_right-down,arrow_left-down,arrow-up-down,arrow_up-down_alt,arrow_left-right_alt,arrow_left-right,arrow_expand_alt2,arrow_expand_alt,arrow_condense,arrow_expand,arrow_move,arrow_carrot-up,arrow_carrot-down,arrow_carrot-left,arrow_carrot-right,arrow_carrot-2up,arrow_carrot-2down,arrow_carrot-2left,arrow_carrot-2right,arrow_carrot-up_alt2,arrow_carrot-down_alt2,arrow_carrot-left_alt2,arrow_carrot-right_alt2,arrow_carrot-2up_alt2,arrow_carrot-2down_alt2,arrow_carrot-2left_alt2,arrow_carrot-2right_alt2,arrow_triangle-up,arrow_triangle-down,arrow_triangle-left,arrow_triangle-right,arrow_triangle-up_alt2,arrow_triangle-down_alt2,arrow_triangle-left_alt2,arrow_triangle-right_alt2,arrow_back,icon_minus-06,icon_plus,icon_close,icon_check,icon_minus_alt2,icon_plus_alt2,icon_close_alt2,icon_check_alt2,icon_zoom-out_alt,icon_zoom-in_alt,icon_search,icon_box-empty,icon_box-selected,icon_minus-box,icon_plus-box,icon_box-checked,icon_circle-empty,icon_circle-slelected,icon_stop_alt2,icon_stop,icon_pause_alt2,icon_pause,icon_menu,icon_menu-square_alt2,icon_menu-circle_alt2,icon_ul,icon_ol,icon_adjust-horiz,icon_adjust-vert,icon_document_alt,icon_documents_alt,icon_pencil,icon_pencil-edit_alt,icon_pencil-edit,icon_folder-alt,icon_folder-open_alt,icon_folder-add_alt,icon_info_alt,icon_error-oct_alt,icon_error-circle_alt,icon_error-triangle_alt,icon_question_alt2,icon_question,icon_comment_alt,icon_chat_alt,icon_vol-mute_alt,icon_volume-low_alt,icon_volume-high_alt,icon_quotations,icon_quotations_alt2,icon_clock_alt,icon_lock_alt,icon_lock-open_alt,icon_key_alt,icon_cloud_alt,icon_cloud-upload_alt,icon_cloud-download_alt,icon_image,icon_images,icon_lightbulb_alt,icon_gift_alt,icon_house_alt,icon_genius,icon_mobile,icon_tablet,icon_laptop,icon_desktop,icon_camera_alt,icon_mail_alt,icon_cone_alt,icon_ribbon_alt,icon_bag_alt,icon_creditcard,icon_cart_alt,icon_paperclip,icon_tag_alt,icon_tags_alt,icon_trash_alt,icon_cursor_alt,icon_mic_alt,icon_compass_alt,icon_pin_alt,icon_pushpin_alt,icon_map_alt,icon_drawer_alt,icon_toolbox_alt,icon_book_alt,icon_calendar,icon_film,icon_table,icon_contacts_alt,icon_headphones,icon_lifesaver,icon_piechart,icon_refresh,icon_link_alt,icon_link,icon_loading,icon_blocked,icon_archive_alt,icon_heart_alt,icon_star_alt,icon_star-half_alt,icon_star,icon_star-half,icon_tools,icon_tool,icon_cog,icon_cogs,arrow_up_alt,arrow_down_alt,arrow_left_alt,arrow_right_alt,arrow_left-up_alt,arrow_right-up_alt,arrow_right-down_alt,arrow_left-down_alt,arrow_condense_alt,arrow_expand_alt3,arrow_carrot_up_alt,arrow_carrot-down_alt,arrow_carrot-left_alt,arrow_carrot-right_alt,arrow_carrot-2up_alt,arrow_carrot-2dwnn_alt,arrow_carrot-2left_alt,arrow_carrot-2right_alt,arrow_triangle-up_alt,arrow_triangle-down_alt,arrow_triangle-left_alt,arrow_triangle-right_alt,icon_minus_alt,icon_plus_alt,icon_close_alt,icon_check_alt,icon_zoom-out,icon_zoom-in,icon_stop_alt,icon_menu-square_alt,icon_menu-circle_alt,icon_document,icon_documents,icon_pencil_alt,icon_folder,icon_folder-open,icon_folder-add,icon_folder_upload,icon_folder_download,icon_info,icon_error-circle,icon_error-oct,icon_error-triangle,icon_question_alt,icon_comment,icon_chat,icon_vol-mute,icon_volume-low,icon_volume-high,icon_quotations_alt,icon_clock,icon_lock,icon_lock-open,icon_key,icon_cloud,icon_cloud-upload,icon_cloud-download,icon_lightbulb,icon_gift,icon_house,icon_camera,icon_mail,icon_cone,icon_ribbon,icon_bag,icon_cart,icon_tag,icon_tags,icon_trash,icon_cursor,icon_mic,icon_compass,icon_pin,icon_pushpin,icon_map,icon_drawer,icon_toolbox,icon_book,icon_contacts,icon_archive,icon_heart,icon_profile,icon_group,icon_grid-2x2,icon_grid-3x3,icon_music,icon_pause_alt,icon_phone,icon_upload,icon_download,social_facebook,social_twitter,social_pinterest,social_googleplus,social_tumblr,social_tumbleupon,social_wordpress,social_instagram,social_dribbble,social_vimeo,social_linkedin,social_rss,social_deviantart,social_share,social_myspace,social_skype,social_youtube,social_picassa,social_googledrive,social_flickr,social_blogger,social_spotify,social_delicious,social_facebook_circle,social_twitter_circle,social_pinterest_circle,social_googleplus_circle,social_tumblr_circle,social_stumbleupon_circle,social_wordpress_circle,social_instagram_circle,social_dribbble_circle,social_vimeo_circle,social_linkedin_circle,social_rss_circle,social_deviantart_circle,social_share_circle,social_myspace_circle,social_skype_circle,social_youtube_circle,social_picassa_circle,social_googledrive_alt2,social_flickr_circle,social_blogger_circle,social_spotify_circle,social_delicious_circle,social_facebook_square,social_twitter_square,social_pinterest_square,social_googleplus_square,social_tumblr_square,social_stumbleupon_square,social_wordpress_square,social_instagram_square,social_dribbble_square,social_vimeo_square,social_linkedin_square,social_rss_square,social_deviantart_square,social_share_square,social_myspace_square,social_skype_square,social_youtube_square,social_picassa_square,social_googledrive_square,social_flickr_square,social_blogger_square,social_spotify_square,social_delicious_square,icon_printer,icon_calulator,icon_building,icon_floppy,icon_drive,icon_search-2,icon_id,icon_id-2,icon_puzzle,icon_like,icon_dislike,icon_mug,icon_currency,icon_wallet,icon_pens,icon_easel,icon_flowchart,icon_datareport,icon_briefcase,icon_shield,icon_percent,icon_globe,icon_globe-2,icon_target,icon_hourglass,icon_balance,icon_rook,icon_printer-alt,icon_calculator_alt,icon_building_alt,icon_floppy_alt,icon_drive_alt,icon_search_alt,icon_id_alt,icon_id-2_alt,icon_puzzle_alt,icon_like_alt,icon_dislike_alt,icon_mug_alt,icon_currency_alt,icon_wallet_alt,icon_pens_alt,icon_easel_alt,icon_flowchart_alt,icon_datareport_alt,icon_briefcase_alt,icon_shield_alt,icon_percent_alt,icon_globe_alt,icon_clipboard'
        ),
        'elusive' => array(
            'name' => 'Elusive Icons',
            'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/icons/elusive/css/elusive-icons.min.css',
            'iconmap' => 'el el-address-book-alt,el el-address-book,el el-adjust-alt,el el-adjust,el el-adult,el el-align-center,el el-align-justify,el el-align-left,el el-align-right,el el-arrow-down,el el-arrow-left,el el-arrow-right,el el-arrow-up,el el-asl,el el-asterisk,el el-backward,el el-ban-circle,el el-barcode,el el-behance,el el-bell,el el-blind,el el-blogger,el el-bold,el el-book,el el-bookmark-empty,el el-bookmark,el el-braille,el el-briefcase,el el-broom,el el-brush,el el-bulb,el el-bullhorn,el el-calendar-sign,el el-calendar,el el-camera,el el-car,el el-caret-down,el el-caret-left,el el-caret-right,el el-caret-up,el el-cc,el el-certificate,el el-check-empty,el el-check,el el-chevron-down,el el-chevron-left,el el-chevron-right,el el-chevron-up,el el-child,el el-circle-arrow-down,el el-circle-arrow-left,el el-circle-arrow-right,el el-circle-arrow-up,el el-cloud-alt,el el-cloud,el el-cog-alt,el el-cog,el el-cogs,el el-comment-alt,el el-comment,el el-compass-alt,el el-compass,el el-credit-card,el el-css,el el-dashboard,el el-delicious,el el-deviantart,el el-digg,el el-download-alt,el el-download,el el-dribbble,el el-edit,el el-eject,el el-envelope-alt,el el-envelope,el el-error-alt,el el-error,el el-eur,el el-exclamation-sign,el el-eye-close,el el-eye-open,el el-facebook,el el-facetime-video,el el-fast-backward,el el-fast-forward,el el-female,el el-file-alt,el el-file-edit-alt,el el-file-edit,el el-file-new-alt,el el-file-new,el el-file,el el-film,el el-filter,el el-fire,el el-flag-alt,el el-flag,el el-flickr,el el-folder-close,el el-folder-open,el el-folder-sign,el el-folder,el el-font,el el-fontsize,el el-fork,el el-forward-alt,el el-forward,el el-foursquare,el el-friendfeed-rect,el el-friendfeed,el el-fullscreen,el el-gbp,el el-gift,el el-github-text,el el-github,el el-glass,el el-glasses,el el-globe-alt,el el-globe,el el-googleplus,el el-graph-alt,el el-graph,el el-group-alt,el el-group,el el-guidedog,el el-hand-down,el el-hand-left,el el-hand-right,el el-hand-up,el el-hdd,el el-headphones,el el-hearing-impaired,el el-heart-alt,el el-heart-empty,el el-heart,el el-home-alt,el el-home,el el-hourglass,el el-idea-alt,el el-idea,el el-inbox-alt,el el-inbox-box,el el-inbox,el el-indent-left,el el-indent-right,el el-info-circle,el el-instagram,el el-iphone-home,el el-italic,el el-key,el el-laptop-alt,el el-laptop,el el-lastfm,el el-leaf,el el-lines,el el-link,el el-linkedin,el el-list-alt,el el-list,el el-livejournal,el el-lock-alt,el el-lock,el el-magic,el el-magnet,el el-male,el el-map-marker-alt,el el-map-marker,el el-mic-alt,el el-mic,el el-minus-sign,el el-minus,el el-move,el el-music,el el-myspace,el el-network,el el-off,el el-ok-circle,el el-ok-sign,el el-ok,el el-opensource,el el-paper-clip-alt,el el-paper-clip,el el-path,el el-pause-alt,el el-pause,el el-pencil-alt,el el-pencil,el el-person,el el-phone-alt,el el-phone,el el-photo-alt,el el-photo,el el-picasa,el el-picture,el el-pinterest,el el-plane,el el-play-alt,el el-play-circle,el el-play,el el-plurk-alt,el el-plurk,el el-plus-sign,el el-plus,el el-podcast,el el-print,el el-puzzle,el el-qrcode,el el-question-sign,el el-question,el el-quote-alt,el el-quote-right-alt,el el-quote-right,el el-quotes,el el-random,el el-record,el el-reddit,el el-redux,el el-refresh,el el-remove-circle,el el-remove-sign,el el-remove,el el-repeat-alt,el el-repeat,el el-resize-full,el el-resize-horizontal,el el-resize-small,el el-resize-vertical,el el-return-key,el el-retweet,el el-reverse-alt,el el-road,el el-rss,el el-scissors,el el-screen-alt,el el-screen,el el-screenshot,el el-search-alt,el el-search,el el-share-alt,el el-share,el el-shopping-cart-sign,el el-shopping-cart,el el-signal,el el-skype,el el-slideshare,el el-smiley-alt,el el-smiley,el el-soundcloud,el el-speaker,el el-spotify,el el-stackoverflow,el el-star-alt,el el-star-empty,el el-star,el el-step-backward,el el-step-forward,el el-stop-alt,el el-stop,el el-stumbleupon,el el-tag,el el-tags,el el-tasks,el el-text-height,el el-text-width,el el-th-large,el el-th-list,el el-th,el el-thumbs-down,el el-thumbs-up,el el-time-alt,el el-time,el el-tint,el el-torso,el el-trash-alt,el el-trash,el el-tumblr,el el-twitter,el el-universal-access,el el-unlock-alt,el el-unlock,el el-upload,el el-usd,el el-user,el el-viadeo,el el-video-alt,el el-video-chat,el el-video,el el-view-mode,el el-vimeo,el el-vkontakte,el el-volume-down,el el-volume-off,el el-volume-up,el el-warning-sign,el el-website-alt,el el-website,el el-wheelchair,el el-wordpress,el el-wrench-alt,el el-wrench,el el-youtube,el el-zoom-in,el el-zoom-out'
        ),
        'fontawesome' => array(
            'name' => 'FontAwesome 4',
            'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/icons/fontawesome/css/font-awesome.min.css',
            'iconmap' => 'fa fa-glass,fa fa-music,fa fa-search,fa fa-envelope-o,fa fa-heart,fa fa-star,fa fa-star-o,fa fa-user,fa fa-film,fa fa-th-large,fa fa-th ,fa fa-th-list ,fa fa-check ,fa fa-remove,fa fa-close,fa fa-times ,fa fa-search-plus ,fa fa-search-minus,fa fa-power-off,fa fa-signal,fa fa-gear,fa fa-cog,fa fa-trash-o,fa fa-home,fa fa-file-o,fa fa-clock-o,fa fa-road,fa fa-download,fa fa-arrow-circle-o-down ,fa fa-arrow-circle-o-up ,fa fa-inbox ,fa fa-play-circle-o ,fa fa-rotate-right,fa fa-repeat ,fa fa-refresh,fa fa-list-alt,fa fa-lock,fa fa-flag,fa fa-headphones,fa fa-volume-off,fa fa-volume-down,fa fa-volume-up,fa fa-qrcode,fa fa-barcode ,fa fa-tag ,fa fa-tags ,fa fa-book ,fa fa-bookmark ,fa fa-print ,fa fa-camera,fa fa-font,fa fa-bold,fa fa-italic,fa fa-text-height,fa fa-text-width,fa fa-align-left,fa fa-align-center,fa fa-align-right,fa fa-align-justify,fa fa-list ,fa fa-dedent,fa fa-outdent ,fa fa-indent ,fa fa-video-camera ,fa fa-photo,fa fa-image,fa fa-picture-o ,fa fa-pencil,fa fa-map-marker,fa fa-adjust,fa fa-tint,fa fa-edit,fa fa-pencil-square-o,fa fa-share-square-o,fa fa-check-square-o,fa fa-arrows,fa fa-step-backward,fa fa-fast-backward,fa fa-backward ,fa fa-play ,fa fa-pause ,fa fa-stop ,fa fa-forward ,fa fa-fast-forward,fa fa-step-forward,fa fa-eject,fa fa-chevron-left,fa fa-chevron-right,fa fa-plus-circle,fa fa-minus-circle,fa fa-times-circle,fa fa-check-circle,fa fa-question-circle,fa fa-info-circle ,fa fa-crosshairs ,fa fa-times-circle-o ,fa fa-check-circle-o ,fa fa-ban ,fa fa-arrow-left,fa fa-arrow-right,fa fa-arrow-up,fa fa-arrow-down,fa fa-mail-forward,fa fa-share,fa fa-expand,fa fa-compress,fa fa-plus,fa fa-minus,fa fa-asterisk,fa fa-exclamation-circle ,fa fa-gift ,fa fa-leaf ,fa fa-fire ,fa fa-eye ,fa fa-eye-slash,fa fa-warning,fa fa-exclamation-triangle,fa fa-plane,fa fa-calendar,fa fa-random,fa fa-comment,fa fa-magnet,fa fa-chevron-up,fa fa-chevron-down,fa fa-retweet,fa fa-shopping-cart ,fa fa-folder ,fa fa-folder-open ,fa fa-arrows-v ,fa fa-arrows-h ,fa fa-bar-chart-o,fa fa-bar-chart,fa fa-twitter-square,fa fa-facebook-square,fa fa-camera-retro,fa fa-key,fa fa-gears,fa fa-cogs,fa fa-comments,fa fa-thumbs-o-up,fa fa-thumbs-o-down,fa fa-star-half,fa fa-heart-o ,fa fa-sign-out ,fa fa-linkedin-square ,fa fa-thumb-tack ,fa fa-external-link ,fa fa-sign-in,fa fa-trophy,fa fa-github-square,fa fa-upload,fa fa-lemon-o,fa fa-phone,fa fa-square-o,fa fa-bookmark-o,fa fa-phone-square,fa fa-twitter,fa fa-facebook-f,fa fa-facebook ,fa fa-github ,fa fa-unlock ,fa fa-credit-card ,fa fa-rss ,fa fa-hdd-o ,fa fa-bullhorn ,fa fa-bell ,fa fa-certificate ,fa fa-hand-o-right ,fa fa-hand-o-left ,fa fa-hand-o-up ,fa fa-hand-o-down ,fa fa-arrow-circle-left ,fa fa-arrow-circle-right ,fa fa-arrow-circle-up,fa fa-arrow-circle-down,fa fa-globe,fa fa-wrench,fa fa-tasks,fa fa-filter ,fa fa-briefcase ,fa fa-arrows-alt ,fa fa-group,fa fa-users ,fa fa-chain,fa fa-link ,fa fa-cloud ,fa fa-flask ,fa fa-cut,fa fa-scissors ,fa fa-copy,fa fa-files-o ,fa fa-paperclip ,fa fa-save,fa fa-floppy-o ,fa fa-square ,fa fa-navicon,fa fa-reorder,fa fa-bars ,fa fa-list-ul,fa fa-list-ol,fa fa-strikethrough,fa fa-underline,fa fa-table,fa fa-magic ,fa fa-truck ,fa fa-pinterest ,fa fa-pinterest-square ,fa fa-google-plus-square ,fa fa-google-plus ,fa fa-money ,fa fa-caret-down ,fa fa-caret-up ,fa fa-caret-left ,fa fa-caret-right,fa fa-columns,fa fa-unsorted,fa fa-sort,fa fa-sort-down,fa fa-sort-desc,fa fa-sort-up,fa fa-sort-asc,fa fa-envelope ,fa fa-linkedin ,fa fa-rotate-left,fa fa-undo ,fa fa-legal,fa fa-gavel ,fa fa-dashboard,fa fa-tachometer ,fa fa-comment-o ,fa fa-comments-o ,fa fa-flash,fa fa-bolt ,fa fa-sitemap ,fa fa-umbrella ,fa fa-paste,fa fa-clipboard,fa fa-lightbulb-o,fa fa-exchange,fa fa-cloud-download,fa fa-cloud-upload,fa fa-user-md ,fa fa-stethoscope ,fa fa-suitcase ,fa fa-bell-o ,fa fa-coffee ,fa fa-cutlery ,fa fa-file-text-o ,fa fa-building-o ,fa fa-hospital-o ,fa fa-ambulance ,fa fa-medkit,fa fa-fighter-jet ,fa fa-beer,fa fa-h-square fa fa-plus-square,fa fa-angle-double-left,fa fa-angle-double-right,fa fa-angle-double-up,fa fa-angle-double-down,fa fa-angle-left,fa fa-angle-right,fa fa-angle-up,fa fa-angle-down,fa fa-desktop,fa fa-laptop,fa fa-tablet ,fa fa-mobile-phone,fa fa-mobile ,fa fa-circle-o ,fa fa-quote-left ,fa fa-quote-right ,fa fa-spinner,fa fa-circle,fa fa-mail-reply,fa fa-reply,fa fa-github-alt,fa fa-folder-o,fa fa-folder-open-o,fa fa-smile-o,fa fa-frown-o,fa fa-meh-o ,fa fa-gamepad ,fa fa-keyboard-o ,fa fa-flag-o ,fa fa-flag-checkered ,fa fa-terminal,fa fa-code,fa fa-mail-reply-all,fa fa-reply-all,fa fa-star-half-empty,fa fa-star-half-full,fa fa-star-half-o,fa fa-location-arrow,fa fa-crop,fa fa-code-fork,fa fa-unlink,fa fa-chain-broken,fa fa-question,fa fa-info,fa fa-exclamation ,fa fa-superscript ,fa fa-subscript ,fa fa-eraser ,fa fa-puzzle-piece ,fa fa-microphone,fa fa-microphone-slash,fa fa-shield,fa fa-calendar-o,fa fa-fire-extinguisher,fa fa-rocket,fa fa-maxcdn,fa fa-chevron-circle-left,fa fa-chevron-circle-right,fa fa-chevron-circle-up,fa fa-chevron-circle-down ,fa fa-html ,fa fa-css ,fa fa-anchor ,fa fa-unlock-alt ,fa fa-bullseye,fa fa-ellipsis-h,fa fa-ellipsis-v,fa fa-rss-square,fa fa-play-circle,fa fa-ticket,fa fa-minus-square,fa fa-minus-square-o,fa fa-level-up,fa fa-level-down,fa fa-check-square ,fa fa-pencil-square ,fa fa-external-link-square ,fa fa-share-square ,fa fa-compass ,fa fa-toggle-down,fa fa-caret-square-o-down,fa fa-toggle-up,fa fa-caret-square-o-up,fa fa-toggle-right,fa fa-caret-square-o-right,fa fa-euro,fa fa-eur,fa fa-gbp,fa fa-dollar,fa fa-usd,fa fa-rupee,fa fa-inr,fa fa-cny,fa fa-rmb,fa fa-yen,fa fa-jpy,fa fa-ruble,fa fa-rouble,fa fa-rub,fa fa-won,fa fa-krw,fa fa-bitcoin,fa fa-btc ,fa fa-file ,fa fa-file-text ,fa fa-sort-alpha-asc ,fa fa-sort-alpha-desc ,fa fa-sort-amount-asc,fa fa-sort-amount-desc,fa fa-sort-numeric-asc,fa fa-sort-numeric-desc,fa fa-thumbs-up,fa fa-thumbs-down,fa fa-youtube-square,fa fa-youtube,fa fa-xing,fa fa-xing-square,fa fa-youtube-play ,fa fa-dropbox ,fa fa-stack-overflow ,fa fa-instagram ,fa fa-flickr ,fa fa-adn,fa fa-bitbucket,fa fa-bitbucket-square,fa fa-tumblr,fa fa-tumblr-square,fa fa-long-arrow-down,fa fa-long-arrow-up,fa fa-long-arrow-left,fa fa-long-arrow-right,fa fa-apple,fa fa-windows ,fa fa-android ,fa fa-linux ,fa fa-dribbble ,fa fa-skype ,fa fa-foursquare,fa fa-trello,fa fa-female,fa fa-male,fa fa-gittip,fa fa-gratipay,fa fa-sun-o,fa fa-moon-o,fa fa-archive,fa fa-bug,fa fa-vk,fa fa-weibo ,fa fa-renren ,fa fa-pagelines ,fa fa-stack-exchange ,fa fa-arrow-circle-o-right ,fa fa-arrow-circle-o-left,fa fa-toggle-left,fa fa-caret-square-o-left,fa fa-dot-circle-o,fa fa-wheelchair,fa fa-vimeo-square,fa fa-turkish-lira,fa fa-try,fa fa-plus-square-o,fa fa-space-shuttle,fa fa-slack,fa fa-envelope-square,fa fa-wordpress ,fa fa-openid ,fa fa-institution,fa fa-bank,fa fa-university ,fa fa-mortar-board,fa fa-graduation-cap ,fa fa-yahoo ,fa fa-google ,fa fa-reddit ,fa fa-reddit-square ,fa fa-stumbleupon-circle ,fa fa-stumbleupon ,fa fa-delicious ,fa fa-digg ,fa fa-pied-piper ,fa fa-pied-piper-alt ,fa fa-drupal ,fa fa-joomla,fa fa-language,fa fa-fax,fa fa-building,fa fa-child,fa fa-paw ,fa fa-spoon ,fa fa-cube ,fa fa-cubes ,fa fa-behance ,fa fa-behance-square ,fa fa-steam ,fa fa-steam-square ,fa fa-recycle ,fa fa-automobile,fa fa-car ,fa fa-cab,fa fa-taxi,fa fa-tree,fa fa-spotify,fa fa-deviantart,fa fa-soundcloud,fa fa-database ,fa fa-file-pdf-o ,fa fa-file-word-o ,fa fa-file-excel-o ,fa fa-file-powerpoint-o ,fa fa-file-photo-o,fa fa-file-picture-o,fa fa-file-image-o ,fa fa-file-zip-o,fa fa-file-archive-o ,fa fa-file-sound-o,fa fa-file-audio-o ,fa fa-file-movie-o,fa fa-file-video-o ,fa fa-file-code-o ,fa fa-vine,fa fa-codepen,fa fa-jsfiddle,fa fa-life-bouy,fa fa-life-buoy,fa fa-life-saver,fa fa-support,fa fa-life-ring,fa fa-circle-o-notch,fa fa-ra,fa fa-rebel ,fa fa-ge,fa fa-empire ,fa fa-git-square ,fa fa-git ,fa fa-hacker-news ,fa fa-tencent-weibo ,fa fa-qq ,fa fa-wechat,fa fa-weixin ,fa fa-send,fa fa-paper-plane ,fa fa-send-o,fa fa-paper-plane-o ,fa fa-history,fa fa-genderless,fa fa-circle-thin,fa fa-header,fa fa-paragraph,fa fa-sliders,fa fa-share-alt ,fa fa-share-alt-square ,fa fa-bomb ,fa fa-soccer-ball-o,fa fa-futbol-o ,fa fa-tty ,fa fa-binoculars ,fa fa-plug ,fa fa-slideshare ,fa fa-twitch ,fa fa-yelp ,fa fa-newspaper-o,fa fa-wifi,fa fa-calculator,fa fa-paypal,fa fa-google-wallet,fa fa-cc-visa ,fa fa-cc-mastercard ,fa fa-cc-discover ,fa fa-cc-amex ,fa fa-cc-paypal ,fa fa-cc-stripe ,fa fa-bell-slash ,fa fa-bell-slash-o ,fa fa-trash ,fa fa-copyright ,fa fa-at,fa fa-eyedropper,fa fa-paint-brush,fa fa-birthday-cake,fa fa-area-chart,fa fa-pie-chart,fa fa-line-chart,fa fa-lastfm,fa fa-lastfm-square,fa fa-toggle-off,fa fa-toggle-on,fa fa-bicycle,fa fa-bus,fa fa-ioxhost,fa fa-angellist,fa fa-cc ,fa fa-shekel,fa fa-sheqel,fa fa-ils ,fa fa-meanpath ,fa fa-buysellads ,fa fa-connectdevelop ,fa fa-dashcube,fa fa-forumbee,fa fa-leanpub,fa fa-sellsy,fa fa-shirtsinbulk,fa fa-simplybuilt,fa fa-skyatlas,fa fa-cart-plus,fa fa-cart-arrow-down,fa fa-diamond,fa fa-ship ,fa fa-user-secret ,fa fa-motorcycle ,fa fa-street-view ,fa fa-heartbeat ,fa fa-venus,fa fa-mars,fa fa-mercury,fa fa-transgender,fa fa-transgender-alt,fa fa-venus-double,fa fa-mars-double,fa fa-venus-mars,fa fa-mars-stroke,fa fa-mars-stroke-v ,fa fa-mars-stroke-h ,fa fa-neuter ,fa fa-facebook-official,fa fa-pinterest-p,fa fa-whatsapp,fa fa-server,fa fa-user-plus,fa fa-user-times,fa fa-hotel,fa fa-bed,fa fa-viacoin,fa fa-train,fa fa-subway,fa fa-medium'
        ),
        'fontawesome5' => array(
            'name' => 'FontAwesome 5',
            'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/icons/fontawesome5/css/all.min.css',
            'iconmap' => 'fab fa-500px,fab fa-accessible-icon,fab fa-accusoft,fab fa-adn,fab fa-adversal,fab fa-algolia,fab fa-alipay,fab fa-amazon,fab fa-amazon-pay,fab fa-app-store,fab fa-app-store-ios,fab fa-apper,fab fa-apple,fas fa-apple-alt,fab fa-apple-pay,fab fa-affiliatetheme,fas fa-ad,fas fa-address-book,fas fa-address-card,fas fa-adjust,fas fa-air-freshener,fas fa-align-center,fas fa-align-justify,fas fa-align-left,fas fa-align-right,fas fa-allergies,fas fa-ambulance,fas fa-american-sign-language-interpreting,fab fa-amilia,fas fa-anchor,fab fa-android,fab fa-angellist,fas fa-angle-double-down,fas fa-angle-double-left,fas fa-angle-double-right,fas fa-angle-double-up,fas fa-angle-down,fas fa-angle-left,fas fa-angle-right,fas fa-angle-up,fas fa-angry,fab fa-angrycreative,fab fa-angular,fas fa-ankh,fas fa-archive,fas fa-archway,fas fa-arrow-alt-circle-down,fas fa-arrow-alt-circle-left,fas fa-arrow-alt-circle-right,fas fa-arrow-alt-circle-up,fas fa-arrow-circle-down,fas fa-arrow-circle-left,fas fa-arrow-circle-right,fas fa-arrow-circle-up,fas fa-arrow-down,fas fa-arrow-left,fas fa-arrow-right,fas fa-arrow-up,fas fa-arrows-alt,fas fa-arrows-alt-h,fas fa-arrows-alt-v,fas fa-assistive-listening-systems,fas fa-asterisk,fab fa-asymmetrik,fas fa-at,fas fa-atlas,fas fa-atom,fab fa-audible,fas fa-audio-description,fab fa-autoprefixer,fab fa-avianex,fab fa-aviato,fas fa-award,fab fa-aws,fas fa-backspace,fas fa-backward,fas fa-balance-scale,fas fa-ban,fas fa-band-aid,fab fa-bandcamp,fas fa-barcode,fas fa-bars,fas fa-baseball-ball,fas fa-basketball-ball,fas fa-bath,fas fa-battery-empty,fas fa-battery-full,fas fa-battery-half,fas fa-battery-quarter,fas fa-battery-three-quarters,fas fa-bed,fas fa-beer,fab fa-behance,fab fa-behance-square,fas fa-bell,fas fa-bell-slash,fas fa-bezier-curve,fas fa-bible,fas fa-bicycle,fab fa-bimobject,fas fa-binoculars,fas fa-birthday-cake,fab fa-bitbucket,fab fa-bitcoin,fab fa-bity,fab fa-black-tie,fab fa-blackberry,fas fa-blender,fas fa-blind,fab fa-blogger,fab fa-blogger-b,fab fa-bluetooth,fab fa-bluetooth-b,fas fa-bold,fas fa-bolt,fas fa-bomb,fas fa-bone,fas fa-bong,fas fa-book,fas fa-book-open,fas fa-book-reader,fas fa-bookmark,fas fa-bowling-ball,fas fa-box,fas fa-box-open,fas fa-boxes,fas fa-braille,fas fa-brain,fas fa-briefcase,fas fa-briefcase-medical,fas fa-broadcast-tower,fas fa-broom,fas fa-brush,fab fa-btc,fas fa-bug,fas fa-building,fas fa-bullhorn,fas fa-bullseye,fas fa-burn,fab fa-buromobelexperte,fas fa-bus,fas fa-bus-alt,fas fa-business-time,fab fa-buysellads,fas fa-calculator,fas fa-calendar,fas fa-calendar-alt,fas fa-calendar-check,fas fa-calendar-minus,fas fa-calendar-plus,fas fa-calendar-times,fas fa-camera,fas fa-camera-retro,fas fa-cannabis,fas fa-capsules,fas fa-car,fas fa-car-alt,fas fa-car-battery,fas fa-car-crash,fas fa-car-side,fas fa-caret-down,fas fa-caret-left,fas fa-caret-right,fas fa-caret-square-down,fas fa-caret-square-left,fas fa-caret-square-right,fas fa-caret-square-up,fas fa-caret-up,fas fa-cart-arrow-down,fas fa-cart-plus,fab fa-cc-amazon-pay,fab fa-cc-amex,fab fa-cc-apple-pay,fab fa-cc-diners-club,fab fa-cc-discover,fab fa-cc-jcb,fab fa-cc-mastercard,fab fa-cc-paypal,fab fa-cc-stripe,fab fa-cc-visa,fab fa-centercode,fas fa-certificate,fas fa-chalkboard,fas fa-chalkboard-teacher,fas fa-charging-station,fas fa-chart-area,fas fa-chart-bar,fas fa-chart-line,fas fa-chart-pie,fas fa-check,fas fa-check-circle,fas fa-check-double,fas fa-check-square,fas fa-chess,fas fa-chess-bishop,fas fa-chess-board,fas fa-chess-king,fas fa-chess-knight,fas fa-chess-pawn,fas fa-chess-queen,fas fa-chess-rook,fas fa-chevron-circle-down,fas fa-chevron-circle-left,fas fa-chevron-circle-right,fas fa-chevron-circle-up,fas fa-chevron-down,fas fa-chevron-left,fas fa-chevron-right,fas fa-chevron-up,fas fa-child,fab fa-chrome,fas fa-church,fas fa-circle,fas fa-circle-notch,fas fa-city,fas fa-clipboard,fas fa-clipboard-check,fas fa-clipboard-list,fas fa-clock,fas fa-clone,fas fa-closed-captioning,fas fa-cloud,fas fa-cloud-download-alt,fas fa-cloud-upload-alt,fab fa-cloudscale,fab fa-cloudsmith,fab fa-cloudversify,fas fa-cocktail,fas fa-code,fas fa-code-branch,fab fa-codepen,fab fa-codiepie,fas fa-coffee,fas fa-cog,fas fa-cogs,fas fa-coins,fas fa-columns,fas fa-comment,fas fa-comment-alt,fas fa-comment-dollar,fas fa-comment-dots,fas fa-comment-slash,fas fa-comments,fas fa-comments-dollar,fas fa-compact-disc,fas fa-compass,fas fa-compress,fas fa-concierge-bell,fab fa-connectdevelop,fab fa-contao,fas fa-cookie,fas fa-cookie-bite,fas fa-copy,fas fa-copyright,fas fa-couch,fab fa-cpanel,fab fa-creative-commons,fab fa-creative-commons-by,fab fa-creative-commons-nc,fab fa-creative-commons-nc-eu,fab fa-creative-commons-nc-jp,fab fa-creative-commons-nd,fab fa-creative-commons-pd,fab fa-creative-commons-pd-alt,fab fa-creative-commons-remix,fab fa-creative-commons-sa,fab fa-creative-commons-sampling,fab fa-creative-commons-sampling-plus,fab fa-creative-commons-share,fas fa-credit-card,fas fa-crop,fas fa-crop-alt,fas fa-cross,fas fa-crosshairs,fas fa-crow,fas fa-crown,fab fa-css3,fab fa-css3-alt,fas fa-cube,fas fa-cubes,fas fa-cut,fab fa-cuttlefish,fab fa-d-and-d,fab fa-dashcube,fas fa-database,fas fa-deaf,fab fa-delicious,fab fa-deploydog,fab fa-deskpro,fas fa-desktop,fab fa-deviantart,fas fa-dharmachakra,fas fa-diagnoses,fas fa-dice,fas fa-dice-five,fas fa-dice-four,fas fa-dice-one,fas fa-dice-six,fas fa-dice-three,fas fa-dice-two,fab fa-digg,fab fa-digital-ocean,fas fa-digital-tachograph,fas fa-directions,fab fa-discord,fab fa-discourse,fas fa-divide,fas fa-dizzy,fas fa-dna,fab fa-dochub,fab fa-docker,fas fa-dollar-sign,fas fa-dolly,fas fa-dolly-flatbed,fas fa-donate,fas fa-door-closed,fas fa-door-open,fas fa-dot-circle,fas fa-dove,fas fa-download,fab fa-draft2digital,fas fa-drafting-compass,fas fa-draw-polygon,fab fa-dribbble,fab fa-dribbble-square,fab fa-dropbox,fas fa-drum,fas fa-drum-steelpan,fab fa-drupal,fas fa-dumbbell,fab fa-dyalog,fab fa-earlybirds,fab fa-ebay,fab fa-edge,fas fa-edit,fas fa-eject,fab fa-elementor,fas fa-ellipsis-h,fas fa-ellipsis-v,fab fa-ello,fab fa-ember,fab fa-empire,fas fa-envelope,fas fa-envelope-open,fas fa-envelope-open-text,fas fa-envelope-square,fab fa-envira,fas fa-equals,fas fa-eraser,fab fa-erlang,fab fa-ethereum,fab fa-etsy,fas fa-euro-sign,fas fa-exchange-alt,fas fa-exclamation,fas fa-exclamation-circle,fas fa-exclamation-triangle,fas fa-expand,fas fa-expand-arrows-alt,fab fa-expeditedssl,fas fa-external-link-alt,fas fa-external-link-square-alt,fas fa-eye,fas fa-eye-dropper,fas fa-eye-slash,fab fa-facebook,fab fa-facebook-f,fab fa-facebook-messenger,fab fa-facebook-square,fas fa-fast-backward,fas fa-fast-forward,fas fa-fax,fas fa-feather,fas fa-feather-alt,fas fa-female,fas fa-fighter-jet,fas fa-file,fas fa-file-alt,fas fa-file-archive,fas fa-file-audio,fas fa-file-code,fas fa-file-contract,fas fa-file-download,fas fa-file-excel,fas fa-file-export,fas fa-file-image,fas fa-file-import,fas fa-file-invoice,fas fa-file-invoice-dollar,fas fa-file-medical,fas fa-file-medical-alt,fas fa-file-pdf,fas fa-file-powerpoint,fas fa-file-prescription,fas fa-file-signature,fas fa-file-upload,fas fa-file-video,fas fa-file-word,fas fa-fill,fas fa-fill-drip,fas fa-film,fas fa-filter,fas fa-fingerprint,fas fa-fire,fas fa-fire-extinguisher,fab fa-firefox,fas fa-first-aid,fab fa-first-order,fab fa-first-order-alt,fab fa-firstdraft,fas fa-fish,fas fa-flag,fas fa-flag-checkered,fas fa-flask,fab fa-flickr,fab fa-flipboard,fas fa-flushed,fab fa-fly,fas fa-folder,fas fa-folder-minus,fas fa-folder-open,fas fa-folder-plus,fas fa-font,fab fa-font-awesome,fab fa-font-awesome-alt,fab fa-font-awesome-flag,fab fa-fonticons,fab fa-fonticons-fi,fas fa-football-ball,fab fa-fort-awesome,fab fa-fort-awesome-alt,fab fa-forumbee,fas fa-forward,fab fa-foursquare,fab fa-free-code-camp,fab fa-freebsd,fas fa-frog,fas fa-frown,fas fa-frown-open,fab fa-fulcrum,fas fa-funnel-dollar,fas fa-futbol,fab fa-galactic-republic,fab fa-galactic-senate,fas fa-gamepad,fas fa-gas-pump,fas fa-gavel,fas fa-gem,fas fa-genderless,fab fa-get-pocket,fab fa-gg,fab fa-gg-circle,fas fa-gift,fab fa-git,fab fa-git-square,fab fa-github,fab fa-github-alt,fab fa-github-square,fab fa-gitkraken,fab fa-gitlab,fab fa-gitter,fas fa-glass-martini,fas fa-glass-martini-alt,fas fa-glasses,fab fa-glide,fab fa-glide-g,fas fa-globe,fas fa-globe-africa,fas fa-globe-americas,fas fa-globe-asia,fab fa-gofore,fas fa-golf-ball,fab fa-goodreads,fab fa-goodreads-g,fab fa-google,fab fa-google-drive,fab fa-google-play,fab fa-google-plus,fab fa-google-plus-g,fab fa-google-plus-square,fab fa-google-wallet,fas fa-gopuram,fas fa-graduation-cap,fab fa-gratipay,fab fa-grav,fas fa-greater-than,fas fa-greater-than-equal,fas fa-grimace,fas fa-grin,fas fa-grin-alt,fas fa-grin-beam,fas fa-grin-beam-sweat,fas fa-grin-hearts,fas fa-grin-squint,fas fa-grin-squint-tears,fas fa-grin-stars,fas fa-grin-tears,fas fa-grin-tongue,fas fa-grin-tongue-squint,fas fa-grin-tongue-wink,fas fa-grin-wink,fas fa-grip-horizontal,fas fa-grip-vertical,fab fa-gripfire,fab fa-grunt,fab fa-gulp,fas fa-h-square,fab fa-hacker-news,fab fa-hacker-news-square,fab fa-hackerrank,fas fa-hamsa,fas fa-hand-holding,fas fa-hand-holding-heart,fas fa-hand-holding-usd,fas fa-hand-lizard,fas fa-hand-paper,fas fa-hand-peace,fas fa-hand-point-down,fas fa-hand-point-left,fas fa-hand-point-right,fas fa-hand-point-up,fas fa-hand-pointer,fas fa-hand-rock,fas fa-hand-scissors,fas fa-hand-spock,fas fa-hands,fas fa-hands-helping,fas fa-handshake,fas fa-hashtag,fas fa-haykal,fas fa-hdd,fas fa-heading,fas fa-headphones,fas fa-headphones-alt,fas fa-headset,fas fa-heart,fas fa-heartbeat,fas fa-helicopter,fas fa-highlighter,fab fa-hips,fab fa-hire-a-helper,fas fa-history,fas fa-hockey-puck,fas fa-home,fab fa-hooli,fab fa-hornbill,far fa-hospital,fas fa-hospital-alt,fas fa-hospital-symbol,fas fa-hot-tub,fas fa-hotel,fab fa-hotjar,fas fa-hourglass,fas fa-hourglass-end,fas fa-hourglass-half,fas fa-hourglass-start,fab fa-houzz,fab fa-html5,fab fa-hubspot,fas fa-i-cursor,fas fa-id-badge,fas fa-id-card,fas fa-id-card-alt,fas fa-image,fas fa-images,fab fa-imdb,fas fa-inbox,fas fa-indent,fas fa-industry,fas fa-infinity,fas fa-info,fas fa-info-circle,fab fa-instagram,fab fa-internet-explorer,fab fa-ioxhost,fas fa-italic,fab fa-itunes,fab fa-itunes-note,fab fa-java,fas fa-jedi,fab fa-jedi-order,fab fa-jenkins,fab fa-joget,fas fa-joint,fab fa-joomla,fas fa-journal-whills,fab fa-js,fab fa-js-square,fab fa-jsfiddle,fas fa-kaaba,fab fa-kaggle,fas fa-key,fab fa-keybase,fas fa-keyboard,fab fa-keycdn,fas fa-khanda,fab fa-kickstarter,fab fa-kickstarter-k,fas fa-kiss,fas fa-kiss-beam,fas fa-kiss-wink-heart,fas fa-kiwi-bird,fab fa-korvue,fas fa-landmark,fas fa-language,fas fa-laptop,fas fa-laptop-code,fab fa-laravel,fab fa-lastfm,fab fa-lastfm-square,fas fa-laugh,fas fa-laugh-beam,fas fa-laugh-squint,fas fa-laugh-wink,fas fa-layer-group,fas fa-leaf,fab fa-leanpub,fas fa-lemon,fab fa-less,fas fa-less-than,fas fa-less-than-equal,fas fa-level-down-alt,fas fa-level-up-alt,fas fa-life-ring,fas fa-lightbulb,fab fa-line,fas fa-link,fab fa-linkedin,fab fa-linkedin-in,fab fa-linode,fab fa-linux,fas fa-lira-sign,fas fa-list,fas fa-list-alt,fas fa-list-ol,fas fa-list-ul,fas fa-location-arrow,fas fa-lock,fas fa-lock-open,fas fa-long-arrow-alt-down,fas fa-long-arrow-alt-left,fas fa-long-arrow-alt-right,fas fa-long-arrow-alt-up,fas fa-low-vision,fas fa-luggage-cart,fab fa-lyft,fab fa-magento,fas fa-magic,fas fa-magnet,fas fa-mail-bulk,fab fa-mailchimp,fas fa-male,fab fa-mandalorian,fas fa-map,fas fa-map-marked,fas fa-map-marked-alt,fas fa-map-marker,fas fa-map-marker-alt,fas fa-map-pin,fas fa-map-signs,fab fa-markdown,fas fa-marker,fas fa-mars,fas fa-mars-double,fas fa-mars-stroke,fas fa-mars-stroke-h,fas fa-mars-stroke-v,fab fa-mastodon,fab fa-maxcdn,fas fa-medal,fab fa-medapps,fab fa-medium,fab fa-medium-m,fas fa-medkit,fab fa-medrt,fab fa-meetup,fab fa-megaport,fas fa-meh,fas fa-meh-blank,fas fa-meh-rolling-eyes,fas fa-memory,fas fa-menorah,fas fa-mercury,fas fa-microchip,fas fa-microphone,fas fa-microphone-alt,fas fa-microphone-alt-slash,fas fa-microphone-slash,fas fa-microscope,fab fa-microsoft,fas fa-minus,fas fa-minus-circle,fas fa-minus-square,fab fa-mix,fab fa-mixcloud,fab fa-mizuni,fas fa-mobile,fas fa-mobile-alt,fab fa-modx,fab fa-monero,fas fa-money-bill,fas fa-money-bill-alt,fas fa-money-bill-wave,fas fa-money-bill-wave-alt,fas fa-money-check,fas fa-money-check-alt,fas fa-monument,fas fa-moon,fas fa-mortar-pestle,fas fa-mosque,fas fa-motorcycle,fas fa-mouse-pointer,fas fa-music,fab fa-napster,fab fa-neos,fas fa-neuter,fas fa-newspaper,fab fa-nimblr,fab fa-nintendo-switch,fab fa-node,fab fa-node-js,fas fa-not-equal,fas fa-notes-medical,fab fa-npm,fab fa-ns8,fab fa-nutritionix,fas fa-object-group,fas fa-object-ungroup,fab fa-odnoklassniki,fab fa-odnoklassniki-square,fas fa-oil-can,fab fa-old-republic,fas fa-om,fab fa-opencart,fab fa-openid,fab fa-opera,fab fa-optin-monster,fab fa-osi,fas fa-outdent,fab fa-page4,fab fa-pagelines,fas fa-paint-brush,fas fa-paint-roller,fas fa-palette,fab fa-palfed,fas fa-pallet,fas fa-paper-plane,fas fa-paperclip,fas fa-parachute-box,fas fa-paragraph,fas fa-parking,fas fa-passport,fas fa-pastafarianism,fas fa-paste,fab fa-patreon,fas fa-pause,fas fa-pause-circle,fas fa-paw,fab fa-paypal,fas fa-peace,fas fa-pen,fas fa-pen-alt,fas fa-pen-fancy,fas fa-pen-nib,fas fa-pen-square,fas fa-pencil-alt,fas fa-pencil-ruler,fas fa-people-carry,fas fa-percent,fas fa-percentage,fab fa-periscope,fab fa-phabricator,fab fa-phoenix-framework,fab fa-phoenix-squadron,fas fa-phone,fas fa-phone-slash,fas fa-phone-square,fas fa-phone-volume,fab fa-php,fab fa-pied-piper,fab fa-pied-piper-alt,fab fa-pied-piper-hat,fab fa-pied-piper-pp,fas fa-piggy-bank,fas fa-pills,fab fa-pinterest,fab fa-pinterest-p,fab fa-pinterest-square,fas fa-place-of-worship,fas fa-plane,fas fa-plane-arrival,fas fa-plane-departure,fas fa-play,fas fa-play-circle,fab fa-playstation,fas fa-plug,fas fa-plus,fas fa-plus-circle,fas fa-plus-square,fas fa-podcast,fas fa-poll,fas fa-poll-h,fas fa-poo,fas fa-poop,fas fa-portrait,fas fa-pound-sign,fas fa-power-off,fas fa-pray,fas fa-praying-hands,fas fa-prescription,fas fa-prescription-bottle,fas fa-prescription-bottle-alt,fas fa-print,fas fa-procedures,fab fa-product-hunt,fas fa-project-diagram,fab fa-pushed,fas fa-puzzle-piece,fab fa-python,fab fa-qq,fas fa-qrcode,fas fa-question,fas fa-question-circle,fas fa-quidditch,fab fa-quinscape,fab fa-quora,fas fa-quote-left,fas fa-quote-right,fas fa-quran,fab fa-r-project,fas fa-random,fab fa-ravelry,fab fa-react,fab fa-readme,fab fa-rebel,fas fa-receipt,fas fa-recycle,fab fa-red-river,fab fa-reddit,fab fa-reddit-alien,fab fa-reddit-square,fas fa-redo,fas fa-redo-alt,fas fa-registered,fab fa-rendact,fab fa-renren,fas fa-reply,fas fa-reply-all,fab fa-replyd,fab fa-researchgate,fab fa-resolving,fas fa-retweet,fab fa-rev,fas fa-ribbon,fas fa-road,fas fa-robot,fas fa-rocket,fab fa-rocketchat,fab fa-rockrms,fas fa-route,fas fa-rss,fas fa-rss-square,fas fa-ruble-sign,fas fa-ruler,fas fa-ruler-combined,fas fa-ruler-horizontal,fas fa-ruler-vertical,fas fa-rupee-sign,fas fa-sad-cry,fas fa-sad-tear,fab fa-safari,fab fa-sass,fas fa-save,fab fa-schlix,fas fa-school,fas fa-screwdriver,fab fa-scribd,fas fa-search,fas fa-search-dollar,fas fa-search-location,fas fa-search-minus,fas fa-search-plus,fab fa-searchengin,fas fa-seedling,fab fa-sellcast,fab fa-sellsy,fas fa-server,fab fa-servicestack,fas fa-shapes,fas fa-share,fas fa-share-alt,fas fa-share-alt-square,fas fa-share-square,fas fa-shekel-sign,fas fa-shield-alt,fas fa-ship,fas fa-shipping-fast,fab fa-shirtsinbulk,fas fa-shoe-prints,fas fa-shopping-bag,fas fa-shopping-basket,fas fa-shopping-cart,fab fa-shopware,fas fa-shower,fas fa-shuttle-van,fas fa-sign,fas fa-sign-in-alt,fas fa-sign-language,fas fa-sign-out-alt,fas fa-signal,fas fa-signature,fab fa-simplybuilt,fab fa-sistrix,fas fa-sitemap,fab fa-sith,fas fa-skull,fab fa-skyatlas,fab fa-skype,fab fa-slack,fab fa-slack-hash,fas fa-sliders-h,fab fa-slideshare,fas fa-smile,fas fa-smile-beam,fas fa-smile-wink,fas fa-smoking,fas fa-smoking-ban,fab fa-snapchat,fab fa-snapchat-ghost,fab fa-snapchat-square,fas fa-snowflake,fas fa-socks,fas fa-solar-panel,fas fa-sort,fas fa-sort-alpha-down,fas fa-sort-alpha-up,fas fa-sort-amount-down,fas fa-sort-amount-up,fas fa-sort-down,fas fa-sort-numeric-down,fas fa-sort-numeric-up,fas fa-sort-up,fab fa-soundcloud,fas fa-spa,fas fa-space-shuttle,fab fa-speakap,fas fa-spinner,fas fa-splotch,fab fa-spotify,fas fa-spray-can,fas fa-square,fas fa-square-full,fas fa-square-root-alt,fab fa-squarespace,fab fa-stack-exchange,fab fa-stack-overflow,fas fa-stamp,fas fa-star,fas fa-star-and-crescent,fas fa-star-half,fas fa-star-half-alt,fas fa-star-of-david,fas fa-star-of-life,fab fa-staylinked,fab fa-steam,fab fa-steam-square,fab fa-steam-symbol,fas fa-step-backward,fas fa-step-forward,fas fa-stethoscope,fab fa-sticker-mule,fas fa-sticky-note,fas fa-stop,fas fa-stop-circle,fas fa-stopwatch,fas fa-store,fas fa-store-alt,fab fa-strava,fas fa-stream,fas fa-street-view,fas fa-strikethrough,fab fa-stripe,fab fa-stripe-s,fas fa-stroopwafel,fab fa-studiovinari,fab fa-stumbleupon,fab fa-stumbleupon-circle,fas fa-subscript,fas fa-subway,fas fa-suitcase,fas fa-suitcase-rolling,fas fa-sun,fab fa-superpowers,fas fa-superscript,fab fa-supple,fas fa-surprise,fas fa-swatchbook,fas fa-swimmer,fas fa-swimming-pool,fas fa-synagogue,fas fa-sync,fas fa-sync-alt,fas fa-syringe,fas fa-table,fas fa-table-tennis,fas fa-tablet,fas fa-tablet-alt,fas fa-tablets,fas fa-tachometer-alt,fas fa-tag,fas fa-tags,fas fa-tape,fas fa-tasks,fas fa-taxi,fab fa-teamspeak,fas fa-teeth,fas fa-teeth-open,fab fa-telegram,fab fa-telegram-plane,fab fa-tencent-weibo,fas fa-terminal,fas fa-text-height,fas fa-text-width,fas fa-th,fas fa-th-large,fas fa-th-list,fab fa-the-red-yeti,fas fa-theater-masks,fab fa-themeco,fab fa-themeisle,fas fa-thermometer,fas fa-thermometer-empty,fas fa-thermometer-full,fas fa-thermometer-half,fas fa-thermometer-quarter,fas fa-thermometer-three-quarters,fas fa-thumbs-down,fas fa-thumbs-up,fas fa-thumbtack,fas fa-ticket-alt,fas fa-times,fas fa-times-circle,fas fa-tint,fas fa-tint-slash,fas fa-tired,fas fa-toggle-off,fas fa-toggle-on,fas fa-toolbox,fas fa-tooth,fas fa-torah,fas fa-torii-gate,fab fa-trade-federation,fas fa-trademark,fas fa-traffic-light,fas fa-train,fas fa-transgender,fas fa-transgender-alt,fas fa-trash,fas fa-trash-alt,fas fa-tree,fab fa-trello,fab fa-tripadvisor,fas fa-trophy,fas fa-truck,fas fa-truck-loading,fas fa-truck-monster,fas fa-truck-moving,fas fa-truck-pickup,fas fa-tshirt,fas fa-tty,fab fa-tumblr,fab fa-tumblr-square,fas fa-tv,fab fa-twitch,fab fa-twitter,fab fa-twitter-square,fab fa-typo3,fab fa-uber,fab fa-uikit,fas fa-umbrella,fas fa-umbrella-beach,fas fa-underline,fas fa-undo,fas fa-undo-alt,fab fa-uniregistry,fas fa-universal-access,fas fa-university,fas fa-unlink,fas fa-unlock,fas fa-unlock-alt,fab fa-untappd,fas fa-upload,fab fa-usb,fas fa-user,fas fa-user-alt,fas fa-user-alt-slash,fas fa-user-astronaut,fas fa-user-check,fas fa-user-circle,fas fa-user-clock,fas fa-user-cog,fas fa-user-edit,fas fa-user-friends,fas fa-user-graduate,fas fa-user-lock,fas fa-user-md,fas fa-user-minus,fas fa-user-ninja,fas fa-user-plus,fas fa-user-secret,fas fa-user-shield,fas fa-user-slash,fas fa-user-tag,fas fa-user-tie,fas fa-user-times,fas fa-users,fas fa-users-cog,fab fa-ussunnah,fas fa-utensil-spoon,fas fa-utensils,fab fa-vaadin,fas fa-vector-square,fas fa-venus,fas fa-venus-double,fas fa-venus-mars,fab fa-viacoin,fab fa-viadeo,fab fa-viadeo-square,fas fa-vial,fas fa-vials,fab fa-viber,fas fa-video,fas fa-video-slash,fas fa-vihara,fab fa-vimeo,fab fa-vimeo-square,fab fa-vimeo-v,fab fa-vine,fab fa-vk,fab fa-vnv,fas fa-volleyball-ball,fas fa-volume-down,fas fa-volume-off,fas fa-volume-up,fab fa-vuejs,fas fa-walking,fas fa-wallet,fas fa-warehouse,fab fa-weebly,fab fa-weibo,fas fa-weight,fas fa-weight-hanging,fab fa-weixin,fab fa-whatsapp,fab fa-whatsapp-square,fas fa-wheelchair,fab fa-whmcs,fas fa-wifi,fab fa-wikipedia-w,fas fa-window-close,fas fa-window-maximize,fas fa-window-minimize,fas fa-window-restore,fab fa-windows,fas fa-wine-glass,fas fa-wine-glass-alt,fab fa-wix,fab fa-wolf-pack-battalion,fas fa-won-sign,fab fa-wordpress,fab fa-wordpress-simple,fab fa-wpbeginner,fab fa-wpexplorer,fab fa-wpforms,fas fa-wrench,fas fa-x-ray,fab fa-xbox,fab fa-xing,fab fa-xing-square,fab fa-y-combinator,fab fa-yahoo,fab fa-yandex,fab fa-yandex-international,fab fa-yelp,fas fa-yen-sign,fas fa-yin-yang,fab fa-yoast,fab fa-youtube,fab fa-youtube-square,fab fa-zhihu'
        ),
        'foundation' => array(
            'name' => 'Foundation Icons',
            'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/icons/foundation/foundation-icons.min.css',
            'iconmap' => 'fi-address-book,fi-alert,fi-align-center,fi-align-justify,fi-align-left,fi-align-right,fi-anchor,fi-annotate,fi-archive,fi-arrow-down,fi-arrow-left,fi-arrow-right,fi-arrow-up,fi-arrows-compress,fi-arrows-expand,fi-arrows-in,fi-arrows-out,fi-asl,fi-asterisk,fi-at-sign,fi-background-color,fi-battery-empty,fi-battery-full,fi-battery-half,fi-bitcoin-circle,fi-bitcoin,fi-blind,fi-bluetooth,fi-bold,fi-book-bookmark,fi-book,fi-bookmark,fi-braille,fi-burst-new,fi-burst-sale,fi-burst,fi-calendar,fi-camera,fi-check,fi-checkbox,fi-clipboard-notes,fi-clipboard-pencil,fi-clipboard,fi-clock,fi-closed-caption,fi-cloud,fi-comment-minus,fi-comment-quotes,fi-comment-video,fi-comment,fi-comments,fi-compass,fi-contrast,fi-credit-card,fi-crop,fi-crown,fi-css3,fi-database,fi-die-five,fi-die-four,fi-die-one,fi-die-six,fi-die-three,fi-die-two,fi-dislike,fi-dollar-bill,fi-dollar,fi-download,fi-eject,fi-elevator,fi-euro,fi-eye,fi-fast-forward,fi-female-symbol,fi-female,fi-filter,fi-first-aid,fi-flag,fi-folder-add,fi-folder-lock,fi-folder,fi-foot,fi-foundation,fi-graph-bar,fi-graph-horizontal,fi-graph-pie,fi-graph-trend,fi-guide-dog,fi-hearing-aid,fi-heart,fi-home,fi-html5,fi-indent-less,fi-indent-more,fi-info,fi-italic,fi-key,fi-laptop,fi-layout,fi-lightbulb,fi-like,fi-link,fi-list-bullet,fi-list-number,fi-list-thumbnails,fi-list,fi-lock,fi-loop,fi-magnifying-glass,fi-mail,fi-male-female,fi-male-symbol,fi-male,fi-map,fi-marker,fi-megaphone,fi-microphone,fi-minus-circle,fi-minus,fi-mobile-signal,fi-mobile,fi-monitor,fi-mountains,fi-music,fi-next,fi-no-dogs,fi-no-smoking,fi-page-add,fi-page-copy,fi-page-csv,fi-page-delete,fi-page-doc,fi-page-edit,fi-page-export-csv,fi-page-export-doc,fi-page-export-pdf,fi-page-export,fi-page-filled,fi-page-multiple,fi-page-pdf,fi-page-remove,fi-page-search,fi-page,fi-paint-bucket,fi-paperclip,fi-pause,fi-paw,fi-paypal,fi-pencil,fi-photo,fi-play-circle,fi-play-video,fi-play,fi-plus,fi-pound,fi-power,fi-previous,fi-price-tag,fi-pricetag-multiple,fi-print,fi-prohibited,fi-projection-screen,fi-puzzle,fi-quote,fi-record,fi-refresh,fi-results-demographics,fi-results,fi-rewind-ten,fi-rewind,fi-rss,fi-safety-cone,fi-save,fi-share,fi-sheriff-badge,fi-shield,fi-shopping-bag,fi-shopping-cart,fi-shuffle,fi-skull,fi-social-500px,fi-social-adobe,fi-social-amazon,fi-social-android,fi-social-apple,fi-social-behance,fi-social-bing,fi-social-blogger,fi-social-delicious,fi-social-designer-news,fi-social-deviant-art,fi-social-digg,fi-social-dribbble,fi-social-drive,fi-social-dropbox,fi-social-evernote,fi-social-facebook,fi-social-flickr,fi-social-forrst,fi-social-foursquare,fi-social-game-center,fi-social-github,fi-social-google-plus,fi-social-hacker-news,fi-social-hi5,fi-social-instagram,fi-social-joomla,fi-social-lastfm,fi-social-linkedin,fi-social-medium,fi-social-myspace,fi-social-orkut,fi-social-path,fi-social-picasa,fi-social-pinterest,fi-social-rdio,fi-social-reddit,fi-social-skillshare,fi-social-skype,fi-social-smashing-mag,fi-social-snapchat,fi-social-spotify,fi-social-squidoo,fi-social-stack-overflow,fi-social-steam,fi-social-stumbleupon,fi-social-treehouse,fi-social-tumblr,fi-social-twitter,fi-social-vimeo,fi-social-windows,fi-social-xbox,fi-social-yahoo,fi-social-yelp,fi-social-youtube,fi-social-zerply,fi-social-zurb,fi-sound,fi-star,fi-stop,fi-strikethrough,fi-subscript,fi-superscript,fi-tablet-landscape,fi-tablet-portrait,fi-target-two,fi-target,fi-telephone-accessible,fi-telephone,fi-text-color,fi-thumbnails,fi-ticket,fi-torso-business,fi-torso-female,fi-torso,fi-torsos-all-female,fi-torsos-all,fi-torsos-female-male,fi-torsos-male-female,fi-torsos,fi-trash,fi-trees,fi-trophy,fi-underline,fi-universal-access,fi-unlink,fi-unlock,fi-upload-cloud,fi-upload,fi-usb,fi-video,fi-volume-none,fi-volume-strike,fi-volume,fi-web,fi-wheelchair,fi-widget,fi-wrench,fi-x-circle,fi-x,fi-yen,fi-zoom-in,fi-zoom-out'
        ),
        'themify' => array(
            'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/icons/themify/themify-icons.min.css',
            'name' => 'Themify Icons',
            'iconmap' => 'ti-wand,ti-volume,ti-user,ti-unlock,ti-unlink,ti-trash,ti-thought,ti-target,ti-tag,ti-tablet,ti-star,ti-spray,ti-signal,ti-shopping-cart,ti-shopping-cart-full,ti-settings,ti-search,ti-zoom-in,ti-zoom-out,ti-cut,ti-ruler,ti-ruler-pencil,ti-ruler-alt,ti-bookmark,ti-bookmark-alt,ti-reload,ti-plus,ti-pin,ti-pencil,ti-pencil-alt,ti-paint-roller,ti-paint-bucket,ti-na,ti-mobile,ti-minus,ti-medall,ti-medall-alt,ti-marker,ti-marker-alt,ti-arrow-up,ti-arrow-right,ti-arrow-left,ti-arrow-down,ti-lock,ti-location-arrow,ti-link,ti-layout,ti-layers,ti-layers-alt,ti-key,ti-import,ti-image,ti-heart,ti-heart-broken,ti-hand-stop,ti-hand-open,ti-hand-drag,ti-folder,ti-flag,ti-flag-alt,ti-flag-alt-2,ti-eye,ti-export,ti-exchange-vertical,ti-desktop,ti-cup,ti-crown,ti-comments,ti-comment,ti-comment-alt,ti-close,ti-clip,ti-angle-up,ti-angle-right,ti-angle-left,ti-angle-down,ti-check,ti-check-box,ti-camera,ti-announcement,ti-brush,ti-briefcase,ti-bolt,ti-bolt-alt,ti-blackboard,ti-bag,ti-move,ti-arrows-vertical,ti-arrows-horizontal,ti-fullscreen,ti-arrow-top-right,ti-arrow-top-left,ti-arrow-circle-up,ti-arrow-circle-right,ti-arrow-circle-left,ti-arrow-circle-down,ti-angle-double-up,ti-angle-double-right,ti-angle-double-left,ti-angle-double-down,ti-zip,ti-world,ti-wheelchair,ti-view-list,ti-view-list-alt,ti-view-grid,ti-uppercase,ti-upload,ti-underline,ti-truck,ti-timer,ti-ticket,ti-thumb-up,ti-thumb-down,ti-text,ti-stats-up,ti-stats-down,ti-split-v,ti-split-h,ti-smallcap,ti-shine,ti-shift-right,ti-shift-left,ti-shield,ti-notepad,ti-server,ti-quote-right,ti-quote-left,ti-pulse,ti-printer,ti-power-off,ti-plug,ti-pie-chart,ti-paragraph,ti-panel,ti-package,ti-music,ti-music-alt,ti-mouse,ti-mouse-alt,ti-money,ti-microphone,ti-menu,ti-menu-alt,ti-map,ti-map-alt,ti-loop,ti-location-pin,ti-list,ti-light-bulb,ti-Italic,ti-info,ti-infinite,ti-id-badge,ti-hummer,ti-home,ti-help,ti-headphone,ti-harddrives,ti-harddrive,ti-gift,ti-game,ti-filter,ti-files,ti-file,ti-eraser,ti-envelope,ti-download,ti-direction,ti-direction-alt,ti-dashboard,ti-control-stop,ti-control-shuffle,ti-control-play,ti-control-pause,ti-control-forward,ti-control-backward,ti-cloud,ti-cloud-up,ti-cloud-down,ti-clipboard,ti-car,ti-calendar,ti-book,ti-bell,ti-basketball,ti-bar-chart,ti-bar-chart-alt,ti-back-right,ti-back-left,ti-arrows-corner,ti-archive,ti-anchor,ti-align-right,ti-align-left,ti-align-justify,ti-align-center,ti-alert,ti-alarm-clock,ti-agenda,ti-write,ti-window,ti-widgetized,ti-widget,ti-widget-alt,ti-wallet,ti-video-clapper,ti-video-camera,ti-vector,ti-themify-logo,ti-themify-favicon,ti-themify-favicon-alt,ti-support,ti-stamp,ti-split-v-alt,ti-slice,ti-shortcode,ti-shift-right-alt,ti-shift-left-alt,ti-ruler-alt-2,ti-receipt,ti-pin,ti-pin-alt,ti-pencil-alt,ti-palette,ti-more,ti-more-alt,ti-microphone-alt,ti-magnet,ti-line-double,ti-line-dotted,ti-line-dashed,ti-layout-width-full,ti-layout-width-default,ti-layout-width-default-alt,ti-layout-tab,ti-layout-tab-window,ti-layout-tab-v,ti-layout-tab-min,ti-layout-slider,ti-layout-slider-alt,ti-layout-sidebar-right,ti-layout-sidebar-none,ti-layout-sidebar-left,ti-layout-placeholder,ti-layout-menu,ti-layout-menu-v,ti-layout-menu-separated,ti-layout-menu-full,ti-layout-media-right-alt,ti-layout-media-right,ti-layout-media-overlay,ti-layout-media-overlay-alt,ti-layout-media-overlay-alt-2,ti-layout-media-left-alt,ti-layout-media-left,ti-layout-media-center-alt,ti-layout-media-center,ti-layout-list-thumb,ti-layout-list-thumb-alt,ti-layout-list-post,ti-layout-list-large-image,ti-layout-line-solid,ti-layout-grid4,ti-layout-grid3,ti-layout-grid2,ti-layout-grid2-thumb,ti-layout-cta-right,ti-layout-cta-left,ti-layout-cta-center,ti-layout-cta-btn-right,ti-layout-cta-btn-left,ti-layout-column4,ti-layout-column3,ti-layout-column2,ti-layout-accordion-separated,ti-layout-accordion-merged,ti-layout-accordion-list,ti-ink-pen,ti-info-alt,ti-help-alt,ti-headphone-alt,ti-hand-point-up,ti-hand-point-right,ti-hand-point-left,ti-hand-point-down,ti-gallery,ti-face-smile,ti-face-sad,ti-credit-card,ti-control-skip-forward,ti-control-skip-backward,ti-control-record,ti-control-eject,ti-comments-smiley,ti-brush-alt,ti-youtube,ti-vimeo,ti-twitter,ti-time,ti-tumblr,ti-skype,ti-share,ti-share-alt,ti-rocket,ti-pinterest,ti-new-window,ti-microsoft,ti-list-ol,ti-linkedin,ti-layout-sidebar-2,ti-layout-grid4-alt,ti-layout-grid3-alt,ti-layout-grid2-alt,ti-layout-column4-alt,ti-layout-column3-alt,ti-layout-column2-alt,ti-instagram,ti-google,ti-github,ti-flickr,ti-facebook,ti-dropbox,ti-dribbble,ti-apple,ti-android,ti-save,ti-save-alt,ti-yahoo,ti-wordpress,ti-vimeo-alt,ti-twitter-alt,ti-tumblr-alt,ti-trello,ti-stack-overflow,ti-soundcloud,ti-sharethis,ti-sharethis-alt,ti-reddit,ti-pinterest-alt,ti-microsoft-alt,ti-linux,ti-jsfiddle,ti-joomla,ti-html5,ti-flickr-alt,ti-email,ti-drupal,ti-dropbox-alt,ti-css3,ti-rss,ti-rss-alt'
        )
    );

    return $register_icons;
  }

// Default Options 
//------------------------------------------------------------------------------

  function configuration($defaults) {

    $defaults['viewport'] = 1;

    $defaults['styles'] = 1;

    $defaults['styles_normalize'] = 1;

    $defaults['styles_widgets'] = 1;

    $defaults['styles_icons'] = 'dashicons';

    $defaults['styles_pscrollbar'] = 1;

    $defaults['styles_owlcarousel'] = 1;

    return $defaults;
  }

  function responsive($defaults) {

    $defaults['gutter'] = '30';
    $defaults['screen_sm_width'] = '768';
    $defaults['screen_md_width'] = '992';
    $defaults['screen_lg_width'] = '1200';

    return $defaults;
  }

  function css($defaults) {

    $defaults['css'] = '';

    return $defaults;
  }

  function themes_options($defaults) {

    // Layout
    // ---------------------------------------------------------------------   
    $defaults['layout'] = 'embed';
    $defaults['layout_offcanvas_float'] = 'right';
    $defaults['layout_align'] = 'right';
    $defaults['layout_sticky'] = 0;
    $defaults['layout_sticky_offset'] = 0;
    $defaults['layout_divider'] = 'hide';
    $defaults['layout_caret'] = 'show';
    $defaults['layout_trigger'] = 'hoverintent';
    $defaults['layout_current'] = 0;
    $defaults['layout_classes'] = '';
    $defaults['layout_breakpoint'] = '768';
    $defaults['layout_width'] = 0;
    $defaults['layout_width_inner'] = 0;
    $defaults['layout_width_inner_selector'] = '';
    $defaults['layout_lazyload'] = 0;
    $defaults['layout_dropdown_maxheight'] = 1;

    // Fonts
    // ---------------------------------------------------------------------
    $defaults['font'] = array(
        'font-family' => 'Verdana, Geneva, sans-serif',
        //'google' => true,
        'font-size' => '11',
        'font-style' => 'normal',
        'font-weight' => '400',
        'letter-spacing' => 'inherit',
    );

    $defaults['navbar_font'] = array(
        'font-family' => 'Verdana, Geneva, sans-serif',
        //'google' => true,
        'font-size' => '11',
        'font-weight' => '400',
        'font-style' => 'normal',
        'letter-spacing' => 'inherit',
    );

    $defaults['dropdown_font'] = array(
        'font-family' => 'Verdana, Geneva, sans-serif',
        //'google' => true,
        'font-size' => '11',
        'font-weight' => '400',
        'font-style' => 'normal',
        'letter-spacing' => 'inherit',
    );

    // Navbar
    // --------------------------------------------------------------------- 

    $defaults['navbar_logo'] = array(
        'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/images/logo.png'
    );
    $defaults['navbar_logo_link'] = home_url('/');
    $defaults['navbar_height'] = '60';
    $defaults['navbar_width'] = '260';
    $defaults['navbar_background'] = 'color';
    $defaults['navbar_background_color'] = '#333333';
    $defaults['navbar_background_to'] = '#000000';

    $defaults['navbar_background_deg'] = '17';

    $defaults['navbar_divider'] = $defaults['navbar_sharp'] = 'rgba(255,255,255,0.5)';

    $defaults['navbar_toggle_open'] = '#ffffff';
    $defaults['navbar_toggle_close'] = '#fb88dd';

    $defaults['navbar_mobile_border'] = 'rgba(255,255,255,0.1)';

    $defaults['navbar_text'] = '#aaaaaa';

    $defaults['navbar_logo_bg'] = 'transparent';

    $defaults['navbar_logo_height'] = '25';
    $defaults['navbar_link'] = '#f1f1f1';
    $defaults['navbar_link_hover'] = '#ffffff';
    $defaults['navbar_link_bg'] = 'transparent';
    $defaults['navbar_link_bg_hover'] = '#111111';
    $defaults['navbar_link_hover_effect'] = 'rgba(255,255,255,0.3)';
    $defaults['navbar_link_margin'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
    $defaults['navbar_link_radius'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
    $defaults['navbar_link_transform'] = 'uppercase';
    $defaults['navbar_link_icon'] = '#eeeeee';
    $defaults['navbar_link_icon_hover'] = '#ffffff';
    $defaults['navbar_link_subtitle'] = '#eeeeee';
    $defaults['navbar_link_subtitle_hover'] = '#ffffff';
    $defaults['navbar_button'] = '#ffffff';
    $defaults['navbar_button_bg'] = '#fb88dd';
    $defaults['navbar_button_hover'] = '#ffffff';
    $defaults['navbar_button_bg_hover'] = '#383838';
    $defaults['navbar_badge'] = '#fb88dd';
    $defaults['navbar_badge_color'] = '#ffffff';
    $defaults['sticky_height'] = '60';
    $defaults['sticky_background'] = 'rgba(0,0,0,0.95)';
    $defaults['sticky_logo_height'] = '25';
    $defaults['navbar_scrollbar'] = '#fb88dd';
    $defaults['navbar_scrollbar_rail'] = '#ffffff';
    $defaults['navbar_button'] = '#ffffff';
    $defaults['navbar_button_background'] = '#fb88dd';
    $defaults['navbar_button_hover'] = '#383838';
    $defaults['navbar_button_hover_background'] = '#eeeeee';
    $defaults['navbar_button_radius'] = array(
        'border-top' => '2',
        'border-right' => '2',
        'border-left' => '2',
        'border-bottom' => '2',
    );
    // Mobile
    // ---------------------------------------------------------------------

    $defaults['mobile_shadow'] = 'show';
    $defaults['mobile_link_padding'] = array('border-top' => '15', 'border-right' => '30', 'border-left' => '30', 'border-bottom' => '15');
    $defaults['mobile_link_border'] = array('border-all' => '0', 'border-top' => '0', 'border-color' => 'transparent', 'border-style' => 'none');

    // Dropdown
    // ---------------------------------------------------------------------
    $defaults['dropdown_shadow'] = 'show';
    $defaults['dropdown_margin'] = 0;
    $defaults['dropdown_radius'] = array(
        'border-top' => '0',
        'border-right' => '0',
        'border-left' => '0',
        'border-bottom' => '0',
    );
    $defaults['dropdown_border'] = array(
        'border-top' => '0',
        'border-right' => '0',
        'border-left' => '0',
        'border-bottom' => '0',
        'border-color' => '#000000'
    );
    $defaults['dropdown_background'] = '#ffffff';
    $defaults['dropdown_scrollbar'] = '#fb88dd';
    $defaults['dropdown_scrollbar_rail'] = '#ffffff';
    $defaults['dropdown_title'] = '#444444';
    $defaults['dropdown_title_border'] = array('border-all' => '1', 'border-top' => '1', 'border-color' => '#fb88dd', 'border-style' => 'solid');
    $defaults['dropdown_link'] = '#444444';
    $defaults['dropdown_link_hover'] = '#333333';
    $defaults['dropdown_link_bg_hover'] = '#f4f4f4';
    $defaults['dropdown_link_border'] = array('border-all' => '1', 'border-top' => '1', 'border-color' => '#f4f4f4', 'border-style' => 'solid');
    $defaults['dropdown_link_transform'] = 'none';
    $defaults['dropdown_link_icon'] = '#fb88dd';
    $defaults['dropdown_link_icon_hover'] = '#a9a9a9';
    $defaults['dropdown_link_subtitle'] = '#a0a0a0';
    $defaults['dropdown_link_subtitle_hover'] = '#cccccc';
    //$defaults['dropdown_link_padding'] = array('border-top' => '15', 'border-right' => '15', 'border-left' => '15', 'border-bottom' => '15');
    $defaults['dropdown_button'] = '#ffffff';
    $defaults['dropdown_button_bg'] = '#fb88dd';
    $defaults['dropdown_button_hover'] = '#ffffff';
    $defaults['dropdown_button_bg_hover'] = '#000000';
    //$defaults['dropdown_button_radius'] = array('border-top' => '0', 'border-right' => '0', 'border-left' => '0', 'border-bottom' => '0');
    $defaults['dropdown_tab_bg'] = 'rgba(0,0,0,0.05)';
    $defaults['dropdown_tab_bg_hover'] = 'rgba(0,0,0,0.1)';

    // Animations
    // ---------------------------------------------------------------------
    $defaults['layout_hover_effect'] = 'quadmenu-hover-ripple';

    $defaults['navbar_animation_text'] = array(
        'options' => '',
        'action' => 'hover',
        'speed' => 't_1000',
    );

    $defaults['navbar_animation_subtitle'] = array(
        'options' => '',
        'action' => 'hover',
        'speed' => 't_1000',
    );

    $defaults['navbar_animation_icon'] = array(
        'options' => '',
        'action' => 'hover',
        'speed' => 't_1000',
    );

    $defaults['navbar_animation_badge'] = array(
        'options' => 'quadmenu_swing',
        'action' => 'hover',
        'speed' => 't_1000',
    );

    $defaults['navbar_animation_cart'] = array(
        'options' => 'quadmenu_bounce',
        'speed' => 't_500',
    );

    $defaults['layout_animation'] = array(
        'options' => 'quadmenu_btt',
        'speed' => 't_300',
    );

    $defaults['dropdown_animation_text'] = array(
        'options' => '',
        'action' => 'hover',
        'speed' => 't_1000',
    );

    $defaults['dropdown_animation_subtitle'] = array(
        'options' => '',
        'action' => 'hover',
        'speed' => 't_1000',
    );

    $defaults['dropdown_animation_icon'] = array(
        'options' => '',
        'action' => 'hover',
        'speed' => 't_1000',
    );

    $defaults['dropdown_animation_badge'] = array(
        'options' => 'quadmenu_swing',
        'action' => 'loop',
        'speed' => 't_1000',
    );

    return $defaults;
  }

  function locations_options($defaults) {

    $defaults['integration'] = 0;
    $defaults['unwrap'] = 0;
    $defaults['theme'] = 'default_theme';

    return $defaults;
  }

}

new QuadMenu_Configuration();
