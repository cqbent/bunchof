<?php

/*
Plugin Name: SB Uploader
Plugin URI: http://www.sean-barton.co.uk
Description: Allows the simple uploading of images to posts, pages, categories and custom post types/taxonomies. Provides shortcodes and PHP functions for easy addition to your site.
Author: Sean Barton
Version: 4.6
Author URI: http://www.sean-barton.co.uk
*/

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

add_theme_support('post-thumbnails');

@ini_set('upload_max_filesize', '24M');

$sbu_plugin_name = 'SB Uploader';
$sbu_plugin_dir_url = sbu_generate_plugin_dir_url();

$sbu = new sb_uploader();

class sb_uploader {
	private $updates_processed = false;
	private $error = false;
	private $jquery_run = false;
	private $settings = array();
	private $error_msgs = array();
	private $feedback_msgs = array();
	private $upload_dir_error = '';
	private $allowed_types = array();
	
	private $td_style = 'padding: 3px; border-bottom: 1px solid silver;';
	private $th_style = 'padding: 3px; text-align: left; border-bottom: 1px solid silver; font-weight: bold;';
	
	public function __construct() {
		global $sbu_plugin_dir_url;
		
		$this->allowed_types = array(
			'jpg'
			, 'jpeg'
			, 'png'
			, 'gif'
			, 'bmp'
			, 'tiff'
			, 'tif'
			, 'mp3'
			, 'mp4'
			, 'wav'
			, 'flac'
			, 'avi'
		);
		
		if ($upload_path = $this->post('upload_path')) {
			$_POST['upload_path'] = trailingslashit($upload_path);
		}
		
		if (!is_dir($sbu_plugin_dir_url . 'cache')) {
			@mkdir($sbu_plugin_dir_url . 'cache', 0777);
		} else if (!is_writeable($sbu_plugin_dir_url . 'cache')) {
			@chmod($sbu_plugin_dir_url . 'cache', 0777);
		}
		
		$this->get_upload_path(); //return not needed but the function does set the status of the plugin accordingly (on or off depending on whether the upload location is writeable)
		
		add_action('admin_notices', array($this, 'check_upload_dir_writeable'));
		add_action('admin_menu', array($this, 'admin_page'));
		add_action('admin_head', array($this, 'header_scripts'));
		add_action('save_post', array($this, 'process_updates'), 10, 1);
		add_action('admin_init', array($this, 'admin_init'));
		add_action('init', array($this, 'init'));
		add_action('widgets_init', create_function('', 'return register_widget("sbu_image_widget");'));
		add_action('widgets_init', create_function('', 'return register_widget("sbu_post_image_widget");'));
		add_action('widgets_init', create_function('', 'return register_widget("sbu_taxonomy_image_widget");'));
	
		add_action('create_term', array($this, 'handle_update_taxonomy_data'));
		add_action('edit_term', array($this, 'handle_update_taxonomy_data'));
		
		add_action("manage_posts_custom_column", array($this, 'admin_column_content'));
		add_filter("manage_edit-post_columns", array($this, 'admin_columns'));						
		add_filter("manage_edit-page_columns", array($this, 'admin_columns'));						
		
		add_action('post_edit_form_tag', array($this, 'add_post_enctype'));

		if ($this->get('tag_ID')) {
			add_action('edit_category_form', array($this, 'taxonomy_meta_form'));
			add_action('edit_tag_form', array($this, 'taxonomy_meta_form'));
			add_action('edit_taxonomy_form', array($this, 'taxonomy_meta_form'));
		}
	}
	

	function admin_columns($columns) {
		$columns['sbu_thumb'] = 'Image';
		
		return $columns;
	}
	
	function admin_column_content($column) {
		global $post, $ml_plugin_dir_url;
		
		if ($column == 'sbu_thumb') {
			if (!$image = get_the_post_thumbnail($post->ID, array(50,50))) {
				$image = '<em>None set</em>';
			}
			
			echo $image;
		}
		
	}		
	
	public function add_post_enctype() {
		echo ' enctype="multipart/form-data"';
	}
	
	public function check_upload_dir_writeable() {
		global $sbu_plugin_name;
		$upload_path = $this->get_upload_path();

		if ($this->upload_dir_error) {
			echo '	<div class="fade error">
					<p>' . $sbu_plugin_name . ' can not function because of an error with the upload directory. The upload boxes will return once the issue has been rectified.<br /><br />The following error was returned by Wordpress:<br /><em>' . $this->upload_dir_error . '</em></p>
				</div>';
		}
	}
	
	public function get_upload_path() {
		$upload_dir = wp_upload_dir();
		
		if ($upload_dir['error']) {
			$this->upload_dir_error = $upload_dir['error'];
		}
		
		$default_path = trailingslashit($upload_dir['path']);
		
		return $this->post('upload_path', $default_path);
	}	
	
	public function get($name, $default=false) {
		if (isset($_GET[$name])) {
			$default = $_GET[$name];
		}
		
		return $default;
	}
	
	public function handle_update_taxonomy_data($term_id) {
		if ($uploaders = $this->settings->taxonomy_uploaders) {
			foreach ($uploaders as $i=>$uploader) {
				$name = $uploader['name'];
				
				if (!$this->files($name, 'error')) {
					if ($value = $this->handle_upload_independent_file($name)) {
						$this->set_taxonomy_data($term_id, $name, $value);
					}
				}
			}
		}
	}
	
	public function set_taxonomy_data($term_id, $name, $value=false) {
		$all_data = get_option('sbu_taxonomy_meta');
		$all_data[$term_id][$name] = stripcslashes($value);
		update_option('sbu_taxonomy_meta', $all_data);
	}
	
	public function get_taxonomy_data($term_id, $name) {
		$return = false;
		
		if ($data = get_option('sbu_taxonomy_meta')) {
			if (isset($data[$term_id])) {
				if (isset($data[$term_id][$name])) {
					$return = $data[$term_id][$name];
				}
			}
		}
		
		return $return;
	}	
	
	public function admin_init() {
		$this->settings = $this->get_settings();
		
		if (!$this->upload_dir_error) {
			if ($this->settings->post_uploader_count) {
				if ($uploaders = $this->settings->post_uploaders) {
					$loops = 1;
					
					foreach ($uploaders as $i=>$uploader) {
						if ($loops <= $this->settings->post_uploader_count) {
							add_meta_box('sb_uploader_meta_box_' . $loops, $uploader['label'], array($this, 'post_sidebar'), 'post', 'side', 'core', $uploader['name']);
						} else {
							break;
						}
						
						$loops++;
					}
				}
			}
			
			if ($this->settings->page_uploader_count) {
				if ($uploaders = $this->settings->page_uploaders) {
					$loops = 1;
					
					foreach ($uploaders as $i=>$uploader) {
						if ($loops <= $this->settings->page_uploader_count) {
							add_meta_box('sb_uploader_meta_box_' . $loops, $uploader['label'], array($this, 'post_sidebar'), 'page', 'side', 'core', $uploader['name']);
						} else {
							break;
						}
						
						$loops++;
					}
				}
			}
			
			if ($post_types = get_post_types(array('_builtin'=>false))) {
				foreach ($post_types as $post_type) {
					$uploader_type = $post_type . '_uploader_count';
					if ($this->settings->$uploader_type) {
						$uploaders_name = $post_type . '_uploaders';
						
						if ($uploaders = @$this->settings->$uploaders_name) {
							$loops = 1;
							foreach ($uploaders as $i=>$uploader) {
								$j = $i+1;
								
								if ($loops <= $this->settings->$uploader_type) {
									add_meta_box('sb_uploader_meta_box_' . $j, $uploader['label'], array($this, 'post_sidebar'), $post_type, 'side', 'core', $uploader['name']);
								} else {
									continue;
								}
								
								$loops++;
							}
						}
					}
				}
			}
		}
	}
	
	public function init() {
		$this->settings = $this->get_settings();
		
		if ($uploaders = $this->settings->post_uploaders) {
			foreach ($uploaders as $i=>$uploader) {
				add_shortcode('sbu_' . $uploader['name'], array($this, 'shortcode_parse'));
			}
		}
		
		if ($uploaders = $this->settings->taxonomy_uploaders) {
			foreach ($uploaders as $i=>$uploader) {
				add_shortcode('sbu_taxonomy_' . $uploader['name'], array($this, 'shortcode_parse'));
			}
		}
		
		if ($post_types = get_post_types(array('_builtin'=>false))) {
			foreach ($post_types as $post_type) {
				$uploaders_name = $post_type . '_uploaders';
				
				add_filter("manage_edit-" . $post_type . "_columns", array($this, 'admin_columns'));						
				
				if ($uploaders = @$this->settings->$uploaders_name) {
					foreach ($uploaders as $i=>$uploader) {
						add_shortcode('sbu_' . $uploader['name'], array($this, 'shortcode_parse'));
					}
				}
			}
		}		
	}
	
	public function shortcode_parse($args, $content, $tag) {
		$return = '';
	
		if ($uploaders = $this->settings->post_uploaders) {
			foreach ($uploaders as $i=>$uploader) {
				if ($tag == 'sbu_' . $uploader['name']) {
					$return = $this->get_the_post_image($uploader['name'], false, false, @$args['width'], @$args['height']);
					
					break;
				}
			}
		}
		
		if ($uploaders = $this->settings->taxonomy_uploaders) {
			if ($terms = get_the_category()) {
				foreach ($uploaders as $i=>$uploader) {
					if ($tag == 'sbu_taxonomy_' . $uploader['name']) {
						foreach($terms as $category) {
							if ($image = $this->get_taxonomy_data($category->cat_ID, $uploader['name'])) {
								$return = '<img src="' . $image . '" alt="' . $category->cat_name . '" class="sbu_taxonomy_image sbu_taxonomy_' . $uploader['name'] . '_image" title="' . $category->cat_name . '" />';
								break;
							}
						}
					}
				}
			}
		}
		
		if ($post_types = get_post_types(array('_builtin'=>false))) {
			foreach ($post_types as $post_type) {
				$uploaders_name = $post_type . '_uploaders';
				
				if ($uploaders = @$this->settings->$uploaders_name) {
					foreach ($uploaders as $i=>$uploader) {
						if ($tag == 'sbu_' . $uploader['name']) {
							$return = $this->get_the_post_image($uploader['name'], false, false, @$args['width'], @$args['height']);
							
							break;
						}
					}
				}
			}
		}		
	    
		return $return;
	}
	
