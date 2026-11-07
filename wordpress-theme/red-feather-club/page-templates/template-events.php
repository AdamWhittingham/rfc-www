<?php
/**
 * Template Name: Events Programme
 * Template Post Type: page
 *
 * Page content, then the full list of events as .event-row blocks grouped by year.
 *
 * @package Red_Feather_Club
 */

get_header();

while ( have_posts() ) :
	the_post();

	rfc_page_hero(
		array(
			'label' => sprintf(
				/* translators: %s: year. */
				__( '%s Season', 'red-feather-club' ),
				gmdate( 'Y' )
			),
			'title' => get_the_title(),
			'body'  => has_excerpt() ? get_the_excerpt() : '',
			'image' => get_the_post_thumbnail_url( null, 'rfc-hero' ),
		)
	);
	?>

	<main id="main" class="site-main">
		<section class="section">
			<div class="events-full">
				<?php if ( trim( get_the_content() ) ) : ?>
					<div class="entry-content"><?php the_content(); ?></div>
				<?php endif; ?>

				<?php
				$rfc_events = new WP_Query(
					array(
						'post_type'      => 'rfc_event',
						'posts_per_page' => 100,
						'meta_key'       => '_rfc_event_date',
						'orderby'        => 'meta_value',
						'order'          => 'ASC',
						'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
							array(
								'key'     => '_rfc_event_date',
								'value'   => current_time( 'Y-m-d' ),
								'compare' => '>=',
								'type'    => 'DATE',
							),
						),
						'no_found_rows'  => true,
					)
				);

				if ( $rfc_events->have_posts() ) :
					$rfc_current_year = '';

					while ( $rfc_events->have_posts() ) :
						$rfc_events->the_post();

						$rfc_date = get_post_meta( get_the_ID(), '_rfc_event_date', true );
						$rfc_year = $rfc_date ? gmdate( 'Y', strtotime( $rfc_date ) ) : '';

						if ( $rfc_year && $rfc_year !== $rfc_current_year ) {
							$rfc_current_year = $rfc_year;
							echo '<h2 class="season-heading observe-fade">' . esc_html( $rfc_year ) . '</h2>';
						}

						rfc_event_row( get_post() );
					endwhile;

					wp_reset_postdata();
				else :
					?>
					<p class="section-body"><?php esc_html_e( 'No forthcoming events are listed yet — please check back soon.', 'red-feather-club' ); ?></p>
					<?php
				endif;
				?>
			</div>
		</section>
	</main>

	<?php
endwhile;

get_footer();
