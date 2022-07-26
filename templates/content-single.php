<?php global $custom_fields, $classes; ?>
<?php if( is_singular('praemie') ) : $header = get_fields(14); ?>
<?= do_shortcode('[vc_row full_width="stretch_row" content_placement="bottom" css_animation="fadeIn"]
  [vc_column css=".custom_page_header{padding-top: 13.8vw !important;background-image: url(' . wp_get_attachment_image_src($header['image'], 'full')[0] . ' !important}"]
  [vc_column_text]' . $header['title'] . '[/vc_column_text]
  [/vc_column][/vc_row]') ?>
<?php endif; ?>
<?php if( is_singular('event') ) : ?>
  <div class="col"><a href="/event" class="previous pt-3 pb-4<?php if (!has_post_thumbnail() ) echo ' border-top-solid'; ?>">« Zurück zur Eventübersicht</a></div>
<?php endif; ?>
<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class($classes); ?>>
    <?php if ( is_singular('event') ) : ?>
    <div class="col-sm-7">
    <?php endif; ?>
    <?php if ( !is_singular('praemie') ) : ?>
      <header>
        <h5 class="text-primary mb-0"><em><?= $custom_fields['date']; ?> - <?= $custom_fields['country']; ?></em></h5>
        <h5 class="entry-title"><?= get_event_gategories(get_the_ID()); ?> - <?php the_title(); ?></h5>
        <?php // get_template_part('templates/entry-meta'); ?>
      </header>
    <?php endif; ?>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <?php if ( !is_singular(['praemie','event']) ) : ?>
      <footer>
        <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
      </footer>
      <?php // comments_template('/templates/comments.php'); ?>
    <?php endif; ?>
    <?php if ( is_singular('event') ) : ?>
    </div>
    <div class="col-sm-4 offset-sm-1">
      <div class="row">
        <div class="col-sm-12 sidebar-bgcolor">
          <h5 class="text-primary mb-3"><em><?= __('Status'); ?></em><br></h5>
          <?php
            $status = get_field_object('status');
            $status_value = $status['value'];
            $status_label = $status['choices'][ $status_value ];
          ?>
          <p><?= $status_label; ?></p>

          <h5><em><?= __('Overview', 'sage'); ?></em></h5>

          <?php if ( !empty($custom_fields['location']) ) : ?>
            <strong><?= __('Loccation', 'sage'); ?></strong>
            <?= $custom_fields['location']; ?>
          <?php endif; ?>

          <p>
            <strong><?= __('Date', 'sage'); ?></strong><br>
            <?= $custom_fields['date']; ?>
          </p>

          <?php if ( !empty($custom_fields['hour']) ) : ?>
            <p>
              <strong><?= __('Starts at', 'sage'); ?></strong><br>
              <?= $custom_fields['hour']; ?> <?= __('Uhr') ?>
            </p>
          <?php endif; ?>

          <?php if ( !empty($custom_fields['duration']) ) : ?>
            <p>
              <strong><?= __('Duration', 'sage'); ?></strong><br>
              <?= $custom_fields['duration']; ?> <?= __('Stunden') ?>
              </p>
          <?php endif; ?>

          <?php if ( !empty($custom_fields['price']) ) : ?>
            <p>
              <strong><?= __('Price', 'sage'); ?></strong><br>
              <?= __('CHF') . $custom_fields['price']; ?>.– (<?= __('inkl. Getränke und Snack'); ?>)
            </p>
          <?php endif; ?>

          <?php if ( !empty($custom_fields['contact']) ) : ?>
            <p>
              <strong><?= __('Contact', 'sage'); ?></strong><br>
              <?= $custom_fields['contact']; ?>
            </p>
          <?php endif; ?>

          <?php if ( !empty($custom_fields['telephone']) || !empty($custom_fields['email']) ) : ?>
            <p><strong><?= nl2br(__("Registration", "sage")); ?></strong><br>
          <?php if ( !empty($custom_fields['telephone']) ) : ?>
            <?= __('Telephone'); ?>: <?= $custom_fields['telephone']; ?>
          <?php endif; ?>
          <?php if ( !empty($custom_fields['telephone']) && !empty($custom_fields['email']) ) echo '<br>'; ?>
          <?php if ( !empty($custom_fields['email']) ) : ?>
            <?= __('E-Mail'); ?>: <?php printf("<a href=\"mailto:%s\" class=\"text-muted\">%s</a>", $custom_fields['email'], $custom_fields['email']) ?></p>
          <?php endif; ?>
          <?php endif; ?>
        </div>
        <div class="col-sm-12 pl-0 pr-0 mt-2">
          <a href="anmeldung/?event_id=<?php the_ID(); ?>" class="btn btn-primary w-100"><?= __('Jetzt Anmelden') ?></a>
        </div>
      </div>
      <?php //print_r($custom_fields); ?>
    </div>
    <?php endif; ?>
  </article>
<?php endwhile; ?>
