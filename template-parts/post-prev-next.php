<?php
/**
 * The template part for displaying post prev next section.
 *
 * @package Squaretype
 */

$prev_post = get_previous_post();
$next_post = get_next_post();
$ignore_cat_slug = array('danh-ngon','hero-section');

$prev_post_cat = get_the_category( $prev_post->ID );
if ( ! empty( $prev_post_cat ) ) {
	$prev_post_slug =  $prev_post_cat[0]->slug;
}

$next_post_cat = get_the_category( $next_post->ID );
if ( ! empty( $next_post_cat ) ) {
	$next_post_slug =  $next_post_cat[0]->slug;
}

if ( $prev_post || $next_post ) {
?>
	<div class="post-prev-next">
		<?php

		// Prev post.
		if ( $prev_post &&  $prev_post_slug !== 'danh-ngon' && $prev_post_slug !== 'hero-section' ) {
			?>
				<a class="link-item prev-link" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
					<div class="link-content">
						<div class="link-label">
							<span class="link-arrow"></span><span class="link-text"> — <?php esc_html_e( 'Previous arcticle', 'squaretype' ); ?></span>
						</div>

						<h2 class="entry-title">
							<?php echo esc_attr( $prev_post->post_title ); ?>
						</h2>
					</div>
				</a>
			<?php
		}

		// Next post.
		if ( $next_post  && $next_post_slug !== 'danh-ngon' && $next_post_slug !== 'hero-section' ) {
			?>
				<a class="link-item next-link" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
					<div class="link-content">
						<div class="link-label">
							<span class="link-text"><?php esc_html_e( 'Next arcticle', 'squaretype' ); ?> — </span><span class="link-arrow"></span>
						</div>

						<h2 class="entry-title">
							<?php echo esc_attr( $next_post->post_title ); ?>
						</h2>
					</div>
				</a>
			<?php
		}
		?>
	</div>
<?php
}
