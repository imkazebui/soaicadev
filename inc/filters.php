<?php
/**
 * Filters
 *
 * Filtering native WordPress and third-party plugins' functions.
 *
 * @package Squaretype
 */

if ( ! function_exists( 'csco_body_class' ) ) {
	/**
	 * Adds classes to <body> tag
	 *
	 * @param array $classes is an array of all body classes.
	 */
	function csco_body_class( $classes ) {

		// Header Layout.
		$classes[] = 'header-' . get_theme_mod( 'header_layout', 'large' );

		// Header Width.
		$classes[] = 'navbar-width-' . get_theme_mod( 'header_width', 'boxed' );

		// Header Alignment.
		$classes[] = 'navbar-alignment-' . get_theme_mod( 'header_alignment', 'left' );

		// Sticky Navbar.
		if ( get_theme_mod( 'navbar_sticky', true ) ) {
			$classes[] = 'navbar-sticky-enabled';
		}

		// Smart Navbar.
		if ( get_theme_mod( 'navbar_sticky', true ) && get_theme_mod( 'effects_navbar_scroll', true ) ) {
			$classes[] = 'navbar-smart-enabled';
		}

		// Sticky Sidebar.
		if ( get_theme_mod( 'misc_sticky_sidebar', true ) ) {
			$classes[] = 'sticky-sidebar-enabled';
			$classes[] = get_theme_mod( 'misc_sticky_sidebar_method', 'stick-to-bottom' );
		}

		// State Large Section.
		if ( csco_get_state_large_section() ) {
			$classes[] = 'large-section-enabled';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'csco_body_class' );

if ( ! function_exists( 'csco_sitecontent_class' ) ) {
	/**
	 * Adds the classes for the site-content element.
	 *
	 * @param array $classes Classes to add to the class list.
	 */
	function csco_sitecontent_class( $classes ) {
		$sidebar = apply_filters( 'csco_sidebar', 'sidebar-main' );

		// Page Sidebar.
		if ( is_active_sidebar( $sidebar ) && 'disabled' !== csco_get_page_sidebar() ) {
			$classes[] = 'sidebar-enabled sidebar-' . csco_get_page_sidebar();
		} else {
			$classes[] = 'sidebar-disabled';
		}

		// Post Sidebar.
		if ( is_single() && csco_has_post_meta( 'shares' ) && csco_powerkit_module_enabled( 'share_buttons' ) && powerkit_share_buttons_exists( 'post_sidebar' ) ) {
			$classes[] = 'post-sidebar-enabled';
		} else {
			$classes[] = 'post-sidebar-disabled';
		}

		return $classes;
	}
}
add_filter( 'csco_site_content_class', 'csco_sitecontent_class' );

if ( ! function_exists( 'csco_remove_hentry_class' ) ) {
	/**
	 * Remove hentry from post_class
	 *
	 * @param array $classes One or more classes to add to the class list.
	 */
	function csco_remove_hentry_class( $classes ) {
		return array_diff( $classes, array( 'hentry' ) );
	}
}
add_filter( 'post_class', 'csco_remove_hentry_class' );

if ( ! function_exists( 'csco_kirki_support_rtl' ) ) {
	/**
	 * Add support rtl to Kirki
	 *
	 * @param string $style The dynamic css.
	 */
	function csco_kirki_support_rtl( $style ) {
		if ( is_rtl() ) {
			$style = str_replace( 'border-top-left-radius:', 'border-top-right-radius:', $style );
			$style = str_replace( 'border-bottom-left-radius:', 'border-bottom-right-radius:', $style );
		}

		return $style;
	}
}
add_filter( 'kirki_global_dynamic_css', 'csco_kirki_support_rtl' );
add_filter( 'kirki_csco_theme_mod_dynamic_css', 'csco_kirki_support_rtl' );

if ( ! function_exists( 'csco_theme_fonts' ) ) {
	/**
	 * Add theme fonts
	 *
	 * @param array $fonts List fonts.
	 */
	function csco_theme_fonts( $fonts ) {

		if ( is_customize_preview() ) {
			$fonts['fonts']['families']['theme'] = array(
				'text'     => esc_html__( 'Theme Fonts', 'squaretype' ),
				'children' => array(),
			);

			// Add HK Grotesk font.
			$fonts['fonts']['families']['theme']['children'][] = array(
				'id'   => 'hg-grotesk',
				'text' => esc_html__( 'HK Grotesk', 'squaretype' ),
			);

			$fonts['fonts']['variants']['hg-grotesk'] = array( '500', '600', '700' );
		}

		return $fonts;
	}
}
add_filter( 'powerkit_fonts_choices', 'csco_theme_fonts', 999 );

if ( ! function_exists( 'csco_theme_fonts_stack' ) ) {
	/**
	 * Change theme fonts stack
	 *
	 * @param string $style The dynamic css.
	 */
	function csco_theme_fonts_stack( $style ) {
		$style = str_replace( 'font-family:hg-grotesk;', 'font-family:hg-grotesk,-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";', $style );

		return $style;
	}
}
add_filter( 'csco_kirki_dynamic_css', 'csco_theme_fonts_stack' );
add_filter( 'kirki_global_dynamic_css', 'csco_theme_fonts_stack' );
add_filter( 'kirki_csco_theme_mod_dynamic_css', 'csco_theme_fonts_stack' );

if ( ! function_exists( 'csco_overwrite_sidebar' ) ) {
	/**
	 * Overwrite Default Sidebar
	 *
	 * @param string $sidebar Sidebar slug.
	 */
	function csco_overwrite_sidebar( $sidebar ) {
		// Check Nonce.
		wp_verify_nonce( null );

		if ( isset( $_REQUEST['action'] ) && 'csco_ajax_load_nextpost' === $_REQUEST['action'] ) { // Input var ok.
			if ( is_active_sidebar( 'sidebar-loaded' ) ) {
				$sidebar = 'sidebar-loaded';
			}
		}
		return $sidebar;
	}
}
add_filter( 'csco_sidebar', 'csco_overwrite_sidebar' );

if ( ! function_exists( 'csco_tiny_mce_refresh_cache' ) ) {
	/**
	 * TinyMCE Refresh Cache.
	 *
	 * @param array $settings An array with TinyMCE config.
	 */
	function csco_tiny_mce_refresh_cache( $settings ) {

		$theme = wp_get_theme();

		$settings['cache_suffix'] = sprintf( 'v=%s', $theme->get( 'Version' ) );

		return $settings;
	}
}
add_filter( 'tiny_mce_before_init', 'csco_tiny_mce_refresh_cache' );

if ( ! function_exists( 'csco_max_srcset_image_width' ) ) {
	/**
	 * Changes max image width in srcset attribute
	 *
	 * @param int   $max_width  The maximum image width to be included in the 'srcset'. Default '1600'.
	 * @param array $size_array Array of width and height values in pixels (in that order).
	 */
	function csco_max_srcset_image_width( $max_width, $size_array ) {
		return 3840;
	}
}
add_filter( 'max_srcset_image_width', 'csco_max_srcset_image_width', 10, 2 );

if ( ! function_exists( 'csco_embed_oembed_html' ) ) {
	/**
	 *  Add responsive container to embeds, except for Instagram and Twitter
	 *
	 * @param string $html oembed markup.
	 */
	function csco_embed_oembed_html( $html ) {
		// Skip if Jetpack is active.
		if ( class_exists( 'Jetpack' ) ) {
			return $html;
		}
		// Skip for Instagram, Twitter & Facebook embeds.
		if ( strpos( $html, 'instagram' ) || strpos( $html, 'twitter-tweet' ) || strpos( $html, 'facebook' ) ) {
			return $html;
		}
		return '<div class="cs-embed cs-embed-responsive">' . $html . '</div>';
	}
}
add_filter( 'embed_oembed_html', 'csco_embed_oembed_html', 10, 3 );

if ( ! function_exists( 'csco_get_the_archive_title' ) ) {
	/**
	 * Archive Title
	 *
	 * Removes default prefixes, like "Category:" from archive titles.
	 *
	 * @param string $title Archive title.
	 */
	function csco_get_the_archive_title( $title ) {
		if ( is_category() ) {

			$title = single_cat_title( '', false );

		} elseif ( is_tag() ) {

			$title = single_tag_title( '', false );

		} elseif ( is_author() ) {

			$title = get_the_author( '', false );

		}
		return $title;
	}
}
add_filter( 'get_the_archive_title', 'csco_get_the_archive_title' );

if ( ! function_exists( 'csco_excerpt_length' ) ) {
	/**
	 * Excerpt Length
	 *
	 * @param string $length of the excerpt.
	 */
	function csco_excerpt_length( $length ) {
		return 18;
	}
}
add_filter( 'excerpt_length', 'csco_excerpt_length' );

if ( ! function_exists( 'csco_strip_shortcode_from_excerpt' ) ) {
	/**
	 * Strip shortcodes from excerpt
	 *
	 * @param string $content Excerpt.
	 */
	function csco_strip_shortcode_from_excerpt( $content ) {
		$content = strip_shortcodes( $content );
		return $content;
	}
}
add_filter( 'the_excerpt', 'csco_strip_shortcode_from_excerpt' );

if ( ! function_exists( 'csco_strip_tags_from_excerpt' ) ) {
	/**
	 * Strip HTML from excerpt
	 *
	 * @param string $content Excerpt.
	 */
	function csco_strip_tags_from_excerpt( $content ) {
		$content = strip_tags( $content );
		return $content;
	}
}
add_filter( 'the_excerpt', 'csco_strip_tags_from_excerpt' );

if ( ! function_exists( 'csco_excerpt_more' ) ) {
	/**
	 * Excerpt Suffix
	 *
	 * @param string $more suffix for the excerpt.
	 */
	function csco_excerpt_more( $more ) {
		return '&hellip;';
	}
}
add_filter( 'excerpt_more', 'csco_excerpt_more' );

if ( ! function_exists( 'csco_post_meta_process' ) ) {
	/**
	 * Pre processing post meta choices
	 *
	 * @param array $data Post meta list.
	 */
	function csco_post_meta_process( $data ) {
		if ( ! csco_powerkit_module_enabled( 'share_buttons' ) && isset( $data['shares'] ) ) {
			unset( $data['shares'] );
		}
		if ( ! class_exists( 'Post_Views_Counter' ) && isset( $data['views'] ) ) {
			unset( $data['views'] );
		}
		return $data;
	}
}
add_filter( 'csco_post_meta_choices', 'csco_post_meta_process' );

if ( ! function_exists( 'csco_wrap_post_gallery' ) ) {
	/**
	 * Alignment of Galleries in Classic Block
	 *
	 * @param string $html     The current output.
	 * @param array  $attr     Attributes from the gallery shortcode.
	 * @param int    $instance Numeric ID of the gallery shortcode instance.
	 */
	function csco_wrap_post_gallery( $html, $attr, $instance ) {
		switch ( get_theme_mod( 'misc_classic_gallery_alignment', 'default' ) ) {
			case 'wide':
				$wrap = 'alignwide';
				break;
			case 'large':
				$wrap = 'alignfull';
				break;
		}

		if ( ! isset( $attr['wrap'] ) && isset( $wrap ) ) {
			$attr['wrap'] = $wrap;

			// Our custom HTML wrapper.
			$html = sprintf( '<div class="%s">%s</div>', esc_attr( $wrap ), gallery_shortcode( $attr ) );
		}

		return $html;
	}
	add_filter( 'post_gallery', 'csco_wrap_post_gallery', 99, 3 );
}

if ( ! function_exists( 'csco_page_remove_alignment' ) ) {
	/**
	 * Remove wide/full alignment on pages.
	 */
	function csco_page_remove_alignment() {
		global $pagenow;

		if ( is_admin() ) {
			wp_verify_nonce( null );

			$post_type = null;

			if ( isset( $_GET['post_type'] ) ) { // Input var ok; sanitization ok.
				$post_type = sanitize_text_field( $_GET['post_type'] ); // Input var ok; sanitization ok.
			}

			if ( isset( $_GET['post'] ) ) { // Input var ok; sanitization ok.
				$page_id = sanitize_text_field( $_GET['post'] ); // Input var ok; sanitization ok.

				$page = get_post( (int) $page_id );

				if ( $page ) {
					$post_type = $page->post_type;
				}
			}

			if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ), true ) && 'page' === $post_type ) {
				remove_theme_support( 'align-wide' );
			}
		}
	}
}
add_action( 'admin_init', 'csco_page_remove_alignment' );

