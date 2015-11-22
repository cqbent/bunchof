<?php


function dbx_install()
{
	global $wpdb;
	if(get_option('xyz_credit_link')=="")
	{
		add_option("xyz_credit_link", '0');
	}
	add_option("xyz_dbx_tinymce", '1');
	add_option("xyz_dbx_html", 'Hello world.');
	add_option("xyz_dbx_top", '25');
	add_option("xyz_dbx_width", '500');
	add_option("xyz_dbx_height", '50');
	add_option("xyz_dbx_left", '25');
	add_option("xyz_dbx_right", '25');
	add_option("xyz_dbx_bottom", '25');
	add_option("xyz_dbx_display_position", '1');
	add_option("xyz_dbx_delay", '0');
	add_option("xyz_dbx_page_count", '1');
	add_option("xyz_dbx_mode", 'delay_only'); //page_count_only,both are other options
	add_option("xyz_dbx_repeat_interval", '1');
	add_option("xyz_dbx_repeat_interval_timing", '1');//hrs or  minute
	add_option("xyz_dbx_z_index",'10000');
		
	add_option("xyz_dbx_corner_radius",'5');
	add_option("xyz_dbx_width_dim",'px');
	add_option("xyz_dbx_height_dim",'px');
		add_option("xyz_dbx_right_dim",'%');
		add_option("xyz_dbx_bottom_dim",'%');
		add_option("xyz_dbx_left_dim",'%');
		add_option("xyz_dbx_top_dim",'%');
	add_option("xyz_dbx_border_color",'#c33c3c');
	add_option("xyz_dbx_bg_color",'#ffffff');
	add_option("xyz_dbx_title",'Title');
	add_option("xyz_dbx_title_color",'#fcfcfa');
	add_option("xyz_dbx_border_width",'5');
	add_option("xyz_dbx_page_option",'1');
	add_option("xyz_dbx_close_button_option",'1');
	add_option("xyz_dbx_iframe",'0');
	
	add_option("xyz_dbx_positioning",'1');
	add_option("xyz_dbx_position_predefined","4");
	
	$version=get_option('xyz_dbx_free_version');
	$currentversion=xyz_dbx_plugin_get_version();
	if($version=="")
	{
		add_option("xyz_dbx_free_version", $currentversion);
	}
	else
	{
	
		update_option('xyz_dbx_free_version', $currentversion);
	}
	
}
register_activation_hook(XYZ_DBX_PLUGIN_FILE,'dbx_install');


?>