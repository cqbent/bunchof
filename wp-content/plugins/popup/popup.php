<?php

/*
Plugin Name: Popup
Plugin URI: http://www.gopiplus.com/work/2011/01/14/wordpress-popup/
Description: One easy way to send your visitors a welcome message, notice or advertisement is to add this popup plugin to your site. 
Author: Gopi.R
Version: 10.0
Author URI: http://www.gopiplus.com/work/2011/01/14/wordpress-popup/
Donate link: http://www.gopiplus.com/work/2011/01/14/wordpress-popup/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;

if (!session_id())
    session_start();

function PPOPOUPUP_activation()
{
	add_option('PPOPOUPUP_RANDOM', "YES");
	add_option('PPOPOUPUP_IMG1', "Sample text message 1");
	add_option('PPOPOUPUP_URL1', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_1.jpg");
	add_option('PPOPOUPUP_LINK1', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG2', "Sample text message 2");
	add_option('PPOPOUPUP_URL2', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_2.jpg");
	add_option('PPOPOUPUP_LINK2', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG3', "Sample text message 3");
	add_option('PPOPOUPUP_URL3', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_3.jpg");
	add_option('PPOPOUPUP_LINK3', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG4', "Sample text message 4");
	add_option('PPOPOUPUP_URL4', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_1.jpg");
	add_option('PPOPOUPUP_LINK4', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG5', "Sample text message 5");
	add_option('PPOPOUPUP_URL5', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_2.jpg");
	add_option('PPOPOUPUP_LINK5', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG6', "Sample text message 6");
	add_option('PPOPOUPUP_URL6', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_3.jpg");
	add_option('PPOPOUPUP_LINK6', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_SESSION', "NO");
}

function mypopup()
{
	if(get_option('PPOPOUPUP_SESSION') == "YES")
	{
		if ( $_SESSION['popupsession'] <> "YES" )
		{
			$_SESSION['popupsession'] = "YES"; 
			g_popup();
		}
	}
	else
	{
		g_popup();
	}
}

function g_popup() 
{
	if(get_option('PPOPOUPUP_RANDOM') == "YES") 
	{
		$randomalert =  rand(1, 5);
		$mytxt= array("PPOPOUPUP_IMG1", "PPOPOUPUP_IMG2", "PPOPOUPUP_IMG3", "PPOPOUPUP_IMG4", "PPOPOUPUP_IMG5", "PPOPOUPUP_IMG6");
		$myurl= array("PPOPOUPUP_URL1", "PPOPOUPUP_URL2", "PPOPOUPUP_URL3", "PPOPOUPUP_URL4", "PPOPOUPUP_URL5", "PPOPOUPUP_URL6");
		$mylink= array("PPOPOUPUP_LINK1", "PPOPOUPUP_LINK2", "PPOPOUPUP_LINK3", "PPOPOUPUP_LINK4", "PPOPOUPUP_LINK5", "PPOPOUPUP_LINK6");
		@$txt = get_option($mytxt[$randomalert]); 
		@$url = get_option($myurl[$randomalert]); 
		@$link = get_option($mylink[$randomalert]); 
	}
	else
	{
		@$imgurl =  $matches[1];
		if($imgurl == "IMG1") { 
			@$txt = get_option('PPOPOUPUP_IMG1'); 
			@$url = get_option('PPOPOUPUP_URL1'); 
			@$link = get_option('PPOPOUPUP_LINK1'); 
		} elseif($imgurl == "IMG2") { 
			@$txt= get_option('PPOPOUPUP_IMG2'); 
			@$url= get_option('PPOPOUPUP_URL2'); 
			@$link = get_option('PPOPOUPUP_LINK2');
		} elseif($imgurl == "IMG3") { 
			@$txt= get_option('PPOPOUPUP_IMG3'); 
			@$url= get_option('PPOPOUPUP_URL3'); 
			@$link = get_option('PPOPOUPUP_LINK3');
		} elseif($imgurl == "IMG4") { 
			@$txt= get_option('PPOPOUPUP_IMG4'); 
			@$url= get_option('PPOPOUPUP_URL4'); 
			@$link = get_option('PPOPOUPUP_LINK4');
		} elseif($imgurl == "IMG5") { 
			@$txt= get_option('PPOPOUPUP_IMG5'); 
			@$url= get_option('PPOPOUPUP_URL5'); 
			@$link = get_option('PPOPOUPUP_LINK5');
		} elseif($imgurl == "IMG6") { 
			@$txt= get_option('PPOPOUPUP_IMG6'); 
			@$url= get_option('PPOPOUPUP_URL6'); 
			@$link = get_option('PPOPOUPUP_LINK6');
		} else {
			@$txt= get_option('PPOPOUPUP_IMG1'); 
			@$url= get_option('PPOPOUPUP_URL1'); 
			@$link = get_option('PPOPOUPUP_LINK1');
		}
	}
	
	$PPOPOUPUP_siteurl = get_option('siteurl');
	$PPOPOUPUP_pluginurl = $PPOPOUPUP_siteurl . "/wp-content/plugins/popup/";
	
	if(get_option('PPOPOUPUP_SESSION') == "YES")
	{

		if ( $_SESSION['popupsession'] <> "YES" )
		{
			$_SESSION['popupsession'] = "YES"; 
			
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript" src="'. $PPOPOUPUP_pluginurl . 'popup.js"><script>';
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<link rel="stylesheet" type="text/css" href="'. $PPOPOUPUP_pluginurl . 'popup.css" />';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">PopupDivFunc();</script>';
			echo $PPOPOUPUP_txt;
		}

	}
	else
	{
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript" src="'. $PPOPOUPUP_pluginurl . 'popup.js"><script>';
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<link rel="stylesheet" type="text/css" href="'. $PPOPOUPUP_pluginurl . 'popup.css" />';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">PopupDivFunc();</script>';
			echo $PPOPOUPUP_txt;
	}
}

function PPOPOUPUP_deactivate() 
{

}

function PPOPOUPUP_add_to_menu() 
{
	add_options_page('Popup', 'Popup', 'manage_options', __FILE__, 'PPOPOUPUP_admin_options' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'PPOPOUPUP_add_to_menu');
}

function PPOPOUPUP_admin_options() 
{
	?>
	<div class="wrap">
	  <h2>POPUP</h2>
	  <?php
	global $wpdb, $wp_version;
	
	$PPOPOUPUP_RANDOM = get_option('PPOPOUPUP_RANDOM');
	$PPOPOUPUP_IMG1 = get_option('PPOPOUPUP_IMG1');
	$PPOPOUPUP_IMG2 = get_option('PPOPOUPUP_IMG2');
	$PPOPOUPUP_IMG3 = get_option('PPOPOUPUP_IMG3');
	$PPOPOUPUP_IMG4 = get_option('PPOPOUPUP_IMG4');
	$PPOPOUPUP_IMG5 = get_option('PPOPOUPUP_IMG5');
	$PPOPOUPUP_IMG6 = get_option('PPOPOUPUP_IMG6');
	
	$PPOPOUPUP_URL1 = get_option('PPOPOUPUP_URL1');
	$PPOPOUPUP_URL2 = get_option('PPOPOUPUP_URL2');
	$PPOPOUPUP_URL3 = get_option('PPOPOUPUP_URL3');
	$PPOPOUPUP_URL4 = get_option('PPOPOUPUP_URL4');
	$PPOPOUPUP_URL5 = get_option('PPOPOUPUP_URL5');
	$PPOPOUPUP_URL6 = get_option('PPOPOUPUP_URL6');
	
	$PPOPOUPUP_LINK1 = get_option('PPOPOUPUP_LINK1');
	$PPOPOUPUP_LINK2 = get_option('PPOPOUPUP_LINK2');
	$PPOPOUPUP_LINK3 = get_option('PPOPOUPUP_LINK3');
	$PPOPOUPUP_LINK4 = get_option('PPOPOUPUP_LINK4');
	$PPOPOUPUP_LINK5 = get_option('PPOPOUPUP_LINK5');
	$PPOPOUPUP_LINK6 = get_option('PPOPOUPUP_LINK6');
	
	$PPOPOUPUP_SESSION = get_option('PPOPOUPUP_SESSION');
	
	
	
	if (@$_POST['PPOPOUPUP_submit']) 
	{
		$PPOPOUPUP_RANDOM = stripslashes(trim($_POST['PPOPOUPUP_RANDOM']));
		$PPOPOUPUP_IMG1 = stripslashes(trim($_POST['PPOPOUPUP_IMG1']));
		$PPOPOUPUP_IMG2 = stripslashes(trim($_POST['PPOPOUPUP_IMG2']));
		$PPOPOUPUP_IMG3 = stripslashes(trim($_POST['PPOPOUPUP_IMG3']));
		$PPOPOUPUP_IMG4 = stripslashes(trim($_POST['PPOPOUPUP_IMG4']));
		$PPOPOUPUP_IMG5 = stripslashes(trim($_POST['PPOPOUPUP_IMG5']));
		$PPOPOUPUP_IMG6 = stripslashes(trim($_POST['PPOPOUPUP_IMG6']));
		
		$PPOPOUPUP_URL1 = stripslashes(trim($_POST['PPOPOUPUP_URL1']));
		$PPOPOUPUP_URL2 = stripslashes(trim($_POST['PPOPOUPUP_URL2']));
		$PPOPOUPUP_URL3 = stripslashes(trim($_POST['PPOPOUPUP_URL3']));
		$PPOPOUPUP_URL4 = stripslashes(trim($_POST['PPOPOUPUP_URL4']));
		$PPOPOUPUP_URL5 = stripslashes(trim($_POST['PPOPOUPUP_URL5']));
		$PPOPOUPUP_URL6 = stripslashes(trim($_POST['PPOPOUPUP_URL6']));
		
		$PPOPOUPUP_LINK1 = stripslashes(trim($_POST['PPOPOUPUP_LINK1']));
		$PPOPOUPUP_LINK2 = stripslashes(trim($_POST['PPOPOUPUP_LINK2']));
		$PPOPOUPUP_LINK3 = stripslashes(trim($_POST['PPOPOUPUP_LINK3']));
		$PPOPOUPUP_LINK4 = stripslashes(trim($_POST['PPOPOUPUP_LINK4']));
		$PPOPOUPUP_LINK5 = stripslashes(trim($_POST['PPOPOUPUP_LINK5']));
		$PPOPOUPUP_LINK6 = stripslashes(trim($_POST['PPOPOUPUP_LINK6']));
		
		$PPOPOUPUP_SESSION = stripslashes(trim($_POST['PPOPOUPUP_SESSION']));
		
		
		update_option('PPOPOUPUP_RANDOM', $PPOPOUPUP_RANDOM );
		update_option('PPOPOUPUP_IMG1', $PPOPOUPUP_IMG1 );
		update_option('PPOPOUPUP_IMG2', $PPOPOUPUP_IMG2 );
		update_option('PPOPOUPUP_IMG3', $PPOPOUPUP_IMG3 );
		update_option('PPOPOUPUP_IMG4', $PPOPOUPUP_IMG4 );
		update_option('PPOPOUPUP_IMG5', $PPOPOUPUP_IMG5 );
		update_option('PPOPOUPUP_IMG6', $PPOPOUPUP_IMG6 );
		
		update_option('PPOPOUPUP_URL1', $PPOPOUPUP_URL1 );
		update_option('PPOPOUPUP_URL2', $PPOPOUPUP_URL2 );
		update_option('PPOPOUPUP_URL3', $PPOPOUPUP_URL3 );
		update_option('PPOPOUPUP_URL4', $PPOPOUPUP_URL4 );
		update_option('PPOPOUPUP_URL5', $PPOPOUPUP_URL5 );
		update_option('PPOPOUPUP_URL6', $PPOPOUPUP_URL6 );
		
		update_option('PPOPOUPUP_LINK1', $PPOPOUPUP_LINK1 );
		update_option('PPOPOUPUP_LINK2', $PPOPOUPUP_LINK2 );
		update_option('PPOPOUPUP_LINK3', $PPOPOUPUP_LINK3 );
		update_option('PPOPOUPUP_LINK4', $PPOPOUPUP_LINK4 );
		update_option('PPOPOUPUP_LINK5', $PPOPOUPUP_LINK5 );
		update_option('PPOPOUPUP_LINK6', $PPOPOUPUP_LINK6 );
		
		update_option('PPOPOUPUP_SESSION', $PPOPOUPUP_SESSION );	
		
	}
	
	echo '<table width="100%" border="0" cellspacing="5" cellpadding="0">';
	echo '<tr>';
	echo '<td align="left">';
	echo '<form name="form_PopUpFad" method="post" action="">';
	
	echo '<p>Random message	:<br />	<input  style="width: 100px;" type="text" value="';
	echo $PPOPOUPUP_RANDOM . '" name="PPOPOUPUP_RANDOM" id="PPOPOUPUP_RANDOM" /> ( YES / NO ) </p>';
	echo '<br />';
	echo '<p>Popup IMG1 txt	:<br /> <input  style="width: 500px;" type="text" value="';
	echo $PPOPOUPUP_IMG1 . '" name="PPOPOUPUP_IMG1" id="PPOPOUPUP_IMG1" /> (IMG1)</p>';
	echo '<p>Popup IMG1 url :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_URL1 . '" name="PPOPOUPUP_URL1" id="PPOPOUPUP_URL1" /> </p>';
	echo '<p>Popup IMG1 Link (Page will redirect to this URL on image click) :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_LINK1 . '" name="PPOPOUPUP_LINK1" id="PPOPOUPUP_LINK1" /> </p>';
	echo '<br />';
	echo '<p>Popup IMG2 txt :<br /> <input  style="width: 500px;" type="text" value="';
	echo $PPOPOUPUP_IMG2 . '" name="PPOPOUPUP_IMG2" id="PPOPOUPUP_IMG2" /> (IMG2)</p>';
	echo '<p>Popup IMG2 url :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_URL2 . '" name="PPOPOUPUP_URL2" id="PPOPOUPUP_URL2" /> </p>';
	echo '<p>Popup IMG2 Link :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_LINK2 . '" name="PPOPOUPUP_LINK2" id="PPOPOUPUP_LINK2" /> </p>';
	echo '<br />';
	echo '<p>Popup IMG3 txt :<br /> <input  style="width: 500px;" type="text" value="';
	echo $PPOPOUPUP_IMG3 . '" name="PPOPOUPUP_IMG3" id="PPOPOUPUP_IMG3" /> (IMG3)</p>';
	echo '<p>Popup IMG3 url :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_URL3 . '" name="PPOPOUPUP_URL3" id="PPOPOUPUP_URL3" /> </p>';
	echo '<p>Popup IMG3 Link :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_LINK3 . '" name="PPOPOUPUP_LINK3" id="PPOPOUPUP_LINK3" /> </p>';
	echo '<br />';
	echo '<p>Popup IMG4 txt :<br /> <input  style="width: 500px;" type="text" value="';
	echo $PPOPOUPUP_IMG4 . '" name="PPOPOUPUP_IMG4" id="PPOPOUPUP_IMG4" /> (IMG4)</p>';
	echo '<p>Popup IMG4 url :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_URL4 . '" name="PPOPOUPUP_URL4" id="PPOPOUPUP_URL4" /> </p>';
	echo '<p>Popup IMG4 Link :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_LINK4 . '" name="PPOPOUPUP_LINK4" id="PPOPOUPUP_LINK4" /> </p>';
	echo '<br />';
	echo '<p>Popup IMG5 txt :<br /> <input  style="width: 500px;" type="text" value="';
	echo $PPOPOUPUP_IMG5 . '" name="PPOPOUPUP_IMG5" id="PPOPOUPUP_IMG5" /> (IMG5)</p>';
	echo '<p>Popup IMG5 url :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_URL5 . '" name="PPOPOUPUP_URL5" id="PPOPOUPUP_URL5" /> </p>';
	echo '<p>Popup IMG5 Link :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_LINK5 . '" name="PPOPOUPUP_LINK5" id="PPOPOUPUP_LINK5" /> </p>';
	echo '<br />';
	echo '<p>Popup IMG6 txt :<br /> <input  style="width: 500px;" type="text" value="';
	echo $PPOPOUPUP_IMG6 . '" name="PPOPOUPUP_IMG6" id="PPOPOUPUP_IMG6" /> (IMG6)</p>';
	echo '<p>Popup IMG6 url :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_URL6 . '" name="PPOPOUPUP_URL6" id="PPOPOUPUP_URL6" /> </p>';
	echo '<p>Popup IMG6 Link :<br /> <input  style="width: 600px;" type="text" value="';
	echo $PPOPOUPUP_LINK6 . '" name="PPOPOUPUP_LINK6" id="PPOPOUPUP_LINK6" /> </p>';
	echo '<br />';
	echo '<p>Popup session option:<br /> <input  style="width: 100px;" type="text" value="';
	echo $PPOPOUPUP_SESSION . '" name="PPOPOUPUP_SESSION" id="PPOPOUPUP_SESSION" maxlength="3" /> ( YES / NO ) </p>';
	
	echo '<input type="submit" id="PPOPOUPUP_submit" name="PPOPOUPUP_submit" lang="publish" class="button-primary" value="Update Setting" value="1" />';
	echo '</form>';
	echo '</td>';
	echo '<td align="left">';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	
	?>
	<br>
<strong>Plugin configuration option</strong>
<ol>
	<li>Option to add the popup into particular pages and posts.</li>
	<li>Option to add the popup window into all the pages in the website.</li>
</ol>
  Check official website for more information and live demo <a target="_blank" href='http://www.gopiplus.com/work/2011/01/14/wordpress-popup/'>click here</a>.<br>
  <br>
</div>
<?php
}

add_shortcode( 'popup', 'PPOPOUPUP_shortcode' );

function PPOPOUPUP_shortcode( $atts ) 
{
	// [popup show="ALL"] 
	if ( ! is_array( $atts ) ) { return ''; }
	$imgurl = strtoupper($atts['show']);
	
	$PPOPOUPUP_txt = "";
	
	//if(get_option('PPOPOUPUP_RANDOM') == "YES") 
	if($imgurl == "ALL") 
	{
		$randomalert =  rand(1, 5);
		$mytxt= array("PPOPOUPUP_IMG1", "PPOPOUPUP_IMG2", "PPOPOUPUP_IMG3", "PPOPOUPUP_IMG4", "PPOPOUPUP_IMG5", "PPOPOUPUP_IMG6");
		$myurl= array("PPOPOUPUP_URL1", "PPOPOUPUP_URL2", "PPOPOUPUP_URL3", "PPOPOUPUP_URL4", "PPOPOUPUP_URL5", "PPOPOUPUP_URL6");
		$mylink= array("PPOPOUPUP_LINK1", "PPOPOUPUP_LINK2", "PPOPOUPUP_LINK3", "PPOPOUPUP_LINK4", "PPOPOUPUP_LINK5", "PPOPOUPUP_LINK6");
		@$txt = get_option($mytxt[$randomalert]); 
		@$url = get_option($myurl[$randomalert]); 
		@$link = get_option($mylink[$randomalert]); 
	}
	else
	{
		//@$imgurl =  $matches[1];
		if($imgurl == "IMG1") { 
			@$txt = get_option('PPOPOUPUP_IMG1'); 
			@$url = get_option('PPOPOUPUP_URL1'); 
			@$link = get_option('PPOPOUPUP_LINK1'); 
		} elseif($imgurl == "IMG2") { 
			@$txt= get_option('PPOPOUPUP_IMG2'); 
			@$url= get_option('PPOPOUPUP_URL2'); 
			@$link = get_option('PPOPOUPUP_LINK2');
		} elseif($imgurl == "IMG3") { 
			@$txt= get_option('PPOPOUPUP_IMG3'); 
			@$url= get_option('PPOPOUPUP_URL3'); 
			@$link = get_option('PPOPOUPUP_LINK3');
		} elseif($imgurl == "IMG4") { 
			@$txt= get_option('PPOPOUPUP_IMG4'); 
			@$url= get_option('PPOPOUPUP_URL4'); 
			@$link = get_option('PPOPOUPUP_LINK4');
		} elseif($imgurl == "IMG5") { 
			@$txt= get_option('PPOPOUPUP_IMG5'); 
			@$url= get_option('PPOPOUPUP_URL5'); 
			@$link = get_option('PPOPOUPUP_LINK5');
		} elseif($imgurl == "IMG6") { 
			@$txt= get_option('PPOPOUPUP_IMG6'); 
			@$url= get_option('PPOPOUPUP_URL6'); 
			@$link = get_option('PPOPOUPUP_LINK6');
		} else {
			@$txt= get_option('PPOPOUPUP_IMG1'); 
			@$url= get_option('PPOPOUPUP_URL1'); 
			@$link = get_option('PPOPOUPUP_LINK1');
		}
	}
	
	$PPOPOUPUP_siteurl = get_option('siteurl');
	$PPOPOUPUP_pluginurl = $PPOPOUPUP_siteurl . "/wp-content/plugins/popup/";
	
	//echo "YESY- 1". $_SESSION['popupsession'];
	//echo "YESY- 1". get_option('PPOPOUPUP_SESSION');
	
	if(get_option('PPOPOUPUP_SESSION') == "YES")
	{

		if ( $_SESSION['popupsession'] <> "YES" )
		{
			$_SESSION['popupsession'] = "YES"; 
			
			//echo "YESY- 2". $_SESSION['popupsession'];
			
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript" src="'. $PPOPOUPUP_pluginurl . 'popup.js"><script>';
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<link rel="stylesheet" type="text/css" href="'. $PPOPOUPUP_pluginurl . 'popup.css" />';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">PopupDivFunc();</script>';
			return $PPOPOUPUP_txt;
		}

	}
	else
	{
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript" src="'. $PPOPOUPUP_pluginurl . 'popup.js"><script>';
			//$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<link rel="stylesheet" type="text/css" href="'. $PPOPOUPUP_pluginurl . 'popup.css" />';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">PopupDivFunc();</script>';
			return $PPOPOUPUP_txt;
	}
}

function PPOPOUPUP_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'popup', get_option('siteurl').'/wp-content/plugins/popup/popup.js');
		wp_enqueue_style( 'popup', get_option('siteurl').'/wp-content/plugins/popup/popup.css');
	}
}

add_action('wp_enqueue_scripts', 'PPOPOUPUP_javascript_files');	
register_activation_hook(__FILE__, 'PPOPOUPUP_activation');
register_deactivation_hook( __FILE__, 'PPOPOUPUP_deactivate' );
?>