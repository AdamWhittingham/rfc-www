<?php
/**
 * Single post.
 *
 * @package Red_Feather_Club
 */

get_header();

while ( have_posts() ) :
	the_post();

	rfc_page_hero(
		array(
			'label' => get_the_date(),
			'title' => get_the_title(),
			'body'  => has_excerpt() ? get_the_excerpt() : '',
			'image' => get_the_post_thumbnail_url( null, 'rfc-hero' ),
		)
	);
	?>

	<main id="main" class="site-main">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
			<div class="history-layout">
				<div>
					<?php rfc_posted_on(); ?>

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

					<footer class="entry-footer">
						<?php the_tags( '<span class="entry-meta">', ', ', '</span>' ); ?>
					</footer>

					<?php
					the_post_navigation(
						array(
							'prev_text' => '&larr; %title',
							'next_text' => '%title &rarr;',
							'class'     => 'post-navigation',
						)
					);
					?>

					<?php if ( comments_open() || get_comments_number() ) : ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				</div>

				<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
					<aside class="sidebar" role="complementary">
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</aside>
				<?php endif; ?>
			</div>
		</article>
	</main>

	<?php
endwhile;

get_footer();
