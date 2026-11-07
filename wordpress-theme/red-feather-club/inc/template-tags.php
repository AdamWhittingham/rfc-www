<?php
/**
 * Template tags — the reusable markup of the design system.
 *
 * @package Red_Feather_Club
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline SVG icons used across the design system.
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 * @return string
 */
function rfc_icon( $name, $size = 14 ) {
	$size  = (int) $size;
	$open  = '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false" ';
	$icons = array(
		'arrow'     => $open . 'fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
		'calendar'  => $open . 'fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
		'facebook'  => $open . 'fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
		'instagram' => $open . 'fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Allowed HTML for the inline icons above.
 *
 * @return array
 */
function rfc_svg_allowed_html() {
	return array(
		'svg'      => array(
			'width'        => true,
			'height'       => true,
			'viewbox'      => true,
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
			'aria-hidden'  => true,
			'focusable'    => true,
			'class'        => true,
		),
		'path'     => array( 'd' => true, 'fill' => true, 'stroke' => true ),
		'rect'     => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true ),
		'circle'   => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true ),
		'line'     => array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ),
		'polyline' => array( 'points' => true ),
	);
}

/**
 * Print an icon.
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 */
function rfc_the_icon( $name, $size = 14 ) {
	echo wp_kses( rfc_icon( $name, $size ), rfc_svg_allowed_html() );
}

/**
 * Day / month parts for an event badge.
 *
 * @param int $post_id Event ID.
 * @return array{day:string,month:string,timestamp:int|false}
 */
function rfc_event_date_parts( $post_id ) {
	$date = get_post_meta( $post_id, '_rfc_event_date', true );
	$ts   = $date ? strtotime( $date ) : false;

	if ( ! $ts ) {
		$ts = (int) get_post_time( 'U', false, $post_id );
	}

	return array(
		'day'       => date_i18n( 'j', $ts ),
		'month'     => date_i18n( 'M', $ts ),
		'timestamp' => $ts,
	);
}

/**
 * Tag class + label for an event.
 *
 * @param int $post_id Event ID.
 * @return array{class:string,label:string}
 */
function rfc_event_tag( $post_id ) {
	$tag    = get_post_meta( $post_id, '_rfc_event_tag', true );
	$labels = array(
		'open'    => __( 'Open Day', 'red-feather-club' ),
		'evening' => __( 'Evening Event', 'red-feather-club' ),
		'special' => __( 'Special Event', 'red-feather-club' ),
	);

	if ( ! isset( $labels[ $tag ] ) ) {
		$tag = 'open';
	}

	return array(
		'class' => 'tag--' . $tag,
		'label' => $labels[ $tag ],
	);
}

/**
 * Query upcoming events.
 *
 * @param int  $limit           How many to return.
 * @param bool $include_undated Include events with no date set.
 * @return WP_Post[]
 */
function rfc_get_upcoming_events( $limit = 5, $include_undated = true ) {
	$meta_query = array(
		'relation' => 'OR',
		array(
			'key'     => '_rfc_event_date',
			'value'   => current_time( 'Y-m-d' ),
			'compare' => '>=',
			'type'    => 'DATE',
		),
	);

	if ( $include_undated ) {
		$meta_query[] = array(
			'key'     => '_rfc_event_date',
			'compare' => 'NOT EXISTS',
		);
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'rfc_event',
			'posts_per_page' => (int) $limit,
			'meta_key'       => '_rfc_event_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => $meta_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'no_found_rows'  => true,
		)
	);

	return $query->posts;
}

/**
 * Full-width event row (.event-row) — used on the Events page and archive.
 *
 * @param int|WP_Post $post Event.
 */
