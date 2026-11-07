<?php
/**
 * Shown when a query returns nothing.
 *
 * @package Red_Feather_Club
 */

?>
<section class="no-results">
	<div class="section-label"><?php esc_html_e( 'Nothing here', 'red-feather-club' ); ?></div>
	<h2 class="section-title"><?php esc_html_e( 'Nothing found', 'red-feather-club' ); ?></h2>

	<?php if ( is_search() ) : ?>
		<p class="section-body"><?php esc_html_e( 'Sorry, nothing matched those words. Try another search.', 'red-feather-club' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p class="section-body"><?php esc_html_e( 'There is nothing to show here yet. Please check back soon.', 'red-feather-club' ); ?></p>
	<?php endif; ?>
</section>
