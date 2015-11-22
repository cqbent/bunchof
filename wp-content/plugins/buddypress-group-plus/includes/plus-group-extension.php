<?php
class My_Group_Extension extends BP_Group_Extension {  
 
    function my_group_extension() {
        $this->name = get_option('group_plus_tab_name');
        $this->slug = get_option('group_plus_tab_slug');
 
        $this->create_step_position = 21;
        $this->nav_item_position = 31;
    }
 
    function create_screen() {
        if ( !bp_is_group_creation_step( $this->slug ) )
            return false;
        ?>
        
        	<?php if (get_option('group_plus_field_display') == '1') {
        		if (function_exists('bpgp_group_textfield_field') && get_option('group_plus_textfield') == '1') bpgp_group_textfield_field();
				if (function_exists('bpgp_group_textarea_field') && get_option('group_plus_textarea') == '1') bpgp_group_textarea_field();
        		if (function_exists('bpgp_group_textfield_field2') && get_option('group_plus_textfield2') == '1') bpgp_group_textfield_field2();
        		if (function_exists('bpgp_group_textarea_field2') && get_option('group_plus_textarea2') == '1') bpgp_group_textarea_field2();
        		if (function_exists('bpgp_group_maps_field') && get_option('group_plus_map') == '1') bpgp_group_maps_field();
			} else {
        		if (function_exists('bpgp_group_textfield_field') && get_option('group_plus_textfield') == '1') bpgp_group_textfield_field();
        		if (function_exists('bpgp_group_textfield_field2') && get_option('group_plus_textfield2') == '1') bpgp_group_textfield_field2();
				if (function_exists('bpgp_group_textarea_field') && get_option('group_plus_textarea') == '1') bpgp_group_textarea_field();
        		if (function_exists('bpgp_group_textarea_field2') && get_option('group_plus_textarea2') == '1') bpgp_group_textarea_field2();
        		if (function_exists('bpgp_group_maps_field') && get_option('group_plus_map') == '1') bpgp_group_maps_field();
			} ?>
 
        <?php
        wp_nonce_field( 'groups_create_save_' . $this->slug );
    }
 
    function create_screen_save() {
        global $bp;
 
        check_admin_referer( 'groups_create_save_' . $this->slug );
 
        /* Save any details submitted here */
        $this->settings_save( $bp->groups->new_group_id );
    }
 
    function edit_screen() {
        if ( !bp_is_group_admin_screen( $this->slug ) )
            return false; ?>
 
        <h2><?php echo esc_attr( $this->name ) ?></h2>
		
        	<?php if (get_option('group_plus_field_display') == '1') {
        		if (function_exists('bpgp_group_textfield_field') && get_option('group_plus_textfield') == '1') bpgp_group_textfield_field();
				if (function_exists('bpgp_group_textarea_field') && get_option('group_plus_textarea') == '1') bpgp_group_textarea_field();
        		if (function_exists('bpgp_group_textfield_field2') && get_option('group_plus_textfield2') == '1') bpgp_group_textfield_field2();
        		if (function_exists('bpgp_group_textarea_field2') && get_option('group_plus_textarea2') == '1') bpgp_group_textarea_field2();
        		if (function_exists('bpgp_group_maps_field') && get_option('group_plus_map') == '1') bpgp_group_maps_field();
			} else {
        		if (function_exists('bpgp_group_textfield_field') && get_option('group_plus_textfield') == '1') bpgp_group_textfield_field();
        		if (function_exists('bpgp_group_textfield_field2') && get_option('group_plus_textfield2') == '1') bpgp_group_textfield_field2();
				if (function_exists('bpgp_group_textarea_field') && get_option('group_plus_textarea') == '1') bpgp_group_textarea_field();
        		if (function_exists('bpgp_group_textarea_field2') && get_option('group_plus_textarea2') == '1') bpgp_group_textarea_field2();
        		if (function_exists('bpgp_group_maps_field') && get_option('group_plus_map') == '1') bpgp_group_maps_field();
			} ?>
			<p class="submit">
				<p><input type="submit" value="<?php _e( 'Save Changes' ) ?>" id="save" name="save" /></p>
			</p>
 
        <?php
        wp_nonce_field( 'groups_edit_save_' . $this->slug );
    }
 
    function edit_screen_save() {
        global $bp;
 
        if ( !isset( $_POST['save'] ) )
            return false;
 
        check_admin_referer( 'groups_edit_save_' . $this->slug );
 
        /* Insert your edit screen save code here */
        $this->settings_save( $bp->groups->current_group->id );
		/* Need to work this */
		$success = true;
		
        /* To post an error/success message to the screen, use the following */
        if ( !$success )
            bp_core_add_message( __( 'There was an error saving, please try again', 'buddypress' ), 'error' );
        else
            bp_core_add_message( __( 'Settings saved successfully', 'buddypress' ) );
 
        bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . 'admin/' . $this->slug );
    }
 
    function display() {

        /* Use this function to display the actual content of your group extension when the nav item is selected */
		
		echo '<div id="plus-tab-holder" style="overflow:hidden">';
		
		if (function_exists('bpgp_show_group_maps') && get_option('group_plus_map') == '1') {
					echo '<div id="plus-info-holder" style="float: left;width: 70%;">';
			} else {
					echo '<div id="plus-info-holder" style="float: left;width: 100%;">';
			}
		
		
			if (get_option('group_plus_field_display') == '1') {
	
				if (function_exists('bpgp_group_textfield_field') && get_option('group_plus_textfield') == '1') bpgp_show_group_textfield();
				if (function_exists('bpgp_group_textarea_field') && get_option('group_plus_textarea') == '1') bpgp_show_group_textarea();
				if (function_exists('bpgp_group_textfield_field2') && get_option('group_plus_textfield2') == '1') bpgp_show_group_textfield2();
				if (function_exists('bpgp_group_textarea_field2') && get_option('group_plus_textarea2') == '1') bpgp_show_group_textarea2();
			} else {
				if (function_exists('bpgp_group_textfield_field') && get_option('group_plus_textfield') == '1') bpgp_show_group_textfield();
				if (function_exists('bpgp_group_textfield_field2') && get_option('group_plus_textfield2') == '1') bpgp_show_group_textfield2();
				if (function_exists('bpgp_group_textarea_field') && get_option('group_plus_textarea') == '1') bpgp_show_group_textarea();
				if (function_exists('bpgp_group_textarea_field2') && get_option('group_plus_textarea2') == '1') bpgp_show_group_textarea2();
			}
		echo '</div>';
		
		if (function_exists('bpgp_show_group_maps') && get_option('group_plus_map') == '1') bpgp_show_group_maps();
		
		echo '</div>';
    }
 
    function widget_display() { ?>
        <div class=&quot;info-group&quot;>
            <h4><?php echo esc_attr( $this->name ) ?></h4>
            <p>
                You could display a small snippet of information from your group extension here. It will show on the group
                home screen.
            </p>
        </div>
        <?php
    }
	
function settings_save( $group_id ) {
	global $bp, $wpdb;

	$plain_fields = array(
		'map_field',
		'textfield_field',
		'textfield_field2',
		'textarea_field',
		'textarea_field2'
	);
	foreach( $plain_fields as $field ) {
		$key = 'group_plus_' . $field;
		if ( isset( $_POST[$key] ) ) {
			$value = $_POST[$key];
			groups_update_groupmeta( $group_id, 'group_plus_' . $field, $value );
		}
	}
	
}
}
bp_register_group_extension( 'My_Group_Extension' );
?>