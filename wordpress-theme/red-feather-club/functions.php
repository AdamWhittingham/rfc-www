<?php
/**
 * Red Feather Club — theme functions and definitions.
 *
 * @package Red_Feather_Club
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RFC_VERSION', '1.0.0' );
define( 'RFC_DIR', get_template_directory() );
define( 'RFC_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function rfc_setup() {
	load_theme_textdomain( 'red-feather-club', RFC_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	// The nav emblem is a 42px roundel — square logos work best.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 160,
			'width'       => 160,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/editor-style.css' );

	add_image_size( 'rfc-hero', 2000, 1200, true );
	add_image_size( 'rfc-card', 900, 560, true );

	register_nav_menus(
		array(
			'primary'        => __( 'Primary Navigation (nav bar + mobile drawer)', 'red-feather-club' ),
			'footer-explore' => __( 'Footer — Explore', 'red-feather-club' ),
			'footer-info'    => __( 'Footer — Information', 'red-feather-club' ),
			'footer-connect' => __( 'Footer — Connect', 'red-feather-club' ),
		)
	);
}
add_action( 'after_setup_theme', 'rfc_setup' );

/**
 * Content width, used by WordPress for oEmbeds and large images.
 */
function rfc_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'rfc_content_width', 780 );
}
add_action( 'after_setup_theme', 'rfc_content_width', 0 );

/**
 * Front-end styles and scripts.
 */
function rfc_enqueue_assets() {
	wp_enqueue_style(
		'rfc-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Barlow+Condensed:wght@300;400;600;700;900&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'rfc-style', get_stylesheet_uri(), array( 'rfc-fonts' ), RFC_VERSION );

	wp_enqueue_script( 'rfc-main', RFC_URI . '/assets/js/main.js', array(), RFC_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rfc_enqueue_assets' );

/**
 * Preconnect to the Google Fonts hosts.
 */
function rfc_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && wp_style_is( 'rfc-fonts', 'queue' ) ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'rfc_resource_hints', 10, 2 );

/**
 * Editor assets — give the block editor the same fonts as the front end.
 */
function rfc_editor_assets() {
	wp_enqueue_style(
		'rfc-fonts-editor',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Barlow+Condensed:wght@300;400;600;700;900&display=swap',
		array(),
		null
	);
}
add_action( 'enqueue_block_editor_assets', 'rfc_editor_assets' );

/**
 * Widget areas.
 */
function rfc_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Page Sidebar', 'red-feather-club' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Shown beside posts and pages using the "With Sidebar" template.', 'red-feather-club' ),
			'before_widget' => '<section id="%1$s" class="widget sidebar-card %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title sidebar-card__title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Extra', 'red-feather-club' ),
			'id'            => 'footer-extra',
			'description'   => __( 'Optional widgets appended to the footer columns.', 'red-feather-club' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title footer__col-title">',
			'after_title'   => '</div>',
		)
	);
}
add_action( 'widgets_init', 'rfc_widgets_init' );

/**
 * Move menu item classes onto the anchor, where this design system expects them.
 *
 * The flat-file site styles `.nav__links a.active` and `.nav__cta` on the link
 * itself; WordPress puts menu classes on the <li>.
 */
function rfc_nav_link_attributes( $atts, $item, $args ) {
	if ( empty( $args->theme_location ) || ! in_array( $args->theme_location, array( 'primary' ), true ) ) {
		return $atts;
	}

	$classes = array();

	if ( ! empty( $item->classes ) && is_array( $item->classes ) ) {
		// Let editors flag the "Support Us" style button with a nav__cta CSS class.
		if ( in_array( 'nav__cta', $item->classes, true ) ) {
			$classes[] = 'nav__cta';
		}
		if ( array_intersect( array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_ancestor' ), $item->classes ) ) {
			$classes[] = 'active';
		}
	}

	if ( $classes ) {
		$atts['class'] = trim( ( isset( $atts['class'] ) ? $atts['class'] . ' ' : '' ) . implode( ' ', $classes ) );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'rfc_nav_link_attributes', 10, 3 );

/**
 * Excerpt tuning.
 */
function rfc_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'rfc_excerpt_length' );

function rfc_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'rfc_excerpt_more' );

/**
 * Body classes.
 */
function rfc_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'rfc_body_classes' );

/**
 * Gallery/image blocks feed the site lightbox.
 */
function rfc_lightbox_body_class( $classes ) {
	$classes[] = 'has-rfc-lightbox';
	return $classes;
}
add_filter( 'body_class', 'rfc_lightbox_body_class' );

/**
 * Block pattern category for the theme's own patterns in /patterns.
 */
function rfc_register_pattern_category() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'red-feather-club',
			array( 'label' => __( 'Red Feather Club', 'red-feather-club' ) )
		);
	}
}
add_action( 'init', 'rfc_register_pattern_category' );

require RFC_DIR . '/inc/template-tags.php';
require RFC_DIR . '/inc/post-types.php';
require RFC_DIR . '/inc/customizer.php';
require RFC_DIR . '/inc/class-rfc-mobile-walker.php';
require RFC_DIR . '/inc/starter-content.php';
