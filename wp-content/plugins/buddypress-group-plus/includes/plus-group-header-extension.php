<?php

add_filter( 'groups_custom_group_fields_editable', 'group_header_fields_markup' );
add_action( 'groups_group_details_edited', 'group_header_fields_save' );
add_action( 'groups_created_group',  'group_header_fields_save' );

function plus_field_one() {
	global $bp, $wpdb;
	$field_one = groups_get_groupmeta( $bp->groups->current_group->id, 'group_plus_header_field-one' );
	return $field_one;
}

function plus_field_two() {
	global $bp, $wpdb;
	$field_two = groups_get_groupmeta( $bp->groups->current_group->id, 'group_plus_header_field-two' );
	return $field_two;
}

function group_header_fields_markup() {
global $bp, $wpdb;

 if (get_option('group_plus_header_textfield') == '1') { ?>
	<label for="group-field-one"><?php echo get_option('group_plus_header_textfield_name') ?></label>
	<input type="text" name="group-field-one" id="group-field-one" value="<?php echo plus_field_one(); ?>" />
    <?php }
 if (get_option('group_plus_header_textfield2') == '1') { ?>
    <label for="group-field-two"><?php echo get_option('group_plus_header_textfield_name2') ?></label>
	<input type="text" name="group-field-two" id="group-field-two" value="<?php echo plus_field_two(); ?>" />
<?php  }


}
// show the group meta in group header
function show_field_in_header( $plus_field_meta ) {

		if (!plus_field_one() == "") {

if (get_option('group_plus_header_textfield_link') == '1') {
$plus_field_meta .= '<div class="gtags-header">'. get_option('group_plus_header_textfield_name') .': <a href="'. esc_url(plus_field_one()) .'" title="' . bp_get_group_name() . '&#146s website - opens in a new tab" target="_blank">' . plus_field_one() . '</a></div>'; // create the link
	} else {
$plus_field_meta .= '<div class="gtags-header">'. get_option('group_plus_header_textfield_name') .': '. plus_field_one() .'</div>'; // normal	
	}
		} else { $plus_field_meta .= '';
	}
	
	return $plus_field_meta;
}
if (get_option('group_plus_header_textfield') == '1') { add_filter( 'bp_get_group_description', 'show_field_in_header' ); }

// show the group meta in group header
function show_field_in_header2( $plus_field_meta2 ) {

		if (!plus_field_two() == "") {

if (get_option('group_plus_header_textfield_link2') == '1') {
$plus_field_meta2 .= '<div class="plus-group-header">'. get_option('group_plus_header_textfield_name2') .': <a href="'. esc_url(plus_field_two()) .'" title="' . bp_get_group_name() . '&#146s website - opens in a new tab" target="_blank">' . plus_field_two() . '</a></div>'; // create the link
	} else {
$plus_field_meta2 .= '<div class="plus-group-header">'. get_option('group_plus_header_textfield_name2') .': '. plus_field_two() .'</div>'; // normal	
	}
		} else { $plus_field_meta2 .= '';
	}

	return $plus_field_meta2;
}
if (get_option('group_plus_header_textfield2') == '1') { add_filter( 'bp_get_group_description', 'show_field_in_header2' ); }

// save the group header meta
function group_header_fields_save( $group_id ) {
	global $bp, $wpdb;

	$plain_fields = array(
		'field-one',
		'field-two'
	);
	foreach( $plain_fields as $field ) {
		$key = 'group-' . $field;
		if ( isset( $_POST[$key] ) ) {
			$value = $_POST[$key];
			groups_update_groupmeta( $group_id, 'group_plus_header_' . $field, $value );
		}
	}
}


?>