	public function jquery_add_attr($object, $key, $value) {
		echo "	<script>
			jQuery(document).ready(function() {
				jQuery('" . $object . "').attr('" . $key . "', '" . $value . "');
			});
			</script>";
	}

	public function jquery_move_div_before($div_id, $move_before) {
		echo "<script>
			jQuery(document).ready(function() {
				if (jQuery('" . $move_before . "').length>0) {
					jQuery('div#" . $div_id . "').remove().insertBefore('" . $move_before . "');
				}
			});
			</script>";
			
	}
	
	public function handle_upload_independent_file($post_name) {
		$upload_path = $this->get_upload_path();
		$path_exists = is_dir($upload_path);
		$url = '';
		
		if (!$path_exists) {
			@mkdir($upload_path, 0775, 1);
		}
		
		if (!$error_number = $this->files($post_name, 'error')) {
			if ($this->check_file_type($this->files($post_name, 'name'))) {
				$new_path = $upload_path . $this->files($post_name, 'name');
				$file_exists = file_exists($new_path);
			
				if ($file_exists) {
					@unlink($new_path);
				}
				
				if (!is_writable($upload_path)) {
					@chmod($upload_path, 0777);
				}
				
				if (@move_uploaded_file($this->files($post_name, 'tmp_name', $root_name), $new_path)) {
					@chmod($new_path, 0777);
					
					//in case we shall be adding image resize later on
					//$img = new sb_image_functions();
					//$img->resize_image($new_path, false, 400);
					
					if (strpos($new_path, 'wp-content')) {
						$upload = wp_upload_dir();
						
						$full_url = $upload['url'];
						$site_url = parse_url(site_url());
						$site_url = $site_url['scheme'] . '://' . trailingslashit($site_url['host']);
						$site_url = rtrim($site_url, '/');
						
						$url = str_replace($site_url, '', $full_url);
						$url = trailingslashit($url) . $this->files($post_name, 'name');
						
						if ($this->get_url_type() == 'absolute') {
							$url = 'http://' . rtrim($_SERVER['HTTP_HOST'], '/') . $url;
						}					
						//$url = str_replace(ABSPATH, trailingslashit(get_option('siteurl')), $new_path);
					}
					
				}
			}
		}
		
		return $url;
	}	
	
	public function taxonomy_meta_form() {
		global $tag_ID;
		
		$html = '';
		
		if ($this->settings->taxonomy_uploader_count) {
	
		$this->jquery_add_attr('#addtag', 'enctype', 'multipart/form-data');
		$this->jquery_add_attr('#edittag', 'enctype', 'multipart/form-data');
		$this->jquery_move_div_before('sb_uploader_taxonomy_box', '.submit');
		
		$html .= '<div id="sb_uploader_taxonomy_box"><table class="form-table">';
		
		if ($uploaders = $this->settings->taxonomy_uploaders) {
			foreach ($uploaders as $i=>$uploader) {
				$name = $uploader['name'];
				$label = $uploader['label'];
				
				$html .= '<tr><th style="vertical-align: top; width: 200px;">' . $label . '</th><td>';
				
				if ($image = $this->get_taxonomy_data($tag_ID, $name)) {
					$html .= $this->start_row($label);
					if ($image_data = @getimagesize($image)) {
						$style = '';
						if ($image_data[0] > 235) {
							$style = 'width: 235px;';
						}
						
						$html .= '<div style="margin-bottom: 5px;"><em>Current Image:</em></div>';
						$html .= '<div style="width: 240px; padding: 10px; border: 1px solid #DEDEDE;"><img src="' . $image . '" style="' . $style . '" alt="Existing Taxonomy Image"/><br />URL: <small>' . $image . '</small></div>';
					} else {
						$html .= '<div style="margin-bottom: 5px;"><em>Current Image:</em></div>';
						$html .= '<div style="width: 240px; padding: 10px; border: 1px solid #DEDEDE;"><img src="' . $image . '" style="width: 235px;" alt="Existing Taxonomy Image"/><br />URL: <small>' . $image . '</small></div>';
					}
					$html .= $this->end_row();
				}
				
				$html .= '<div style="margin-bottom: 5px;">';
				$html .= $this->upload_file_form($name, false);
				$html .= $this->draw_hidden('upload_file', true);
				$html .= '<small>Quick shortcode for image: [sbu_taxonomy_' . $name . ']</small>';
				$html .= '</div>';
				
				$html .= '</td></tr>';
			}
		}
		
		$html .= '</table></div>';
		}		
		
		echo $html;
	}	
	
	public function post_sidebar($post, $metabox) {
		global $post_ID, $sbu_plugin_dir_url;
		
		if ($name = $metabox['args']) {
			$html = '';
			
			//if (!$this->jquery_run) {
			//	$this->jquery_add_attr('#post', 'enctype', 'multipart/form-data');
			//	$this->jquery_add_attr('#post', 'encoding', 'multipart/form-data');
			//	$this->jquery_run = true;
			//}
			
			$html .= '<div id="sb_uploader_page_box">';
			
			if ($image = get_post_meta($post_ID, $name, true)) {
				$html .= $this->start_row();
				
				if (!$image_data = @getimagesize($image)) {
					$image_data = @getimagesize(site_url() . $image);
				}
				
				if ($image_data) {
					$style = '';
					if ($image_data[0] > 260) {
						$style = 'width: 260px;';
					}
					
					$image_resized = $sbu_plugin_dir_url . 'timthumb.php?src=' . $image . '&w=260&q=100';
					
					$html .= '	<img src="' . $image_resized . '" style="' . $style . '" alt="Existing Post Image"/>
							<br /><small>' . $image . '</small>
							<br /><small>' . $image_data[0] . 'x' . $image_data[1] . ' (' . $image_data['mime'] . ')</small>
						';
				} else {
					$html .= '	<img src="' . $image . '" style="width: 260px;" alt="Existing Post Image"/><br />URL: <small>' . $image . '</small>';
				}
				
				$html .= '<div><small>Remove? <input type="checkbox" name="sbu_delete_image[' . $name . ']" value="' . $name . '" onclick="jQuery(\'.sbu_upload_new_form_' . $name . '\').slideToggle();" /></small></div>'; //for forced image removal
				
				$html .= '<hr />';
				$html .= $this->end_row();
			}
			
			$html .= '<div class="sbu_upload_new_form_' . $name . '" style="margin-bottom: 5px;">';
			$html .= $this->upload_file_form($name, false);
			$html .= $this->draw_hidden('upload_file', true);
			$html .= '<div><small>Quick shortcode for image: [sbu_' . $name . ']</small></div>';
			
			if ($uploader = $this->get_uploader($name)) {
				if ($uploader['fallback_url']) {
					$html .= '<div><small>Disable fallback image if empty? <input type="checkbox" ' . checked(1, get_post_meta($post_ID, $name . '_no_fallback', true), false) . ' name="sbu_no_fallback[' . $name . ']" value="' . $name . '" /></small></div>'; //for disabling the fallback for this post
				}
			}
			
			$html .= '</div>';
			
			$html .= '</div>';
			
			echo $html;
		}
	}
	
	public function get_uploader($name) {
		$return = false;
		$name_uploaders = get_post_type() . '_uploaders';
		
		if (isset($this->settings) && isset($this->settings->$name_uploaders)) {
			$settings_uploaders = $this->settings->$name_uploaders;
			//echo $this->settings->$name_uploaders;
			if (is_array($settings_uploaders) || is_object($settings_uploaders)) {
				foreach ($settings_uploaders as $uploader) {
					if ($uploader['name'] == $name) {
						$return = $uploader;
						break;
					}
				}
			}
		}
		
		return $return;
	}
	
	public function add_error($msg) {
		$this->error_msgs[] = $msg;
		$this->error = true;
	}
	
	public function is_error() {
		return $this->error;
	}
	
	public function add_feedback($msg) {
		$this->feedback_msgs[] = $msg;
	}
	
	public function display_feedback($return = true) {
		$html = '';
		
		if (count($this->feedback_msgs)) {
			$html .= '<div style="margin: 10px 0px;">';
			foreach ($this->feedback_msgs as $msg) {
				$html .= '<div style="color: #000000; background-color:#CCFFCC; border: 1px solid #336633; padding: 8px; margin: 0px 10px 5px 0px;">' . $msg . '</div>';
			}
			$html .= '</div>';
		}
		
		if ($return) {
			return $html;
		} else {
			echo $html;
		}
	}
	
	public function display_errors($return = true) {
		$html = '';
		
		if (count($this->error_msgs)) {
			$html .= '<div style="margin: 10px 0px;">';
			foreach ($this->error_msgs as $msg) {
				$html .= '<div style="color: #333333; background-color:#FFEBE8; border: 1px solid #CC0000; padding: 8px; margin: 0px 10px 5px 0px;">' . $msg . '</div>';
			}
			$html .= '</div>';
		}
		
		if ($return) {
			return $html;
		} else {
			echo $html;
		}
	}
	
	public function process_updates($post_id = false) {
		if (!$this->updates_processed) {
			$this->updates_processed = true;
			
			$this->handle_update_settings();
			$this->handle_make_writeable();
			$this->handle_create_directory();
			$this->handle_upload_file($post_id);
		}
	}
	
	function handle_update_settings() {
		$settings = $this->get_settings();
		
		if ($this->post('sbu_update_settings')) {
			if ($new_settings = $this->post('settings')) {
				if (is_array($new_settings)) {
					foreach ($new_settings as $key=>$value) {
						if (is_array($value)) {
							$value = serialize($value);
						}
						
						$settings->$key = stripcslashes($value);
					}
					
					if (update_option('sbu_settings', $settings)) {
						$this->add_feedback('Settings have been updated');
					}
				}
			}
		}
	}	
	
