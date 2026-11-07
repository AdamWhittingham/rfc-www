<?php
/**
 * The fallback template — also the blog index.
 *
 * @package Red_Feather_Club
 */

get_header();
?>

<?php
rfc_page_hero(
	array(
		'label' => is_home() && ! is_front_page() ? get_bloginfo( 'description' ) : '',
		'title' => is_home() ? ( get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'News', 'red-feather-club' ) ) : __( 'Archive', 'red-feather-club' ),
		'body'  => '',
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
