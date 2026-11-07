<?php
/**
 * Header: <head>, fixed nav bar and mobile drawer.
 *
 * @package Red_Feather_Club
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'red-feather-club' ); ?></a>

<!-- ===================== NAV ===================== -->
<nav class="nav" aria-label="<?php esc_attr_e( 'Main navigation', 'red-feather-club' ); ?>">
	<a class="nav__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php rfc_nav_emblem(); ?>
		<div class="nav__title">
			<?php echo esc_html( get_theme_mod( 'rfc_nav_title', get_bloginfo( 'name' ) ) ); ?>
			<span><?php echo esc_html( get_theme_mod( 'rfc_nav_tagline', get_bloginfo( 'description' ) ) ); ?></span>
		</div>
	</a>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'nav__links',
			'depth'          => 2,
			'fallback_cb'    => 'rfc_primary_menu_fallback',
		)
	);
	?>

	<button class="nav__burger" aria-label="<?php esc_attr_e( 'Toggle menu', 'red-feather-club' ); ?>" aria-expanded="false" aria-controls="rfc-mobile-nav">
		<span></span><span></span><span></span>
	</button>
</nav>

<nav id="rfc-mobile-nav" class="nav__mobile" aria-label="<?php esc_attr_e( 'Mobile navigation', 'red-feather-club' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'walker'         => new RFC_Mobile_Nav_Walker(),
			'depth'          => 2,
			'fallback_cb'    => 'rfc_mobile_menu_fallback',
		)
	);
	?>
</nav>

<div id="content" class="site-content">
