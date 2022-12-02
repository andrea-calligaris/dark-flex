<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 */

if ( is_single() ) {
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="top-meta-info entry-footer">
	<?php twentyfifteen_entry_meta(); ?>
	<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
</div><!-- .top-meta-info -->

<?php
} else {
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'excerpt' ); ?>>
<?php
}
		// Post thumbnail.
		twentyfifteen_post_thumbnail();
	?>

	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
			?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			if ( ! is_single() ) {
				add_filter('the_excerpt', 'new_the_excerpt');
				the_excerpt();
				remove_filter('the_excerpt', 'new_the_excerpt');
			} else {
				the_content(
					sprintf(
						/* translators: %s: Post title. */
						'Continue reading %s',
						the_title( '<span class="screen-reader-text">', '</span>', false )
					)
				);
			}
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
