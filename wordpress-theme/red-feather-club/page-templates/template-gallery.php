<?php
/**
 * Template Name: Photo Gallery
 * Template Post Type: page
 *
 * Page content, then every Gallery Photo as a filterable masonry grid.
 *
 * @package Red_Feather_Club
 */

get_header();

while ( have_posts() ) :
	the_post();

	rfc_page_hero(
		array(
			'label' => __( 'Photographs', 'red-feather-club' ),
			'title' => get_the_title(),
			'body'  => has_excerpt() ? get_the_excerpt() : '',
			'image' => get_the_post_thumbnail_url( null, 'rfc-hero' ),
		)
	);
	?>

	<main id="main" class="site-main">
		<section class="section">
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry-content" style="margin-bottom:3rem;"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php
			$rfc_terms = get_terms(
				array(
					'taxonomy'   => 'rfc_photo_category',
					'hide_empty' => true,
				)
			);

			if ( $rfc_terms && ! is_wp_error( $rfc_terms ) ) :
				?>
				<div class="gallery-filter">
					<button class="filter-btn active" data-filter="all"><?php esc_html_e( 'All', 'red-feather-club' ); ?></button>
					<?php foreach ( $rfc_terms as $rfc_term ) : ?>
						<button class="filter-btn" data-filter="<?php echo esc_attr( $rfc_term->slug ); ?>"><?php echo esc_html( $rfc_term->name ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			$rfc_photos = new WP_Query(
				array(
					'post_type'      => 'rfc_photo',
					'posts_per_page' => 120,
					'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
					'no_found_rows'  => true,
				)
			);

			if ( $rfc_photos->have_posts() ) :
				?>
				<div class="photo-grid">
					<?php
					while ( $rfc_photos->have_posts() ) :
						$rfc_photos->the_post();
						rfc_photo_item( get_post() );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				<?php
			else :
				?>
				<p class="section-body"><?php esc_html_e( 'No photographs have been added yet. Add them under Gallery in the dashboard.', 'red-feather-club' ); ?></p>
				<?php
			endif;
			?>
		</section>
	</main>

	<?php
endwhile;

get_footer();
