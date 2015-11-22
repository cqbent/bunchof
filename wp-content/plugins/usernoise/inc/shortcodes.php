<?php
class UN_Shortcodes{
	function __construct(){
		add_shortcode('usernoise', array($this, '_usernoise_form'));
	}
	
	function _usernoise_form($attrs){
		do_action('un_feedback_form', '?un_action=feedback_submit');
	}
}

new UN_Shortcodes;
?>