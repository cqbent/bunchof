<?php

global $profile_privacy_use_radio_buttons;


$profile_privacy_use_radio_buttons = 0;


// TODO: Add filter in featured members, get random profile field (disable viewing private fields)

define( 'CPT_BP_XPROFILE_PRIVACY_FIELD', 'privacy_level' );
define( 'CPT_BP_XPROFILE_PRIVACY_FIELD_DEF_VALUE', '3' );
define( 'CPT_BP_XPROFILE_PRIVACY_DB_VERSION', '1.0' );

define( 'CPT_BP_XPROFILE_PRIVACY_CACHE', 'bp_xprofile_privacy_fields_' );


/**
 * Install required database parts.
 */
function cpt_bp_xprofile_privacy_install() {
	global $wpdb, $bp;

	// Check if xprofile data table exists. If not, return.
	if( $wpdb->get_var("SHOW TABLES LIKE '{$bp->profile->table_name_data}'") != $bp->profile->table_name_data ){
		return;
	}

	// Check if profile privacy field already exists and if not add it to profile field data table.
	$field_sql = "SHOW COLUMNS FROM {$bp->profile->table_name_data}
			LIKE '" . CPT_BP_XPROFILE_PRIVACY_FIELD . "'";
	if( $wpdb->get_var($field_sql) != CPT_BP_XPROFILE_PRIVACY_FIELD ){

		$sql = 	"ALTER TABLE " . $bp->profile->table_name_data .
				" ADD COLUMN " . CPT_BP_XPROFILE_PRIVACY_FIELD .
				" smallint NOT NULL " .
				"DEFAULT " . CPT_BP_XPROFILE_PRIVACY_FIELD_DEF_VALUE;

		$wpdb->query($sql);

		add_option( "cpt_bp_xprofile_privacy_db_version", CPT_BP_XPROFILE_PRIVACY_DB_VERSION );
	}
}
add_action( 'admin_menu', 'cpt_bp_xprofile_privacy_install' );


if ( !function_exists( 'cpt_bp_xprofile_privacy_add_css' ) ) :
function cpt_bp_xprofile_privacy_add_css() {
	/* Enqueue the structure CSS file to give basic positional formatting for components */
	$path = explode( '/', dirname(__FILE__) );
	$last_dir = $path[count($path)-1];
	wp_enqueue_style( 'ctp-bp-xprofile-privacy', WP_PLUGIN_URL . '/' . $last_dir . '/css/xprofile/privacy.css' );
}
endif;
add_action( 'wp_head', 'cpt_bp_xprofile_privacy_add_css',0 );


// Returns list of defined values for privacy settings
// TODO: this should be pulled from database
function cpt_bp_xprofile_privacy_get_options() {

	$allowed_values = array(
		'PRIVATE'  => '0',
		'FRIENDS'  => '1',
		'PUBLIC'   => '2',
		'PUBLIC_LOGGED_IN'   => '3'
		);
	return $allowed_values;
}

// Returns list of defined values for privacy settings
// TODO: this should be pulled from database
function cpt_bp_xprofile_privacy_get_options_text() {

	$allowed_values = array(
		'PRIVATE'  => 'Just me',
		'FRIENDS'  => 'My friends',
		'PUBLIC'   => 'Everyone',
		'PUBLIC_LOGGED_IN'   => 'Everyone logged in'
		);
	return $allowed_values;
}

if ( !function_exists( 'cpt_bp_xprofile_privacy_field_html' ) ) :
function cpt_bp_xprofile_privacy_field_html(){
	global $field,	$profile_privacy_use_radio_buttons;

	// TODO: add option for admin to select what type of field will privacy settings be (radio, selectbox)

	if ($profile_privacy_use_radio_buttons) 
		echo cpt_bp_xprofile_privacy_field_html_radio( $field );
	else 
		echo cpt_bp_xprofile_privacy_field_html_select( $field );
}
endif;

add_action( 'bp_custom_profile_edit_fields', 'cpt_bp_xprofile_privacy_field_html');


