<?php

require_once( '../../../../../wp-load.php' );

load_plugin_textdomain( 'tk-dialogs', false, '../' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

$taxonomy = 'tk-dialogscategory';
$tax_terms = get_terms( $taxonomy );


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php _e( 'Add List of Dialogs', 'tk-dialogs'); ?></title>
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
<form onsubmit="wpListDialogsDialog.insert(); return false;" action="#">
	<div>
		<label for="cat_name"><?php _e( 'Category filter', 'tk-dialogs'); ?></label>
		<select name="cat_name" id="cat_name" class="widefat">
	        <option value=""><?php _e('None', 'tk-dialogs'); ?></option>
	        <?php foreach( $tax_terms as $tax_term ): ?>
	        <option value="<?php echo $tax_term->name; ?>"><?php echo $tax_term->name; ?></option>
	        <?php endforeach; ?>
		</select>
	</div>	
		
	<!-- Order -->
	<div>
		<label for="orderby"><?php _e( 'Order listing by', 'tk-dialogs'); ?></label>
		<select name="orderby" id="orderby" class="widefat">
	        <option value="date"><?php _e( 'Date', 'tk-dialogs'); ?></option>
	        <option value="title"><?php _e( 'Title', 'tk-dialogs'); ?></option>
		</select>
	</div>
	
	<!-- Order -->
	<div>
		<label for="order"><?php _e( 'Order', 'tk-dialogs'); ?></label>
		<select name="order" id="order" class="widefat">
	        <option value="asc"><?php _e( 'Ascending', 'tk-dialogs'); ?></option>
	        <option value="desc"><?php _e( 'Descending', 'tk-dialogs'); ?></option>
		</select>
	</div>
	
	<!-- Linktext -->
	<div>
		<label for="tabname"><?php _e( 'Items per site', 'tk-dialogs'); ?></label>
		<input type="text" name="per_page" id="per_page" class="widefat" value="10" />
	</div>	
	
	<div class="buttons">
		<input type="button" id="cancel" name="cancel" value="<?php _e( 'Cancel', 'tk-dialogs'); ?>" onclick="tinyMCEPopup.close();" class="secondary" />
		<input type="button" id="insert" name="insert" value="<?php _e( 'Insert', 'tk-dialogs'); ?>" onclick="wpListDialogsDialog.insert();" class="primary" />
	</div>
</form>
	
</body>
</html>