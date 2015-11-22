<?php
class UN_Form{
	function __construct(){
		add_action('un_feedback_form', array($this, 'feedback_form'));
	}
	
	function feedback_form($action){
		global $un_h; ?>
		<form action="<?php echo esc_attr(un_ajax_url('feedback_form_submit')) ?>" method="post" class="un-feedback-form">
			<?php if (un_get_option(UN_FEEDBACK_FORM_SHOW_TYPE)): ?>
				<div class="un-types-wrapper">
					<?php $un_h->link_to(__('Idea', 'usernoise'), '#', array('class' => 'un-type-idea selected'))?>
					<?php $un_h->link_to(__('Problem', 'usernoise'), '#', array('class' => 'un-type-problem'))?>
					<?php $un_h->link_to(__('Question', 'usernoise'), '#', array('class' => 'un-type-question'))?>
					<?php $un_h->link_to(__('Praise', 'usernoise'), '#', array('class' => 'un-type-praise'))?>
					<?php $un_h->hidden_field('type', 'idea')?>
				</div>
			<?php endif ?>
			<?php $un_h->textarea('description', __('Your feedback', 'usernoise'), array('id' => 'un-description', 'class' => 'text text-empty'))?>
			<?php if (un_get_option(UN_FEEDBACK_FORM_SHOW_SUMMARY)): ?>
				<?php $un_h->text_field('title', __('Short summary', 'usernoise'), array('id' => 'un-title', 'class' => 'text text-empty'))?>
			<?php endif ?>
			<?php if (un_get_option(UN_FEEDBACK_FORM_SHOW_EMAIL)): ?>
				<?php $un_h->text_field('email', __('Your email (will not be published)', 'usernoise'), array('id' => 'un-email', 'class' => 'text text-empty'))?>
			<?php endif ?>
			<?php do_action('un_feedback_form_body') ?>
			<input type="submit" class="un-submit" value="<?php echo esc_attr(un_submit_feedback_button_text()) ?>" id="un-feedback-submit">
			&nbsp;<img src="<?php echo usernoise_url('/images/loader.gif') ?>" id="un-feedback-loader" class="loader" style="display: none;">
			<div class="un-feedback-errors-wrapper" style="display: none;">
				<div class="un-errors"></div>
			</div>
		</form>
		<?php
	}
}

new UN_Form;
?>