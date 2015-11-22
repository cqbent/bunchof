<?php
/*
Plugin Name: Widgets Controller
Plugin URI: http://wordpress.org/extend/plugins/widgets-controller/
Description: A plugin that give you control for show or hide widgets on WordPress Categories, Posts and Pages.
Author: IndiaNIC
Author URI: http://profiles.wordpress.org/indianic
Version: 1.1
*/
add_action('admin_head', 'widgets_controller_head');
function widgets_controller_head() { ?>
	<script type="text/javascript" language="javascript">
		var PLUGINPATH = "<?php echo plugin_dir_url( __FILE__ ); ?>";
	</script>
<?php }
add_filter('widget_display_callback', 'widgets_controller_show');
add_action('in_widget_form', 'widgets_controller_append', 10, 3);
add_filter('widget_update_callback', 'widgets_controller_update', 10, 3);
wp_enqueue_script( 'my-ajax-request', plugin_dir_url( __FILE__ ) . 'widgets_controller.js', array( 'jquery' ) );
wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
//do_action('wp_ajax_nopriv_' . $_REQUEST['action']);
//do_action('wp_ajax_' . $_POST['action']);
add_action( 'wp_ajax_myajax-submit', 'widgets_controller_submit' );
add_action('admin_print_styles', 'widgets_controller_css');

