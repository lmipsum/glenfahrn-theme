<header class="banner">
  <div class="container text-center">
    <?php if ( has_custom_logo() ) : the_custom_logo(); else: ?>
      <a class="brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    <?php endif; ?>
    <div id="language_code_selector"><?php language_selector_codes(); ?></div >
  </div>
  <nav class="nav-primary navbar navbar-toggleable-md navbar-light text-uppercase">
    <div class="navbar-header">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
    <?php
    if (has_nav_menu('primary_navigation')) :
      //wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      wp_nav_menu([
          'menu'              => 'primary_navigation',
          'theme_location'    => 'primary_navigation',
          'depth'             => 2,
          'container'         => 'div',
          'container_class'   => 'collapse navbar-collapse',
          'container_id'      => 'navbarSupportedContent',
          'menu_class'        => 'nav navbar-nav mx-auto',
          'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
          'walker'            => new WP_Bootstrap_Navwalker()
      ]);
    endif;
    ?>
  </nav>
</header>
