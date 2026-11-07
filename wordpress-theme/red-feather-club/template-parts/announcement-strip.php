<?php
/**
 * Announcement / merch strip.
 *
 * @package Red_Feather_Club
 */

if ( ! get_theme_mod( 'rfc_strip_enabled', true ) ) {
	return;
}

$rfc_text     = get_theme_mod( 'rfc_strip_text', '' );
$rfc_btn_text = get_theme_mod( 'rfc_strip_btn_text', '' );
$rfc_btn_url  = get_theme_mod( 'rfc_strip_btn_url', '' );

if ( ! $rfc_text && ! $rfc_btn_text ) {
	return;
}
?>
<div class="merch-strip">
	<?php if ( $rfc_text ) : ?>
		<p><?php echo wp_kses( $rfc_text, array( 'strong' => array(), 'em' => array(), 'br' => array() ) ); ?></p>
	<?php endif; ?>
	<?php if ( $rfc_btn_text && $rfc_btn_url ) : ?>
		<a href="<?php echo esc_url( $rfc_btn_url ); ?>" target="_blank" rel="noopener" class="btn btn--outline">
			<?php echo esc_html( $rfc_btn_text ); ?>
		</a>
	<?php endif; ?>
</div>
