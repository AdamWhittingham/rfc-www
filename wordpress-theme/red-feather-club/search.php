<?php
/**
 * Search results.
 *
 * @package Red_Feather_Club
 */

get_header();

rfc_page_hero(
	array(
		'label' => __( 'Search', 'red-feather-club' ),
		/* translators: %s: search term. */
		'title' => sprintf( __( 'Results for “%s”', 'red-feather-club' ), get_search_query() ),
	)
);
?>

<main id="main" class="site-main">
	<div class="section">
		<div style="max-width:520px; margin-bottom:3rem;"><?php get_search_form(); ?></div>

		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( '← Newer', 'red-feather-club' ),
					'next_text' => __( 'Older →', 'red-feather-club' ),
					'class'     => 'pagination',
				)
			);
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
