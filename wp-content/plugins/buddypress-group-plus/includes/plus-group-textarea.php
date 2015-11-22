<?php
/* Text Area Stuff */
//return the text area info
function plus_textarea_info() {
	global $bp, $wpdb;
	$info = groups_get_groupmeta( $bp->groups->current_group->id, 'group_plus_textarea_field' );
	return $info;
}

function bpgp_group_textarea_field() {
	global $bp, $wpdb;
	?>
	<label for="group_plus_textarea_field"><?php echo get_option('group_plus_textarea_name') ?></label>
	<textarea name="group_plus_textarea_field" id="group_plus_textarea_field"><?php echo plus_textarea_info() ?></textarea>
    <?php
    }

function bpgp_show_group_textarea() {
	
		if (!plus_textarea_info() == "") {
	
	?>
    	<h5><?php echo get_option('group_plus_textarea_name'); ?></h5>
		<p><?php echo plus_textarea_info() ?></p>

  <?php
		}
}

//return the second text area info
function plus_textarea_info2() {
	global $bp, $wpdb;
	$info2 = groups_get_groupmeta( $bp->groups->current_group->id, 'group_plus_textarea_field2' );
	return $info2;
}

function bpgp_group_textarea_field2() {
	global $bp, $wpdb;
	?>
	<label for="group_plus_textarea_field2"><?php echo get_option('group_plus_textarea_name2') ?></label>
	<textarea name="group_plus_textarea_field2" id="group_plus_textarea_field2"><?php echo plus_textarea_info2() ?></textarea>
    <?php
    }

function bpgp_show_group_textarea2() {
	
		if (!plus_textarea_info2() == "") {
	
	?>
    	<h5><?php echo get_option('group_plus_textarea_name2'); ?></h5>
		<p><?php echo plus_textarea_info2() ?></p>

  <?php
		}
}
?>