<?php
/**
 * Generic archive (category, tag, author, date).
 *
 * @package Red_Feather_Club
 */

get_header();

rfc_page_hero(
	array(
		'label' => __( 'Archive', 'red-feather-club' ),
		'title' => wp_strip_all_tags( get_the_archive_title() ),
		'body'  => wp_strip_all_tags( get_the_archive_description() ),
	)
);
?>

<main id="main" class="site-main">
	<div class="section">
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
