<?php
/* -----------------------------------------------------------------------------------
 *		Theme functions
 * ----------------------------------------------------------------------------------- */

/**
 * Remove Customization
 */

/* Remove colors customization and its inline CSS */

function tfc_deregister_colors ( $wp_customize ) {
	$wp_customize->remove_setting( 'color_scheme' );
	$wp_customize->remove_control( 'color_scheme' );
	$wp_customize->remove_setting( 'sidebar_textcolor' );
	$wp_customize->remove_control( 'sidebar_textcolor' );
	$wp_customize->remove_setting( 'header_background_color' );
	$wp_customize->remove_control( 'header_background_color' );
}
add_action( 'customize_register', 'tfc_deregister_colors', 20 );

function tfc_remove_color_scheme_inline_css() {
	wp_styles()->add_data( 'twentyfifteen-style', 'after', '' );
}
add_action( 'wp_print_styles', 'tfc_remove_color_scheme_inline_css' );

/* Remove Custom Support */
function tfc_remove_theme_custom_support() {
	remove_theme_support('custom-background');
	remove_theme_support('custom-header');
}
add_action( 'after_setup_theme', 'tfc_remove_theme_custom_support', 20 );

/* Customizer Descriptions */
function tfc_customize_descriptions( $wp_customize ) {
	$wp_customize->get_section( 'title_tagline' /*(Site Identity)*/ )->description = 'The logo must have a height of 86 pixels exactly; the width is free.';
}
add_action( 'customize_register', 'tfc_customize_descriptions', 21 );


/**
 * Stylesheets
 */

add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );
function child_theme_enqueue_styles() {
    $parenthandle = 'twentyfifteen-style'; // Twenty Fifteen theme CSS.
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}


/**
 * Scripts
 */

function child_theme_enqueue_scripts() {
	// "functions.js"
	wp_dequeue_script('twentyfifteen-script');
	wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/js/functions.js', array( 'jquery' ), '20220323', true );

	wp_localize_script(
		'child-script',
		'screenReaderText',
		array(
			'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'twentyfifteen' ) . '</span>',
			'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'twentyfifteen' ) . '</span>',
		)
	);

	// Sticky sidebar
	wp_enqueue_script( 'sticky-sidebar',  get_stylesheet_directory_uri() . '/js/sticky-sidebar.js', array(), true, true );

	wp_add_inline_script(
		'sticky-sidebar',
		'try{ new StickySidebar( "#sidebar", {containerSelector:".site-content",innerWrapperSelector:".sidebar__inner",topSpacing:57,bottomSpacing:0,minWidth:956} ); }catch(e){}'
	);
}
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_scripts', 20 );


/**
 Get back the search button that the parent theme removed
 */
function restore_the_submit_button( $html ) {
	return str_replace( 'class="search-submit screen-reader-text"', 'class="search-submit"', $html );
}
add_filter( 'get_search_form', 'restore_the_submit_button', 20);


/**
 * Menus
 */
function change_menu_locations() {
	unregister_nav_menu( 'social' );

	register_nav_menus(
		array(
			'sidebar' => __( 'Sidebar Menu', 'twentyfifteen-child' ),
		)
	);
}
add_action( 'after_setup_theme', 'change_menu_locations', 20 );


/**
 * Remove author info unconditionally
 */

add_filter('the_author', '__return_false');
add_filter('get_the_author', '__return_false');
add_filter('the_author_meta', '__return_false');
add_filter('get_avatar', '__return_false');
/* then, for the icon and the spacing, I just do "display: none" in the "bypass" class in the CSS, which is the cleanest way to do it since I want to remove the author info for all the posts, so no need for a script */


/**
 * Excerpts
 */
function new_the_excerpt() {
	$excerpt = '';
	if (has_excerpt()) {
		// the user has inserted a manual excerpt, so there will be no "Continue reading" link; however, I do want it, so I add it manually here
		$excerpt = '<p>' . get_the_excerpt() . '</p>';
		$excerpt .= sprintf(
			'<a href="%1$s" class="more-link">%2$s</a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Post title. */
			sprintf( 'Read the full entry %s', '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>'));
	} else {
		// in this case get_the_excerpt() will be filtered with "wp_trim_excerpt" to automatically create an excerpt, so it will also call "excerpt_more" for the "Continue reading" link; so no need to add yet another link
		$excerpt = '<p>' . get_the_excerpt() . '</p>';
	}
	echo $excerpt;
}
// Then, when needed, you'll add and remove the filter


/**
 * Entry meta
 */
/* prints HTML with meta information for posts, e.g. categories and tags */
function get_normal_time_string($time_string) {
	$time_string = sprintf(
	$time_string,
	esc_attr( get_the_date( 'c' ) ),
	get_the_date(),
	esc_attr( get_the_modified_date( 'c' ) ),
	get_the_modified_date()
	);
	return $time_string;
}
function twentyfifteen_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'twentyfifteen' ) );
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		$post_format_name_html = '';
		if( $format != 'aside' ) {
			$post_format_name_html = sprintf('<a href="%s">%s</a>', esc_url( get_post_format_link($format) ),
				get_post_format_string( $format ) );
		}
		else {
			$post_format_name_html = sprintf('%s', get_post_format_string( $format ) );
		}
		printf(
			'<span class="entry-format">%1$s%2$s</span>',
				sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentyfifteen' ) ),
			$post_format_name_html
		);
	}

	if( $format === 'quote' && ! is_single() ) {
		printf( '<span class="quote-link">(<a href="%s">link</a>)</span>', esc_url( get_permalink() ) );
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ), true ) &&
	   		$format != 'quote' ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		/* Date */
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = get_normal_time_string($time_string);
		printf(
			'<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Posted on', 'Used before publish date.', 'twentyfifteen' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

	if ( 'post' === get_post_type() ) {

		/* Categories */
		$cat_parents_list = '';
		$cat_terms = get_the_category(); // get all the categories as an array of WP_Terms
		$counter = 0;
		foreach( $cat_terms as $cat ) {
			$cat_parent_id = $cat->parent;
			if( $cat_parent_id != 0 ) {
				$cat_parent = get_term( $cat_parent_id, 'category' );
				if( $counter > 0) {
					$cat_parents_list .= ', ';
				}
				$cat_parents_list .= '<a href="' . esc_url( get_category_link( $cat_parent_id ) ) . '" rel="category tag">' . wptexturize($cat_parent->name) . '</a>';
				$counter += 1;
			}
		}
		if( $cat_parents_list !== '' ) {
			$cat_parents_list .= ' – '; // separate parents and children categories
		}
		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
		if ( strpos( $categories_list, "Uncategorized" ) == false ) {
			if ( $categories_list && twentyfifteen_categorized_blog() ) {
				printf(
					'<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					_x( 'Categories', 'Used before category names.', 'twentyfifteen' ),
					$cat_parents_list . $categories_list
				);
			}
		}

		/* Tags */
		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
		if ( $tags_list && ! is_wp_error( $tags_list ) ) {
			printf(
				'<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'twentyfifteen' ),
				$tags_list
			);
		}
	}

	/* Attachment image */
	if ( is_attachment() && wp_attachment_is_image() ) {
		// retrieve attachment metadata
		$metadata = wp_get_attachment_metadata();

		printf(
			'<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'twentyfifteen' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}

	/* Comments */
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: Post title. */
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentyfifteen' ), get_the_title() ) );
		echo '</span>';
	}
}
add_action( 'init', 'twentyfifteen_entry_meta', 20 );

?>
