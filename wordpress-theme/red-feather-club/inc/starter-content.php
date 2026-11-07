<?php
/**
 * Starter content — the site's page structure and menus on a fresh install.
 *
 * WordPress applies this only to a brand-new site (no posts of its own and no
 * saved Customizer changes), when the Customizer is first opened. It is a
 * starting point, not a demo importer: everything here is ordinary editable
 * content once published.
 *
 * @package Red_Feather_Club
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register starter content.
 */
function rfc_starter_content() {
	$home_content = '<!-- wp:pattern {"slug":"red-feather-club/section-header"} /-->'
		. '<!-- wp:pattern {"slug":"red-feather-club/intro-cards"} /-->'
		. '<!-- wp:pattern {"slug":"red-feather-club/cta-band"} /-->'
		. '<!-- wp:pattern {"slug":"red-feather-club/visit-cards"} /-->';

	add_theme_support(
		'starter_content',
		array(
			'posts' => array(
				'home'    => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'Home', 'Theme starter content', 'red-feather-club' ),
					'post_content' => $home_content,
				),
				'history' => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'Our Past', 'Theme starter content', 'red-feather-club' ),
					'post_excerpt' => _x( 'The history of the 95th Bomb Group — 334 missions, three Distinguished Unit Citations, and the first daylight raid on Berlin.', 'Theme starter content', 'red-feather-club' ),
					'post_content' => '<!-- wp:pattern {"slug":"red-feather-club/stat-block"} /-->'
						. '<!-- wp:pattern {"slug":"red-feather-club/timeline"} /-->'
						. '<!-- wp:pattern {"slug":"red-feather-club/pull-quote"} /-->',
				),
				'museum'  => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'The Museum', 'Theme starter content', 'red-feather-club' ),
					'post_excerpt' => _x( 'A faithfully restored 1940s NCO’s social club at Horham, Suffolk.', 'Theme starter content', 'red-feather-club' ),
					'post_content' => '<!-- wp:pattern {"slug":"red-feather-club/feature-list"} /-->',
				),
				'events'  => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'Events', 'Theme starter content', 'red-feather-club' ),
					'post_excerpt' => _x( 'Open days, evening dances and living history weekends throughout the season.', 'Theme starter content', 'red-feather-club' ),
					'post_content' => '',
					'meta_input'   => array( '_wp_page_template' => 'page-templates/template-events.php' ),
				),
				'gallery' => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'Gallery', 'Theme starter content', 'red-feather-club' ),
					'post_excerpt' => _x( 'Photographs of the site, the collections and our events.', 'Theme starter content', 'red-feather-club' ),
					'post_content' => '',
					'meta_input'   => array( '_wp_page_template' => 'page-templates/template-gallery.php' ),
				),
				'contact' => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'Find Us', 'Theme starter content', 'red-feather-club' ),
					'post_excerpt' => _x( 'Horham Road, Denham, Eye, IP21 5DG.', 'Theme starter content', 'red-feather-club' ),
					'post_content' => '<!-- wp:pattern {"slug":"red-feather-club/visit-cards"} /-->',
				),
				'support' => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'Support Us', 'Theme starter content', 'red-feather-club' ),
					'post_excerpt' => _x( 'The Red Feather Club is run entirely by volunteers.', 'Theme starter content', 'red-feather-club' ),
					'post_content' => '<!-- wp:pattern {"slug":"red-feather-club/support-cards"} /-->',
				),
				'news'    => array(
					'post_type'  => 'page',
					'post_title' => _x( 'News', 'Theme starter content', 'red-feather-club' ),
				),
			),

			'options' => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{news}}',
				'blogname'       => _x( 'Red Feather Club', 'Theme starter content', 'red-feather-club' ),
				'blogdescription' => _x( '95th Bomb Group Heritage Association', 'Theme starter content', 'red-feather-club' ),
			),

			'theme_mods' => array(
				'rfc_hero_btn1_url' => '{{events}}',
				'rfc_hero_btn2_url' => '{{museum}}',
			),

			'nav_menus' => array(
				'primary'        => array(
					'name'  => __( 'Primary', 'red-feather-club' ),
					'items' => array(
						'page_home'    => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{home}}' ),
						'page_history' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{history}}' ),
						'page_museum'  => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{museum}}' ),
						'page_events'  => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{events}}' ),
						'page_gallery' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{gallery}}' ),
						'page_contact' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{contact}}' ),
						'page_support' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{support}}' ),
					),
				),
				'footer-explore' => array(
					'name'  => __( 'Explore', 'red-feather-club' ),
					'items' => array(
						'page_history' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{history}}' ),
						'page_museum'  => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{museum}}' ),
						'page_events'  => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{events}}' ),
						'page_gallery' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{gallery}}' ),
					),
				),
				'footer-info'    => array(
					'name'  => __( 'Information', 'red-feather-club' ),
					'items' => array(
						'page_contact' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{contact}}' ),
						'page_support' => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{support}}' ),
						'page_news'    => array( 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{news}}' ),
					),
				),
			),
		)
	);
}
add_action( 'after_setup_theme', 'rfc_starter_content', 11 );
