<?php
/* Text Field Stuff */
//return the text field info
function plus_textfield_info() {
	global $bp, $wpdb;
	$text = groups_get_groupmeta( $bp->groups->current_group->id, 'group_plus_textfield_field' );
	return $text;
}

function bpgp_group_textfield_field() {
	global $bp, $wpdb;
	?>
	<label for="group_plus_textfield_field"><?php echo get_option('group_plus_textfield_name') ?><?php echo 'required'; ?></label>
	<input type="text" aria-required="true" name="group_plus_textfield_field" id="group_plus_textfield_field" value="<?php echo plus_textfield_info() ?>" />
    <?php
    }

function bpgp_show_group_textfield() {
	
		if (!plus_textfield_info() == "") {
	
	?>
    	<h5><?php echo get_option('group_plus_textfield_name'); ?></h5>
<?php // adding the links ?>

		<?php if (get_option('group_plus_textfield_link') == '1') {
			$group_website = esc_url(plus_textfield_info());
					?><p><a href="<?php echo $group_website ?>" title="<?php echo bp_get_group_name() ?>&#146s website - opens in a new tab" target="_blank"><?php echo plus_textfield_info() ?></a></p><?php ;
			} else {
					?><p><?php echo plus_textfield_info() ?></p><?php ;
			} ?>
  <?php
		}
}
// second text field stuff
function plus_textfield_info2() {
	global $bp, $wpdb;
	$text2 = groups_get_groupmeta( $bp->groups->current_group->id, 'group_plus_textfield_field2' );
	return $text2;
}

function bpgp_group_textfield_field2() {
	global $bp, $wpdb;
	?>
	<label for="group_plus_textfield_field2"><?php echo get_option('group_plus_textfield_name2') ?></label>
	<input type="text" name="group_plus_textfield_field2" id="group_plus_textfield_field2" value="<?php echo plus_textfield_info2() ?>" />
    <?php
    }

function bpgp_show_group_textfield2() {
	
	if (!plus_textfield_info2() == "") {
	
	?>
    	<h5><?php echo get_option('group_plus_textfield_name2'); ?></h5>
<?php // adding the links ?>

		<?php if (get_option('group_plus_textfield2_link') == '1') {
			$group_website2 = esc_url(plus_textfield_info2());
					?><p><a href="<?php echo $group_website2 ?>" title="<?php echo bp_get_group_name() ?>&#146s website - opens in a new tab" target="_blank"><?php echo plus_textfield_info2() ?></a></p><?php ;
			} else {
					?><p><?php echo plus_textfield_info2() ?></p><?php ;
			} ?>

  <?php
	}
}
?>