<?php
/**
 * Customizer — every piece of chrome the flat-file site hard-coded.
 *
 * @package Red_Feather_Club
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allow a little inline markup (em / br / strong) in headline fields.
 *
 * @param string $value Raw value.
 * @return string
 */
function rfc_sanitize_headline( $value ) {
	return wp_kses(
		$value,
		array(
			'em'     => array(),
			'strong' => array(),
			'br'     => array(),
			'span'   => array( 'class' => array() ),
		)
	);
}

/**
 * Checkbox sanitiser.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function rfc_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Register Customizer panels, sections and settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function rfc_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_panel(
		'rfc_panel',
		array(
			'title'       => __( 'Red Feather Club', 'red-feather-club' ),
			'description' => __( 'Hero, announcement strip, footer and social settings for this theme.', 'red-feather-club' ),
			'priority'    => 30,
		)
	);

	/* ---------- Identity ---------- */
	$wp_customize->add_section(
		'rfc_identity',
		array(
			'title' => __( 'Nav & Emblem', 'red-feather-club' ),
			'panel' => 'rfc_panel',
		)
	);

	$fields = array(
		'rfc_nav_title'    => array( 'Nav title', 'Red Feather Club', 'sanitize_text_field' ),
		'rfc_nav_tagline'  => array( 'Nav tagline (small line under the title)', '95th Bomb Group Heritage Association', 'sanitize_text_field' ),
		'rfc_emblem_text'  => array( 'Emblem fallback text (used when no logo is set)', '95 BG', 'sanitize_text_field' ),
	);

	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $field[1],
				'sanitize_callback' => $field[2],
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $field[0],
				'section' => 'rfc_identity',
				'type'    => 'text',
			)
		);
	}

	/* ---------- Homepage hero ---------- */
	$wp_customize->add_section(
		'rfc_hero',
		array(
			'title'       => __( 'Homepage Hero', 'red-feather-club' ),
			'description' => __( 'Shown by the front page template. Leave the image empty for the olive-drab gradient fallback.', 'red-feather-club' ),
			'panel'       => 'rfc_panel',
		)
	);

	$wp_customize->add_setting(
		'rfc_hero_image',
		array( 'sanitize_callback' => 'esc_url_raw' )
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'rfc_hero_image',
			array(
				'label'   => __( 'Hero image', 'red-feather-club' ),
				'section' => 'rfc_hero',
			)
		)
	);

	$hero_fields = array(
		'rfc_hero_kicker'   => array( __( 'Kicker', 'red-feather-club' ), 'Horham, Suffolk · Est. 1943', 'sanitize_text_field', 'text' ),
		'rfc_hero_title'    => array( __( 'Title (allows <em> and <br>)', 'red-feather-club' ), 'The <em>Red Feather Club</em><br>Horham', 'rfc_sanitize_headline', 'textarea' ),
		'rfc_hero_subtitle' => array( __( 'Subtitle', 'red-feather-club' ), 'Home of the 95th Bomb Group Heritage Association — a living museum and monument to the men of the Eighth Air Force', 'sanitize_text_field', 'textarea' ),
	);

	foreach ( $hero_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array( 'default' => $field[1], 'sanitize_callback' => $field[2] ) );
		$wp_customize->add_control( $id, array( 'label' => $field[0], 'section' => 'rfc_hero', 'type' => $field[3] ) );
	}

	$stat_defaults = array(
		1 => array( '334', __( 'Missions Flown', 'red-feather-club' ) ),
		2 => array( '3×', __( 'Distinguished Unit Citation', 'red-feather-club' ) ),
		3 => array( '425', __( 'Enemy Aircraft Destroyed', 'red-feather-club' ) ),
	);

	foreach ( $stat_defaults as $i => $default ) {
		$wp_customize->add_setting( "rfc_hero_stat{$i}_num", array( 'default' => $default[0], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			"rfc_hero_stat{$i}_num",
			array(
				/* translators: %d: stat number. */
				'label'   => sprintf( __( 'Stat %d — figure', 'red-feather-club' ), $i ),
				'section' => 'rfc_hero',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting( "rfc_hero_stat{$i}_label", array( 'default' => $default[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			"rfc_hero_stat{$i}_label",
			array(
				/* translators: %d: stat number. */
				'label'   => sprintf( __( 'Stat %d — label', 'red-feather-club' ), $i ),
				'section' => 'rfc_hero',
				'type'    => 'text',
			)
		);
	}

	$buttons = array(
		'rfc_hero_btn1_text' => array( __( 'Primary button text', 'red-feather-club' ), 'Forthcoming Events', 'sanitize_text_field' ),
		'rfc_hero_btn1_url'  => array( __( 'Primary button URL', 'red-feather-club' ), '/events/', 'esc_url_raw' ),
		'rfc_hero_btn2_text' => array( __( 'Secondary button text', 'red-feather-club' ), 'Explore the Museum', 'sanitize_text_field' ),
		'rfc_hero_btn2_url'  => array( __( 'Secondary button URL', 'red-feather-club' ), '/the-museum/', 'esc_url_raw' ),
	);

	foreach ( $buttons as $id => $field ) {
		$wp_customize->add_setting( $id, array( 'default' => $field[1], 'sanitize_callback' => $field[2] ) );
		$wp_customize->add_control( $id, array( 'label' => $field[0], 'section' => 'rfc_hero', 'type' => 'text' ) );
	}

	/* ---------- Announcement strip ---------- */
	$wp_customize->add_section(
		'rfc_strip',
		array(
			'title'       => __( 'Announcement Strip', 'red-feather-club' ),
			'description' => __( 'The olive band directly beneath the hero.', 'red-feather-club' ),
			'panel'       => 'rfc_panel',
		)
	);

	$wp_customize->add_setting( 'rfc_strip_enabled', array( 'default' => true, 'sanitize_callback' => 'rfc_sanitize_checkbox' ) );
	$wp_customize->add_control( 'rfc_strip_enabled', array( 'label' => __( 'Show the strip', 'red-feather-club' ), 'section' => 'rfc_strip', 'type' => 'checkbox' ) );

	$strip_fields = array(
		'rfc_strip_text'     => array( __( 'Text (allows <strong>)', 'red-feather-club' ), '🎽 <strong>Official Merch Now Available</strong> — Support the museum by shopping our online store', 'rfc_sanitize_headline' ),
		'rfc_strip_btn_text' => array( __( 'Button text', 'red-feather-club' ), 'Shop Now →', 'sanitize_text_field' ),
		'rfc_strip_btn_url'  => array( __( 'Button URL', 'red-feather-club' ), 'https://empireclothingstore.com/95th-bg-heritage-museum/', 'esc_url_raw' ),
	);

	foreach ( $strip_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array( 'default' => $field[1], 'sanitize_callback' => $field[2] ) );
		$wp_customize->add_control( $id, array( 'label' => $field[0], 'section' => 'rfc_strip', 'type' => 'text' ) );
	}

	/* ---------- Footer ---------- */
	$wp_customize->add_section(
		'rfc_footer',
		array(
			'title' => __( 'Footer', 'red-feather-club' ),
			'panel' => 'rfc_panel',
		)
	);

	$footer_fields = array(
		'rfc_footer_desc'    => array( __( 'Footer description', 'red-feather-club' ), 'A living museum and monument to the men of the 95th Bomb Group, Eighth Air Force — the only group to receive the Distinguished Unit Citation three times. Operated by volunteers from a faithfully restored site at Horham, Suffolk.', 'sanitize_textarea_field', 'textarea' ),
		'rfc_footer_address' => array( __( 'Address line', 'red-feather-club' ), 'Horham Road, Denham, Eye, IP21 5DG', 'sanitize_text_field', 'text' ),
		'rfc_footer_charity' => array( __( 'Registered charity line', 'red-feather-club' ), 'Registered Charity No. 1119769', 'sanitize_text_field', 'text' ),
		'rfc_footer_legal'   => array( __( 'Legal line', 'red-feather-club' ), 'Any unauthorised reproduction constitutes an infringement of copyright.', 'sanitize_text_field', 'text' ),
	);

	foreach ( $footer_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array( 'default' => $field[1], 'sanitize_callback' => $field[2] ) );
		$wp_customize->add_control( $id, array( 'label' => $field[0], 'section' => 'rfc_footer', 'type' => $field[3] ) );
	}

	/* ---------- Social ---------- */
	$wp_customize->add_section(
		'rfc_social',
		array(
			'title' => __( 'Social Links', 'red-feather-club' ),
			'panel' => 'rfc_panel',
		)
	);

	$social_fields = array(
		'rfc_facebook_url'  => array( __( 'Facebook URL', 'red-feather-club' ), 'https://www.facebook.com/people/95th-Bomb-Group-Heritage/61554777124268/' ),
		'rfc_instagram_url' => array( __( 'Instagram URL', 'red-feather-club' ), 'https://www.instagram.com/95thbombgroupheritage/' ),
	);

	foreach ( $social_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array( 'default' => $field[1], 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( $id, array( 'label' => $field[0], 'section' => 'rfc_social', 'type' => 'url' ) );
	}

	/* ---------- Selective refresh ---------- */
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'rfc_hero_title',
			array(
				'selector'        => '.hero__title',
				'render_callback' => function () {
					return rfc_sanitize_headline( get_theme_mod( 'rfc_hero_title', 'The <em>Red Feather Club</em><br>Horham' ) );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'rfc_footer_desc',
			array(
				'selector'        => '.footer__brand-desc',
				'render_callback' => function () {
					return esc_html( get_theme_mod( 'rfc_footer_desc', '' ) );
				},
			)
		);
	}
}
add_action( 'customize_register', 'rfc_customize_register' );

/**
 * Live-preview script for the title/description postMessage settings.
 */
function rfc_customize_preview_js() {
	wp_enqueue_script( 'rfc-customizer', RFC_URI . '/assets/js/customizer.js', array( 'customize-preview' ), RFC_VERSION, true );
}
add_action( 'customize_preview_init', 'rfc_customize_preview_js' );
