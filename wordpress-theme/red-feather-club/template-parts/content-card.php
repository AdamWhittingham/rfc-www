<?php
/**
 * Post card used in archives and the blog index.
 *
 * @package Red_Feather_Club
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card observe-fade' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-card__thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'rfc-card', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="post-card__body">
		<?php rfc_posted_on(); ?>
		<h2 class="post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
		<a class="post-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'red-feather-club' ); ?>
			<?php rfc_the_icon( 'arrow', 12 ); ?>
		</a>
	</div>
</article>
