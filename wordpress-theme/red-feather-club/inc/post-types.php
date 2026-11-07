<?php
/**
 * Custom content types: Events and Gallery Photos.
 *
 * These back the .event-row / .photo-item components so the site's recurring
 * content can be edited in wp-admin instead of hand-written HTML.
 *
 * @package Red_Feather_Club
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register post types and taxonomies.
 */
function rfc_register_post_types() {
	register_post_type(
		'rfc_event',
		array(
			'labels'        => array(
				'name'               => __( 'Events', 'red-feather-club' ),
				'singular_name'      => __( 'Event', 'red-feather-club' ),
				'add_new_item'       => __( 'Add New Event', 'red-feather-club' ),
				'edit_item'          => __( 'Edit Event', 'red-feather-club' ),
				'new_item'           => __( 'New Event', 'red-feather-club' ),
				'view_item'          => __( 'View Event', 'red-feather-club' ),
				'search_items'       => __( 'Search Events', 'red-feather-club' ),
				'not_found'          => __( 'No events found', 'red-feather-club' ),
				'all_items'          => __( 'All Events', 'red-feather-club' ),
				'menu_name'          => __( 'Events', 'red-feather-club' ),
			),
			'public'        => true,
			'has_archive'   => 'events',
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 20,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'rewrite'       => array( 'slug' => 'events' ),
			'show_in_rest'  => true,
		)
	);

	register_post_type(
		'rfc_photo',
		array(
			'labels'        => array(
				'name'          => __( 'Gallery Photos', 'red-feather-club' ),
				'singular_name' => __( 'Gallery Photo', 'red-feather-club' ),
				'add_new_item'  => __( 'Add New Photo', 'red-feather-club' ),
				'edit_item'     => __( 'Edit Photo', 'red-feather-club' ),
				'all_items'     => __( 'All Photos', 'red-feather-club' ),
				'menu_name'     => __( 'Gallery', 'red-feather-club' ),
			),
			'public'        => true,
			'has_archive'   => 'gallery',
			'menu_icon'     => 'dashicons-format-gallery',
			'menu_position' => 21,
			'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
			'rewrite'       => array( 'slug' => 'gallery' ),
			'show_in_rest'  => true,
		)
	);

	register_taxonomy(
		'rfc_photo_category',
		'rfc_photo',
		array(
			'labels'            => array(
				'name'          => __( 'Photo Categories', 'red-feather-club' ),
				'singular_name' => __( 'Photo Category', 'red-feather-club' ),
				'menu_name'     => __( 'Categories', 'red-feather-club' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'gallery-category' ),
		)
	);
}
add_action( 'init', 'rfc_register_post_types' );

/**
 * Event meta fields, exposed to the REST API so the block editor keeps them.
 */
function rfc_register_event_meta() {
	$fields = array(
		'_rfc_event_date' => 'string',
		'_rfc_event_end'  => 'string',
		'_rfc_event_time' => 'string',
		'_rfc_event_tag'  => 'string',
	);

	foreach ( $fields as $key => $type ) {
		register_post_meta(
			'rfc_event',
			$key,
			array(
				'type'              => $type,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'rfc_register_event_meta' );

/**
 * Event details meta box.
 */
function rfc_event_meta_box() {
	add_meta_box(
		'rfc_event_details',
		__( 'Event Details', 'red-feather-club' ),
		'rfc_event_meta_box_render',
		'rfc_event',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'rfc_event_meta_box' );

/**
 * Render the event meta box.
 *
 * @param WP_Post $post Current post.
 */
function rfc_event_meta_box_render( $post ) {
	wp_nonce_field( 'rfc_save_event_meta', 'rfc_event_meta_nonce' );

	$date = get_post_meta( $post->ID, '_rfc_event_date', true );
	$end  = get_post_meta( $post->ID, '_rfc_event_end', true );
	$time = get_post_meta( $post->ID, '_rfc_event_time', true );
	$tag  = get_post_meta( $post->ID, '_rfc_event_tag', true );

	$tags = array(
		'open'    => __( 'Open Day (green)', 'red-feather-club' ),
		'evening' => __( 'Evening Event (red)', 'red-feather-club' ),
		'special' => __( 'Special Event (gold)', 'red-feather-club' ),
	);
	?>
	<p>
		<label for="rfc_event_date"><strong><?php esc_html_e( 'Date', 'red-feather-club' ); ?></strong></label><br />
		<input type="date" id="rfc_event_date" name="rfc_event_date" value="<?php echo esc_attr( $date ); ?>" class="widefat" />
	</p>
	<p>
		<label for="rfc_event_end"><strong><?php esc_html_e( 'End date (optional)', 'red-feather-club' ); ?></strong></label><br />
		<input type="date" id="rfc_event_end" name="rfc_event_end" value="<?php echo esc_attr( $end ); ?>" class="widefat" />
	</p>
	<p>
		<label for="rfc_event_time"><strong><?php esc_html_e( 'Time / entry line', 'red-feather-club' ); ?></strong></label><br />
		<input type="text" id="rfc_event_time" name="rfc_event_time" value="<?php echo esc_attr( $time ); ?>" class="widefat"
			placeholder="<?php esc_attr_e( '10am – 4pm · Free Entry', 'red-feather-club' ); ?>" />
	</p>
	<p>
		<label for="rfc_event_tag"><strong><?php esc_html_e( 'Tag', 'red-feather-club' ); ?></strong></label><br />
		<select id="rfc_event_tag" name="rfc_event_tag" class="widefat">
			<?php foreach ( $tags as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $tag, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php
}

/**
 * Persist event meta.
 *
 * @param int $post_id Post ID.
 */
function rfc_save_event_meta( $post_id ) {
	if ( ! isset( $_POST['rfc_event_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['rfc_event_meta_nonce'] ) ), 'rfc_save_event_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$map = array(
		'_rfc_event_date' => 'rfc_event_date',
		'_rfc_event_end'  => 'rfc_event_end',
		'_rfc_event_time' => 'rfc_event_time',
		'_rfc_event_tag'  => 'rfc_event_tag',
	);

	foreach ( $map as $meta_key => $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
			if ( '' === $value ) {
				delete_post_meta( $post_id, $meta_key );
			} else {
				update_post_meta( $post_id, $meta_key, $value );
			}
		}
	}
}
add_action( 'save_post_rfc_event', 'rfc_save_event_meta' );

/**
 * Order the events archive by event date, soonest first.
 *
 * @param WP_Query $query Query object.
 */
function rfc_events_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'rfc_event' ) ) {
		$query->set( 'meta_key', '_rfc_event_date' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', 50 );
	}

	if ( $query->is_post_type_archive( 'rfc_photo' ) || $query->is_tax( 'rfc_photo_category' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
		$query->set( 'posts_per_page', 60 );
	}
}
add_action( 'pre_get_posts', 'rfc_events_query' );

/**
 * Show the event date in the admin list table.
 *
 * @param array $columns Columns.
 * @return array
 */
function rfc_event_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['rfc_event_date'] = __( 'Event Date', 'red-feather-club' );
			$new['rfc_event_tag']  = __( 'Tag', 'red-feather-club' );
		}
	}
	return $new;
}
add_filter( 'manage_rfc_event_posts_columns', 'rfc_event_columns' );

/**
 * Populate the custom admin columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function rfc_event_column_content( $column, $post_id ) {
	if ( 'rfc_event_date' === $column ) {
		$date = get_post_meta( $post_id, '_rfc_event_date', true );
		echo $date ? esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) ) : '—';
	}
	if ( 'rfc_event_tag' === $column ) {
		$tag = get_post_meta( $post_id, '_rfc_event_tag', true );
		echo $tag ? esc_html( ucfirst( $tag ) ) : '—';
	}
}
add_action( 'manage_rfc_event_posts_custom_column', 'rfc_event_column_content', 10, 2 );

/**
 * Flush rewrite rules once on theme activation so /events and /gallery resolve.
 */
function rfc_flush_rewrites() {
	rfc_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'rfc_flush_rewrites' );
