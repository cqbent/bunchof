
<div class="tk-metabox">
    <?php wp_nonce_field('_tk_dialogs_splashscreen_metabox', '_tknonce'); ?>

    <dl class="fields">
        <dt><?php _e('Show Dialog?', $this->textdomain); ?></dt>
        <dd>
            <input type="checkbox" name="_tk_dialogs_splashscreen_show" id="_tk_dialogs_splashscreen_show"<?php echo ($splashscreen_checked ? ' checked="checked"' : ''); ?> />
            <label for="_tk_dialogs_splashscreen_show"><?php _e('Yes', $this->textdomain); ?></label>
        </dd>
        <dt><?php _e('Appearance Rules', $this->textdomain); ?></dt>
        <dd>
        	<div>
	            <input type="radio" name="_tk_dialogs_splashscreen_schedule" id="_tk_dialogs_splashscreen_schedule_once" value="once"<?php echo ($showsplash_schedule == 'once' ? ' checked="checked"' : ''); ?> />
	            <label for="_tk_dialogs_splashscreen_schedule_once"><?php _e('On first visit', $this->textdomain); ?></label>
            </div>
            
            <div>
	            <input type="radio" name="_tk_dialogs_splashscreen_schedule" id="_tk_dialogs_splashscreen_schedule_always" value="always"<?php echo ($showsplash_schedule == 'always' ? ' checked="checked"' : ''); ?> />
	            <label for="_tk_dialogs_splashscreen_schedule_always"><?php _e('On every visit', $this->textdomain); ?></label>
            </div>
        </dd>
        <dt><?php _e('Dialog to display', $this->textdomain); ?></dt>
        <dd>
            <select name="_tk_dialogs_splashscreen_dialog" id="_tk_dialogs_splashscreen_dialog">
                <option value="0"><?php _e('Choose a dialog', $this->textdomain); ?></option>
                <?php foreach($dialogs as $dialog): ?>
                <option value="<?php echo $dialog->ID; ?>"<?php echo ($selected_dialog == $dialog->ID ? ' selected="selected"' : ''); ?>><?php echo $dialog->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </dd>
        
	<dt><?php _e('Window size', $this->textdomain); ?></dt>
	<dd>
	    <label for="_tk_dialogs_splashscreen_width"><?php _e('Width:', $this->textdomain) ?></label>
	    <input type="text" name="_tk_dialogs_splashscreen_width" id="_tk_dialogs_splashscreen_width" value="<?php echo $showsplash_width; ?>" /> <?php _e('px', $this->textdomain); ?>
	    <br />
	    <label for="_tk_dialogs_splashscreen_height"><?php _e('Height:', $this->textdomain) ?></label>
	    <input type="text" name="_tk_dialogs_splashscreen_height" id="_tk_dialogs_splashscreen_height" value="<?php echo $showsplash_height; ?>" /> <?php _e('px', $this->textdomain); ?>
	</dd>
    </dl>
</div>