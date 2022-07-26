<?php
use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header');
      get_template_part('templates/header');
      $custom_fields = get_fields();
    ?>
    <?php if( is_singular('event') && has_post_thumbnail() ) : ?>
      <?= do_shortcode('[vc_row full_width="stretch_row" content_placement="bottom" css_animation="fadeIn"]
          [vc_column css=".custom_post_header{opacity: 0;}"]
          [/vc_column][/vc_row]'); ?>
    <?php endif; ?>
    <div class="wrap <?= !is_page() && !is_singular('praemie') && !is_post_type_archive('praemie') ? 'container' : 'container-fluid'; ?>" role="document">
      <div class="content row">
        <main class="main">
          <?php include Wrapper\template_path(); ?>
        </main><!-- /.main -->
        <?php if (Setup\display_sidebar()) : ?>
          <aside class="sidebar">
            <?php include Wrapper\sidebar_path(); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
      </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>
  </body>
</html>
