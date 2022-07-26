<?php global $classes; ?>

<article <?php post_class($classes); ?>>
  <header>
    <?php if( 'event' == get_post_type() ) : ?>
    <a href="<?php the_permalink(); ?>">
      <h5 class="mb-0"><em><time datetime="<?= get_field('date'); ?>"><?= get_field('date'); ?></time><?php if (!empty(get_field('country'))) echo ' - ' . get_field('country'); ?></em></h5>
    </a>
    <?php endif; ?>
    <?php if( 'praemie' == get_post_type() ) : ?>
    <a href="<?php the_permalink(); ?>">
      <div class="praemie-thumbnail mb-3" style="background-image:url(<?= get_the_post_thumbnail_url(null, 'full');?>);"></div>
    </a>
    <?php endif; ?>
    <p class="entry-title<?= ( 'praemie' == get_post_type() ) ? ' mb-0' : ' mb-3'; ?>"><?= get_event_gategories(get_the_ID()); ?> - <?php the_title(); ?></p>
    <?php //get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
</article>
