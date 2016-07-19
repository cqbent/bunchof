<?php
/*
function my_filter_group_stati( $stati ) {
if( $key = array_search( 'hidden', $stati ) ) {
unset( $stati[$key] );
}
return $stati;
}
add_filter( 'groups_valid_status', 'my_filter_group_stati' );
 * 
 */

/*
* add meta_query filter to search string. not using because it creates an 'AND' 
* filter to the query instead of what we need which is an 'OR' filter. also is not 
* customizable enough

function filter_add_location_search($querystring = '', $object = '') {
    if( $object != 'groups' ) {
        return $querystring;
    }
    else {
        // Let's rebuild the querystring as an array to ease the job
        $defaults = array(
            'type'            => 'active',
            'action'          => 'active',
            'scope'           => 'all',
            'page'            => 1,
            'user_id'         => 0,
            'search_terms'    => '',
            'exclude'         => false,
        );
        $bpgmq_querystring = wp_parse_args( $querystring, $defaults );
        
        $bpgmq_querystring['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => 'group_plus_map_field',
                'value' => $bpgmq_querystring['search_terms'],
                'compare' => 'LIKE'
            )
        );
        return apply_filters( 'bpgmq_filter_ajax_querystring', $bpgmq_querystring, $querystring );
    }
}
//add_filter( 'bp_ajax_querystring', 'filter_add_location_search', 20, 2);
*/


// bog_group_custom_search
// custom search query for BOG groups: adds group_plus plugin meta fields to the search query
// includes the map location field and the zipcode (group_plus_header_field-one) field as
// extra fields to search
function bog_group_custom_search($sqljoin, $sqlarray, $r) {
    // if "search" exists then add meta_query
    if ($sqlarray['search']) {
        /*
        //create meta_query using the WP_Meta_Query class. not customizable enough for the 
        //buddypress query functions so not using. (bp uses commas (,) for joins instead of INNER JOIN so not compatable)
        $groupmeta = array(
            'relation' => 'OR',
            array(
                'key' => 'group_plus_map_field',
                'value' => $r['search_terms'],
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'group_plus_header_field-one',
                'value' => $r['search_terms'],
                'compare' => 'LIKE'
            )
        );
        $gmquery = new WP_Meta_Query($groupmeta);
        $gmqsql = $gmquery->get_sql( 'group', 'g', 'id' );
        */
        // add group_plus fields and custom query variables to the "from" and "search" parts of the query array
        $sqlarray['from'] .= ' wp_bp_groups_groupmeta AS mt1, wp_bp_groups_groupmeta AS mt2, ';
        $gmwhere = "( (mt1.meta_key = 'group_plus_map_field' AND mt1.meta_value LIKE '%".$r['search_terms']."%') OR "
            . "(mt2.meta_key = 'group_plus_header_field-one' AND mt2.meta_value LIKE '%".$r['search_terms']."%') )"
            . " AND mt1.group_id = g.id AND mt2.group_id = g.id ";
        $sqlarray['search'] = str_replace(')', ' OR ', $sqlarray['search']) . $gmwhere . ')';
    }
    // join the sql array as a full sql query
    $sqlresult = join(' ', $sqlarray);
    //print_r($sqlresult);
    return $sqlresult;
}
add_filter('bp_groups_get_paged_groups_sql', 'bog_group_custom_search', 10, 3);

function search_default($component) {
    if (strstr($component,'Groups')) { 
        return 'Search by group name, city, zip code, activity, etc.';
    }
    elseif (strstr($component,'Members')) { 
        return 'Search by name, user name, town/zipcode, interest, equipment, etc.';
    }
    else {
        return $component;
    }
}

add_filter( 'bp_get_search_default_text', 'search_default', 1 );

function bunchof_register_media_taxonomy() {
    register_taxonomy_for_object_type( 'category', 'attachment' );
}
add_action( 'init', 'bunchof_register_media_taxonomy' );

function bunchof_add_image_category_filter() {
    $screen = get_current_screen();
    if ( 'upload' == $screen->id ) {
        $dropdown_options = array( 'show_option_all' => __( 'View all categories', 'bp-bunchof' ), 'hide_empty' => false, 'hierarchical' => true, 'orderby' => 'name', );
        wp_dropdown_categories( $dropdown_options );
    }
}
add_action( 'restrict_manage_posts', 'bunchof_add_image_category_filter' );

function display_home_slider() {
    // get images with category slider
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'category_name' => 'slider',
        'order'    => 'DESC'
    );
    $imgquery = new WP_Query($args);
    $imglist = '';
    foreach ( $imgquery->posts as $image) {
        $imglist .= '<li><img title="'.$image->post_excerpt.'" src="'.$image->guid.'" alt="'.$image->post_excerpt.'" /></li>';
    }
    //var_dump($imglist->posts);
    return '<div id="banner-fade"><ul class="bjqs">'.$imglist.'</ul></div>';
}
add_shortcode('home_slider', 'display_home_slider');


