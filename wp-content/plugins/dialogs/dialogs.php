<?php

if ( !class_exists( 'TK_Dialogs' ) ) {

    /**
     * TK_Dialogs
     *
     * A Wordpress Plugin Class, that creates the "tk-d" Custom Post Type. You can use it to display text, forms, images, videos or any other content in a textbox/lihgtbox to interact with the users/visitors
     *
     * @author themekraft.com, Peter Liebetrau <ixiter@ixiter.com>
     * @package tk-dialogs
     * @version 1.0.3
     * @since 0.0.1
     * @license GPL 2
     */
    class TK_Dialogs extends Ixiter_Plugin {

	/**
	 *  Name/Path of our Plugin, required by Ixiter_Plugin
	 *
	 * @var string
	 */
	public $name = 'dialogs';
	/**
	 * The version of the plugin
	 *
	 * @var string
	 */
	private $version = "1.0.3";
	/**
	 * Our class instance. We want a singleton object, so that nobody else can create a new object from our class
	 *
	 * @static
	 * @var object tk-dialogs
	 */
	static private $_instance = null;

	public $textdomain = 'tk-dialogs';
	public $options = array(
	    'default_sc_content' => 'Click Me!', // Default content replacement, wenn no content is given with the shortcode
	);

	/**
	 * Our constructor. Since we want a singleton object, the constructor is private, so only we can create an object of our own and by ourself!
	 *
	 */
	private function __construct() {
	    parent::init();

	    add_filter( 'init', array( $this, 'init_post_type' ) );
	    add_filter( 'init', array( $this, 'init_dialogscategory_taxonomy' ), 0 );
		
	    add_filter( 'init', array( $this, 'init_css' ) );
		add_filter( 'init', array( $this, 'init_js' ) );

	    add_filter( 'template_include', array( $this, 'set_template' ) );

	    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	    add_action( 'save_post', array( $this, 'save_post' ) );

	    add_shortcode( 'dialog', array( $this, 'tk_dialog_shortcode_handler' ), 11 );
		add_shortcode( 'list_dialogs', array( $this, 'list_dialogs' ), 11 );

	    add_action( 'get_header', array( $this, 'show_splashscreen' ), 1 );
		add_action( 'admin_head', array( $this, 'set_admin_icons' ) );
		
		if(is_admin()){
    		add_filter( 'mce_external_plugins', array( $this, 'tinymce_plugin' ) );
    		add_filter( 'mce_buttons', array( $this, 'register_tinymce_button' ) );
    		add_filter( 'tiny_mce_version', 'refresh_mce' );
		}
	}

	/**
	 * We need to disable object cloning for our singleton object!
	 *
	 */
	private function __clone() {

	}

	/**
	 * Checks if an instance was created already.<br>
	 * If not, an instance will be created<br>
	 * Returns the instance
	 *
	 * @return tk-dialogs
	 */
	static public function getInstance() {
	    if ( null == self::$_instance ) {
		self::$_instance = new self;
	    }
	    return self::$_instance;
	}

	/*	 * ***************************************************************** */

	public function init_post_type() {
	    $labels = array(
		'name' => __( 'Dialogs', 'tk-dialogs' ),
		'add_new' => __( 'Add Dialog', 'tk-dialogs' ),
		'new_item' => __( 'New Dialog', 'tk-dialogs' ),
		'all_items' => __( 'All Dialogs', 'tk-dialogs' ),
		'view_item' => __( 'Show Dialog', 'tk-dialogs' ),
		'edit_item' => __( 'Edit Dialog', 'tk-dialogs' ),
		'not_found' => __( 'No Dialog found', 'tk-dialogs' ),
		'menu_name' => __( 'Dialogs', 'tk-dialogs' ),
		'add_new_item' => __( 'Add a new Dialog', 'tk-dialogs' ),
		'search_items' => __( 'Search Dialogs', 'tk-dialogs' ),
		'singular_name' => __( 'Dialog', 'tk-dialogs' ),
		'parent_item_colon' => __( 'Parent Dialog', 'tk-dialogs' ),
		'not_found_in_trash' => __( 'No Dialog in trash', 'tk-dialogs' ),
	    );

	    $support = array(
		'title',
		'thumbnail',
		'editor',
		'author',
		'excerpt',
		'custom_fields',
		'revisions',
	    );

	    $args = array(
		'public' => TRUE,
		'labels' => $labels,
		'show_ui' => TRUE,
		'rewrite' => array(
		    'slug' => 'dialogs',
		),
		'supports' => $support,
		'taxonomies' => array( 'tk-dialogscategory', 'post_tag' ),
		'query_var' => TRUE,
		'has_archive' => TRUE,
		'hierarchical' => false,
		'menu_position' => NULL,
		'capability_type' => 'post',
		'publicly_queryable' => TRUE,
		'show_in_nav_menus' => false
	    );

	    register_post_type( 'tk-dialogs', $args );
	}

	function init_dialogscategory_taxonomy() {

	    $labels = array(
		'name' => __( 'Categories', 'tk-dialogs' ),
		'all_items' => __( 'All Categories', 'tk-dialogs' ),
		'edit_item' => __( 'Edit Category', 'tk-dialogs' ),
		'parent_item' => null,
		'update_item' => __( 'Update Category', 'tk-dialogs' ),
		'search_items' => __( 'Search Categories', 'tk-dialogs' ),
		'add_new_item' => __( 'Add New Category', 'tk-dialogs' ),
		'singular_name' => __( 'Category', 'tk-dialogs' ),
		'new_item_name' => __( 'New Category Name', 'tk-dialogs' ),
		'popular_items' => __( 'Popular Categories', 'tk-dialogs' ),
		'parent_item_colon' => null,
		'add_or_remove_items' => __( 'Add or remove categories', 'tk-dialogs' ),
		'choose_from_most_used' => __( 'Choose from the most used categories', 'tk-dialogs' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'tk-dialogs' ),
	    );

	    register_taxonomy( 'tk-dialogscategory', 'tk-dialogs', array(
		'label' => __( 'Dialogs Category', 'tk-dialogs' ),
		'labels' => $labels,
		'show_ui' => true,
		'rewrite' => array( 'slug' => 'dialogs-category' ),
		'query_var' => true,
		'hierarchical' => true,
		    ) );
	}

	public function init_js() {
	    wp_enqueue_script( 'jquery' );
		add_thickbox();
	}

	public function init_css(){
		wp_register_style( 'dialogs.css', $this->url . '/includes/css/style.css' );
		wp_enqueue_style( 'dialogs.css' );
	    wp_enqueue_style( 'thickbox' );
	}
	
	public function add_meta_boxes() {
	    $post_types = get_post_types(
			    array(
				'show_ui' => true,
			    ),
			    'names'
	    );

	    foreach ( $post_types as $post_type ) {
		if ( $post_type != 'tk-dialogs' ) {
		    add_meta_box(
			    'tk_dialogs_splashscreen',
			    __( 'Open Dialog on site visit', 'tk-dialogs' ),
			    array( $this, 'render_meta_box' ),
			    $post_type
		    );
		}
	    }
	}

	public function render_meta_box() {
	    $dialogs = get_posts(
			    array(
				'numberposts' => 100,
				'orderby' => 'title',
				'order' => 'ASC',
				'post_type' => 'tk-dialogs',
				'post_status' => 'publish',
			    )
	    );
	    $post_custom = get_post_custom();
	    $splashscreen_checked = isset( $post_custom[ 'tk_dialogs_splashscreen_show' ] );
	    $selected_dialog = isset( $post_custom[ 'tk_dialogs_splashscreen_dialog' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_dialog' ][ 0 ] : 0;
	    $showsplash_schedule = isset( $post_custom[ 'tk_dialogs_splashscreen_schedule' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_schedule' ][ 0 ] : 'once';
	    $showsplash_width = isset( $post_custom[ 'tk_dialogs_splashscreen_width' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_width' ][ 0 ] : 600;
	    $showsplash_height = isset( $post_custom[ 'tk_dialogs_splashscreen_height' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_height' ][ 0 ] : 400;

	    include $this->path . '/components/admin/templates/metabox-splashscreen.php';
	}

	public function save_post( $post_id ) {
	    if ( wp_verify_nonce( $_POST[ '_tknonce' ], '_tk_dialogs_splashscreen_metabox' ) ) {
		$schedules = array( 'once', 'always' );
		$splashscreen_checked = isset( $_POST[ '_tk_dialogs_splashscreen_show' ] );
		$selected_dialog = isset( $_POST[ '_tk_dialogs_splashscreen_dialog' ] ) ? ( int ) $_POST[ '_tk_dialogs_splashscreen_dialog' ] : 0;
		$showsplash_schedule = isset( $_POST[ '_tk_dialogs_splashscreen_schedule' ] ) && in_array( $_POST[ '_tk_dialogs_splashscreen_schedule' ], $schedules ) ? $_POST[ '_tk_dialogs_splashscreen_schedule' ] : 'once';
		$showsplash_width = isset( $_POST[ '_tk_dialogs_splashscreen_width' ] ) ? ( int ) $_POST[ '_tk_dialogs_splashscreen_width' ] : 600;
		$showsplash_height = isset( $_POST[ '_tk_dialogs_splashscreen_height' ] ) ? ( int ) $_POST[ '_tk_dialogs_splashscreen_height' ] : 400;

		if ( $splashscreen_checked ) {
		    update_post_meta( $post_id, 'tk_dialogs_splashscreen_show', $splashscreen_checked );
		    update_post_meta( $post_id, 'tk_dialogs_splashscreen_dialog', $selected_dialog );
		    update_post_meta( $post_id, 'tk_dialogs_splashscreen_schedule', $showsplash_schedule );
		    update_post_meta( $post_id, 'tk_dialogs_splashscreen_width', $showsplash_width );
		    update_post_meta( $post_id, 'tk_dialogs_splashscreen_height', $showsplash_height );
		} else {
		    // we have to delete the splashscreen meta data, if it was switched off!
		    $post_custom = get_post_custom();
		    if ( isset( $post_custom[ 'tk_dialogs_splashscreen_show' ] ) ) {
			delete_post_meta( $post_id, 'tk_dialogs_splashscreen_show' );
		    }
		    if ( isset( $post_custom[ 'tk_dialogs_splashscreen_dialog' ] ) ) {
			delete_post_meta( $post_id, 'tk_dialogs_splashscreen_dialog' );
		    }
		    if ( isset( $post_custom[ 'tk_dialogs_splashscreen_schedule' ] ) ) {
			delete_post_meta( $post_id, 'tk_dialogs_splashscreen_schedule' );
		    }
		    if ( isset( $post_custom[ 'tk_dialogs_splashscreen_width' ] ) ) {
			delete_post_meta( $post_id, 'tk_dialogs_splashscreen_width' );
		    }
		    if ( isset( $post_custom[ 'tk_dialogs_splashscreen_height' ] ) ) {
			delete_post_meta( $post_id, 'tk_dialogs_splashscreen_height' );
		    }
		}
	    }
	}

	public function tk_dialog_shortcode_handler( $atts, $content = '' ) {
	    $replacement = '';
	    $attrs = shortcode_atts(
			    array(
				'id' => '',
				'name' => '',
				'width' => 600,
				'height' => 400,
			    ),
			    $atts
	    );

	    $content = $content !== '' ? $content : $this->options[ 'default_sc_content' ];
	    $splashlink = $this->get_splashlink( $attrs[ 'id' ] );
	    $splashlink['url'] .= '&width='.$attrs['width'].'&height='.$attrs['height'];
	    $replacement = '<a class="thickbox" href="' . $splashlink['url'] . '" title="'.$splashlink['caption'].'" onclick="return false">' . $content . '</a>';

	    return $replacement;
	}
	
	public function list_dialogs( $atts ) {
		
		extract(shortcode_atts(array(
			'per_page' 	=> '10',
			'columns' 	=> '4',
			'orderby' => 'date',
			'order' => 'desc',
			'cat_name' => ''
		), $atts));
		
		$args = array(
			'post_type'	=> 'tk-dialogs',
			'post_status' => 'publish',
			'posts_per_page' => $per_page,
			'orderby' => $orderby,
			'order' => $order,
			'tk-dialogscategory' => $cat_name
		);
		
		$templates = array(
		    'loop-dialog.php'
		);
		
		query_posts( $args );	
			
		ob_start();
		$this->locate_template( $templates, true );
		wp_reset_query();
		
		return ob_get_clean();
	}

	public function show_splashscreen() {
	    if ( is_single ( ) || is_page() ) {
	    	
			$post_custom = get_post_custom();
			//echo '<pre>';var_export($post_custom);echo '</pre>'; exit;
			$splashscreen_checked = isset( $post_custom[ 'tk_dialogs_splashscreen_show' ] );
			$selected_dialog = isset( $post_custom[ 'tk_dialogs_splashscreen_dialog' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_dialog' ][ 0 ] : 0;
			$showsplash_schedule = isset( $post_custom[ 'tk_dialogs_splashscreen_schedule' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_schedule' ][ 0 ] : 'once';
			$showsplash_width = isset( $post_custom[ 'tk_dialogs_splashscreen_width' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_width' ][ 0 ] : 600;
			$showsplash_height = isset( $post_custom[ 'tk_dialogs_splashscreen_height' ] ) ? $post_custom[ 'tk_dialogs_splashscreen_height' ][ 0 ] : 400;
			
			if ( $splashscreen_checked ) {
			    if ( $showsplash_schedule == 'always' || !$this->is_shown( $selected_dialog ) ) {
					if ( $splashlink = $this->get_splashlink( $selected_dialog ) ) {
					    $splashlink['url'] = str_replace( '&', '%2526', $splashlink['url'].'&width='.$showsplash_width.'&height='.$showsplash_height );
					    wp_enqueue_script( 'splashscreen', $this->url . '/includes/js/splashscreen.js.php?url=' . $splashlink['url'].'&caption='.$splashlink['caption'], array( ), null, true );
					}
			    }
			}
	    }
	}

	public function get_splashlink( $dialogID ) {
	    $dialog = get_post( $dialogID );
	    if ( $dialog->post_type == 'tk-dialogs' ) {
			$url = get_permalink( $dialogID );
			$url .= strstr( $url, '?' ) !== false ? '&TB_iframe=1' : '?TB_iframe=1';
			$caption = urlencode($dialog->post_title);
	    } else {
			$url = 'not found';
			$caption = 'not found';
	    }

	    return array('url' => $url, 'caption' => $caption);
	}

	private function is_shown( $id ) {
	    $tk_splashscreens_shown = array( );
	    $tk_splashscreens_shown = isset( $_COOKIE[ 'tk_splashscreens_shown' ] ) ? unserialize( stripslashes( $_COOKIE[ 'tk_splashscreens_shown' ] ) ) : $tk_splashscreens_shown;
		
	    if ( in_array( $id, $tk_splashscreens_shown ) ) {
			return true;
	    } else {
			$tk_splashscreens_shown[] = ( int ) $id;
			
			if( !setcookie( 'tk_splashscreens_shown', serialize( $tk_splashscreens_shown ) ) )
				return true;
			
			return false;
	    }
	}

	/*
	 * Template-Tag Functions
	 */
	public function set_template( $template ) {
	    global $wp_query;
	    $object = $wp_query->get_queried_object();

	    if ( get_post_type ( ) == 'tk-dialogs' ) {
	    	
			if( is_archive() ){
				$templates = array(
				    'dialogs.php'
				);
			}else{
				$templates = array(
				    'dialogs/dialog-' . $object->ID . '.php',
				    'dialogs/dialog.php'
				);
			}
	
			$template = $this->locate_template( $templates );
	    }
	    return $template;
	}

	public function locate_template( $template_names, $load = false, $require_once = true ) {
	    $located = '';

	    $located = locate_template( $template_names, $load, $require_once );

	    if ( $located == '' ) {
		foreach ( ( array ) $template_names as $template_name ) {
		    if ( !$template_name )
			continue;
		    if ( file_exists( $this->path . '/templates/' . $template_name ) ) {
			$located = $this->path . '/templates/' . $template_name;
			break;
		    }
		}
	    }

	    if ( $load && '' != $located )
		    load_template( $located, $require_once );

	    return $located;
	}
	public function get_header( $name = null ) {
	    do_action( 'tk-dialogs_get_header', $name );

	    $templates = array(
			"dialogs/header-{$name}.php",
			"dialogs/header.php",
	    );

	    $template = $this->locate_template( $templates, true, true );
		
	    if($template == ''){
			get_header();
	    }
	}

	public function get_footer( $name = null ) {
	    do_action( 'tk-dialogs_get_footer', $name );

	    $templates = array(
			"dialogs/footer-{$name}.php",
			"dialogs/footer.php",
	    );
	    
	    $template = $this->locate_template( $templates, true, true );
		
	    if($template == ''){
		get_footer();
	    }
	}
	
	public function tinymce_plugin( $plugin_array ){
		$plugin_array['DialogShortcodes'] =  $this->url . '/includes/js/editor-plugin.js.php';
		return $plugin_array;
	}

	public function register_tinymce_button( $buttons ) {
		array_push( $buttons, "|", "dialog_shortcodes_button" );
		return $buttons;
	}
	
	public function refresh_mce($ver) {
		$ver += 3;
		return $ver;
	}
	
	public function set_admin_icons() {
	    $this->add_adminstyle('admin-icons.css');
	}

	public function info( $item ) {
	    switch ( $item ) {
		case 'stylesheet_url':
		 	$css_files = array(
				"dialogs/style.css"
		    );
			$path =  locate_template( $css_files );
			$sub_path = substr( $path, strlen( ABSPATH ), strlen( $path ) );
			$info = get_bloginfo( 'url' ) . '/' . $sub_path;
		    break;
		default:
		    $info = sprintf(__( 'There is no item "%s"!', 'tk-dialogs' ), $item);
		    break;
	    }

	    return $info;
	}

    }

}