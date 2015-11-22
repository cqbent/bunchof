<?php
/*
Plugin Name:    PopPop!
Description:    Easily display your desired widgets inside popups powered by jQuery Reveal.
Author:         Hassan Derakhshandeh
Version:        0.3.1
Author URI:     http://tween.ir/


		* 	Copyright (C) 2011  Hassan Derakhshandeh
		*	http://tween.ir/
		*	hassan.derakhshandeh@gmail.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class PopPop {

	function __construct() {
		if( is_admin() ) {
			add_action( 'in_widget_form', array( &$this, 'widget_popup_code' ), 10, 3 );
			add_filter( 'widget_update_callback', array( &$this, 'widget_update_callback' ), 10, 3 );
			add_action( 'wp_ajax_poppop_save_cookie', array( &$this, 'save_cookie' ) );
			add_action( 'wp_ajax_nopriv_poppop_save_cookie', array( &$this, 'save_cookie' ) );
		} else {
			add_action( 'template_redirect', array( &$this, 'queue_reveal' ) );
			add_action( 'wp_footer', array( &$this, 'display' ), 100 );
		}
		add_action( 'widgets_init', array( &$this, 'widgets_init' ), 100 );
	}

	function widgets_init() {
		register_sidebar( array(
			'name'			=> 'Popup',
			'id'			=> 'popup',
			'before_widget'	=> '<div id="%1$s" class="reveal-modal %2$s">',
			'after_widget'	=> '<a class="close-reveal-modal">&#215;</a></div>',
			'before_title'	=> '<h1>',
			'after_title'	=> '</h1>'
		) );
	}

	function display() {
		$widgets = wp_get_sidebars_widgets();
		$autofires = array();
		$ajaxcalls = array();
		$options = get_option( 'poppop', array() );

		// render the popup area
		dynamic_sidebar( 'popup' );

		// determine if any widget has to be fired on page load
		if( ! empty( $widgets['popup'] ) ) {
			foreach( $widgets['popup'] as $popup ) {
				if( isset( $options[$popup] ) && 1 == $options[$popup]['autofire'] ) {
					if( ! isset( $options[$popup]['cookie'] ) ) {
						$autofires[] = $popup;
					} elseif( isset( $options[$popup]['cookie'] ) && $options[$popup]['expire'] > 1 && isset( $_COOKIE["poppop-$popup"] ) ) {}
					elseif( isset( $options[$popup]['cookie'] ) && $options[$popup]['expire'] > 1 && ! isset( $_COOKIE["poppop-$popup"] ) ) {
						$autofires[] = $popup;
						$ajaxcalls[] = $popup;
					}
				}
			}
		}

		// So? Any widgets? Anyone?
		if( ! empty( $autofires ) ) {
			echo '<script>jQuery(function($){';
			foreach( $autofires as $popup ) {
				echo "$('#{$popup}').reveal({closeCallback: function(){";
				if( in_array( $popup, $ajaxcalls ) )
					echo"
					$.ajax({
						type: 'POST',
						url: '". admin_url( 'admin-ajax.php' ) ."',
						data: {
							action: 'poppop_save_cookie',
							widget: '$popup'
						},
						success: function(data){}
					})";
				echo "}});"; // end reveal call
			}
			echo '});</script>';
		}
	}

	/**
	 * Queue script and styles required
	 *
	 * jQuery Reveal
	 * @link http://www.zurb.com/playground/reveal-modal-plugin
	 */
	function queue_reveal() {
		if( is_active_sidebar( 'popup' ) ) {
			wp_enqueue_script( 'PopPop', plugins_url( 'js/jquery-reveal.js', __FILE__ ), array( 'jquery' ), '0.3', true );
			wp_enqueue_style( 'PopPop', plugins_url( 'css/reveal.css', __FILE__ ), array(), '0.3' );
		}
	}

	/**
	 * Shows the code required to launch the popup in front end
	 *
	 * @since 0.1
	 * @return void
	 */
	function widget_popup_code( $widget, $return, $instance ) {
		$defaults = array( 'autofire' => 0, 'cookie' => 0, 'expire' => 10 );
		$options = get_option( 'poppop', array() );
		if( ! isset( $options[$widget->id] ) )
			$options[$widget->id] = array();
		$options = array_merge( $defaults, $options[$widget->id] );
		$widgets = wp_get_sidebars_widgets();

		// if the widget is in Popup position show the options
		if( isset( $widgets['popup'] ) && in_array( $widget->id, $widgets['popup'] ) ) { ?>
			<p><label><input type="checkbox" value="1" name="<?php echo $widget->get_field_name( 'autofire' ) ?>" <?php checked( $options['autofire'], 1 ) ?> /> Auto fire on page load?</p>
			<p><label><input type="checkbox" value="1" name="<?php echo $widget->get_field_name( 'cookie' ) ?>" <?php checked( $options['cookie'], 1 ) ?> /> Unless user has seen it in the last <input type="text" name="<?php echo $widget->get_field_name( 'expire' ) ?>" value="<?php echo $options['expire'] ?>" size="3" /> days.</label></p>
			<?php // send widget->id along so we can save it ?>
			<input type="hidden" name="<?php echo $widget->get_field_name( 'widget_id' ) ?>" value="<?php echo $widget->id ?>" />
			<pre dir="ltr"><?php echo _wp_specialchars( "<a href='#' data-reveal-id='{$widget->id}'>Open this popup</a>" ) ?> </pre>
		<?php }
	}

	/**
	 * Saves the auto-fire option for widget
	 *
	 * @since 0.2
	 * @return array $instance
	 */
	function widget_update_callback( $instance, $new_instance, $old_instance ) {
		if( $_POST['sidebar'] == 'popup' ) {
			$id = $new_instance['widget_id'];
			$options = get_option( 'poppop', array() );
			$options[$id] = array(
				'autofire' => $new_instance['autofire'],
				'cookie' => $new_instance['cookie'],
				'expire' => $new_instance['expire']
			);
			update_option( 'poppop', $options );
		}
		return $instance;
	}

	function save_cookie() {
		if( isset( $_POST['widget'] ) ) {
			$id = $_POST['widget'];
			$options = get_option( 'poppop', array() );
			$days = intval( $options[$id]['expire'] );
			setcookie( "poppop-$id", 1, time()+3600*24*$days, COOKIEPATH, COOKIE_DOMAIN, false);
		}
		die();
	}
}
$poppop = new PopPop;