if ( ! function_exists( 'csco_wp_link_pages_args' ) ) {
	/**
	 * Paginated Post Pagination
	 *
	 * @param string $args Paginated posts pagination args.
	 */
	function csco_wp_link_pages_args( $args ) {
		if ( 'next_and_number' === $args['next_or_number'] ) {
			global $page, $numpages, $multipage, $more, $pagenow;
			$args['next_or_number'] = 'number';

			$prev = '';
			$next = '';
			if ( $multipage ) {
				if ( $more ) {
					$i = $page - 1;
					if ( $i && $more ) {
						$prev .= _wp_link_page( $i );
						$prev .= $args['link_before'] . $args['previouspagelink'] . $args['link_after'] . '</a>';
					}
					$i = $page + 1;
					if ( $i <= $numpages && $more ) {
						$next .= _wp_link_page( $i );
						$next .= $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>';
					}
				}
			}
			$args['before'] = $args['before'] . $prev;
			$args['after']  = $next . $args['after'];
		}
		return $args;
	}
}
add_filter( 'wp_link_pages_args', 'csco_wp_link_pages_args' );

if ( ! function_exists( 'csco_post_header_avatar_size' ) ) {
	/**
	 * Set for post header avatar size.
	 *
	 * @param int $size Avatar size.
	 */
	function csco_post_header_avatar_size( $size ) {
		return 40;
	}
}

