<?php
/**
 * Comments.
 *
 * @package Red_Feather_Club
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'red-feather-club' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => __( '← Older comments', 'red-feather-club' ),
				'next_text' => __( 'Newer comments →', 'red-feather-club' ),
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'red-feather-club' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit'  => 'btn btn--primary',
			'title_reply'   => __( 'Leave a comment', 'red-feather-club' ),
			'label_submit'  => __( 'Post comment', 'red-feather-club' ),
		)
	);
	?>
</div>