	public function handle_make_writeable() {
		clearstatcache();
		
		if ($dir = $this->post('make_writeable')) {
			if (!is_writable($dir)) {
				if (@chmod($dir, 0775)) {
					$this->add_feedback('"' . $dir . '" is now writeable');
				} else {
					$this->add_error('Could not make "' . $dir . '" writeable');
				}
			}
		}
	}
	
	public function check_file_type($name) {
		$return = false;
		
		$extension = ltrim(substr($name, -4, 4), '.');
		if (in_array($extension, $this->allowed_types)) {
			$return = true;
		}
		
		return $return;
	}
	
	public function handle_upload_file($post_id=false) {
		if (isset($_POST['post_ID'])) {
			if ($_POST['post_ID']) {
				$post_id = $_POST['post_ID'];
			}
		}
		
		if ($this->post('upload_file')) {
			
			if ($upload_path = $this->post('upload_path')) {
				$create_path = $this->post('create_path');
				$replace_existing = $this->post('replace_existing');
				$path_exists = is_dir($upload_path);
				
				if ($path_exists || $create_path) {
					if (!$path_exists) {
						if (!@mkdir($upload_path, 0775, 1)) {
							$this->add_error('There was a problem creating your directory');
						}
					}
					
					if (!$this->is_error()) {
						$post_types = (array)get_post_types(array('_builtin'=>false));
						$post_types['post'] = 'post';
						$post_types['page'] = 'page';
						
						if ($post_types) {
							foreach ($post_types as $post_type) {
								$uploaders_name = $post_type . '_uploaders';						
								if ($uploaders = $this->settings->$uploaders_name) {
									foreach ($uploaders as $i=>$uploader) {
										if (!$post_id) {
											$name = 'logo';
											$label = 'Uploaded File';
										} else {
											$name = $uploader['name'];
											$label = $uploader['label'];
										}
										
										if (isset($_POST['sbu_no_fallback'][$name])) {
											update_post_meta($post_id, $name . '_no_fallback', 1);
										} else {
											delete_post_meta($post_id, $name . '_no_fallback');
										}
										
										if (isset($_POST['sbu_delete_image'][$name])) {
											delete_post_meta($post_id, $name);
											
											if (isset($uploader['set_featured']) && $uploader['set_featured']) {
												global $post;
												delete_post_thumbnail( $post );
											}
										} else if (isset($_FILES[$name]) && $_FILES[$name]['error'] == 0) {
											if ($this->settings->official_wp_uploader) {
												require_once(ABSPATH . 'wp-admin/includes/admin.php');
												$id = media_handle_upload($name, $post_id); //post id of Client Files page
												//unset($_FILES);
												if ( is_wp_error($id) ) {
												    $errors['upload_error'] = $id;
												    $id = false;
												}
											    
												if ($errors || !$id) {
													echo 'There was an error with the image you are trying to upload. Please use the back button in your browser and correct as necessary.';
													$this->printr($errors);
													die;
												} else {
													global $post;
													if (isset($uploader['set_featured']) && $uploader['set_featured']) {
													    set_post_thumbnail( $post, $id );
													}
													
													$image = wp_get_attachment_image_src($id, 'full');
													
													update_post_meta($post_id, $name, $image[0]);
												}
											} else {
												
												
												if (!$error_number = $this->files($name, 'error')) {
													$file_name = str_replace(' ' , '_', $this->files($name, 'name'));
													
													if ($this->check_file_type($file_name)) {
														$new_path = $upload_path . $file_name;
														$file_exists = file_exists($new_path);
														
														if (($file_exists && $replace_existing) || !$file_exists) {
															if ($file_exists) {
																if (!@unlink($new_path)) {
																	$new_path = $upload_path . date('YmdHis') . $file_name; //no need to top in the event of this happening. Just rename the uploaded file
																	//$this->add_error($label . ': Could not delete the old file. Please remove "' . $new_path . '" to continue');
																}
															}
															
															if (!$this->is_error()) {
																
																if (is_writable($upload_path)) {
																	if (@move_uploaded_file($this->files($name, 'tmp_name'), $new_path)) {
																		@chmod($new_path, 0777);
																		
																		//$img = new sb_image_functions();
																		//$img->resize_image($new_path, false, (int)get_option( 'large_size_w', '1024' ));
																		$url = '';
																		if ($post_id) {
																			$upload = wp_upload_dir();
																			
																			$full_url = $upload['url'];
																			$site_url = parse_url(site_url());
																			$site_url = $site_url['scheme'] . '://' . trailingslashit($site_url['host']);
																			$site_url = rtrim($site_url, '/');
																			
																			$url = str_replace($site_url, '', $full_url);
																			$url = trailingslashit($url) . $file_name;
																			//$url = str_replace(ABSPATH, trailingslashit(get_option('siteurl')), $new_path);
																			
																			if ($this->get_url_type() == 'absolute') {
																				$url = 'http://' . rtrim($_SERVER['HTTP_HOST'], '/') . $url;
																			}
																			
																			if ($post_id) {
																				update_post_meta($post_id, $name, $url);
																			}
																			
																			$url = '<p>It is accessable via the site at the following URL: "<a href="' . $url . '">' . $url . '</a>"</p>';
																		}
																		
																		$this->add_feedback($label . ': You have successfully uploaded your file to the site. It is now located at the following location: "' . $new_path . '"' . $url);
																	} else {
																		$this->add_error($label . ': There was a problem moving the file. Please make sure that the destination directory is writeable "' . $upload_path . '"');
																	}
																} else {
																	$this->add_error($label . ': Cannot write to the chosen directory "' . $upload_path . '". Please check the permissions are correct and try again');
																}
															}
														} else {
															$this->add_error($label . ': A file by that name already exists. To overwrite it you can use the advanced options if necessary');
														}
													} else {
														$this->add_error($label . ': Please upload an image filetype only. Please contact the plugin author if you believe the file you are uploading to be valid');
													}
												} else {
													$dont_break = false;
													switch ($error_number) {
														case UPLOAD_ERR_INI_SIZE:
															$max = ini_get('upload_max_filesize');
															$msg = 'The uploaded file is too big (Max ' . $max . ')';
															break;
														case UPLOAD_ERR_FORM_SIZE:
															$msg = 'The uploaded file is too big (2)';
															break;
														case UPLOAD_ERR_PARTIAL:
															$msg = 'The uploaded file was only partially uploaded';
															break;
														case UPLOAD_ERR_NO_FILE:
															$msg = 'No file was uploaded';
															$dont_break = true;
															//break;
													}
													
													if (!$dont_break) {
														$this->add_error($label . ': There was a problem uploading your file: ' . $msg);
													}
												}
											
											}
											
											unset($_FILES[$name]); //so the same script can't rerun this for any reason
										}
									}
								}
							}
						}
					}
				} else {
					$this->add_error('The directory "' . $upload_path . '" does not exist. You can create it using the form at the bottom of the page.');
				}
				
			} else {
				$this->add_error('You must add an upload location');
			}
		}
	}
	
	public function get_settings() {
		//delete_option('sbu_settings');
		
		//cache settings?
		if (!$settings = get_option('sbu_settings')) {
			$settings = new stdClass();
			
			$settings->post_uploader_count = 1;
			$settings->post_uploaders = array();
			$settings->post_uploaders[1] = array('label'=>'Post image', 'set_featured'=>1, 'fallback_url'=>'', 'name'=>'post_image', 'post_types'=>array());
			$settings->post_uploaders = serialize($settings->post_uploaders);
			
			$settings->page_uploader_count = 1;
			$settings->page_uploaders = array();
			$settings->page_uploaders[1] = array('label'=>'Page image', 'set_featured'=>1, 'fallback_url'=>'', 'name'=>'post_image', 'post_types'=>array());
			$settings->page_uploaders = serialize($settings->page_uploaders);
			
			$settings->taxonomy_uploader_count = 1;
			$settings->taxonomy_uploaders = array();
			$settings->taxonomy_uploaders[1] = array('label'=>'Taxonomy image', 'name'=>'image', 'fallback_url'=>'');
			$settings->taxonomy_uploaders = serialize($settings->taxonomy_uploaders);
			
			$settings->url_type = 'relative';
			$settings->official_wp_uploader = true;
			
			update_option('sbu_settings', $settings);
		}
		
		if ($settings->post_uploaders) {
			$settings->post_uploaders = @unserialize($settings->post_uploaders);
			
			$post_uploader_count = count($settings->post_uploaders);
			if ($settings->post_uploader_count > $post_uploader_count) {
				for ($i = ($post_uploader_count+1); $i <= $settings->post_uploader_count; $i++) {
					$settings->post_uploaders[$i] = array('name'=>'post_image_' . $i, 'label'=>'Post Image ' . $i, 'fallback_url'=>'');
				}
			}
		}
		
		if ($settings->page_uploaders) {
			$settings->page_uploaders = @unserialize($settings->page_uploaders);
			
			$post_uploader_count = count($settings->page_uploaders);
			if ($settings->page_uploader_count > $post_uploader_count) {
				for ($i = ($post_uploader_count+1); $i <= $settings->page_uploader_count; $i++) {
					$settings->page_uploaders[$i] = array('name'=>'post_image_' . $i, 'label'=>'Post Image ' . $i, 'fallback_url'=>'');
				}
			}
		}
		
		if ($settings->taxonomy_uploaders) {
			$settings->taxonomy_uploaders = @unserialize($settings->taxonomy_uploaders);
								     
			$taxonomy_uploader_count = count($settings->taxonomy_uploaders);
			if ($settings->taxonomy_uploader_count > $taxonomy_uploader_count) {
				for ($i = ($taxonomy_uploader_count+1); $i <= $settings->taxonomy_uploader_count; $i++) {
					$settings->taxonomy_uploaders[$i] = array('name'=>'image_' . $i, 'label'=>'Taxonomy Image ' . $i, 'fallback_url'=>'');
				}
			}								     
		}
		
		if ($post_types = get_post_types(array('_builtin'=>false))) {
			foreach ($post_types as $post_type) {
				$uploaders_name = $post_type . '_uploaders';
				$uploader_count_name = $post_type . '_uploader_count';
				if (@$settings->$uploaders_name && !is_array($settings->$uploaders_name)) {
					$settings->$uploaders_name = unserialize($settings->$uploaders_name);
					
					$uploader_count = count($settings->$uploaders_name);
					if ($settings->$uploader_count_name > $uploader_count) {
						for ($i = ($uploader_count+1); $i <= $settings->$uploader_count_name; $i++) {
							$settings_uploaders_name = &$settings->$uploaders_name;
							$settings_uploaders_name[$i] = array(
								'name'=>str_replace(' ', '_', strtolower($post_type)) . '_image_' . $i
								, 'label'=>ucwords($post_type) . ' Image ' . $i
							);
						}
					}
				}
			}
		}		
		
		$this->settings = $settings;
		
		return $settings;
	}	
	
