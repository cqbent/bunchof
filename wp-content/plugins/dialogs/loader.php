<?php
/*
  Plugin Name: Dialogs
  Plugin URI: http://themekraft.com/
  Description: Create dialogs in lightboxes on pages and articles.
  Author: Peter Liebetrau<ixiter@ixiter.com>, Sven Wagener<sven.wagener@rheinschmiede.de> themekraft.com<contact@themekraft.com>
  Version: 1.0.3
  License: unknown
  Network: false
 */

if ( ! function_exists( 'tk_dialogs_init' ) ) {
    // If we have no Ixiter_Plugin yet, we try to load it
    if(!class_exists('Ixiter_Plugin')){
        if(file_exists(  dirname( __FILE__).'/Ixiter_Plugin.php')){
            require_once('Ixiter_Plugin.php');
        }
    }
	
    // Maybe, we still have no Ixiter_Plugin!
    if(class_exists('Ixiter_Plugin')){
        /**
         * Loads TK dialogs only when BP/Multisite is active
         *
         * @package TK dialogs
         * @since 0.0.1
         */
        function tk_dialogs_init() {
            require_once( dirname( __FILE__ ) . '/dialogs.php' );
			
            /**
             * set up the global var $tk_dialogs to give access to the plugin.<br>
             * Alternatively you can use TK_dialogs::_get_instance() to get the plugin's object
             *
             * @global TK_dialogs $tk_dialogs
             */
            $GLOBALS[ 'tk_dialogs' ] = TK_Dialogs::getInstance();
            require_once(TK_Dialogs::getInstance()->path.'/components/core/template-tags.php');
        }

        add_action( 'plugins_loaded', 'tk_dialogs_init' );
    }
}