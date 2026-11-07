<?php
/**
 * Walker for the mobile drawer.
 *
 * The drawer in this design system is a <nav> holding bare anchors, not a list,
 * so this walker drops the ul/li scaffolding WordPress would normally emit.
 *
 * @package Red_Feather_Club
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RFC_Mobile_Nav_Walker
 */
class RFC_Mobile_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * No wrapper for sub-levels — items are flattened with an indent class.
	 *
	 * @param string $output Output buffer.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * No wrapper for sub-levels.
	 *
	 * @param string $output Output buffer.
	 * @param int    $depth  Depth.
	 * @param array  $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Render a single anchor.
	 *
	 * @param string   $output      Output buffer.
	 * @param WP_Post  $data_object Menu item.
	 * @param int      $depth       Depth.
	 * @param stdClass $args        Args.
	 * @param int      $current_object_id Current object ID.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item    = $data_object;
		$classes = array();

		if ( $depth > 0 ) {
			$classes[] = 'is-sub';
		}

		$item_classes = isset( $item->classes ) ? (array) $item->classes : array();

		if ( array_intersect( array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor' ), $item_classes ) ) {
			$classes[] = 'active';
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$output .= sprintf(
			'<a href="%1$s"%2$s%3$s%4$s>%5$s</a>',
			esc_url( $item->url ),
			$classes ? ' class="' . esc_attr( implode( ' ', $classes ) ) . '"' : '',
			$item->target ? ' target="' . esc_attr( $item->target ) . '"' : '',
			$item->xfn ? ' rel="' . esc_attr( $item->xfn ) . '"' : '',
			esc_html( $title )
		);
	}

	/**
	 * Nothing to close.
	 *
	 * @param string  $output      Output buffer.
	 * @param WP_Post $data_object Menu item.
	 * @param int     $depth       Depth.
	 * @param array   $args        Args.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}
