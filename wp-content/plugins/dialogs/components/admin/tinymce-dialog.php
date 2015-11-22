<?php

require_once( '../../../../../wp-load.php' );

load_plugin_textdomain( 'tk-dialogs', false, '../' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

$dialogs = get_posts(
	    array(
		'numberposts' => 100,
		'orderby' => 'title',
		'order' => 'ASC',
		'post_type' => 'tk-dialogs',
		'post_status' => 'publish',
	    )
);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php _e( 'Add Dialog', 'tk-dialogs'); ?></title>
	<script type="text/javascript" src="<?php echo home_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script type="text/javascript" src="../../includes/js/tinymce-insert.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/css/tinymce-popup.css">
	<style type="text/css">
	.size label{
		font-size: 10px;
	}
	</style>
</head>
<body>
<form onsubmit="wpDialogDialog.insert(); return false;" action="#">
	<div>
		<label for="dialog_id"><?php _e( 'Dialog', 'tk-dialogs'); ?></label>
		<select name="dialog_id" id="dialog_id" class="widefat">
	        <!--<option value="0"><?php _e('Choose an dialog to display', ''); ?></option>-->
	        <?php foreach($dialogs as $dialog): ?>
	        <option value="<?php echo $dialog->ID; ?>"><?php echo $dialog->post_title; ?></option>
	        <?php endforeach; ?>
		</select>
	</div>	
		
	<!-- Linktext -->
	<div>
		<label for="tabname"><?php _e( 'Linktext', 'tk-dialogs'); ?></label>
		<input type="text" name="linktext" id="linktext" class="widefat" />
	</div>	
	
	<!-- Width & Height -->
	<div class="size">
		<p><?php _e( 'Window size', 'tk-dialogs'); ?></p>
		<div style="float:left;width:50%"><label for="width"><?php _e( 'Width:', 'tk-dialogs'); ?></label> <input type="text" name="width" id="width" style="width:75px" /> <small><?php _e( 'px', 'tk-dialogs'); ?></small></div> 
		<div style="float:right;width:50%"><label for="Height"><?php _e( 'Height:', 'tk-dialogs'); ?></label> <input type="text" name="height" id="height" style="width:75px" /> <small><?php _e( 'px', 'tk-dialogs'); ?></small></div>
		<div style="clear: both;"></div> 
	</div>		

	
	<div class="buttons">
		<input type="button" id="cancel" name="cancel" value="<?php _e( 'Cancel', 'tk-dialogs'); ?>" onclick="tinyMCEPopup.close();" class="secondary" />
		<input type="button" id="insert" name="insert" value="<?php _e( 'Insert', 'tk-dialogs'); ?>" onclick="wpDialogDialog.insert();" class="primary" />
	</div>
</form>
	
</body>
</html>