function add_bubble($text) {
    $close = '<a id="closepanel" href="#"><span class="entypo-cancel-squared"></span> Got it! close this message.</a>';
    $bubbletext = '<div id="helpopen" class="em-heading">'.$text.$close.'</div>';
    print $bubbletext;
}
function add_event_bubble() {
    $add_event = '<p>Schedule and fully describe your project to your group members. Remember, from this point thereafter your project will be referred to as an EVENT in your group and on group pages.</p>
        <p>Be descriptive about your project. You want the group to know and understand the project details. This is also your opportunity to entice your group; choose your words wisely so members look forward to your project.</p>
        <p>Don\'t forget to indicate the equipment to bring, the number of each, and the type of experience that would be helpful 
        (in the equipment and expertise boxes). If you have photos of the area, include those too.</p>';
    add_bubble($add_event);
}
add_action('em_front_event_form_header', 'add_event_bubble');

function add_group_bubble() {
    $add_group = '<p>You are about to form a GROUP to work on many different projects at each others’ homes and/or at other locations. Please title and be very descriptive.  This information will be viewable on the public “Groups” page and any part of it may be used as a search term by other BunchOf Gardeners members.
        Include location information!</p>
        <p>Include city, town, and/or zip code so local members can find and join your group.  Include any location information if on public grounds (i.e. the name of field, square, school, with city/town/zip).</p>
        <p>Once the group is formed, members may be invited, projects scheduled, plus much more!</p>';
    add_bubble($add_group);
}
add_action('bp_before_create_group', 'add_group_bubble');

function my_events_bubble() {
    $event_bubble = '<p><strong>Your Event’s Attendance and Related Information</strong><br>
  Click on the wheel below <span class="gear-icon"></span> to display all relevant information who is attending your event, what they are bringing, their expertise, any comments they may have, their email address, number of people (including them).</p>
<p><em>To display the information:</em></p>
<ol>
  <li>Click on wheel</li>
  <li>Drag and drop the following boxes to the left-hand-size yellow column (remove all other boxes by dragging to the right white-column area):
    <ol type="a">
      <li>Status</li>
      <li>Spaces</li>
      <li>Event</li>
      <li>Name</li>
      <li>What I’m Bringing</li>
      <li>Expertise</li>
      <li>Email</li>
      <li>Save Setting</li>
    </ol>
  </li>
  <li><em>You can also export this information in CSV format. </em>
    <ol>
      <li>Click on the CSV icon </li>
      <li>Drag and drop the same boxes as listed above in #2</li>
      <li>Click on Export Bookings</li>
    </ol>
  </li>
</ol>';
    add_bubble($event_bubble);
}
add_action('em_template_my_bookings_header', 'my_events_bubble');

function media_uploader_message() {
    $message = '<p><strong>Only photos, videos or music files are allowed for uploading.</strong></p>';
    print $message;
}
add_action('rtmedia_before_uploader', 'media_uploader_message');

function bp_event_can_manage_override( $result, $EM_Event, $owner_capability, $admin_capability, $user_to_check){
    if( $EM_Event->event_owner != get_current_user_id() && !empty($EM_Event->group_id) && bp_is_active('groups') && $EM_Event->event_id ){ 
    //if (1<>1) {
        return false;
    }
    else {
        return $result;
    }
}
add_filter('em_event_can_manage','bp_event_can_manage_override',1,5);

function add_event_button() {
    $current_user = wp_get_current_user();
    $event_button = '<div class="generic-button group-button public">'
        . '<a href="/members/'.$current_user->user_login.'/events/my-events/edit/?action=edit" title="Add Event" class="group-button join-group">Add Event</a>'
        . '</div>';  
    print $event_button;
}
add_filter('bp_group_header_actions','add_event_button');


function show_widget() {
    the_widget( 'WP_Widget_Text', 'id=text-9' ); 
    
    //the_widget( 'WP_Widget_Archives' );
}

wp_enqueue_script( 'jquery-validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js', array('jquery') );
wp_enqueue_script( 'bog-script', get_stylesheet_directory_uri().'/script.js', array('jquery', 'jquery-validate') );

// modify logout link
function go_home() {
    wp_redirect( home_url() );
    exit();
}
add_action('wp_logout', 'go_home');

function max_upload_filesize_message() {
    $maxfilesize = get_option('wpisl_options');
    $msg = '<div class="msg">The maximum file size for images is '
        .$maxfilesize['img_upload_limit'].
    'kb</div>';
    print $msg;
}
add_action('bp_before_group_avatar_creation_step','max_upload_filesize_message');

function bp_dtheme_header_style() {
    // do nothing here
}

function profile_edit_message() {
    $message = '<p>Note: aside from your name and avatar, only friends may see your personal information.</p>';
    add_bubble($message);
}
add_action('bp_before_profile_field_content','profile_edit_message');

?>
