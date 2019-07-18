<?php
/**
 * Gutenberg compatibility functions.
 *
 * @package Squaretype
 */

/**
 * Adds classes to <div class="editor-styles-wrapper"> tag
 */
function csco_block_editor_wrapper() {
	$post_id = get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	// Set post type.
	$post_type = sprintf( 'post-type-%s', get_post_type( $post_id ) );

	// Set page layout.
	$default_layout = csco_get_page_sidebar( $post_id, 'default' );
	$page_layout    = csco_get_page_sidebar( $post_id );

	if ( 'disabled' === $default_layout ) {
		$default_layout = 'sidebar-disabled';
	} else {
		$default_layout = 'sidebar-enabled';
	}

	if ( 'disabled' === $page_layout ) {
		$page_layout = 'sidebar-disabled';
	} else {
		$page_layout = 'sidebar-enabled';
	}

	wp_enqueue_script( 'csco-editor-wrapper', get_template_directory_uri() . '/js/gutenberg-wrapper.js',
		array(
			'wp-editor',
			'wp-element',
			'wp-compose',
			'wp-data',
			'wp-plugins',
		),
		csco_get_theme_data( 'Version' ),
		true
	);

	wp_localize_script( 'csco-editor-wrapper', 'cscoGWrapper', array(
		'post_type'      => $post_type,
		'default_layout' => $default_layout,
		'page_layout'    => $page_layout,
	) );
}
add_action( 'enqueue_block_editor_assets', 'csco_block_editor_wrapper' );

/**
 * Add css selectors to output of kirki.
 */
add_filter( 'csco_color_primary', function( $rules ) {
	array_push( $rules, array(
		'element'  => csco_implode( array(
			'.edit-post-visual-editor a',
		) ),
		'property' => 'color',
		'context'  => array( 'editor' ),
	) );
	return $rules;
} );

add_filter( 'csco_font_base', function( $rules ) {
	array_push( $rules, array(
		'element' => csco_implode( array(
			'.edit-post-visual-editor.editor-styles-wrapper',
		) ),
		'context' => array( 'editor' ),
	) );
	return $rules;
} );

add_filter( 'csco_color_primary', function( $rules ) {
	array_push( $rules, array(
		'element'  => csco_implode( array(
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-button .wp-block-button__link:not(.has-background)',
		) ),
		'property' => 'background-color',
		'context'  => array( 'editor' ),
	) );
	return $rules;
} );

add_filter( 'csco_font_secondary', function( $rules ) {
	array_push( $rules, array(
		'element' => csco_implode( array(
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-quote cite',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-quote__citation',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-image figcaption',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-audio figcaption',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-embed figcaption',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-pullquote cite',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-pullquote footer',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-pullquote .wp-block-pullquote__citation',
		) ),
		'context' => array( 'editor' ),
	) );
	return $rules;
} );

add_filter( 'csco_font_post_content', function( $rules ) {
	array_push( $rules, array(
		'element' => csco_implode( array(
			'.edit-post-visual-editor .editor-writing-flow',
			'.edit-post-visual-editor .editor-writing-flow p',
		) ),
		'context' => array( 'editor' ),
	) );
	return $rules;
} );

add_filter( 'csco_font_headings', function( $rules ) {
	array_push( $rules, array(
		'element' => csco_implode( array(
			'.edit-post-visual-editor.editor-styles-wrapper h1',
			'.edit-post-visual-editor.editor-styles-wrapper h2',
			'.edit-post-visual-editor.editor-styles-wrapper h3',
			'.edit-post-visual-editor.editor-styles-wrapper h4',
			'.edit-post-visual-editor.editor-styles-wrapper h5',
			'.edit-post-visual-editor.editor-styles-wrapper h6',
			'.edit-post-visual-editor.editor-styles-wrapper .h1',
			'.edit-post-visual-editor.editor-styles-wrapper .h2',
			'.edit-post-visual-editor.editor-styles-wrapper .h3',
			'.edit-post-visual-editor.editor-styles-wrapper .h4',
			'.edit-post-visual-editor.editor-styles-wrapper .h5',
			'.edit-post-visual-editor.editor-styles-wrapper .h6',
			'.edit-post-visual-editor.editor-styles-wrapper .editor-post-title__input',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-quote',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-quote p',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-pullquote p',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-freeform blockquote p:first-child',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-cover .wp-block-cover-image-text',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-cover .wp-block-cover-text',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-cover-image .wp-block-cover-image-text',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-cover-image .wp-block-cover-text',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-cover-image h2',
			'.edit-post-visual-editor.editor-styles-wrapper .wp-block-cover h2',
			'.edit-post-visual-editor.editor-styles-wrapper p.has-drop-cap:not(:focus):first-letter',
		) ),
		'context' => array( 'editor' ),
	) );
	return $rules;
} );
