<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */

$sage_includes = [
  'acf/acf.php',
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php',// Theme customizer
  'lib/wp-bootstrap-navwalker.php',
  'lib/acf_fields.php'
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

add_filter('acf/settings/path', 'acf_settings_path');
function acf_settings_path($path) {
  $path = get_stylesheet_directory() . '/acf/';
  return $path;
}

add_filter('acf/settings/dir', 'acf_settings_dir');
function acf_settings_dir($dir) {
  $dir = get_stylesheet_directory_uri() . '/acf/';
  return $dir;
}

add_filter('acf/settings/default_language', 'acf_settings_default_language');
function acf_settings_default_language($language) {
  return 'en';
}

add_filter('dynamic_sidebar_params', 'my_dynamic_sidebar_params');
function my_dynamic_sidebar_params( $params ) {
  // get widget vars
  $widget_name = $params[0]['widget_name'];
  $widget_id = $params[0]['widget_id'];

  // bail early if this widget is not a Text widget
  if( $widget_name != 'Text' ) {
    return $params;
  }

  // add image to after_widget
  /*$image = get_field('image', 'widget_' . $widget_id);
  if( $image ) {
    $params[0]['after_widget'] = '<img src="' . $image['url'] . '">' . $params[0]['after_widget'];
  }*/

  if( get_field('list', 'widget_' . $widget_id) ):
    // loop through the rows of data
    $params[0]['before_widget'] .= '<ul class="list-inline mb-0 align-self-center">';
    while ( has_sub_field('list', 'widget_' . $widget_id) ) :
      if( get_row_layout() == 'link' ):
        $link = get_sub_field('isExternal') ? get_sub_field('extranal_url') : get_sub_field('page_url');
        $target = get_sub_field('isExternal') ? ' target="_blank"' : '';
        $params[0]['before_widget'] .= '<li class="list-inline-item"><a href="'. $link . '"' . $target . '>';
        $params[0]['before_widget'] .= get_sub_field('link_name');
        $params[0]['before_widget'] .= '</a></li>';
      elseif( get_row_layout() == 'text' ):
        $params[0]['before_widget'] .= '<li class="list-inline-item">' . get_sub_field('text_content') . '</li>';
      endif;
    endwhile;
    $params[0]['before_widget'] .= '</ul>';
    //$params[0]['after_widget'] .= $params[0]['after_widget'];
  else :
    // no layouts found
  endif;

  // return
  return $params;
}

add_action( 'pre_get_posts', 'override_query_vars' );
function override_query_vars( $query ) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ( is_post_type_archive('event') || get_post_type() == 'event' ) {
      $query->set( 'meta_key', 'date' );
      $query->set( 'orderby', 'meta_value' );
      $query->set( 'order', 'ASC' );
    }
    if ( is_post_type_archive('praemie') || get_post_type() == 'praemie' ) {
      $query->set( 'order', 'ASC' );
    }
  }
  return $query;
}

function language_selector_codes(){
  $r = '<ul class="list-inline text-uppercase">';
  $languages = icl_get_languages('skip_missing=0');
  if(!empty($languages)){ ?>
    <?php
    foreach($languages as $l){
      $r .= '<li class="list-inline-item">';
      if(!$l['active']) $r.='<a href="'.$l['url'].'">';
      $r.= $l['language_code'];
      if(!$l['active']) $r.='</a>';
      $r .= '</li>';
    }
    ?>
  <?php
  }
  $r.= '</ul>';
  echo $r;
}

function eventtitle_func( $atts ) {
  $post_id = isset($_GET['event_id']) ? $_GET['event_id'] : 0;
  $r = "<time datetime=\"" . get_field('date', $post_id) . "\">" . get_field('date', $post_id) . "</time> - " . get_field('country', $post_id);
  $r .= "<br>" . get_event_gategories($post_id) . " - ". get_the_title($post_id);
  return $r;
}
add_shortcode( 'eventtitle', 'eventtitle_func' );

function get_event_gategories($id) {
  $result = wp_get_post_categories( $id, ['fields' => 'names'] );
  return implode(" ",$result);
}

function mytheme_custom_styles() {
  $header = get_fields(14);
  if( is_post_type_archive('praemie') || is_singular('praemie') ) :
    $custom_css = '.custom_page_header{padding-top: 13.8vw !important;background-image: url(' . wp_get_attachment_image_src($header['image'], 'full')[0] . ') !important;}';
    wp_add_inline_style( 'sage/css', $custom_css );
  endif;
  if( is_singular('event') && has_post_thumbnail() ) :
    $featured_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
    $custom_css = '.custom_post_header{padding-top: 22vw;background-image: url(' . $featured_image . ') !important;}';
    wp_add_inline_style( 'sage/css', $custom_css );
  endif;
}
add_action( 'wp_enqueue_scripts', 'mytheme_custom_styles', 1000 );

function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
  global $wpdb;
  if( empty( $key ) ) return;

  $r = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s' 
        AND p.post_status = '%s' 
        AND p.post_type = '%s'
    ", $key, $status, $type ) );

  return $r;
}

add_shortcode('CF7_ADD_EVENTDATE', 'cf7_add_eventdate');
function cf7_add_eventdate(){
  if ( isset($_GET['event_id']) ) :
    return get_field('date', $_GET['event_id']);
  endif;
  return '';
}

add_shortcode('CF7_ADD_EVENTNAME', 'cf7_add_eventname');
function cf7_add_eventname(){
  if ( isset($_GET['event_id']) ) :
    return get_the_title($_GET['event_id']);
  endif;
  return '';
}

add_filter( 'wpcf7_support_html5_fallback', '__return_true' );

add_filter('acf/settings/show_admin', '__return_false');
add_action( 'admin_menu', 'custom_menu_page_removing', 1000 );
function custom_menu_page_removing() {
  remove_menu_page( 'edit.php' );
  remove_menu_page( 'edit-comments.php' );
  remove_menu_page( 'plugins.php' );
  remove_menu_page( 'tools.php' );
  remove_menu_page( 'options-general.php' );
  //remove_menu_page( 'vc-general' );
  remove_menu_page( 'about-ultimate' );
  remove_menu_page( 'sitepress-multilingual-cms/menu/languages.php' );
  remove_submenu_page( 'index.php', 'bsf-registration' );
}

vc_set_as_theme( $disable_updater = true );