/**
 * -------------------------------------------------------------------------
 * [ Primary Menu ]
 * -------------------------------------------------------------------------
 */

if ( ! function_exists( 'csco_primary_menu_item_args' ) ) {
	/**
	 * Filters the arguments for a single nav menu item.
	 *
	 * @param object $args  An object of wp_nav_menu() arguments.
	 * @param object $item  (WP_Post) Menu item data object.
	 * @param int    $depth Depth of menu item. Used for padding.
	 */
	function csco_primary_menu_item_args( $args, $item, $depth ) {
		$args->link_before = '';
		$args->link_after  = '';
		if ( 'primary' === $args->theme_location && 0 === $depth ) {
			$args->link_before = '<span>';
			$args->link_after  = '</span>';
		}
		return $args;
	}
	add_filter( 'nav_menu_item_args', 'csco_primary_menu_item_args', 10, 3 );
}

/**
 * -------------------------------------------------------------------------
 * [ SearchWP Live Ajax Search ]
 * -------------------------------------------------------------------------
 */

if ( ! function_exists( 'csco_searchwp_live_enqueue_scripts' ) ) {
	/**
	 * Enqueue scripts and styles.
	 */
	function csco_searchwp_live_enqueue_scripts() {

		$style = sprintf( '.searchwp-live-search-no-min-chars:after { content: "%s" }', esc_html__( 'Continue typing', 'squaretype' ) );

		wp_add_inline_style( 'csco-styles', $style );
	}
}
add_action( 'wp_enqueue_scripts', 'csco_searchwp_live_enqueue_scripts' );


/**
 * Remove Output the base styles.
 */
add_filter( 'searchwp_live_search_base_styles', '__return_false' );
