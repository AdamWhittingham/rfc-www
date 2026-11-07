<?php
/**
 * Homepage hero.
 *
 * @package Red_Feather_Club
 */

$rfc_hero_image = get_theme_mod( 'rfc_hero_image' );
$rfc_kicker     = get_theme_mod( 'rfc_hero_kicker', 'Horham, Suffolk · Est. 1943' );
$rfc_title      = get_theme_mod( 'rfc_hero_title', 'The <em>Red Feather Club</em><br>Horham' );
$rfc_subtitle   = get_theme_mod( 'rfc_hero_subtitle', '' );
$rfc_btn1_text  = get_theme_mod( 'rfc_hero_btn1_text', '' );
$rfc_btn1_url   = get_theme_mod( 'rfc_hero_btn1_url', '' );
$rfc_btn2_text  = get_theme_mod( 'rfc_hero_btn2_text', '' );
$rfc_btn2_url   = get_theme_mod( 'rfc_hero_btn2_url', '' );
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Welcome', 'red-feather-club' ); ?>">
	<div class="hero__img-fallback">
		<?php if ( $rfc_hero_image ) : ?>
			<img class="hero__img" src="<?php echo esc_url( $rfc_hero_image ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" fetchpriority="high" />
		<?php else : ?>
			<?php esc_html_e( 'Set a hero image in Customizer → Red Feather Club → Homepage Hero', 'red-feather-club' ); ?>
		<?php endif; ?>
	</div>

	<div class="hero__overlay"></div>

	<div class="hero__content">
		<?php if ( $rfc_kicker ) : ?>
			<p class="hero__kicker fade-up fade-up--1"><?php echo esc_html( $rfc_kicker ); ?></p>
		<?php endif; ?>

		<h1 class="hero__title fade-up fade-up--2">
			<?php echo wp_kses( $rfc_title, array( 'em' => array(), 'br' => array(), 'strong' => array(), 'span' => array( 'class' => array() ) ) ); ?>
		</h1>

		<?php if ( $rfc_subtitle ) : ?>
			<p class="hero__subtitle fade-up fade-up--3"><?php echo esc_html( $rfc_subtitle ); ?></p>
		<?php endif; ?>

		<div class="hero__stats fade-up fade-up--3">
			<?php
			for ( $rfc_i = 1; $rfc_i <= 3; $rfc_i++ ) :
				$rfc_num   = get_theme_mod( "rfc_hero_stat{$rfc_i}_num" );
				$rfc_label = get_theme_mod( "rfc_hero_stat{$rfc_i}_label" );

				if ( ! $rfc_num && ! $rfc_label ) {
					continue;
				}
				?>
				<div>
					<div class="hero__stat-num"><?php echo esc_html( $rfc_num ); ?></div>
					<div class="hero__stat-label"><?php echo esc_html( $rfc_label ); ?></div>
				</div>
			<?php endfor; ?>
		</div>

		<?php if ( $rfc_btn1_text || $rfc_btn2_text ) : ?>
			<div class="hero__actions fade-up fade-up--4">
				<?php if ( $rfc_btn1_text ) : ?>
					<a href="<?php echo esc_url( $rfc_btn1_url ); ?>" class="btn btn--primary">
						<?php rfc_the_icon( 'calendar' ); ?>
						<?php echo esc_html( $rfc_btn1_text ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $rfc_btn2_text ) : ?>
					<a href="<?php echo esc_url( $rfc_btn2_url ); ?>" class="btn btn--outline"><?php echo esc_html( $rfc_btn2_text ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
