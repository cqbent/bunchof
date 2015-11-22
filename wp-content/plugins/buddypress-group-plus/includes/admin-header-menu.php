<?php

function bpgp_admin_header_menu() {
	?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable Text Field</th>
    		<td><input type="checkbox" name="group_plus_header_textfield" value="1" <?php checked( 1, get_option('group_plus_header_textfield') ); ?> /></td>
		</tr>
</table>
    <?php
	if ( get_option('group_plus_header_textfield') == '1' ) {
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable <b>links</b></th>
    		<td><input type="checkbox" name="group_plus_header_textfield_link" value="1" <?php checked( 1, get_option('group_plus_header_textfield_link') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Customize the <b>name</b></th>
            <td><input style="overflow:auto;" name="group_plus_header_textfield_name" value="<?php echo get_option('group_plus_header_textfield_name') ?>"/></td>
		</tr>
</table>
<?php
	} else { //disbale it
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Enable <b>links</b></th>
    		<td><input disabled="disabled" type="checkbox" name="group_plus_header_textfield_link" value="1" <?php checked( 1, get_option('group_plus_header_textfield_link') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Customize the <b>name</b></th>
            <td><input disabled="disabled" style="overflow:auto;" name="group_plus_header_textfield_name" value="<?php echo get_option('group_plus_header_textfield_name') ?>"/></td>
		</tr>
</table>
<?php
	}
	?>
<br />
<br />
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable Text Field (2)</th>
    		<td><input type="checkbox" name="group_plus_header_textfield2" value="1" <?php checked( 1, get_option('group_plus_header_textfield2') ); ?> /></td>
		</tr>
</table>
	<?php if ( get_option('group_plus_header_textfield2') == '1' ) {
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Enable <b>links</b></th>
    		<td><input type="checkbox" name="group_plus_header_textfield_link2" value="1" <?php checked( 1, get_option('group_plus_header_textfield_link2') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Customize the <b>name</b></th>
            <td><input style="overflow:auto;" name="group_plus_header_textfield_name2" value="<?php echo get_option('group_plus_header_textfield_name2') ?>"/></td>
		</tr>
</table>
<?php
	} else { //disbale it
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Enable <b>links</b></th>
    		<td><input disabled="disabled" type="checkbox" name="group_plus_header_textfield_link2" value="1" <?php checked( 1, get_option('group_plus_header_textfield_link2') ); ?> /></td>
		</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th scope="row" style="color:#888; text-decoration:line-through;">Customize the <b>name</b></th>
            <td><input disabled="disabled" style="overflow:auto;" name="group_plus_header_textfield_name2" value="<?php echo get_option('group_plus_header_textfield_name2') ?>"/></td>
		</tr>
</table>
<?php
	} 
}
?>