	public function handle_create_directory() {
		if ($this->post('create_directory')) {
			if ($new_dir = $this->post('path_to_create')) {
				if (is_dir($new_dir) || is_file($new_dir) || is_link($new_dir)) {
					$this->add_error('The path already exists as either a file or directory');
				} else {
					if (!@mkdir($new_dir, 0777, 1)) {
						$this->add_error('There was a problem creating your directory');
					} else {
						$this->add_feedback('The directory "' . $new_dir . '" was created successfully');
					}
				}
			} else {
				$this->add_error('There was an error with your request');
			}
		}
	}
	
	public function header_scripts() {
		wp_enqueue_script("jquery");
	}
	
	public function get_url_type() {
		if (!$this->settings->url_type) {
			$this->settings->url_type = 'relative';
		}
		
		return $this->settings->url_type;
	}

	function generate_options_page() {
		global $sbu_plugin_name;
		$html = '';
		
		$label_style = 'style="float: left; margin-right: 30px; margin-top: 8px; font-weight: bold; width: 150px;"';
		$style = 'display: inline-block; margin-right: 5px; margin-left: 5px;';
		
		$this->process_updates();
		$settings = $this->get_settings();
		
		$html .= $this->display_messages();
		
		$html .= '<div class="wrap" id="poststuff" style="margin-bottom: 10px;">
				<h2>' . $sbu_plugin_name . '</h2>
				<p>This page allows you to configure the plugin. Add more uploaders for the various post types and categories using the boxes below.</p>';
		
		$html .= '<form method="POST" style="margin-left: 6px;">';
		
		$html .= $this->start_box('About the Author');
		$html .= '<p><strong>' . $sbu_plugin_name . ' was written and is maintained by <a href="http://www.sean-barton.co.uk/" target="_blank">Sean Barton</a> of <a href="http://www.sean-barton.co.uk/" target="_blank">Tortoise IT</a>. If you would like to ask a question, report a bug or ask for an extension to this or any other plugin then please <a href="http://www.sean-barton.co.uk/contact-me/enquiry/" target="_blank">get in touch</a>.</strong></p>';
		$html .= $this->end_box();
		
		$html .= $this->start_box('Configure Post Uploaders');
		
		$label = 'Post Uploader Count';
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
		$html .= '<select name="settings[post_uploader_count]">';
		for ($i=0; $i<=25; $i++) {
			$html .= '<option value="' . $i . '" ' . selected($i, $settings->post_uploader_count, false) . '>' . $i . '</option>';
		}
		$html .= '</select>';
		
		$html .= $this->draw_submit('sbu_update_settings', 'Confirm Update');
		$html .= $this->get_inline_caption('How many Post uploaders would you like to add?');
		$html .= '</div>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		if (!$uploaders = $settings->post_uploaders) {
			$uploaders = array();
		}
		
		for ($i = 1; $i <= $settings->post_uploader_count; $i++) {
			$name_value = (isset($uploaders[$i]['name']) && $uploaders[$i]['name'] ? $uploaders[$i]['name']:'post_image_' . $i);
			$label_value = (isset($uploaders[$i]['label']) && $uploaders[$i]['label'] ? $uploaders[$i]['label']:'Post Image ' . $i);
			$featured_value = (isset($uploaders[$i]['set_featured']) && $uploaders[$i]['set_featured'] ? $uploaders[$i]['set_featured']:0);
			$fallback_url = (isset($uploaders[$i]['fallback_url']) && $uploaders[$i]['fallback_url'] ? $uploaders[$i]['fallback_url']:'');
			
			$label = 'Post Uploader ' . $i;
			$html .= $this->start_row('margin-bottom: 10px;');
			$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
			
			$html .= '<span style="' . $style . '">Custom Field Name</span>';
			$html .= $this->draw_text('settings[post_uploaders][' . $i . '][name]', $name_value);
			$html .= '<span style="' . $style . ' margin-left: 5px;">Uploader Label</span>';
			$html .= $this->draw_text('settings[post_uploaders][' . $i . '][label]', $label_value);
			$html .= '<span style="' . $style . ' margin-left: 5px;">Fallback URL</span>';
			$html .= $this->draw_text('settings[post_uploaders][' . $i . '][fallback_url]', $fallback_url);
			
			if ($settings->official_wp_uploader) {
				$html .= '<input style="' . $style . '" type="checkbox" name="settings[post_uploaders][' . $i . '][set_featured]" value="1" ' . checked($featured_value, 1, false) . ' /> Set as featured image?';
			}			
			
			$html .= '</div>';
			$html .= $this->draw_clearing_div();
			$html .= $this->end_row();
		}
		
		$html .= $this->start_row();
		$html .= $this->draw_submit('sbu_update_settings', 'Update All Settings');
		$html .= $this->end_row();		
		
		$html .= $this->end_box();
		
		$html .= $this->start_box('Configure Page Uploaders');
		
		$label = 'Page Uploader Count';
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
		$html .= '<select name="settings[page_uploader_count]">';
		for ($i=0; $i<=25; $i++) {
			$html .= '<option value="' . $i . '" ' . selected($i, $settings->page_uploader_count, false) . '>' . $i . '</option>';
		}
		$html .= '</select>';
		
