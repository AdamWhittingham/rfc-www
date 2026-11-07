<?php
/**
 * Search form.
 *
 * @package Red_Feather_Club
 */

$rfc_search_id = 'search-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="form-label" for="<?php echo esc_attr( $rfc_search_id ); ?>"><?php esc_html_e( 'Search the site', 'red-feather-club' ); ?></label>
	<div style="display:flex; gap:0.5rem;">
		<input type="search" id="<?php echo esc_attr( $rfc_search_id ); ?>" class="form-input" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"
			placeholder="<?php esc_attr_e( 'Search…', 'red-feather-club' ); ?>" />
		<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Search', 'red-feather-club' ); ?></button>
	</div>
</form>
