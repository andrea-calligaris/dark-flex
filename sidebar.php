<?php
/**
 * Sidebar
 */

if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="secondary" class="secondary">

		<div class="search-area">
		<?php
			get_search_form();
		?>
		</div><!-- .search-area -->

		<div id="widget-area" class="widget-area" role="complementary">

			<?php dynamic_sidebar( 'sidebar-1' ); ?>

		</div><!-- .widget-area -->

		<?php if ( has_nav_menu( 'sidebar' ) ) : ?>
		<nav id="sidebar-navigation" class="sidebar-navigation">
			<?php
			// Sidebar navigation menu.
			wp_nav_menu(
				array(
					'menu_class'     => 'nav-menu',
					'theme_location' => 'sidebar',
				)
			);
			?>
		</nav><!-- .sidebar-navigation -->
		<?php endif; ?>

	</div><!-- .secondary -->

<?php endif; ?>