		$html .= $this->draw_submit('sbu_update_settings', 'Confirm Update');
		$html .= $this->get_inline_caption('How many Page uploaders would you like to add?');
		$html .= '</div>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		if (!$uploaders = $settings->page_uploaders) {
			$uploaders = array();
		}
		
		for ($i = 1; $i <= $settings->page_uploader_count; $i++) {
			$name_value = (isset($uploaders[$i]['name']) && $uploaders[$i]['name'] ? $uploaders[$i]['name']:'post_image_' . $i);
			$label_value = (isset($uploaders[$i]['label']) && $uploaders[$i]['label'] ? $uploaders[$i]['label']:'Page Image ' . $i);
			$featured_value = (isset($uploaders[$i]['set_featured']) && $uploaders[$i]['set_featured'] ? $uploaders[$i]['set_featured']:0);
			$fallback_url = (isset($uploaders[$i]['fallback_url']) && $uploaders[$i]['fallback_url'] ? $uploaders[$i]['fallback_url']:'');
			
			$label = 'Page Uploader ' . $i;
			$html .= $this->start_row('margin-bottom: 10px;');
			$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
			
			$html .= '<span style="' . $style . '">Custom Field Name</span>';
			$html .= $this->draw_text('settings[page_uploaders][' . $i . '][name]', $name_value);
			$html .= '<span style="' . $style . ' margin-left: 5px;">Uploader Label</span>';
			$html .= $this->draw_text('settings[page_uploaders][' . $i . '][label]', $label_value);
			$html .= '<span style="' . $style . ' margin-left: 5px;">Fallback URL</span>';
			$html .= $this->draw_text('settings[page_uploaders][' . $i . '][fallback_url]', $fallback_url);
			
			if ($settings->official_wp_uploader) {
				$html .= '<input style="' . $style . '" type="checkbox" name="settings[page_uploaders][' . $i . '][set_featured]" value="1" ' . checked($featured_value, 1, false) . ' /> Set as featured image?';
			}			
			
			$html .= '</div>';
			$html .= $this->draw_clearing_div();
			$html .= $this->end_row();
		}
		
		$html .= $this->start_row();
		$html .= $this->draw_submit('sbu_update_settings', 'Update All Settings');
		$html .= $this->end_row();
		
		$html .= $this->end_box();
		
		if ($post_types = get_post_types(array('_builtin'=>false))) {
			foreach ($post_types as $post_type) {
				$uploader_count_name = $post_type . '_uploader_count';
				$uploader_name = $post_type . '_uploaders';
				
				$html .= $this->start_box('Configure "' . $post_type . '" Uploaders (Custom Post Type)');
				
				$label = 'Uploader Count';
				$html .= $this->start_row('margin-bottom: 10px;');
				$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
				$html .= '<select name="settings[' . $uploader_count_name . ']">';
				for ($i=0; $i<=25; $i++) {
					$html .= '<option value="' . $i . '" ' . selected($i, @$settings->$uploader_count_name, false) . '>' . $i . '</option>';
				}
				$html .= '</select>';
				$html .= $this->draw_submit('sbu_update_settings', 'Confirm Update');
				$html .= $this->get_inline_caption('How many "' . $post_type . '" uploaders would you like to add?');
				$html .= '</div>';
				$html .= $this->draw_clearing_div();
				$html .= $this->end_row();
				
				if (!$uploaders = $settings->$uploader_name) {
					$uploaders = array();
				}
				
				for ($i = 1; $i <= $settings->$uploader_count_name; $i++) {
					$name_value = (isset($uploaders[$i]['name']) && $uploaders[$i]['name'] ? $uploaders[$i]['name']:$post_type . '_image_' . $i);
					$label_value = (isset($uploaders[$i]['label']) && $uploaders[$i]['label'] ? $uploaders[$i]['label']:ucwords($post_type) . ' Image ' . $i);
					$featured_value = (isset($uploaders[$i]['set_featured']) && $uploaders[$i]['set_featured'] ? $uploaders[$i]['set_featured']:0);
					$fallback_url = (isset($uploaders[$i]['fallback_url']) && $uploaders[$i]['fallback_url'] ? $uploaders[$i]['fallback_url']:'');
					
					$label = 'Uploader ' . $i;
					$html .= $this->start_row('margin-bottom: 10px;');
					$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
					
					$html .= '<span style="' . $style . '">Custom Field Name</span>';
					$html .= $this->draw_text('settings[' . $uploader_name . '][' . $i . '][name]', $name_value);
					$html .= '<span style="' . $style . ' margin-left: 5px;">Uploader Label</span>';
					$html .= $this->draw_text('settings[' . $uploader_name . '][' . $i . '][label]', $label_value);
					$html .= '<span style="' . $style . ' margin-left: 5px;">Fallback URL</span>';
					$html .= $this->draw_text('settings[' . $uploader_name . '][' . $i . '][fallback_url]', $fallback_url);
					
					if ($settings->official_wp_uploader) {
						$html .= '<input style="' . $style . '" type="checkbox" name="settings[' . $uploader_name . '][' . $i . '][set_featured]" value="1" ' . checked($featured_value, 1, false) . ' /> Set as featured image?';
					}					
					
					$html .= '</div>';
					$html .= $this->draw_clearing_div();
					$html .= $this->end_row();
				}
				
				$html .= $this->start_row();
				$html .= $this->draw_submit('sbu_update_settings', 'Update All Settings');
				$html .= $this->end_row();				
				
				$html .= $this->end_box();
			}
		}
		
		$html .= $this->start_box('Configure Taxonomy Uploaders');
		
		$label = 'Taxonomy Uploader Count';
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
		$html .= '<select name="settings[taxonomy_uploader_count]">';
		for ($i=0; $i<=25; $i++) {
			$html .= '<option value="' . $i . '" ' . selected($i, $settings->taxonomy_uploader_count, false) . '>' . $i . '</option>';
		}
		$html .= '</select>';
		$html .= $this->draw_submit('sbu_update_settings', 'Confirm Update');
		$html .= $this->get_inline_caption('How many taxonomy (Tag, Category, etc...) uploaders would you like to add?');
		$html .= '</div>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		if (!$uploaders = $settings->taxonomy_uploaders) {
			$uploaders = array();
		}
		
		for ($i = 1; $i <= $settings->taxonomy_uploader_count; $i++) {
			$name_value = (isset($uploaders[$i]['name']) && $uploaders[$i]['name'] ? $uploaders[$i]['name']:'taxonomy_image_' . $i);
			$label_value = (isset($uploaders[$i]['label']) && $uploaders[$i]['label'] ? $uploaders[$i]['label']:'Taxonomy Image ' . $i);
			
			$label = 'Taxonomy Uploader ' . $i;
			$html .= $this->start_row('margin-bottom: 10px;');
			$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
			
			$html .= '<span style="' . $style . '">Custom Field Name</span>';
			$html .= $this->draw_text('settings[taxonomy_uploaders][' . $i . '][name]', $name_value);
			$html .= '<span style="' . $style . ' margin-left: 5px;">Uploader Label</span>';
			$html .= $this->draw_text('settings[taxonomy_uploaders][' . $i . '][label]', $label_value);
			//$html .= '<span style="' . $style . ' margin-left: 5px;">Fallback URL</span>';
			//$html .= $this->draw_text('settings[taxonomy_uploaders][' . $i . '][fallback_url]', $fallback_url);
			
			$html .= '</div>';
			$html .= $this->draw_clearing_div();
			$html .= $this->end_row();
		}
		
		$html .= $this->start_row();
		$html .= $this->draw_submit('sbu_update_settings', 'Update All Settings');
		$html .= $this->end_row();		
		
		$html .= $this->end_box();
		
		$html .= $this->start_box('Debug');
		
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
		
		$html .= '<p>upload_max_filesize: ' . $max_upload . 'mb</p>';
		$html .= '<p>post_max_size: ' . $max_upload . 'mb</p>';
		$html .= '<p>memory_limit: ' . $max_upload . 'mb</p>';
		
		$html .= '<hr />';
		
		$html .= '<p>Upload path is set to: ' . $this->get_upload_path() . '</p>';
		
		$html .= '<p>Result from the wp_upload_dir command used by this plugin is: <pre>' . print_r(wp_upload_dir(), true) . '</pre></p>';
		
		$html .= $this->end_box();
		
		$html .= $this->start_box('General Settings (Phasing out)');
		
		$label = 'Select an uploader to use';
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
		$html .= '<select name="settings[official_wp_uploader]">';
		foreach (array('core'=>true, 'legacy'=>false) as $label=>$value) {
			$html .= '<option value="' . $value . '" ' . selected($value, $settings->official_wp_uploader, false) . '>' . ucfirst($label) . '</option>';
		}
		$html .= '</select>';
		$html .= $this->get_inline_caption('Select Legacy if you don\'t want images resized. "Core" recommended. This option is here in for old users of the system in the event that the new core method doesn\'t work');
		$html .= '</div>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		$label = 'Relative/Absolute URLs';
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<div ' . $label_style . '>' . $label . '</div><div style="float: left;">';
		$html .= '<select name="settings[url_type]">';
		foreach (array('relative', 'absolute') as $url_type) {
			$html .= '<option value="' . $url_type . '" ' . selected($url_type, $settings->url_type, false) . '>' . ucfirst($url_type) . '</option>';
		}
		$html .= '</select>';
		$html .= $this->get_inline_caption('Do you want the URLs to be stored as relative or absolute? If you don\'t know what this means or don\'t care then choose Relative. Effects Legacy settings only');
		$html .= '</div>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		$html .= $this->start_row();
		$html .= $this->draw_submit('sbu_update_settings', 'Update All Settings');
		$html .= $this->end_row();		
		
		$html .= $this->end_box();
		
		$html .= '</form>';
		
		$html .= '</div>';
			
		echo $html;
	}
	
	public function draw_text($name, $value=false, $style=false, $class=false, $caption=false, $args=false) {
		if (!$style) {
			$style = 'width: 200px; padding: 5px;';
		}
		
		$field = '<input id="' . $name . '" type="text" value="' . $value . '" style="' . $style . '" ' . ($args ? $args:'') . ' class="input ' . $class . '" name="' . $name . '" />' . ($caption ? $this->get_block_caption($caption):'');
		
		return $field;
	}	
	
	public function draw_text_row($label, $name, $value=false, $style=false, $class=false, $caption=false, $args=false) {
		$html = '';
		
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<label><div style="float: left; margin-right: 30px; margin-top: 8px; width: 200px;">' . $label . '</div><div style="float: left;">';
		$html .= $this->draw_text($name, $value, $style, $class, false, $args) . ($caption ? $this->get_block_caption($caption):'');
		$html .= '</div></label>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		return $html;
	}
	
	public function get_inline_caption($text) {
		return '<span style="color: gray; margin-left: 10px;">' . $text . '</span>';
	}
	
	public function get_block_caption($text) {
		return '<div style="color: gray; margin-top: 5px; width: 500px;">' . $text . '</div>';
	}	
	
	public function draw_clearing_div() {
		return '<div style="clear: both; padding: 0px; margin: 0px; height: 1px;">&nbsp;</div>';
	}   
	
	public function draw_textarea_short_row($label, $name, $value=false, $style=false, $class=false, $caption=false) {
		$html = '';
		
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<label><div style="float: left; margin-right: 30px; margin-top: 8px; width: 200px;">' . $label . '</div><div style="float: left;">';
		$html .= $this->draw_textarea_short($name, $value, $style, $class) . ($caption ? $this->get_block_caption($caption):'');
		$html .= '</div></label>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		return $html;		
	}

	public function draw_textarea_short($name, $value=false, $style=false, $class=false, $caption=false) {
		if (!$style) {
			$style = 'width: 370px; height: 80px;';
		}
		
		return '<textarea style="' . $style . '" class="input ' . $class . '" name="' . $name . '">' . htmlentities($value) . '</textarea>' . ($caption ? $this->get_block_caption($caption):'');
	}		
	
	public function draw_textarea_row($label, $name, $value=false, $style=false, $class=false, $caption=false) {
		$html = '';
		
		$html .= $this->start_row('margin-bottom: 10px;');
		$html .= '<label><div style="float: left; margin-right: 30px; margin-top: 8px; width: 200px;">' . $label . '</div><div style="float: left;">';
		$html .= $this->draw_textarea($name, $value, $style, $class) . ($caption ? $this->get_block_caption($caption):'');
		$html .= '</div></label>';
		$html .= $this->draw_clearing_div();
		$html .= $this->end_row();
		
		return $html;		
	}	
	
	public function admin_loader($page) {
		if (method_exists($this, $page)) {
			echo $this->$page();
		} else {
			echo $this->generate_options_page();
		}
	}		

	public function admin_page() {
		global $sbu_plugin_name, $sbu_plugin_dir_url;
		
		$sbu_pages = array(
			//__('File Manager','sbu')=>'upload_page'
		);		
		
		$func = create_function('', 'sbu_call_function("admin_loader", "generate_options_page");');
		$access_level = 'level_10';
		$page = $parent_page = 'sbu_admin';

		//$icon = $sbu_plugin_dir_url . 'icon.png';
		//add_menu_page($sbu_plugin_name, $sbu_plugin_name, $access_level, $page, $func, $icon);
		
		add_options_page($sbu_plugin_name, $sbu_plugin_name, $access_level, $page, $func);
	
		foreach ($sbu_pages as $title=>$page) {
			$func = create_function('', 'sbu_call_function("admin_loader", "' . $page . '");');
			add_submenu_page($parent_page, $title, $title, $access_level, $page, $func);
		}		
		
		//add_menu_page($sbu_plugin_name, $sbu_plugin_name, 'level_10', __FILE__, array($this, 'upload_page'));
	}
	
	public function start_row($additional_style=false) {
		return '<div style="margin-bottom: 10px;' . $additional_style . '">';
	}
	
	public function end_row() {
		return '</div>';
	}
	
	public function post($name, $default=false) {
		$var =  (isset($_POST[$name]) ? $_POST[$name]:$default);
		return $var;
	}
	
	public function files($name, $attribute) {
	    return (isset($_FILES[$name][$attribute]) ? $_FILES[$name][$attribute]:false);
	}	
	
	public function draw_radio($name, $value, $default=false) {
		$html = ' <input type="radio" style="width: 30px;" value="' . $value . '" ' . ($this->post($name, $default) ? 'checked="checked"':'') . ' name="' . $name . '" />';
		
		return $html;
	}
	
	public function jquery_show_hide_event($div_id, $toggle_id) {
		echo "<script>
			jQuery(document).ready(function() {
				jQuery('#" . $div_id . "').hide();
				
				jQuery('a#" . $toggle_id . "').click(function() {
					jQuery('#" . $div_id . "').slideToggle(400);
					return false;
				});
			});
			</script>";
	}
	
	public function upload_file_form($name=false, $advanced_controls=true) {
		$upload_path = $this->get_upload_path();
		
		$html = '';
		
		$html .= $this->start_row();
		$html .= '<div><em>Upload</em>:</div><div><input type="file" name="' . $name . '" /></div>';
		$html .= $this->end_row();
		
		if ($advanced_controls) {
			$html .= $this->start_row();
			$caption = 'Where would you like the file to be uploaded to? (Absolute path required)';
			$html .= '<label>Upload directory: <input type="text" style="width: 500px;" value="' . $upload_path . '" name="upload_path" /></label>
					' . $this->get_inline_caption($caption);
			$html .= $this->end_row();		
			
			//start of advanced options
			$html .= '<div id="sbu_advanced" style="border: 1px solid #DEDEDE; padding: 10px; margin-bottom: 10px;">';
			
			/*
			$html .= $this->start_row();
			$caption = 'What permissions should be assigned to the uploaded file? (774 recommended)';
			$html .= '<label>File Permissions: <input type="text" style="width: 100px;" value="' . $this->post('file_permissions', 774) . '" name="file_permissions" /></label>
					' . $this->get_inline_caption($caption);
			$html .= $this->end_row();
			*/
			
			$html .= $this->start_row();
			$caption = 'If the upload path (above) does not exist then should it be created? (Might be prevented by permissions)';
			$html .= 'Create path if it doesn\'t exist?:
					<label>' . $this->draw_radio('create_path', 1, 1) . ' Yes</label>
					<label>' . $this->draw_radio('create_path', 0) . ' No</label>
					' . $this->get_inline_caption($caption);
			$html .= $this->end_row();
			
			/*
			$html .= $this->start_row();
			$caption = 'If a directory were to be created by the above setting then what should the new permissions be? (774 recommended)';
			$html .= '<label>Directory Permissions: <input type="text" style="width: 100px;" value="' . $this->post('directory_permissions', 774) . '" name="directory_permissions" /></label>
					' . $this->get_inline_caption($caption);
			$html .= $this->end_row();
			*/
			
			$html .= $this->start_row();
			$caption = 'If the file you are uploading has the same name as a file already in the uploads directory then should the existing file be overwritten?';
			$html .= 'Replace existing?:
					<label>' . $this->draw_radio('replace_existing', 1, 1) . ' Yes</label>
					<label>' . $this->draw_radio('replace_existing', 0) . ' No</label>
					' . $this->get_inline_caption($caption);
			$html .= $this->end_row();
			
			$html .= '</div>';
			
			$html .= '<div style="float: left;"><a id="sbu_advanced_toggle" href="#">Toggle Advanced Options</a></div>';
			//end of advanced_options
		} else {
			$html .= $this->draw_hidden('upload_path', $upload_path);
			$html .= $this->draw_hidden('replace_existing', 1);
			$html .= $this->draw_hidden('create_path', 1);
		}
		
		return $html;
	}
	
	public function display_messages($return = true) {
		$html = '';
		
		$html .= $this->display_errors();
		$html .= $this->display_feedback();
		
		if ($return) {
			return $html;
		} else {
			echo $html;
		}
	}
	
	public function upload_page() {
		global $sbu_plugin_name;
		$html = '';
		
		$this->process_updates();
		
		$this->jquery_show_hide_event('sbu_advanced', 'sbu_advanced_toggle');
		
		$html .= $this->display_messages();
		
		$html .= '<div class="wrap" id="poststuff" style="margin-bottom: 10px;">
				<h2>' . $sbu_plugin_name . '</h2>
				<p>This simple plugin is designed to allow you to upload files to your server/site. By default the plugin will upload to the Wordpress uploads directory but if you know the Absolute path you can upload to where ever you like. The file explorer at the bottom of the page will let you navigate your server. The upload location is shown in the bottom section. Use the advanced controls for more control over your uploads if necessary.</p>';
		
		$html .= $this->start_box('Upload a file');
		$html .= '<form method="POST" enctype="multipart/form-data">';
		$html .= $this->upload_file_form();
		$html .= $this->start_row();
		$html .= '<input type="submit" name="upload_file" style="color: white; background-color: #CC6600; font-weight: bold;" value="Upload File" />';
		$html .= $this->end_row();
		
		$html .= '</form>';
		$html .= $this->end_box();
		
		$html .= $this->start_box('Browse a directory');
		$html .= $this->view_directory_form();
		$html .= $this->end_box();
		
		$html .= '</div>';
		
		echo $html;
	}

	public function extend_dir_scan($root, $dir) {
		$new_dir = array();
		
		foreach ($dir as $object) {
			if (!in_array($object, array('.', '..'))) {
				$abs_file = trailingslashit($root) . $object;
				
				$type = (is_dir($abs_file) ? 'Directory':'File');
				$atype = (is_dir($abs_file) ? 'd':'f');
				$size = filesize($abs_file) . ' bytes';
				$perms = substr(decoct(fileperms($abs_file)), 2);
				
				$new_dir[] = array(
					'name'=> $object
					,'abspath'=> $abs_file
					, 'type'=> $type
					, 'perms'=> $perms
					, 'atype'=> $atype
					, 'size'=> $size
				);
			}
		}
		
		return $new_dir;
	}
	
	public function get_back_link($link) {
		$backlink = trailingslashit(substr($link, 0, strrpos(rtrim($link, '/'), '/')));
		
		if (!trim(rtrim($backlink, '/'))) {
			$backlink = false;
		}
		
		return $backlink;
	}

	public function view_directory_form() {
		$upload_path = $this->get_upload_path();
		$this->jquery_show_hide_event('sbu_view_different', 'sbu_view_different_toggle');
		$html = '';
		
		$html .= '<h2 style="margin: 0px; color: gray;">
				Viewing directory: "' . $upload_path . '"
				<form style="display: inline; margin-left: 10px;" method="POST">
				' . $this->draw_hidden('upload_path', $upload_path) . '
				' . $this->draw_submit('', 'Refresh Contents', 'font-weight: bold; color: white; background-color: #CC6600;') . '
				</form>
			</h2>';
			
		$html .= '<div style="padding: 10px; margin-bottom: 10px; border: 1px solid #DEDEDE;">';
		
		if (is_dir($upload_path)) {
			if ($back_link = $this->get_back_link($upload_path)) {
				$html .= $this->start_row();
				$html .= '<form method="POST">';
				$html .= $this->draw_hidden('upload_path', $back_link);
				$html .= $this->draw_submit('', 'Go up a directory to \'' . $back_link . '\'');
				$html .= '</form>';
				$html .= $this->end_row();
			}
				
			if ($dir = @scandir($upload_path)) {
				
				if ($dir = $this->extend_dir_scan($upload_path, $dir)) {
					$num_files = count($dir);
					
					$html .= '<table cellspacing="0" style="margin-bottom: 10px; width: 75%;">
							<tr>';
							
					$html .= $this->draw_th('Name');
					$html .= $this->draw_th('Type');
					$html .= $this->draw_th('Size');
					$html .= $this->draw_th('Permissions');
					
					$html .= '</tr>';
							
					foreach ($dir as $object) {
						$html .= '<tr>';
						
						$name = $object['name'];
						$perms = $object['perms'];
						
						if ($object['atype'] == 'd') {
							$form = '<form method="POST">';
							$form .= $this->draw_hidden('upload_path', $object['abspath']);
							$form .= $this->draw_submit('', $object['name']);
							$form .= '</form>';
							$name = $form;
							
							if (!is_writable($object['abspath'])) {
								$form2 = '<form method="POST" style="display: inline;">';
								$form2 .= $this->draw_hidden('make_writeable', $object['abspath']);
								$form2 .= $this->draw_submit('', 'Make Writeable?');
								$form2 .= '</form>';
								$perms = $perms . ' ' . $form2;
							}
						}
						
						$html .= $this->draw_td($name);
						$html .= $this->draw_td($object['type']);
						$html .= $this->draw_td($object['size']);
						$html .= $this->draw_td($perms);
						
						$html .= '</tr>';
					}
					
					$html .= '</table>';
					$html .= 'Directory contents: ' . $num_files . ' file' . ($num_files == 1 ? '':'s') . ' as at ' . date('H:i jS F Y');
				} else {
					$html .= 'The directory is currently empty (as at ' . date('H:i jS F Y') . ')';
				}
			} else {
				$html .= 'Could not open directory. The permissions likely forbid it.';
			}
		} else {
			$html .= $this->start_row();
			$html .= $upload_path . ' does not exist. Would you like to create it?';
			$html .= $this->end_row();
			
			$html .= '<form method="POST">';
			$html .= $this->draw_hidden('path_to_create', $upload_path);
			$html .= $this->draw_submit('create_directory', 'Create Directory', 'font-weight: bold; color: white; background-color: #669933;');
			$html .= '</form>';
		}
		
		$html .= '</div>';

		$html .= '<a href="#" id="sbu_view_different_toggle">View a different directory?</a>';
		
		$html .= '<form method="POST">';
		
		$html .= '<div id="sbu_view_different">
				<label>Which directory would you like to view?: <input type="text" style="width: 500px;" value="' . $upload_path . '" name="upload_path" /></label>
				<input type="submit" name="change_upload_path" value="Change Directory" style="font-weight: bold; color: white; background-color: #CC6600;" />
			</div>';
		
		$html .= '</form>';
		
		return $html;
	}
	
	public function draw_th($value, $additional_style=false) {
		return '<td style="' . $this->th_style . $additional_style . '">' . $value . '</td>';
	}
	
	public function draw_td($value, $additional_style=false) {
		return '<td style="' . $this->td_style . $additional_style . '">' . $value . '</td>';
	}	
	
	public function draw_submit($name, $value, $style=false, $class=false) {
		return '<input type="submit" name="' . $name . '" class="' . $class . ' button-primary" value="' . $value . '" style="' . $style . '" />';
	}
	
	public function draw_hidden($name, $value=false) {
		return '<input type="hidden" value="' . $value . '" name="' . $name . '" />';
	}
	
	public function start_box($title , $return=true){
		$html = '   <div class="postbox" style="margin: 5px 0px;">
				<h3>' . $title . '</h3>
				<div class="inside">';
	
		if ($return) {
			return $html;
		} else {
			echo $html;
		}
	}
	
	public function end_box($return=true) {
		$html = '       </div>
			    </div>';
	
		if ($return) {
			return $html;
		} else {
			echo $html;
		}
	}
	
	public function display_message($msg, $type='feedback', $return = true) {
		$class = ($type == 'feedback' ? 'updated fade':'error');
		$html =  '	<div id="message" class="' . $class . '" style="clear: both; margin-top: 5px; padding: 7px;">
					' . $msg . '
				</div>';
				
		if ($return) {
		    return $html;
		} else {
		    echo $html;
		}
	}
	
	public function printr($array, $return=false) {
		$html = '<pre>';
		$html .= print_r($array, true);
		$html .= '</pre>';
		
		if ($return) {
			return $return;
		} else {
			echo $html;
		}
	}

	public function get_taxonomy_image($custom_field, $taxonomy=false, $post_id=false, $width=false, $height=false, $quality=100) {
		global $sbu_plugin_dir_url;
		$image = false;
		
		if (!$taxonomy) {
			global $wp_query;
			$term =	$wp_query->queried_object;
			
			$image = $this->get_taxonomy_data($term->term_id, $custom_field);
		} else {
			if (!$post_id) {
				global $post;
				$post_id = $post->ID;
			}
			
			if ($terms = wp_get_post_terms( $post_id, $taxonomy)) {
				foreach ($terms as $category) {
					if ($image = $this->get_taxonomy_data($category->term_id, $custom_field)) {
						break;
					}
				}
			}
		}
		
		if ($image && ($width || $hight)) {
			$image = $sbu_plugin_dir_url . 'timthumb.php?src=' . $image . '&' . ($width ? 'w=' . $width . '&':'') . ($height ? 'h=' . $height . '&':'') . 'q=' . $quality;
		}
		
		return $image;
	}
	
	function get_image($image_name='', $post_id=false, $url_only=false) {
		global $post;
		
		$return = false;
		
		if (!$post_id) {
			$post_id = get_the_ID();
			//$post_id = $post->ID;
		}
		
		if (!$post_id) {
			return false;
		}
		
		if (!$image_name) {
			$image_name = 'full';
		}
		
		if (!$post_id) {
			$post_id = $post->ID;
		}
		
		if (has_post_thumbnail($post_id)) {
			if ($image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $image_name )) {
				$return = $image[0];
			}
		}
		
		if (!$return) {
			if ($post_type = get_post_type()) {
				$uploaders_name = $post_type . '_uploaders';
				if (isset($this->settings->$uploaders_name)) {
					$uploaders = @$this->settings->$uploaders_name;
					if (is_serialized($uploaders)) {
						$uploaders = unserialize($uploaders);
					}
					
					if (is_array($uploaders)) {
						foreach ($uploaders as $uploader) {
							if ($uploader['set_featured']) {
								if (!get_post_meta($post_id, $uploader['name'] . '_no_fallback', true)) {
									$return = $uploader['fallback_url'];
								}
								break;
							}
						}
					}
				}
			}
		}
		
		if (!$url_only && $return) {
			$return = '<img src="' . $return . '" class="sb_image_' . $image_name . '" />';
		}
		
		return $return;
	}	
	
	function get_the_post_image($custom_field, $post_id=false, $url_only=false, $width=false, $height=false, $quality=100) {
		global $post, $sbu_plugin_dir_url;
		
		$image = false;
		
		if (!$post_id) {
			$post_id = $post->ID;
		}
		
		if ($image = get_post_meta($post_id, $custom_field, true)) {
			if ($width || $height) {
				$image = $sbu_plugin_dir_url . 'timthumb.php?src=' . $image . '&' . ($width ? 'w=' . $width . '&':'') . ($height ? 'h=' . $height . '&':'') . 'q=' . $quality;
			}
			
		}
		
		if (!$image) {
			if ($uploader = $this->get_uploader($custom_field)) {
				if (!get_post_meta($post_id, $uploader['name'] . '_no_fallback', true)) {
					$image = $uploader['fallback_url'];
				}
			}
		}		
		
		if ($image) {
		    if (!$url_only) {
			$image = '<img src="' . $image . '" class="sbu_image sbu_' . $custom_field . '" alt="' . $custom_field . '" />';
		    }
		}		
		
		return $image;
	}	
}

