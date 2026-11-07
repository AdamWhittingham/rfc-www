<?php
/**
 * Single page.
 *
 * The page title becomes the olive-drab page hero; a featured image becomes
 * its background; the excerpt (if set) becomes the standfirst.
 *
 * @package Red_Feather_Club
 */

get_header();

while ( have_posts() ) :
	the_post();

	rfc_page_hero(
		array(
			'label' => get_theme_mod( 'rfc_nav_title', get_bloginfo( 'name' ) ),
			'title' => get_the_title(),
			'body'  => has_excerpt() ? get_the_excerpt() : '',
			'image' => get_the_post_thumbnail_url( null, 'rfc-hero' ),
		)
	);
	?>

	<main id="main" class="site-main">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="page-links">',
						'after'  => '</div>',
					)
				);
				?>
			</div>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		</article>
	</main>

	<?php
endwhile;

get_footer();
