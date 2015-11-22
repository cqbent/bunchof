<?php 
/**
Plugin Name: BuddyPress Group Plus
Plugin URI: http://buddypress.org/community/groups/buddypress-group-plus
Description: Adds loads more features to your BuddyPress groups - Add a extra tab to your groups, group maps, info into the group header and more.
Version: 1.2
Author: Charl Kruger
Author URI:
License:GPL2
**/

function bp_group_plus_init() {
	require( dirname( __FILE__ ) . '/buddypress-group-plus.php' );
}
add_action( 'bp_include', 'bp_group_plus_init' );

?>