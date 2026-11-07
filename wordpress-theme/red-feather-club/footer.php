<?php
/**
 * Footer, lightbox and wp_footer.
 *
 * @package Red_Feather_Club
 */

?>
</div><!-- #content -->

<!-- ===================== FOOTER ===================== -->
<footer class="footer" aria-label="<?php esc_attr_e( 'Site footer', 'red-feather-club' ); ?>">
	<div class="footer__inner">
		<div>
			<div class="footer__brand-name"><?php echo esc_html( get_theme_mod( 'rfc_nav_title', get_bloginfo( 'name' ) ) ); ?></div>
			<div class="footer__brand-sub"><?php echo esc_html( get_theme_mod( 'rfc_nav_tagline', get_bloginfo( 'description' ) ) ); ?></div>
			<?php $footer_desc = get_theme_mod( 'rfc_footer_desc' ); ?>
			<?php if ( $footer_desc ) : ?>
				<p class="footer__brand-desc"><?php echo esc_html( $footer_desc ); ?></p>
			<?php endif; ?>
			<?php rfc_social_links(); ?>
		</div>

		<?php
		rfc_footer_menu( 'footer-explore', __( 'Explore', 'red-feather-club' ) );
		rfc_footer_menu( 'footer-info', __( 'Information', 'red-feather-club' ) );
		rfc_footer_menu( 'footer-connect', __( 'Connect', 'red-feather-club' ) );

		if ( is_active_sidebar( 'footer-extra' ) ) {
			echo '<div>';
			dynamic_sidebar( 'footer-extra' );
			echo '</div>';
		}
		?>
	</div>

	<div class="footer__bottom">
		<p>
			<?php
			printf(
				/* translators: 1: year, 2: site name. */
				esc_html__( '© %1$s %2$s', 'red-feather-club' ),
				esc_html( gmdate( 'Y' ) ),
				esc_html( get_theme_mod( 'rfc_nav_tagline', get_bloginfo( 'name' ) ) )
			);
			$charity = get_theme_mod( 'rfc_footer_charity' );
			if ( $charity ) :
				?>
				· <span class="footer__charity"><?php echo esc_html( $charity ); ?></span>
			<?php endif; ?>
		</p>
		<?php $address = get_theme_mod( 'rfc_footer_address' ); ?>
		<?php if ( $address ) : ?>
			<p><?php echo esc_html( $address ); ?></p>
		<?php endif; ?>
		<?php $legal = get_theme_mod( 'rfc_footer_legal' ); ?>
		<?php if ( $legal ) : ?>
			<p><?php echo esc_html( $legal ); ?></p>
		<?php endif; ?>
	</div>
</footer>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Image lightbox', 'red-feather-club' ); ?>">
	<button class="lightbox__close" aria-label="<?php esc_attr_e( 'Close', 'red-feather-club' ); ?>">&#10005;</button>
	<img id="lightbox-img" class="lightbox__img" src="" alt="<?php esc_attr_e( 'Gallery image', 'red-feather-club' ); ?>" />
</div>

<?php wp_footer(); ?>
</body>
</html>
