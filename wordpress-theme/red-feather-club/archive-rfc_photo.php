<?php
/**
 * Gallery archive — filterable masonry grid with lightbox.
 *
 * @package Red_Feather_Club
 */

get_header();

rfc_page_hero(
	array(
		'label' => __( 'Photographs', 'red-feather-club' ),
		'title' => is_tax() ? single_term_title( '', false ) : post_type_archive_title( '', false ),
		'body'  => __( 'Images of the restored site, the collections, our events and the men of the 95th. Click any photograph to view it full size.', 'red-feather-club' ),
	)
);

$rfc_terms = get_terms(
	array(
		'taxonomy'   => 'rfc_photo_category',
		'hide_empty' => true,
	)
);
?>

<main id="main" class="site-main">
	<section class="section">
		<?php if ( $rfc_terms && ! is_wp_error( $rfc_terms ) && ! is_tax() ) : ?>
			<div class="gallery-filter">
				<button class="filter-btn active" data-filter="all"><?php esc_html_e( 'All', 'red-feather-club' ); ?></button>
				<?php foreach ( $rfc_terms as $rfc_term ) : ?>
					<button class="filter-btn" data-filter="<?php echo esc_attr( $rfc_term->slug ); ?>"><?php echo esc_html( $rfc_term->name ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="photo-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					rfc_photo_item( get_post() );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size' => 2,
					'class'    => 'pagination',
				)
			);
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
