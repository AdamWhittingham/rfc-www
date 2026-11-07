<?php
/**
 * "Latest News" band — WordPress posts rendered as feed cards, with tabs for
 * Instagram and a Live Feed panel for a Facebook Page Plugin / LightWidget embed.
 *
 * @package Red_Feather_Club
 */

$rfc_news = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => false,
		'no_found_rows'       => true,
	)
);

$rfc_facebook  = get_theme_mod( 'rfc_facebook_url', '' );
$rfc_instagram = get_theme_mod( 'rfc_instagram_url', '' );

if ( ! $rfc_news->have_posts() && ! $rfc_facebook && ! $rfc_instagram ) {
	return;
}
?>
<section class="social-feed" id="news" aria-label="<?php esc_attr_e( 'Latest news', 'red-feather-club' ); ?>">
	<div class="social-feed__inner">
		<div class="social-feed__header observe-fade">
			<div>
				<div class="section-label"><?php esc_html_e( 'Latest News', 'red-feather-club' ); ?></div>
				<h2 class="section-title" style="margin-bottom:0"><?php esc_html_e( 'From the Museum', 'red-feather-club' ); ?></h2>
			</div>
			<ul class="social-tabs" role="tablist">
				<li class="social-tab active" role="tab" tabindex="0" data-tab="news" aria-selected="true"><?php esc_html_e( 'News', 'red-feather-club' ); ?></li>
				<li class="social-tab" role="tab" tabindex="0" data-tab="embed" aria-selected="false"><?php esc_html_e( 'Live Feed', 'red-feather-club' ); ?></li>
			</ul>
		</div>

		<div class="social-tab-panel active" data-panel="news">
			<?php if ( $rfc_news->have_posts() ) : ?>
				<div class="feed-grid">
					<?php
					while ( $rfc_news->have_posts() ) :
						$rfc_news->the_post();
						?>
						<article class="feed-card observe-fade">
							<div class="feed-card__img">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'rfc-card', array( 'loading' => 'lazy' ) ); ?>
								<?php else : ?>
									<div class="feed-card__img-placeholder">&#9992;</div>
								<?php endif; ?>
							</div>
							<div class="feed-card__body">
								<div class="feed-card__date"><?php echo esc_html( get_the_date() ); ?></div>
								<p class="feed-card__text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34 ) ); ?></p>
								<a href="<?php the_permalink(); ?>" class="feed-card__link">
									<?php esc_html_e( 'Read the full post', 'red-feather-club' ); ?>
									<?php rfc_the_icon( 'arrow', 12 ); ?>
								</a>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>

			<?php if ( $rfc_facebook || $rfc_instagram ) : ?>
				<div class="social-cta observe-fade">
					<?php if ( $rfc_facebook ) : ?>
						<a href="<?php echo esc_url( $rfc_facebook ); ?>" target="_blank" rel="noopener" class="btn btn--fb">
							<?php rfc_the_icon( 'facebook' ); ?>
							<?php esc_html_e( 'Follow us on Facebook', 'red-feather-club' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $rfc_instagram ) : ?>
						<a href="<?php echo esc_url( $rfc_instagram ); ?>" target="_blank" rel="noopener" class="btn btn--ig">
							<?php rfc_the_icon( 'instagram' ); ?>
							<?php esc_html_e( 'Follow on Instagram', 'red-feather-club' ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="social-tab-panel social-embed-panel" data-panel="embed">
			<div class="embed-grid">
				<div class="embed-placeholder">
					<div class="embed-placeholder__icon">&#128225;</div>
					<p class="embed-placeholder__text">
						<?php esc_html_e( 'Drop a Facebook Page Plugin or LightWidget embed here — add it as a Custom HTML block in a page, or paste it into this template part.', 'red-feather-club' ); ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>
