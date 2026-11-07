<?php
/**
 * 404.
 *
 * @package Red_Feather_Club
 */

get_header();

rfc_page_hero(
	array(
		'label' => __( 'Error 404', 'red-feather-club' ),
		'title' => __( 'Page not found', 'red-feather-club' ),
		'body'  => __( 'That page has gone missing in action. Try a search, or head back to the home page.', 'red-feather-club' ),
	)
);
?>

<main id="main" class="site-main">
	<div class="section">
		<div style="max-width:520px;"><?php get_search_form(); ?></div>
		<p style="margin-top:2rem;">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the home page', 'red-feather-club' ); ?></a>
		</p>
	</div>
</main>

<?php
get_footer();
