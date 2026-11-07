<?php
/**
 * Front page: hero, announcement strip, page content, events and news.
 *
 * Used automatically for the site's front page. If a static page is assigned
 * under Settings → Reading, its blocks render between the strip and the
 * events preview.
 *
 * @package Red_Feather_Club
 */

get_header();
?>

<?php get_template_part( 'template-parts/hero' ); ?>
<?php get_template_part( 'template-parts/announcement-strip' ); ?>

<main id="main" class="site-main">

	<?php if ( is_page() && have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();

			if ( trim( get_the_content() ) ) :
				?>
				<div class="section entry-content">
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
				<?php
			endif;
		endwhile;
		?>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/events-preview' ); ?>
	<?php get_template_part( 'template-parts/news-feed' ); ?>

</main>

<?php
get_footer();