function sbu_get_the_image($custom_field, $post_id=false, $url_only=false, $width=false, $height=false, $quality=100) {
	global $sbu;
	
	return $sbu->get_the_post_image($custom_field, $post_id, $url_only, $width, $height, $quality);
}

function sbu_the_image($custom_field, $post_id=false, $url_only=false, $width=false, $height=false, $quality=100) {
	echo sbu_get_the_image($custom_field, $post_id, $url_only, $width, $height, $quality);
}

function sbu_the_wp_image($image_name=false, $post_id=false, $url_only=false) {
	global $sbu;
	
	return $sbu->get_image($image_name, $post_id, $url_only);
}

function sbu_get_the_image_resized($custom_field, $width=false, $height=false, $quality=100, $post_id=false) {
	return sbu_get_the_image($custom_field, $post_id, false, $width, $height, $quality);
}

function sbu_the_image_resized($custom_field, $width=false, $height=false, $quality=100, $post_id=false) {
	echo sbu_get_the_image_resized($custom_field, $width, $height, $quality, $post_id);
}

function sbu_get_the_taxonomy_image_resized($custom_field, $width=false, $height=false, $taxonomy=false, $quality=100, $post_id=false) {
	return sbu_get_the_taxonomy_image($custom_field, $taxonomy, $post_id, $width, $height, $quality);
}

