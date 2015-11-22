<?php
/*
Plugin Name: Simple Popup Plugin
Plugin URI: http://www.grimmdude.com/wordpress-simple-popup-plugin
Description: This plugin makes it easy to create a simple, modifiable popup window.  Using the shortcode you can create a popup link and set the dimensions of each individual popup.
Version: 4.1
Author: Garrett Grimm
Author URI: http://www.grimmdude.com
*/

/*  Copyright 2012  Garrett Grimm  (email : garrett@grimmdude.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once('simple-popup-widget.php');

//Javascript to be placed in header.php
function popup_plugin_script(){
    $width=(get_option('popup_window_width')==null)?'750':get_option('popup_window_width');
    $height=(get_option('popup_window_height')==null)?'550':get_option('popup_window_height');
    $myleft=(get_option('popup_window_left')==null || (get_option('popup_window_left')==0)) ? '0' : get_option('popup_window_left');
    $mytop=(get_option('popup_window_top')==null || (get_option('popup_window_top')==0))  ? '0' : get_option('popup_window_top');
    $scrollbar=(get_option('popup_scrollbar')==1) ? "yes" : "no";
    $toolbar=(get_option('popup_window_toolbar')==1) ? "yes" : "no";
    $location=(get_option('popup_window_location')==1) ? "yes" : "no";
	?>
	<!--Simple Popup Plugin v4.0 / RH Mods-->
	<script language="javascript" type="text/javascript">
	<!--
	var swin=null;
	function popitup(mypage,w,h,pos,myname,infocus){
	    if (w!=parseInt(w)||w<=0) w=$width;
	    if (h!=parseInt(h)||h<=0) h=$height;
	    if (myname==null){myname="swin"};
	    var myleft = <?php echo $myleft; ?>;
	    var mytop = <?php echo $mytop; ?>;
	    if (myleft==0 && mytop==0 && pos!="random"){pos="center"};
	    if (pos=="random"){myleft=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;mytop=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
	    if (pos=="center"){myleft=(screen.width)?(screen.width-w)/2:100;mytop=(screen.height)?(screen.height-h)/2:100;}
	    settings="width=" + w + ",height=" + h + ",top=" + mytop + ",left=" + myleft + ",scrollbars=$scrollbar,location=$location,directories=no,status=no,menubar=no,toolbar=$toolbar,resizable=no";swin=window.open(mypage,myname,settings);
	    if (infocus==null || infocus=="front"){swin.focus()};
	    return false;
	}
	// -->
	</script>
	<!--/Simple Popup Plugin-->
	<?php
}

//inserts script in head of document
add_action ( 'wp_head', 'popup_plugin_script' );

//Options page
add_action('admin_menu', 'simple_popup_menu');

//Set option defaults only if option doesn't exist
add_option('popup_window_width', '500');
add_option('popup_window_height', '500');
add_option('popup_window_left', '0');
add_option('popup_window_top', '0');

//defines shortcode
add_shortcode('popup', 'popup_plugin_shortcode');

add_action('init', 'simple_popup_Widget_init', 1);

function simple_popup_menu() {
    add_options_page('Simple Popup Plugin Options', 'Simple Popup Plugin', 8, __FILE__, 'simple_popup_options');
}

function simple_popup_options() {
    include('simple_popup_admin.php');
}

function popup_plugin_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url' => '',
		'class' => '',
		'height' => get_option('popup_window_height'),
		'width' => get_option('popup_window_width'),
	), $atts ) );
    return "<a href='{$url}' onclick='return popitup(this.href, {$width}, {$height});' class='simple_popup_link {$class}'>$content</a>";    
}

//defines tag for theme templates
function simple_popup_link( $templateurl, $link_text ) {
    echo "<a href='$templateurl' onclick='return popitup(this.href);' class='simple_popup_link'>$link_text</a>";    
}

function simple_popup_Widget_init() {
    register_widget('simple_popup_Widget');
}