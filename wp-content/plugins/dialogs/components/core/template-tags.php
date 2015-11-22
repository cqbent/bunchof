<?php

if ( class_exists( 'TK_Dialogs' ) ) {

    /**
     * Load header template.
     *
     * Includes the header template for a theme or if a name is specified then a
     * specialised header will be included.
     *
     * For the parameter, if the file is called "header-special.php" then specify
     * "special".
     *
     * @uses locate_template()
     * @since 1.5.0
     * @uses do_action() Calls 'get_header' action.
     *
     * @param string $name The name of the specialised header.
     */
    function dialog_get_header( $name = null ) {
		TK_Dialogs::getInstance()->get_header( $name );
    }

    function dialog_get_footer( $name = null ) {
		TK_Dialogs::getInstance()->get_footer( $name );
    }

    function dialog_info( $item ) {
		return TK_Dialogs::getInstance()->info( $item );
    }
	
	function get_splashlink( $atts = array() ) {
		global $post;
		
		extract( wp_parse_args( $atts, array(
			'width' 	=> '600',
			'height' 	=> '400'
		)));
		
		$splashlink = TK_Dialogs::getInstance()->get_splashlink( $post->ID );
		$url = $splashlink['url'] . '&width=' . $width . '&height= ' . $height;
		return $url;
    }
	
	function the_splashlink( $atts = array() ) {
		echo get_splashlink( $atts );
    }

}