function sbu_get_the_category_image($custom_field, $post_id=false, $width=false, $height=false, $quality=100, $taxonomy=false) {
	global $sbu;
	
	return $sbu->get_taxonomy_image($custom_field, $taxonomy, $post_id, $width, $height, $quality);
}

function sbu_get_the_taxonomy_image($custom_field, $taxonomy=false, $post_id=false, $width=false, $height=false, $quality=100) {
	return sbu_get_the_category_image($custom_field, $post_id, $width, $height, $quality, $taxonomy);
}

function sbu_call_function($function, $arg=false) {
	global $sbu;
	
	$sbu->$function($arg);
}

function sbu_generate_plugin_dir_url() {
	$last_slash = strrpos(__FILE__, '/');
	$abs_dir_path = substr(__FILE__, 0, $last_slash);
	$last_slash = strrpos($abs_dir_path, '/');
	$dir_path = substr($abs_dir_path, $last_slash);
	
	return plugins_url() . trailingslashit($dir_path);
}

if (!class_exists('sb_image_functions')) {
	class sb_image_functions {
		
		public function resize_image($image_url, $new_file=false, $larger_side) {
			$img = $image_url;
			$new_larger_side = $larger_side;
			
			if (!$image_data = @getimagesize($img)) {
				return false;
			}
			
			$start_w = $image_data[0];
			$start_h = $image_data[1];
			
			if ($start_h <= $larger_side && $start_w <= $larger_side) {
				return false;
			}
			
			if ($start_w >= $start_h) {
				$new_w = $new_larger_side;
				
				$new_h = (100 / ($start_w / $new_w)) * .01;
				$new_h = @round($start_h * $new_h);
			} elseif ($start_h >= $start_w) {
				$new_h = $new_larger_side;
				
				$new_w = (100 / ($start_h / $new_h)) * .01;
				$new_w = @round($start_w * $new_w);
			} else {
				$new_h = $new_w = $new_larger_side;
			}
			
			if (!$im = @$this->imagecreatefromfile($img)) {
				return false;
				
			} else {
				// Create the resized image destination
				$thumb = @imagecreatetruecolor($new_w, $new_h);
				// Copy from image source, resize it, and paste to image destination
				if (@imagecopyresampled($thumb, $im, 0, 0, 0, 0, $new_w, $new_h, $start_w, $start_h)) {
					if (!$new_file) {
						$new_file = $image_url;
						@unlink($image_url);
					}
					
					if (@imagejpeg($thumb, $new_file)) {
						@chmod($new_file, 0777);
					}
				}
			}
		}
		
		function imagecreatefromfile($path) {
		    $info = @getimagesize($path);
		   
		    if (!$info) {
			return false;
		    }
		   
		    $functions = array(
			IMAGETYPE_GIF => 'imagecreatefromgif',
			IMAGETYPE_JPEG => 'imagecreatefromjpeg',
			IMAGETYPE_PNG => 'imagecreatefrompng',
			IMAGETYPE_WBMP => 'imagecreatefromwbmp',
			IMAGETYPE_XBM => 'imagecreatefromwxbm',
			IMAGETYPE_BMP => 'sbu_imagecreatefrombmp'
		    );
		   
		    if (!$functions[$info[2]]) {
			return false;
		    }
		   
		    if (!function_exists($functions[$info[2]])) {
			return false;
		    }
		   
		    return $functions[$info[2]]($path);
		}
		
	}
	
	function sbu_imagecreatefrombmp($filename) {
		 //Ouverture du fichier en mode binaire
		   if (! $f1 = fopen($filename,"rb")) {
			return false;
		   }
		
		 //1 : Chargement des entetes FICHIER
		   $file = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
		 
		   if ($file['file_type'] != 19778) {
			return false;
		   }
		
		 //2 : Chargement des entetes BMP
		   $bmp = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
				 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
				 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
		   $bmp['colors'] = pow(2,$bmp['bits_per_pixel']);
		   
		   if ($bmp['size_bitmap'] == 0) {
			$bmp['size_bitmap'] = $file['file_size'] - $file['bitmap_offset'];
		   }
		   
		   $bmp['bytes_per_pixel'] = $bmp['bits_per_pixel']/8;
		   $bmp['bytes_per_pixel2'] = ceil($bmp['bytes_per_pixel']);
		   $bmp['decal'] = ($bmp['width']*$bmp['bytes_per_pixel']/4);
		   $bmp['decal'] -= floor($bmp['width']*$bmp['bytes_per_pixel']/4);
		   $bmp['decal'] = 4-(4*$bmp['decal']);
		   
		   if ($bmp['decal'] == 4) {
			$bmp['decal'] = 0;
		   }
		
		 //3 : Chargement des couleurs de la palette
		   $palette = array();
		   if ($bmp['colors'] < 16777216) {
			$palette = unpack('V'.$bmp['colors'], fread($f1,$bmp['colors']*4));
		   }
		
		 //4 : Creation de l'image
		   $img = fread($f1,$bmp['size_bitmap']);
		   $vide = chr(0);
		
		   $res = imagecreatetruecolor($bmp['width'], $bmp['height']);
		   $p = 0;
		   $y = $bmp['height']-1;
		   
		   while ($y >= 0) {
		    $x=0;
		    
		    while ($x < $bmp['width']) {
			
		     if ($bmp['bits_per_pixel'] == 24) {
			$color = unpack("V",substr($img,$p,3).$vide);
			
		     } elseif ($bmp['bits_per_pixel'] == 16) { 
			$color = unpack("n",substr($img,$p,2));
			$color[1] = $palette[$color[1]+1];
			
		     } elseif ($bmp['bits_per_pixel'] == 8) { 
			$color = unpack("n",$vide.substr($img,$p,1));
			$color[1] = $palette[$color[1]+1];
			
		     } elseif ($bmp['bits_per_pixel'] == 4) {
			$color = unpack("n",$vide.substr($img,floor($p),1));
			if (($p*2)%2 == 0) {
				$color[1] = ($color[1] >> 4);
			} else {
				$color[1] = ($color[1] & 0x0F);
			}
			
			$color[1] = $palette[$color[1]+1];
			
		     } elseif ($bmp['bits_per_pixel'] == 1) {
			
			$color = unpack("n",$vide.substr($img,floor($p),1));
			if (($p*8)%8 == 0) $color[1] = $color[1] >>7;
			elseif (($p*8)%8 == 1) $color[1] = ($color[1] & 0x40)>>6;
			elseif (($p*8)%8 == 2) $color[1] = ($color[1] & 0x20)>>5;
			elseif (($p*8)%8 == 3) $color[1] = ($color[1] & 0x10)>>4;
			elseif (($p*8)%8 == 4) $color[1] = ($color[1] & 0x8)>>3;
			elseif (($p*8)%8 == 5) $color[1] = ($color[1] & 0x4)>>2;
			elseif (($p*8)%8 == 6) $color[1] = ($color[1] & 0x2)>>1;
			elseif (($p*8)%8 == 7) $color[1] = ($color[1] & 0x1);
			$color[1] = $palette[$color[1]+1];
		     } else {
			return false;
		     }
		     
		     imagesetpixel($res, $x, $y, $color[1]);
		     $x++;
		     $p += $bmp['bytes_per_pixel'];
		     
		    }
		    
		    $y--;
		    $p += $bmp['decal'];
		   }
		
		 //Fermeture du fichier
			fclose($f1);
		
			return $res;
		}
}

