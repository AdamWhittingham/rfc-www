<?php
/**
 * Events archive — the full .event-row programme.
 *
 * @package Red_Feather_Club
 */

get_header();

rfc_page_hero(
	array(
		'label' => sprintf(
			/* translators: %s: year. */
			__( '%s Season', 'red-feather-club' ),
			gmdate( 'Y' )
		),
		'title' => post_type_archive_title( '', false ),
		'body'  => __( 'Open days, evening dances, living history weekends and more. The Red Feather Club comes to life throughout the year — we’d love to see you.', 'red-feather-club' ),
	)
);
?>

<main id="main" class="site-main">
	<section class="section">
		<div class="events-full">
			<?php if ( have_posts() ) : ?>
				<?php
				$rfc_current_year = '';

				while ( have_posts() ) :
					the_post();

					$rfc_date = get_post_meta( get_the_ID(), '_rfc_event_date', true );
					$rfc_year = $rfc_date ? gmdate( 'Y', strtotime( $rfc_date ) ) : '';

					if ( $rfc_year && $rfc_year !== $rfc_current_year ) {
						$rfc_current_year = $rfc_year;
						echo '<h2 class="season-heading observe-fade">' . esc_html( $rfc_year ) . '</h2>';
					}

					rfc_event_row( get_post() );
				endwhile;
				?>

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
		</div>
	</section>
</main>

<?php
get_footer();
