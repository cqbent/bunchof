<?php

if ( is_admin() )
{

	add_action('admin_menu', 'dbx_menu');
	

}

function dbx_menu()
{
	add_menu_page('Popup Dialog Box Settings', 'XYZ Popup Dialog Box', 'manage_options', 'popup-dialog-box-settings', 'dbx_settings');
	

	// Add a submenu to the Dashboard:
	$page=add_submenu_page('popup-dialog-box-settings', 'Popup Dialog Box Settings', 'Popup Dialog Box', 'manage_options', 'popup-dialog-box-settings' ,'dbx_settings'); // 8 for admin
	add_submenu_page('popup-dialog-box-settings', 'Popup Dialog Box - Basic Settings', 'Basic Settings', 'manage_options', 'popup-dialog-box-basic-settings' ,'dbx_basic_settings'); // 8 for admin
	add_submenu_page('popup-dialog-box-settings', 'Popup Dialog Box - About', 'About', 'manage_options', 'popup-dialog-box-about' ,'dbx_about'); // 8 for admin

	
	
	add_action( "admin_print_scripts-$page", 'dbx_farbtastic_script' );
	add_action( "admin_print_styles-$page", 'dbx_farbtastic_style' );
}


function dbx_basic_settings()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function dbx_settings()
{
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/dialogbox-settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}


function dbx_about()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/about.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}



function dbx_farbtastic_script() 
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('farbtastic');

}

function dbx_farbtastic_style() 
{
	wp_enqueue_style('farbtastic');
}
function xyz_dbx_admin_style()
{
	require( dirname( __FILE__ ) . '/style.php' );

}
	wp_enqueue_script('jquery');
add_action('admin_print_styles', 'xyz_dbx_admin_style');

?>