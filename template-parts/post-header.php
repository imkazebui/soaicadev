<?php
/**
 * The template part for displaying post header section.
 *
 * @package Squaretype
 */

$page_header = csco_get_page_header_type();

$class = sprintf( 'entry-header-%s', $page_header );

// Check if post has an image attached.
if ( has_post_thumbnail() ) {
	$class .= ' entry-header-thumbnail';
}
?>

<section class="entry-header <?php echo esc_attr( $class ); ?>">

	<div class="entry-header-inner">

		<?php do_action( 'csco_singular_entry_header_start' ); ?>

		<?php if ( is_singular( 'post' ) ) { ?>
			<div class="entry-inline-meta">
				<?php csco_get_post_meta( 'category', false, true, 'post_meta' ); ?>
			</div>
		<?php } ?>

		<?php if ( get_the_title() ) { ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php } ?>

		<?php
		if ( is_singular( 'post' ) ) {
			csco_get_post_meta( array( 'author', 'date', 'views', 'shares', 'comments', 'reading_time' ), false, true, 'post_meta' );
		}
		?>

		<?php
		if ( is_singular( 'post' ) ) {
			csco_post_media();
		}
		?>

		<?php
		if ( has_excerpt() && false ) {
			?>
			<div class="post-excerpt"><?php the_excerpt(); ?></div>
			<?php
		}
		?>

	</div>

</section>
