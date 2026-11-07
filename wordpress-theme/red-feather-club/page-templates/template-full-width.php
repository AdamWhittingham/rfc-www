<?php
/**
 * Template Name: Full Width (no page hero)
 * Template Post Type: page, post
 *
 * Edge-to-edge canvas for pages built entirely from block patterns.
 *
 * @package Red_Feather_Club
 */

get_header();
?>

<main id="main" class="site-main site-main--offset">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">',
					'after'  => '</div>',
				)
			);
			?>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
