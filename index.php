<?php get_template_part('templates/page', 'header'); ?>
<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php

global $wp_query;
$modifications = array();
$selected_catname = '';
$selected_country = '';
if( isset( $_GET['catname'] ) ) {
  $selected_catname = $_GET['catname'];
  $modifications['category__in'] = $_GET['catname'];
}
if( isset( $_GET['keyword'] ) ) {
  $modifications['s'] = $_GET['keyword'];
}
if( isset( $_GET['country'] ) ) {
  $selected_country = $_GET["country"];
  $modifications['meta_key'] = 'country';
  $modifications['meta_value'] = $selected_country;
}

$args = array_merge(
  $wp_query->query_vars,
  $modifications
);

query_posts( $args );

?>

<?php if( is_post_type_archive('praemie') ) : $classes[] = 'col-md-4'; $header = get_fields(14); ?>
  <?= do_shortcode('[vc_row full_width="stretch_row" content_placement="bottom" css_animation="fadeIn"]
  [vc_column css=".custom_page_header{padding-top: 13.8vw !important;background-image: url(' . wp_get_attachment_image_src($header['image'], 'full')[0] . ' !important}"]
  [vc_column_text]' . $header['title'] . '[/vc_column_text]
  [/vc_column][/vc_row]') ?>
  <div class="container index-content">
    <div class="row ml-0 mr-0">
<?php endif; ?>

<?php if ( is_post_type_archive('event') ) : ?>
  <div class="col"><div class="pb-4 w-100 border-top-solid"></div></div>
  <div class="row ml-0 mr-0 pr-3 pt-2 pb-5">
    <div class="col-sm-12 col-lg-8">
      <h3 class="mb-4 pb-3"><?= __('Events', 'sage'); ?></h3>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
<?php endwhile; ?>

<?php the_posts_navigation(); ?>

<?php if( is_post_type_archive('praemie') ) : ?>
    </div>
  </div>
  <div class="row">
    <?= do_shortcode(get_post(icl_object_id(14, 'page', false, ICL_LANGUAGE_CODE))->post_content); ?>
  </div>
<?php endif; ?>

<?php if ( is_post_type_archive('event') ) : ?>
    </div>
    <div class="col-sm-12 col-lg-4 pl-lg-5">
      <form>
        <div class="row">
          <div class="col sidebar-bgcolor">
            <h3 class="mb-3"><?= __('Quick Search', 'sage') ?></h3>
              <div class="form-group mb-4">
                <label for="catname"><strong><?= __('Selektion Kategorie'); ?></strong></label>
                <select class="form-control" id="catname" name="catname">
                  <option selected value=""><?php _e('Bitte wählen'); ?></option>
                  <?php
                    $terms = get_terms( 'category', 'orderby=name' );
                    foreach ( $terms as $term ) :
                      echo '<option ' . selected( $selected_catname, $term->term_id ) .' value="' . $term->term_id . '">' . $term->name . '</option>';
                    endforeach;
                  ?>
                </select>
              </div>
              <div class="form-group mb-4">
                <label for="inlineFormCustomSelect"><strong><?= __('Selektion Produktegruppe'); ?></strong></label>
                <select class="form-control" id="inlineFormCustomSelect">
                  <option selected><strong><?php _e('Bitte wählen'); ?></strong></option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
                </select>
              </div>
              <div class="form-group mb-4">
                <label for="country"><strong><?= __('Selektion Ortschaft'); ?></strong></label>
                <select class="form-control" id="country" name="country">
                  <option selected value=""><?php _e('Bitte wählen'); ?></option>
                  <?php
                  $countries = get_meta_values( 'country', 'event' );
                  foreach ( $countries as $country ) :
                    echo '<option ' . selected( $selected_country, $country ) .' value="' . $country . '">' . $country . '</option>';
                  endforeach;
                  ?>
                </select>
              </div>
              <div class="form-group mb-4">
                <label for="keyword"><strong><?= __('Selektion mit Suchwort'); ?></strong></label>
                <input type="text" name="keyword" id="keyword" class="w-100 form-control"/>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col pl-0 pr-0 mt-2">
            <button class="btn btn-primary w-100" type="submit"><?php _e('Search'); ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>