/*----------ADD CSS----------*/
function widgets_controller_css() {
    wp_register_style($handle = 'include_css', $src = plugins_url('widgets_controller.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('include_css');
}

/*----------SHOW WIDGET BY CONDITION----------*/
function widgets_controller_show($instance) {
	if($instance['widgets_controller'] == 1) {
		$cat_id = get_the_category();
		$cat_id = $cat_id[0]->cat_ID;
		$post_id = get_the_ID();
		if(is_home() || is_front_page()) {
			if(is_array($instance['general'])) {
				if(in_array('homepage', $instance['general'])) {
					return $instance;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} elseif(is_404()) {
			if(is_array($instance['general'])) {
				if(in_array('error', $instance['general'])) {
					return $instance;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} elseif(is_search()) {
			if(is_array($instance['general'])) {
				if(in_array('search', $instance['general'])) {
					return $instance;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} elseif(is_category() || is_archive()) {
			if(is_category()) {
				if(is_array($instance['category'])) {
					if(in_array($cat_id, $instance['category'])) {
						return $instance;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				if(is_array($instance['general'])) {
					if(in_array('archive', $instance['general'])) {
						return $instance;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		} elseif(is_single()) {
			if(is_array($instance['posts'])) {
				if(in_array($post_id, $instance['posts'])) {
					return $instance;
				} else {
					return false;
				}
			}
			else {
				return false;
			}
		} elseif(is_page()) {
			if(is_array($instance['pages'])) {
				if(in_array($post_id, $instance['pages'])) {
					return $instance;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	} else {
		return $instance;
	}
}

/*----------APPEND FORM----------*/
function widgets_controller_append($widget, $return, $instance) {
	$instance['widgets_controller'] = isset($instance['widgets_controller']) ? $instance['widgets_controller'] : 0;
	$instance['general'] = isset($instance['general']) ? $instance['general'] : 0;
	$instance['category'] = isset($instance['category']) ? $instance['category'] : 0;
	$instance['posts'] = isset($instance['posts']) ? $instance['posts'] : 0;
	$instance['pages'] = isset($instance['posts']) ? $instance['pages'] : 0;
	$none = $instance['widgets_controller'] == 1 ? "" : " none";
	$temcategory = $temposts = $tempages = '';
	if(is_array($instance['category'])) $temcategory = implode(",", $instance['category']);
	if(is_array($instance['posts'])) $temposts = implode(",", $instance['posts']);
	if(is_array($instance['pages'])) $tempages = implode(",", $instance['pages']);
	?>
	<p>
		<input for_id="<?php echo $widget->get_field_id(""); ?>" get_cat_name="<?php echo $widget->get_field_name("category"); ?>" get_posts_name="<?php echo $widget->get_field_name("posts"); ?>" get_pages_name="<?php echo $widget->get_field_name("pages"); ?>" current_cat="<?php if($instance['category']) echo implode("," , $instance['category']); ?>" current_posts="<?php if($instance['posts']) echo implode("," , $instance['posts']); ?>" current_pages="<?php if($instance['pages']) echo implode("," , $instance['pages']); ?>" class="checkbox widgets_controller" type="checkbox" <?php checked($instance['widgets_controller'], true) ?> id="<?php echo $widget->get_field_id('widgets_controller'); ?>" name="<?php echo $widget->get_field_name('widgets_controller'); ?>" value="1" />
		<label class="manage_label" for="<?php echo $widget->get_field_id('widgets_controller'); ?>"><?php _e('Widget controller', 'display-widgets') ?></label>
	</p>
	<div class="widgets_controller_box<?php echo $none; ?>"><span class="show_detail">Show Detail</span>
		<?php //echo "<pre>"; print_r($widget); print_r($instance); echo "</pre>"; ?>
		<div class="pad_5"><span><input type="checkbox" <?php if($instance['general']) checked(in_array('homepage', $instance['general']), true) ?> id="<?php echo $widget->get_field_id('homepage'); ?>" name="<?php echo $widget->get_field_name('general'); ?>[]" value="homepage"><label for="<?php echo $widget->get_field_id('homepage'); ?>"><?php _e('HomePage', 'display-widgets') ?></label></span><span><input type="checkbox" <?php if($instance['general']) checked(in_array('error', $instance['general']), true) ?> id="<?php echo $widget->get_field_id('error'); ?>" name="<?php echo $widget->get_field_name('general'); ?>[]" value="error"><label for="<?php echo $widget->get_field_id('error'); ?>"><?php _e('Error', 'display-widgets') ?></label></span><span><input type="checkbox" <?php if($instance['general']) checked(in_array('search', $instance['general']), true) ?> id="<?php echo $widget->get_field_id('search'); ?>" name="<?php echo $widget->get_field_name('general'); ?>[]" value="search"><label for="<?php echo $widget->get_field_id('search'); ?>"><?php _e('Search', 'display-widgets') ?></label></span><span><input type="checkbox" <?php if($instance['general']) checked(in_array('archive', $instance['general']), true) ?> id="<?php echo $widget->get_field_id('archive'); ?>" name="<?php echo $widget->get_field_name('general'); ?>[]" value="archive"><label for="<?php echo $widget->get_field_id('archive'); ?>"><?php _e('Archive', 'display-widgets') ?></label></span></div>
		<div class="rwp">
			<input type="checkbox" <?php if($instance['general']) checked(in_array('relation-with-posts', $instance['general']), true) ?> id="<?php echo $widget->get_field_id('relation-with-posts'); ?>" name="<?php echo $widget->get_field_name('general'); ?>[]" value="relation-with-posts"><label for="<?php echo $widget->get_field_id('relation-with-posts'); ?>"><?php _e('When click on any category then auto select all related posts', 'display-widgets') ?></label>
		</div>
		<div class="ajax_data"></div>
	</div>
<?php }

/*----------UPDATE FORM----------*/
function widgets_controller_update($instance, $new_instance, $old_instance) {
	$instance['widgets_controller'] = isset($new_instance['widgets_controller']) ? $new_instance['widgets_controller'] : 0;
	$instance['general'] = isset($new_instance['general']) ? $new_instance['general'] : 0;
	if($instance['widgets_controller']) {
		$instance['category'] = isset($new_instance['category']) ? $new_instance['category'] : 0;
		$instance['posts'] = isset($new_instance['posts']) ? $new_instance['posts'] : 0;
		$instance['pages'] = isset($new_instance['pages']) ? $new_instance['pages'] : 0;
		$instance['relation-with-posts'] = isset($new_instance['relation-with-posts']) ? $new_instance['relation-with-posts'] : 0;
	} else {
		$instance['category'] = isset($new_instance['category']) ? $new_instance['category'] : $old_instance['category'];
		$instance['posts'] = isset($new_instance['posts']) ? $new_instance['posts'] : $old_instance['posts'];
		$instance['pages'] = isset($new_instance['pages']) ? $new_instance['pages'] : $old_instance['pages'];
		$instance['relation-with-posts'] = isset($new_instance['relation-with-posts']) ? $new_instance['relation-with-posts'] : $old_instance['relation-with-posts'];
	}
	return $instance;
}

/*----------AJAX DATA----------*/
function widgets_controller_submit() {
    $data = $_POST['data'];
	global $wpdb;
	$prefix = $wpdb->prefix;
	$current_cat = explode("," , $data["current_cat"]);
	$current_posts = explode("," , $data["current_posts"]);
	$current_pages = explode("," , $data["current_pages"]);
	$cat_post_option = Array();
	$ajax_data = "<p>Category</p><div class='overflow category_box'>";
	$cat_list = $wpdb->get_results( "SELECT ".$prefix."terms.term_id, ".$prefix."terms.name FROM ".$prefix."term_taxonomy INNER JOIN ".$prefix."terms ON ".$prefix."term_taxonomy.term_id = ".$prefix."terms.term_id WHERE ".$prefix."term_taxonomy.taxonomy='category'" );
	foreach($cat_list as $k => $v) {
		$for_id = $v->term_id . 0;
		$title = substr($v->name, 0, 20);
		$checked = in_array($v->term_id, $current_cat) ? "checked" : "";
		$ajax_data .= "<span><input {$checked} type='checkbox' id='{$data["for_id"]}{$for_id}' name='{$data["get_cat_name"]}[]' value='{$v->term_id}'><label for='{$data["for_id"]}{$for_id}'>{$title}_{$v->term_id}</label></span>";
	}
	$ajax_data .= "</div><p>Posts <strong class='posts checkall'>Check All</strong></p><div class='overflow posts_box'>";
	$post_list = $wpdb->get_results("SELECT * FROM ".$prefix."posts where post_type='post' && post_status = 'publish'");
	foreach($post_list as $k => $v) {
		$for_id = $v->ID . 1;
		$cat_id = get_the_category($v->ID);
		$cat_name = $cat_id[0]->cat_name;
		$cat_id = $cat_id[0]->cat_ID;
		$title = substr($v->post_title, 0, 20);
		$checked = in_array($v->ID, $current_posts) ? "checked" : "";
		$ajax_data .= "<span><input {$checked} title='{$cat_id}' type='checkbox' id='{$data["for_id"]}{$for_id}' name='{$data["get_posts_name"]}[]' value='{$v->ID}'><label title='{$cat_name}' for='{$data["for_id"]}{$for_id}'>{$title}_{$v->ID}</label></span>";
	}
	$ajax_data .= "</div><p>Pages <strong class='pages checkall'>Check All</strong></p><div class='overflow pages_box'>";
	$page_list = $wpdb->get_results( "SELECT * FROM ".$prefix."posts WHERE post_type='page' && post_status='publish'" );
	foreach($page_list as $k => $v) {
		$for_id = $v->ID . 1;
		$parent_id = $v->ID;
		$parent_id = $parentid = get_page($parent_id)->post_parent;
		$title = substr($v->post_title, 0, 20);
		$checked = in_array($v->ID, $current_pages) ? "checked" : "";
		$ajax_data .= "<span><input {$checked} title='{$parent_id}' type='checkbox' id='{$data["for_id"]}{$for_id}' name='{$data["get_pages_name"]}[]' value='{$v->ID}'><label for='{$data["for_id"]}{$for_id}'>{$title}_{$v->ID}</label></span>";
	}
	$ajax_data .= "</div>";
	echo $ajax_data;
exit;
}