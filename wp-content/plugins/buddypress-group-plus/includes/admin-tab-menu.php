<?php

function bpgp_admin_tab_name_slug() {
// main options
	?>
    <p><b>Group Tab Settings</b> - Please note the tab slug <b>must not</b> contain spaces, use a hyphen instead.</p>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Customize tab <b>name</b></th>
                <td><input style="overflow:auto;" name="group_plus_tab_name" value="<?php echo get_option('group_plus_tab_name') ?>"/></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
        Customize tab <b>slug</b>
        <br />
        <i>Please note the tab slug must not contain spaces, use a hyphen instead.</i>
        </th>
                <td><input style="overflow:auto;" name="group_plus_tab_slug" value="<?php echo get_option('group_plus_tab_slug') ?>"/></td>
		</tr>
</table>
<br />
	<?php
                }
// the text field admin options
function bpgp_admin_class_menu() {
	?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable Text Field</th>
    		<td><input type="checkbox" name="group_plus_textfield" value="1" <?php checked( 1, get_option('group_plus_textfield') ); ?> /></td>
		</tr>
</table>
    <?php
	if ( get_option('group_plus_textfield') == '1' ) {
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable <b>links</b></th>
    		<td><input type="checkbox" name="group_plus_textfield_link" value="1" <?php checked( 1, get_option('group_plus_textfield_link') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Customize the <b>name</b></th>
            <td><input style="overflow:auto;" name="group_plus_textfield_name" value="<?php echo get_option('group_plus_textfield_name') ?>"/></td>
		</tr>
</table>
<?php
	} else { //disbale it
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Enable <b>links</b></th>
    		<td><input disabled="disabled" type="checkbox" name="group_plus_textfield_link" value="1" <?php checked( 1, get_option('group_plus_textfield_link') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Customize the <b>name</b></th>
            <td><input disabled="disabled" style="overflow:auto;" name="group_plus_textfield_name" value="<?php echo get_option('group_plus_textfield_name') ?>"/></td>
		</tr>
</table>
<br />
<?php
	} 	
// the second text field admin options
	?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable Text Field (2)</th>
    		<td><input type="checkbox" name="group_plus_textfield2" value="1" <?php checked( 1, get_option('group_plus_textfield2') ); ?> /></td>
		</tr>
</table>
    <?php
	if ( get_option('group_plus_textfield2') == '1' ) {
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable <b>links</b></th>
    		<td><input type="checkbox" name="group_plus_textfield2_link" value="1" <?php checked( 1, get_option('group_plus_textfield2_link') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Customize the <b>name</b></th>
            <td><input style="overflow:auto;" name="group_plus_textfield_name2" value="<?php echo get_option('group_plus_textfield_name2') ?>"/></td>
		</tr>
</table>
<?php
	} else { //disbale it
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Enable <b>links</b></th>
    		<td><input disabled="disabled" type="checkbox" name="group_plus_textfield2_link" value="1" <?php checked( 1, get_option('group_plus_textfield2_link') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Customize the <b>name</b></th>
            <td><input disabled="disabled" style="overflow:auto;" name="group_plus_textfield_name2" value="<?php echo get_option('group_plus_textfield_name2') ?>"/></td>
		</tr>
</table>
<br />
<?php
	}
// the text area admin options  
?>  
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable Text Area</th>
    		<td><input type="checkbox" name="group_plus_textarea" value="1" <?php checked( 1, get_option('group_plus_textarea') ); ?> /></td>
		</tr>
</table>
    <?php
	if ( get_option('group_plus_textarea') == '1' ) {
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Customize the <b>name</b></th>
            <td><input style="overflow:auto;" name="group_plus_textarea_name" value="<?php echo get_option('group_plus_textarea_name') ?>"/></td>
		</tr>
</table>
<?php
	} else { //disbale it
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Customize the <b>name</b></th>
            <td><input disabled="disabled" style="overflow:auto;" name="group_plus_textarea_name" value="<?php echo get_option('group_plus_textarea_name') ?>"/></td>
		</tr>
</table>
<br />
<?php
	}
// the second text area admin options  
?>  
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable Text Area (2)</th>
    		<td><input type="checkbox" name="group_plus_textarea2" value="1" <?php checked( 1, get_option('group_plus_textarea2') ); ?> /></td>
		</tr>
</table>
    <?php
	if ( get_option('group_plus_textarea2') == '1' ) {
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Customize the <b>name</b></th>
            <td><input style="overflow:auto;" name="group_plus_textarea_name2" value="<?php echo get_option('group_plus_textarea_name2') ?>"/></td>
		</tr>
</table>
<?php
	} else { //disbale it
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Customize the <b>name</b></th>
            <td><input disabled="disabled" style="overflow:auto;" name="group_plus_textarea_name2" value="<?php echo get_option('group_plus_textarea_name2') ?>"/></td>
		</tr>
</table>
<br />
<?php
	}
// main options
	?>
<table class="form-table">
	<tr valign="top">
		<th style="width:210px;" scope="row">
        Display - field,area,field(2),area(2)<br /><br />
        <i>Default is field,field(2),area,area(2)</i>
        </th>
                <td><input type="checkbox" name="group_plus_field_display" value="1" <?php checked( 1, get_option('group_plus_field_display') ); ?> /></td>
		</tr>
</table>
<br />
	<?php
// the map admin options  
?>    
<table class="form-table">
	<tr valign="top">
		<th scope="row"><b>Enable Group Maps</b></th>
    		<td><input type="checkbox" name="group_plus_map" value="1" <?php checked( 1, get_option('group_plus_map') ); ?> /></td>
		</tr>
</table>
    <?php
	if ( get_option('group_plus_map') == '1' ) {
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Location field name</th>
            <td><input style="overflow:auto;" name="group_plus_map_name" value="<?php echo get_option('group_plus_map_name') ?>"/></td>
		</tr>
</table>
<?php
	} else { //disbale it
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Customize the <b>name</b></th>
            <td><input disabled="disabled" style="overflow:auto;" name="group_plus_map_name" value="<?php echo get_option('group_plus_map_name') ?>"/></td>
		</tr>
</table>
<?php
	}
}

?>