function cpt_bp_xprofile_privacy_field_html_select( $field ){
	global $wpdb, $bp;

	$options = cpt_bp_xprofile_privacy_get_options();
	$options_text = cpt_bp_xprofile_privacy_get_options_text();

	if ( empty( $options ) )
		return null;

	$sql = $wpdb->prepare(
			"SELECT " . CPT_BP_XPROFILE_PRIVACY_FIELD . "
			FROM {$bp->profile->table_name_data}
			WHERE user_id = %d AND field_id = %d",
			$bp->displayed_user->id, $field->id );

	$privacy_level = $wpdb->get_var( $sql );

	if ( null == $privacy_level ) {
		$privacy_level = CPT_BP_XPROFILE_PRIVACY_FIELD_DEF_VALUE;
	}

	$option_title = 'field_' . $field->id . '_' . CPT_BP_XPROFILE_PRIVACY_FIELD;

	$html = '<div class="privacy_buttons_wrap radio signup-field privacy" id="field_' . $field->id . '_' . CPT_BP_XPROFILE_PRIVACY_FIELD .'">'.
			//'<div class="privacy">' . __('Show to', 'buddypress') . ':</div>' .
			'<select name="'.$option_title.'">';

	foreach ( $options as $k => $v ) {

		if ( $privacy_level == $v ) {
			$selected = ' selected="selected"';
		} else { 
			$selected = '';
		}

		if ('' == $selected) { 
			if ($_POST[$option_title] == $v) $selected = ' selected="selected"';
		}


		$html .= '<div class="privacy"> ' .
				'<option' . $selected . ' ' .
					'name="' .$option_title . '" id="' . $option_title . '_opt_' . $v . '" ' .
					'value="' . attribute_escape( $v ) . '"> ' .
					__($options_text[$k], 'buddypress') . ' </option>';
			'</div>';
	}

	$html .= '</select></div>';

	return $html;
}



function cpt_bp_xprofile_privacy_field_html_radio( $field ){
	global $wpdb, $bp;

	$options = cpt_bp_xprofile_privacy_get_options();
	$options_text = cpt_bp_xprofile_privacy_get_options_text();

	if ( empty( $options ) )
		return null;

	$sql = $wpdb->prepare(
			"SELECT " . CPT_BP_XPROFILE_PRIVACY_FIELD . "
			FROM {$bp->profile->table_name_data}
			WHERE user_id = %d AND field_id = %d",
			$bp->displayed_user->id, $field->id );

	$privacy_level = $wpdb->get_var( $sql );

	if ( null == $privacy_level ) {
		$privacy_level = CPT_BP_XPROFILE_PRIVACY_FIELD_DEF_VALUE;
	}

	$html = '<div class="privacy_buttons_wrap radio signup-field privacy" id="field_' . $field->id . '_' . CPT_BP_XPROFILE_PRIVACY_FIELD .'">'.
			'<div class="privacy">'.__('Show to', 'buddypress').':</div>';

	foreach ( $options as $k => $v ) {

		if ( $privacy_level == $v ) {
			$selected = ' checked="checked"';
		} else { 
			$selected = '';
		}

		$option_title = 'field_' . $field->id . '_' . CPT_BP_XPROFILE_PRIVACY_FIELD;

		$html .= '<div class="privacy"> ' .
				'<input' . $selected . ' type="radio" ' .
					'name="' .$option_title . '" id="' . $option_title . '_opt_' . $v . '" ' .
					'value="' . attribute_escape( $v ) . '"> ' .
		__($options_text[$k], 'buddypress') .
				'</div>';
	}

	$html .= '</div>';

	return $html;
}


if ( !function_exists('cpt_bp_xprofile_privacy_save') ) :
function cpt_bp_xprofile_privacy_save( $field ) {
	global $bp;

	// Fetch the current field from the _POST array based on field ID.
	$privacy_field_name = 'field_' . $field->field_id . '_' . CPT_BP_XPROFILE_PRIVACY_FIELD;
	$privacy_field_value = ( isset( $_POST[$privacy_field_name] ) ? $_POST[$privacy_field_name] : CPT_BP_XPROFILE_PRIVACY_FIELD_DEF_VALUE );
	// Save field.
	if ( !cpt_bp_xprofile_privacy_save_field( $privacy_field_value, $field->field_id, $field->user_id ) )
		return false;

	// Delete cache object for this user, if exists.
	$cache_name = CPT_BP_XPROFILE_PRIVACY_CACHE . $bp->displayed_user->id;
	wp_cache_delete( $cache_name . '_friend', 'cpt' );
	wp_cache_delete( $cache_name . '_public', 'cpt' );

	return true;
}
endif;
add_action( 'xprofile_data_after_save', 'cpt_bp_xprofile_privacy_save' );


function cpt_bp_xprofile_privacy_save_field( $value, $field_id, $user_id ) {
	global $wpdb, $bp;

	// Check value
	if ( !in_array( $value, cpt_bp_xprofile_privacy_get_options() ) )
		$value = CPT_BP_XPROFILE_PRIVACY_FIELD_DEF_VALUE;

	$sql = $wpdb->prepare(
			"UPDATE {$bp->profile->table_name_data}
			SET " . CPT_BP_XPROFILE_PRIVACY_FIELD . " = %d
			WHERE user_id = %d AND field_id = %d",
			$value, $user_id, $field_id );

	if ( false === $wpdb->query($sql) )
		return false;

	return true;
}



