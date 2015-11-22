<?php

/**
 Plugin Name: Simple BuddyPress Profile Privacy
 Plugin URI: http://simplercomputing.net/2010/03/18/simple-buddypress-profile-privacy/
 Description: Plugin that allows users to select which profile data will be viewable by everyone, everyone logged in, only your friends, or just you. Originally developed by Sandra Petronic - modified to work with Buddypress 1.2.x (including new features) by <a href="http://simplercomputing.net" target="_blank">Mark Edwards of Simpler Computing</a>
 Version: 1.1
 Author: Sandra Petronic (original developer) 
 Author URI: http://devbox.computec.de
 */
function simple_privacy_plugin_init() {
    require( dirname( __FILE__ ) . '/simple-buddypress-privacy.php' );
}
add_action( 'bp_init', 'simple_privacy_plugin_init' );
?>
