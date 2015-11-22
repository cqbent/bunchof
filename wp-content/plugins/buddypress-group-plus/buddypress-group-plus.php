<?php

/*
 * Include the ONLY working files
 */
require ( dirname( __FILE__ ) . '/admin.php' );

/* only include the group header if enabled */
	if ( !$default_group_header ) {
		if ( !$default_group_header = get_option('group_plus_header') )
			$default_group_header = ''; // the default
	}
	if ( $default_group_header == '1' ) { 
require( dirname( __FILE__ ) . '/includes/admin-header-menu.php' );





/* check to see if one/any of the text GROUP HEADS are/is enabled */
	if ( !$default_plus_textarea ) {
		if ( !$default_plus_header_textfield = get_option('group_plus_header_textfield') )
			$default_plus_header_textfield = ''; // the default
	}
	if ( !$default_plus_header_textfield2 ) {
		if ( !$default_plus_header_textfield2 = get_option('group_plus_header_textfield2') )
			$default_plus_header_textfield2 = ''; // the default
	}
	if ( $default_plus_header_textfield == '1' || $default_plus_header_textfield2 == '1' ) { 
require( dirname( __FILE__ ) . '/includes/plus-group-header-extension.php' );
	}



	}
/* only include the group class if enabled */
	if ( !$default_group_class ) {
		if ( !$default_group_class = get_option('group_plus_class') )
			$default_group_class = ''; // the default
	}
	if ( $default_group_class == '1' ) { 
require( dirname( __FILE__ ) . '/includes/plus-group-extension.php' );
require( dirname( __FILE__ ) . '/includes/admin-tab-menu.php' );
	}
/* check to see if the map is enabled */
	if ( !$default_plus_map ) {
		if ( !$default_plus_map = get_option('group_plus_map') )
			$default_plus_map = ''; // the default
	}
	if ( $default_plus_map == '1' ) { 
require( dirname( __FILE__ ) . '/includes/plus-group-map.php' );
	}
/* check to see if/one of the text FIELDS are/is enabled */
	if ( !$default_plus_textfield ) {
		if ( !$default_plus_textfield = get_option('group_plus_textfield') )
			$default_plus_textfield = ''; // the default
	}
	if ( !$default_plus_textfield2 ) {
		if ( !$default_plus_textfield2 = get_option('group_plus_textfield2') )
			$default_plus_textfield2 = ''; // the default
	}
	if ( $default_plus_textfield == '1' || $default_plus_textfield2 == '1' ) { 
require( dirname( __FILE__ ) . '/includes/plus-group-textfield.php' );
	}
/* check to see if/one of the text AREAS are/is enabled */
	if ( !$default_plus_textarea ) {
		if ( !$default_plus_textarea = get_option('group_plus_textarea') )
			$default_plus_textfield = ''; // the default
	}
	if ( !$default_plus_textarea2 ) {
		if ( !$default_plus_textarea2 = get_option('group_plus_textarea2') )
			$default_plus_textarea2 = ''; // the default
	}
	if ( $default_plus_textarea == '1' || $default_plus_textarea2 == '1' ) { 
require( dirname( __FILE__ ) . '/includes/plus-group-textarea.php' );
	}


?>