function cpt_bp_xprofile_privacy_allowed_fields() {
	global $group, $bp, $wpdb;

	// If user logged in is owner of the displayed profile, just continue without restrictions.
	if ( $bp->displayed_user->id == $bp->loggedin_user->id )
	return true;

	$allowed_fields = array();

	// Check if logged user is friend with displayed user.
	$are_friends = friends_check_friendship( $bp->loggedin_user->id, $bp->displayed_user->id );

	// Get adequate cache object, if exists.
	$cache_name = CPT_BP_XPROFILE_PRIVACY_CACHE . $bp->displayed_user->id;
	if ( $are_friends ) {
		$cache_name .= '_friends';
	}
	else {
		$cache_name .= '_public';
	}

	if ( !($allowed_fields = wp_cache_get( $cache_name, 'cpt' ) ) || !isset( $allowed_fields[$group->id] ) ) {

		$field_ids = array();
		foreach ( $group->fields as $field ) {
			$field_ids[] = $field->id;
		}
		$field_ids = implode( ',', $field_ids );

		$options = cpt_bp_xprofile_privacy_get_options();
		$privacy_levels = array();
		if ( $are_friends ) {
			$privacy_levels[] = $options['PUBLIC'];
			$privacy_levels[] = $options['PUBLIC_LOGGED_IN'];
			$privacy_levels[] = $options['FRIENDS'];
		}
		else if ($bp->loggedin_user->id) { 
			$privacy_levels[] = $options['PUBLIC'];
			$privacy_levels[] = $options['PUBLIC_LOGGED_IN'];
		}
		else {
			$privacy_levels[] = $options['PUBLIC'];
		}

		$privacy_levels = implode( ',', $privacy_levels );

		$sql = $wpdb->prepare(
				"SELECT field_id
				FROM {$bp->profile->table_name_data}
				WHERE user_id = %d
				AND field_id IN ( {$field_ids} )
				AND " . CPT_BP_XPROFILE_PRIVACY_FIELD . " IN ( {$privacy_levels} )",
		$bp->displayed_user->id );
		$allowed_fields[$group->id] = $wpdb->get_col( $sql );

		wp_cache_set( $cache_name, $allowed_fields, 'cpt' );
	}

	if ( empty( $allowed_fields[$group->id] ) )
	return false;

	$available_fields = $group->fields;
	$allowed_fields = $allowed_fields[$group->id];
	foreach ( $available_fields as $k => $field ) {
		if ( !in_array($field->id, $allowed_fields) ) {
			unset( $group->fields[$k] );
		}
	}

	return true;
}

function cpt_bp_xprofile_privacy_search_users_count_sql( $sql, $search_terms) {
	global $bp;

	$options = cpt_bp_xprofile_privacy_get_options();
	$public_data = $options['PUBLIC'];

	$total_users_sql =
			"SELECT DISTINCT count(u.ID) as user_id
			FROM " . CUSTOM_USER_TABLE . " u
			LEFT JOIN {$bp->profile->table_name_data} pd ON u.ID = pd.user_id
			WHERE pd." . CPT_BP_XPROFILE_PRIVACY_FIELD . " =  {$public_data}
			AND pd.value LIKE '%%$search_terms%%'
			ORDER BY pd.value ASC";

	return $total_users_sql;
}
add_filter( 'bp_core_search_users_count_sql', 'cpt_bp_xprofile_privacy_search_users_count_sql', 10, 2 );

// TODO: if user is logged in add field visible to friends in the search.
function cpt_bp_xprofile_privacy_search_users_sql( $sql, $search_terms, $pag_sql) {
	global $bp;

	$options = cpt_bp_xprofile_privacy_get_options();
	$public_data = $options['PUBLIC'];

	$paged_users_sql =
			"SELECT DISTINCT u.ID as user_id
			FROM " . CUSTOM_USER_TABLE . " u
			LEFT JOIN {$bp->profile->table_name_data} pd ON u.ID = pd.user_id
			WHERE pd." . CPT_BP_XPROFILE_PRIVACY_FIELD . " =  {$public_data}
			AND pd.value LIKE '%%$search_terms%%'
			ORDER BY pd.value ASC{$pag_sql}";

	return $paged_users_sql;
}
add_filter( 'bp_core_search_users_sql', 'cpt_bp_xprofile_privacy_search_users_sql', 10, 3 );



/***** Profile privacy settings section ******/

function cpt_bp_xprofile_privacy_setup_nav() {
	global $bp;

	bp_core_new_subnav_item( 'settings', BP_XPROFILE_SLUG, __('Profile Privacy', 'buddypress'), $bp->loggedin_user->domain. 'settings/', 'cpt_bp_xprofile_privacy_option_settings', false, bp_is_home() );
}
add_action( 'wp', 'cpt_bp_xprofile_privacy_setup_nav', 2 );
add_action( 'admin_menu', 'cpt_bp_xprofile_privacy_setup_nav', 1 );