class sbu_post_image_widget extends WP_Widget {
    function sbu_post_image_widget() {
        parent::WP_Widget(false, 'SB Uploader Post/Page Image');	
    }

    function widget($args, $instance) {
	global $sbu;
	
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $backup_text = apply_filters('widget_text', $instance['backup_text']);
	$image = get_post_meta(get_the_ID(), $instance['custom_field'], true);

	if ($image || $backup_text) {
		echo $before_widget;
		
		if ($title) {
		    echo $before_title . $title . $after_title;
		}		    
	
		if ($image) {
			echo '<img src="' . $image . '" alt="Post image" class="sbu_image sbu_' . $instance['custom_field'] . '_image" title="Post image" />';
		} else {
			echo $backup_text;
		}
		//image here
		
		echo $after_widget;
	}
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form($instance) {
	global $sbu;
	
        $title = esc_attr($instance['title']);
        $custom_field = esc_attr($instance['custom_field']);
	$backup_text = trim(esc_attr($instance['backup_text']));
	
	$custom_field_options = '';
	$settings = $sbu->get_settings();
	if ($uploaders = $settings->post_uploaders) {
		foreach ($uploaders as $i=>$uploader) {
			$custom_field_options .= '<option value="' . $uploader['name'] . '" ' . selected($uploader['name'], $custom_field, false) . '>' . $uploader['label'] . '</option>';
		}
	}
	
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('custom_field'); ?>"><?php _e('Image to show:'); ?> <select id="<?php echo $this->get_field_id('custom_field'); ?>" name="<?php echo $this->get_field_name('custom_field'); ?>" ><?php echo $custom_field_options; ?></select></label></p>
	    <p><label for="<?php echo $this->get_field_id('backup_text'); ?>"><?php _e('Backup Text:'); ?> <textarea class="widefat" id="<?php echo $this->get_field_id('backup_text'); ?>" name="<?php echo $this->get_field_name('backup_text'); ?>"><?php echo $backup_text; ?></textarea></label></p>
	    <p><em>Backup text used if there is no image for the post/page being looked at. Leave blank to hide widget if no image.</em></p>
        <?php
    }
}

class sbu_taxonomy_image_widget extends WP_Widget {
    function sbu_taxonomy_image_widget() {
        parent::WP_Widget(false, 'SB Uploader Taxonomy Image');	
    }

    function widget($args, $instance) {
	global $sbu;
	
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $backup_text = apply_filters('widget_text', $instance['backup_text']);
	$image = false;
	
	if ($terms = get_the_category()) {
		foreach ($terms as $category) {
			if ($image = $sbu->get_taxonomy_data($category->cat_ID, $instance['custom_field'])) {
				break;
			}
		}
	}

	if ($image || $backup_text) {
		echo $before_widget;
		
		if ($title) {
		    echo $before_title . $title . $after_title;
		}		    
	
		if ($image) {
			echo '<img src="' . $image . '" alt="Post image" class="sbu_image sbu_' . $instance['custom_field'] . '_image" title="Post image" />';
		} else {
			echo $backup_text;
		}
		//image here
		
		echo $after_widget;
	}
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form($instance) {
	global $sbu;
	
        $title = esc_attr($instance['title']);
        $custom_field = esc_attr($instance['custom_field']);
	$backup_text = trim(esc_attr($instance['backup_text']));
	
	$custom_field_options = '';
	$settings = $sbu->get_settings();
	if ($uploaders = $settings->taxonomy_uploaders) {
		foreach ($uploaders as $i=>$uploader) {
			$custom_field_options .= '<option value="' . $uploader['name'] . '" ' . selected($uploader['name'], $custom_field, false) . '>' . $uploader['label'] . '</option>';
		}
	}
	
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('custom_field'); ?>"><?php _e('Image to show:'); ?> <select id="<?php echo $this->get_field_id('custom_field'); ?>" name="<?php echo $this->get_field_name('custom_field'); ?>" ><?php echo $custom_field_options; ?></select></label></p>
	    <p><label for="<?php echo $this->get_field_id('backup_text'); ?>"><?php _e('Backup Text:'); ?> <textarea class="widefat" id="<?php echo $this->get_field_id('backup_text'); ?>" name="<?php echo $this->get_field_name('backup_text'); ?>"><?php echo $backup_text; ?></textarea></label></p>
	    <p><em>Backup text used if there is no taxonomy image for the post/page being looked at. Leave blank to hide widget if no image.</em></p>
        <?php
    }
}

class sbu_image_widget extends WP_Widget {
    function sbu_image_widget() {
        parent::WP_Widget(false, 'SBU Image');
    }
    
    function widget($args, $instance) {
	global $sbu;
	
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $image = $instance['image'];
        $link = $instance['link'];
        $alt = $instance['alt'];
        $class = $instance['class'];
	
	echo $before_widget;
	
	if ($title) {
	    echo $before_title . $title . $after_title;
	}		    

	if ($image) {
		$image = '<img src="' . $image . '" alt="' . $alt . '" class="' . $class . '" />';
	}
	
	if ($link) {
		$image = '<a href="' . $link . '">' . $image . '</a>';
	}
	
	echo $image;
	
	echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form($instance) {
	global $sbu;
	
        $title = esc_attr($instance['title']);
	$image = trim(esc_attr($instance['image']));
	$link = trim(esc_attr($instance['link']));
	$alt = trim(esc_attr($instance['alt']));
	$class = trim(esc_attr($instance['class']));
	
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link URL (optional):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
	    
	<?php
	
	if ($image) {
	    echo '<p><img src="' . $image . '" style="padding: 10px; border: 1px solid silver; width: 200px;" /></p>';
	}
	
	?>
            <p><label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alt (optional):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo $alt; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('Class (optional):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>" /></label></p>
        <?php
    }
}

?>