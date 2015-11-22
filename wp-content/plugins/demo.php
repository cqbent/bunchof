<?php
/*
Plugin Name: BuddyPress Group Dress Code
Plugin URI:
Description: This plugin adds an additional field to group creation for a dress code
Version: 1.0
Revision Date: June 25, 2010
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Charl Kruger
Author URI:
*/

/* Show group dresscode number in group header */
$gdcode_show_dresscode_in_header = true ;

/* Ð HERE BEGINS THE CODE Ð */

// create the form to add the field
function gdcode_add_dresscode_form() {
?>

<input type="text" name="group-dresscode" id="group-dresscode" value="Ó />

<?php

}
add_action( Ôgroups_custom_group_fields_editableÕ, Ôgdcode_add_dresscode_formÕ );

// Save the dresscode number in the group meta Ð perhaps use serialize() and maybe_unserialize()
function gdcode_save_dresscode( $group_id ) {
global $bp;

if($bp->groups->new_group_id)
$id = $bp->groups->new_group_id;
else
$id = $group_id;

if ( $_POST )
groups_update_groupmeta( $id, Ôgdcode_group_dresscodeÕ, $_POST );
}

// Get or return the dresscode number
function gdcode_group_dresscode() {
echo gdcode_get_group_dresscode();
}
function gdcode_get_group_dresscode( $group = false ) {
global $groups_template;
if ( !$group )
$group =& $groups_template->group;
$group_dresscode = groups_get_groupmeta( $group->id, Ôgdcode_group_dresscodeÕ );
$group_dresscode = stripcslashes( $group_dresscode );
return apply_filters( Ôgdcode_get_group_dresscodeÕ, $group_dresscode );
}

// show dresscode number in group header
function gdcode_show_dresscode_in_header( $description ) {
global $gdcode_show_dresscode_in_header;
if ( gdcode_get_group_dresscode() && $gdcode_show_dresscode_in_header ) {
$description .= Ô

Ô. __(ÔDress codeÕ, ÔgdcodeÕ).Õ: 
Ô.gdcode_make_dresscode_for_group().Ó;
}
return $description;
}
add_filter( Ôbp_get_group_descriptionÕ, Ôgdcode_show_dresscode_in_headerÕ );

// show number for an individual group
function gdcode_make_dresscode_for_group() {
global $bp, $wpdb, $gdcode_args;

$group_dresscode = gdcode_get_group_dresscode();
$group_dresscode = Ô

Ô.$group_dresscode.Õ
Ô;

return $group_dresscode;
}

add_action( Ôgroups_create_group_step_save_group-detailsÕ, Ôgdcode_save_dresscodeÕ );
add_action( Ôgroups_details_updatedÕ, Ôgdcode_save_dresscodeÕ );
?>