function cpt_bp_xprofile_privacy_option_settings() {
	global $current_user, $bp_settings_updated, $wpdb, $bp;

	$bp_settings_updated = false;
	$bp_settings_error = false;

	if ( $_POST['submit']  && check_admin_referer('bp_settings_' . BP_XPROFILE_SLUG) ) {
		if ( $_POST['xprofile_privacy'] ) {
			foreach ( $_POST['xprofile_privacy'] as $key => $value ) {
				if ( !cpt_bp_xprofile_privacy_save_field( $value, $key, $bp->loggedin_user->id ) ) {
					$bp_settings_error = true;
					break;
				}
			}
		}
		if ( !$bp_settings_error ) {
			$bp_settings_updated = true;

			// Delete cache object for this user, if exists.
			$cache_name = CPT_BP_XPROFILE_PRIVACY_CACHE . $bp->displayed_user->id;
			wp_cache_delete( $cache_name . '_friend', 'cpt' );
			wp_cache_delete( $cache_name . '_public', 'cpt' );
		}
	}

	add_action( 'bp_template_title', 'cpt_bp_xprofile_privacy_settings_title' );
	add_action( 'bp_template_content', 'cpt_bp_xprofile_privacy_settings_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'plugin-template' ) );
}

function cpt_bp_xprofile_privacy_settings_title() {
	_e( 'Profile Privacy Settings', 'buddypress' );
}


function cpt_bp_xprofile_privacy_settings_content() {
	global $bp, $wpdb, $current_user, $bp_settings_updated, $bp_settings_error; ?>

	<?php if ( $bp_settings_updated ) { ?>
		<div id="message" class="updated fade">
		<p><?php _e( 'Changes Saved.', 'buddypress' ) ?></p>
		</div>
	<?php } ?>

	<?php if ( $bp_settings_error && !$bp_settings_updated ) { ?>
		<div id="message" class="error fade">
			<p><?php _e( 'Database error occur.', 'buddypressdev' ) ?></p>
		</div>
	<?php } ?>

	<?php
	$options_values = cpt_bp_xprofile_privacy_get_options();
	$options_text = cpt_bp_xprofile_privacy_get_options_text();

	// Get profile fields and privacy settings for current user.
	$groups = $wpdb->get_results( $wpdb->prepare(
			"SELECT g.id as group_id, g.name as group_name, f.id as field_id, f.name as field_name, d.privacy_level as privacy_level, d.id as data_id
			FROM {$bp->profile->table_name_fields} as f
			INNER JOIN {$bp->profile->table_name_groups} as g ON f.group_id = g.id
			LEFT JOIN {$bp->profile->table_name_data} as d ON f.id = d.field_id
			WHERE f.parent_id=0 and d.user_id=%d
			ORDER by g.id, f.id",
			$bp->loggedin_user->id ));
	?>

	<form
		action="<?php echo $bp->loggedin_user->domain . 'settings/' . BP_XPROFILE_SLUG ?>"
		method="post" id="settings-form">
	<p><?php _e( 'Choose who can see your profile details (only non empty fields are listed):', 'buddypress' ) ?></p>

	<?php $curr_group = 0;
	foreach ($groups as $group) {
		if ( $group->group_id != $curr_group ) {
			if ( $curr_group != 0 ){
				?></table><?php
			}
			$curr_group = $group->group_id;
			?>
			<table class="notification-settings" id="xprofile-privacy-settings">
				<tr>
					<th class="icon"></th>
					<th class="title"><?php _e( $group->group_name, 'buddypress' ) ?></th>
					<?php foreach( $options_text as $text ) { ?>
						<th class="yes"><?php _e( $text, 'buddypress' ) ?></th>
					<?php } ?>
				</tr>
		<?php } ?>

		<tr>
			<td></td>
			<td><?php _e( $group->field_name, 'buddypress' ) ?></td>
			<?php foreach( $options_values as $option_value) { ?>
			<td class="yes"><input type="radio"
				name="xprofile_privacy[<?php echo $group->field_id ?>]" value="<?php echo $option_value ?>"
				<?php if ( $group->privacy_level == $option_value ) { ?>
				checked="checked" <?php } ?> />
			</td>
			<?php } ?>
		</tr>
	<?php } ?>
	</table>
	<?php do_action( 'cpt_bp_xprofile_privacy_settings' ) ?>


	<p class="submit"><input type="submit" name="submit"
		value="<?php _e( 'Save Changes', 'buddypress' ) ?>" id="submit"
		class="auto" /></p>

	<?php wp_nonce_field('bp_settings_' . BP_XPROFILE_SLUG) ?></form>
	<?php
}



?>
