<?php
/**
 * The template for displaying the header and the sidebar
 *
 * Displays everything up until the "site-content" div.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js?ver=3.7.0"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyfifteen' ); ?></a>

	<div id="header-spacer" class="header-spacer">
	</div>
	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			twentyfifteen_the_custom_logo();

			$title_element = 'p';
			if ( is_front_page() && is_home() ) {
				$title_element = 'h1';
			}
			printf( '<%s class="site-title"><a href="%s" rel="home">%s</a></%s>' . PHP_EOL ,
				$title_element, esc_url( home_url('/') ), get_bloginfo( 'name' ),
				$title_element );
			?>
			<button class="button-top-menu-toggle"></button>
		</div><!-- .site-branding -->

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation">
				<?php
					// Primary navigation menu.
					wp_nav_menu(
						array(
							'menu_class'     => 'nav-menu',
							'theme_location' => 'primary',
						)
					);
				?>
			</nav><!-- .main-navigation -->
		<?php endif; ?>

	</header><!-- .site-header -->

	<div id="main-container" class="clearfix">

		<div id="sidebar" class="sidebar">
			<div class="sidebar__inner">
				<?php get_sidebar(); ?>
			</div>
		</div><!-- .sidebar -->

		<div id="content" class="site-content">
