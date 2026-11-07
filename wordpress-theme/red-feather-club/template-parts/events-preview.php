<?php
/**
 * Upcoming events preview (.events-grid) — homepage.
 *
 * @package Red_Feather_Club
 */

$rfc_events = rfc_get_upcoming_events( 5 );

if ( ! $rfc_events ) {
	return;
}

$rfc_archive = get_post_type_archive_link( 'rfc_event' );
?>
<section class="section" id="events" aria-label="<?php esc_attr_e( 'Forthcoming events', 'red-feather-club' ); ?>">
	<div class="events-grid observe-fade">
		<div>
			<div class="section-label"><?php esc_html_e( 'Diary', 'red-feather-club' ); ?></div>
			<h2 class="section-title"><?php esc_html_e( 'Forthcoming Events', 'red-feather-club' ); ?></h2>
			<p class="section-body"><?php esc_html_e( 'Open days, evening dances and living history weekends — the Red Feather Club comes to life throughout the year.', 'red-feather-club' ); ?></p>
			<?php if ( $rfc_archive ) : ?>
				<p style="margin-top:1.5rem;">
					<a href="<?php echo esc_url( $rfc_archive ); ?>" class="btn btn--primary">
						<?php rfc_the_icon( 'calendar' ); ?>
						<?php esc_html_e( 'See the full programme', 'red-feather-club' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<div class="events-list">
			<?php
			foreach ( $rfc_events as $rfc_event ) {
				rfc_event_item( $rfc_event );
			}
			?>
		</div>
	</div>
</section>