function rfc_event_row( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$parts = rfc_event_date_parts( $post->ID );
	$tag   = rfc_event_tag( $post->ID );
	$time  = get_post_meta( $post->ID, '_rfc_event_time', true );
	$desc  = get_the_excerpt( $post );
	?>
	<div class="event-row observe-fade">
		<div class="event-badge">
			<span class="event-badge__day"><?php echo esc_html( $parts['day'] ); ?></span>
			<span class="event-badge__month"><?php echo esc_html( $parts['month'] ); ?></span>
		</div>
		<div class="event-details">
			<span class="event-details__tag <?php echo esc_attr( $tag['class'] ); ?>"><?php echo esc_html( $tag['label'] ); ?></span>
			<div class="event-details__name">
				<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a>
			</div>
			<?php if ( $time ) : ?>
				<div class="event-details__time"><?php echo esc_html( $time ); ?></div>
			<?php endif; ?>
			<?php if ( $desc ) : ?>
				<p class="event-details__desc"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Compact event item (.event-item) — used in the homepage preview list.
 *
 * @param int|WP_Post $post Event.
 */
function rfc_event_item( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$parts = rfc_event_date_parts( $post->ID );
	$tag   = rfc_event_tag( $post->ID );
	$time  = get_post_meta( $post->ID, '_rfc_event_time', true );
	?>
	<a class="event-item" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
		<div class="event-date">
			<span class="event-date__day"><?php echo esc_html( $parts['day'] ); ?></span>
			<span class="event-date__mon"><?php echo esc_html( $parts['month'] ); ?></span>
		</div>
		<div class="event-info">
			<div class="event-info__type"><?php echo esc_html( $tag['label'] ); ?></div>
			<div class="event-info__name"><?php echo esc_html( get_the_title( $post ) ); ?></div>
			<?php if ( $time ) : ?>
				<div class="event-info__time"><?php echo esc_html( $time ); ?></div>
			<?php endif; ?>
		</div>
	</a>
	<?php
}

/**
 * Sub-page hero band (.page-hero).
 *
 * @param array $args label, title, body, image (URL).
 */
function rfc_page_hero( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'label' => '',
			'title' => '',
			'body'  => '',
			'image' => '',
		)
	);
	?>
	<div class="page-hero">
		<?php if ( $args['image'] ) : ?>
			<div class="page-hero__bg" style="background-image:url('<?php echo esc_url( $args['image'] ); ?>')"></div>
		<?php endif; ?>
		<div class="page-hero__inner">
			<?php if ( $args['label'] ) : ?>
				<div class="section-label"><?php echo esc_html( $args['label'] ); ?></div>
			<?php endif; ?>
			<h1 class="section-title"><?php echo esc_html( $args['title'] ); ?></h1>
			<?php if ( $args['body'] ) : ?>
				<p class="section-body"><?php echo wp_kses_post( $args['body'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Post meta line.
 */
function rfc_posted_on() {
	$categories = get_the_category_list( ', ' );
	?>
	<div class="entry-meta">
		<span class="entry-meta__date"><?php echo esc_html( get_the_date() ); ?></span>
		<?php if ( $categories ) : ?>
			<span class="entry-meta__cats"><?php echo wp_kses_post( $categories ); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Footer / nav social buttons, driven by the Customizer.
 *
 * @param string $class Wrapper class.
 */
function rfc_social_links( $class = 'footer__social' ) {
	$links = array(
		'facebook'  => get_theme_mod( 'rfc_facebook_url', 'https://www.facebook.com/people/95th-Bomb-Group-Heritage/61554777124268/' ),
		'instagram' => get_theme_mod( 'rfc_instagram_url', 'https://www.instagram.com/95thbombgroupheritage/' ),
	);

	$labels = array(
		'facebook'  => __( 'Facebook', 'red-feather-club' ),
		'instagram' => __( 'Instagram', 'red-feather-club' ),
	);

	$links = array_filter( $links );
	if ( ! $links ) {
		return;
	}
	?>
	<div class="<?php echo esc_attr( $class ); ?>">
		<?php foreach ( $links as $network => $url ) : ?>
			<a class="footer__social-btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"
				aria-label="<?php echo esc_attr( $labels[ $network ] ); ?>">
				<?php rfc_the_icon( $network, 16 ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Gallery photo tile (.photo-item) for the rfc_photo post type.
 *
 * @param int|WP_Post $post Photo.
 */
function rfc_photo_item( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$full  = get_the_post_thumbnail_url( $post, 'full' );
	$terms = get_the_terms( $post, 'rfc_photo_category' );
	$slugs = array();

	if ( $terms && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$slugs[] = $term->slug;
		}
	}

	$caption = get_the_excerpt( $post );
	if ( ! $caption ) {
		$caption = get_the_title( $post );
	}
	?>
	<div class="photo-item observe-fade" data-category="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>"
		<?php if ( $full ) : ?>data-lightbox="<?php echo esc_url( $full ); ?>"<?php endif; ?>>
		<?php
		if ( has_post_thumbnail( $post ) ) {
			echo get_the_post_thumbnail( $post, 'rfc-card', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title( $post ) ) ) );
		} else {
			?>
			<div class="photo-placeholder">
				<div class="photo-placeholder__icon">&#128247;</div>
				<div class="photo-placeholder__label"><?php echo esc_html( get_the_title( $post ) ); ?></div>
			</div>
			<?php
		}
		?>
		<span class="photo-caption"><?php echo esc_html( $caption ); ?></span>
	</div>
	<?php
}

/**
 * Fallback for the desktop nav when no menu has been assigned yet.
 */
function rfc_primary_menu_fallback() {
	echo '<ul class="nav__links">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
		)
	);
	echo '</ul>';
}

/**
 * Fallback for the mobile drawer when no menu has been assigned yet.
 */
function rfc_mobile_menu_fallback() {
	$pages = get_pages( array( 'sort_column' => 'menu_order,post_title' ) );

	foreach ( $pages as $page ) {
		printf(
			'<a href="%1$s">%2$s</a>',
			esc_url( get_permalink( $page ) ),
			esc_html( get_the_title( $page ) )
		);
	}
}

/**
 * The nav emblem: custom logo if set, otherwise the 95 BG roundel text.
 */
function rfc_nav_emblem() {
	echo '<div class="nav__emblem">';

	if ( has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => esc_attr( get_bloginfo( 'name' ) ) ) );
	} else {
		$text  = get_theme_mod( 'rfc_emblem_text', '95 BG' );
		$parts = preg_split( '/\s+/', trim( $text ), 2 );
		echo '<div class="nav__emblem-placeholder">' . esc_html( $parts[0] );
		if ( isset( $parts[1] ) ) {
			echo '<br>' . esc_html( $parts[1] );
		}
		echo '</div>';
	}

	echo '</div>';
}

/**
 * One footer menu column. The column heading uses the assigned menu's name.
 *
 * @param string $location      Registered menu location.
 * @param string $default_title Heading to use when the menu has no name.
 */
function rfc_footer_menu( $location, $default_title ) {
	$locations = get_nav_menu_locations();
	$title     = $default_title;

	if ( ! empty( $locations[ $location ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $location ] );
		if ( $menu ) {
			$title = $menu->name;
		}
	} elseif ( ! has_nav_menu( $location ) ) {
		return;
	}

	echo '<div>';
	echo '<div class="footer__col-title">' . esc_html( $title ) . '</div>';

	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'items_wrap'     => '<ul class="footer__links">%3$s</ul>',
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);

	echo '</div>';
}
