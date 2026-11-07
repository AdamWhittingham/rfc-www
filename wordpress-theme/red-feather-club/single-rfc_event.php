<?php
/**
 * Single event.
 *
 * @package Red_Feather_Club
 */

get_header();

while ( have_posts() ) :
	the_post();

	$rfc_parts = rfc_event_date_parts( get_the_ID() );
	$rfc_tag   = rfc_event_tag( get_the_ID() );
	$rfc_time  = get_post_meta( get_the_ID(), '_rfc_event_time', true );

	rfc_page_hero(
		array(
			'label' => $rfc_tag['label'],
			'title' => get_the_title(),
			'body'  => $rfc_time,
			'image' => get_the_post_thumbnail_url( null, 'rfc-hero' ),
		)
	);
	?>

	<main id="main" class="site-main">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
			<div class="history-layout">
				<div class="entry-content">
					<?php the_content(); ?>
				</div>

				<aside class="sidebar">
					<div class="sidebar-card">
						<div class="sidebar-card__title"><?php esc_html_e( 'When', 'red-feather-club' ); ?></div>
						<p>
							<strong>
							<?php
							echo esc_html(
								date_i18n(
									get_option( 'date_format' ),
									$rfc_parts['timestamp']
								)
							);
							?>
							</strong>
						</p>
						<?php if ( $rfc_time ) : ?>
							<p><?php echo esc_html( $rfc_time ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( get_theme_mod( 'rfc_footer_address' ) ) : ?>
						<div class="sidebar-card">
							<div class="sidebar-card__title"><?php esc_html_e( 'Where', 'red-feather-club' ); ?></div>
							<p><?php echo esc_html( get_theme_mod( 'rfc_footer_address' ) ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					<?php endif; ?>
				</aside>
			</div>

			<?php
			the_post_navigation(
				array(
					'prev_text' => '&larr; %title',
					'next_text' => '%title &rarr;',
					'class'     => 'post-navigation',
				)
			);
			?>
		</article>
	</main>

	<?php
endwhile;

get_footer();
