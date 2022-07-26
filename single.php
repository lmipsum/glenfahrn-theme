<?php if( is_singular('event') ) $classes[] = 'row ml-0 mr-0 pr-3';?>
<?php get_template_part('templates/content-single', get_post_type()); ?>
