<?php 
// Loads the header.php template.
get_header();
?>

<?php
// Dispay Loop Meta at top
unos_add_custom_title_content( 'pre', 'page.php' );
if ( unos_titlearea_top() ) {
	unos_loopmeta_header_img( 'page', false );
	get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
	unos_add_custom_title_content( 'post', 'page.php' );
} else {
	unos_loopmeta_header_img( 'page', true );
}

// Template modification Hook
do_action( 'unos_before_content_grid', 'page.php' );
?>

<div class="hgrid main-content-grid">

	<main <?php hoot_attr( 'content' ); ?>>
		<div <?php hoot_attr( 'content-wrap', 'page' ); ?>>

			<?php
			// Template modification Hook
			do_action( 'unos_main_start', 'page.php' );

			// Checks if any posts were found.
			if ( have_posts() ) :

				// Display Featured Image if present
				if ( hoot_get_mod( 'post_featured_image_page' ) == 'content' ) {
					$img_size = apply_filters( 'unos_page_imgsize', '', 'content' );
					hoot_post_thumbnail( 'entry-content-featured-img', $img_size, true );
				}

				// Dispay Loop Meta in content wrap
				if ( ! unos_titlearea_top() ) {
					unos_add_custom_title_content( 'post', 'page.php' );
					get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
				}

				// Template modification Hook
				do_action( 'unos_loop_start', 'page.php' );

				// Begins the loop through found posts, and load the post data.
				while ( have_posts() ) : the_post();

					// Loads the template-parts/content-{$post_type}.php template.
					hoot_get_content_template();

				// End found posts loop.
				endwhile;

				// Template modification Hook
				do_action( 'unos_loop_end', 'page.php' );

				// Template modification Hook
				do_action( 'unos_after_content_wrap', 'page.php' );

				// Loads the comments.php template if this page is not being displayed as frontpage or a custom 404 page or if this is attachment page of media attached (uploaded) to a page.
				if ( !is_front_page() && !is_attachment() ) :

					// Loads the comments.php template
					comments_template( '', true );

				endif;

			// If no posts were found.
			else :

				// Loads the template-parts/error.php template.
				get_template_part( 'template-parts/error' );

			// End check for posts.
			endif;

			// Template modification Hook
			do_action( 'unos_main_end', 'page.php' );
			?>

		</div><!-- #content-wrap -->
	</main><!-- #content -->

	<?php hoot_get_sidebar(); // Loads the sidebar.php template. ?>

</div><!-- .main-content-grid -->

<?php get_footer(); // Loads the footer.php template. ?>