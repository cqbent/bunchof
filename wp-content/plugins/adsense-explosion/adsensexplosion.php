<?php
/*
Plugin Name: Adsense Explosion
Plugin URI: http://adsensexplosion.wordpress.com/
Description: Adsense Explosion is a free plugin that automatically insert and optimize Google Adsense ads to your site for increase your profits. This plugin is compatible with the widget system too. Supported Language English, Italiano, Spanish.
Version: 1.1.4
Author: Romolo Cortese
Author URI: https://plus.google.com/u/0/108422143593585493577/about
*/
include_once('adsensexplosionopt.php');
$aeopt = new aeopt();
include_once('adsensexplosionwidget.php');
//include_once('adsensexplosionsearchwidget.php');
function aeopt_menu()
{
	global $aeopt;
	if(function_exists('add_options_page'))
	{
		add_options_page('Adsense Explosion', 'Adsense Explosion', 'administrator', __FILE__ , array(&$aeopt, 'admin_menu'));
	}
}
function ae_plugin_actions($links, $file)
{
	static $this_plugin;
	if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if($file == $this_plugin)
	{
		$settings_link = '<a href="options-general.php?page=adsense-explosion/adsensexplosion.php">' . __('Settings') . '</a>';
		$links = array_merge(array($settings_link), $links);
	}
	return $links;
}
if(is_admin())
{
	add_action('admin_menu', 'aeopt_menu');
//	add_action('admin_init', array($aeopt, 'aeopt_admin_init'));
    add_action('admin_enqueue_scripts', array($aeopt, 'aeopt_admin_init'));
	add_filter('plugin_action_links', 'ae_plugin_actions', 10, 2);
} else {
	add_filter('init', array($aeopt, 'aeopt_init'),-2147483647);
	add_action('wp_footer', array($aeopt, 'aeopt_debug'));
	add_filter('the_post', array($aeopt, 'post_aeopt'),2147483647);
	add_action('loop_end', array($aeopt, 'destroy_count'),2147483647);
	add_filter('the_content', array($aeopt, 'adsenseoptimize'),2147483647);
    add_action('wp_head', array($aeopt, 'aeopt_async_init') );
}
add_action('widgets_init', 'ae_load_widgets');
function ae_load_widgets()
{
	register_widget('adsense_plugin_explosion_Widget');
//	register_widget('adsense_plugin_explosion_search_Widget');
}
?>