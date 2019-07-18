/**
 * WordPress dependencies.
 */
const { Component }      = wp.element;
const { registerPlugin } = wp.plugins;

class CSCOUpdateWrapper extends Component {
	/**
	 * Add initial class.
	 */
	componentDidMount() {
		const wrapper = document.querySelector( '.editor-styles-wrapper' );


		if ( wrapper ) {
			wrapper.classList.add( cscoGWrapper.post_type );
			wrapper.classList.add( cscoGWrapper.page_layout );
		}
	}

	componentDidUpdate() {
		const wrapper = document.querySelector( '.editor-styles-wrapper' );

		if ( wrapper ) {
			wrapper.classList.add( cscoGWrapper.post_type );
			wrapper.classList.add( cscoGWrapper.page_layout );
		}
	}

	render() {
		return null;
	}
}

/**
 * Update when page layout has changed.
 */
$( 'select[name="csco_singular_sidebar"]' ).on( 'change', function() {
	var layout = $( this ).val();

	$( '.editor-styles-wrapper' ).removeClass( 'sidebar-disabled sidebar-enabled' );

	if ( 'right' === layout || 'left' === layout ) {
		cscoGWrapper.page_layout = 'sidebar-enabled';

		$( '.editor-styles-wrapper' ).addClass( 'sidebar-enabled' );
	} else if ( 'disabled' === layout ) {
		cscoGWrapper.page_layout = 'sidebar-disabled';

		$( '.editor-styles-wrapper' ).addClass( 'sidebar-disabled' );
	} else {
		cscoGWrapper.page_layout = cscoGWrapper.default_layout;

		$( '.editor-styles-wrapper' ).addClass( cscoGWrapper.default_layout );
	}
});

registerPlugin( 'csco-editor-wrapper', { render: CSCOUpdateWrapper } );