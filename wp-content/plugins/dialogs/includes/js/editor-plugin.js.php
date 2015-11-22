<?php

require_once( '../../../../../wp-load.php' );

load_plugin_textdomain( 'tk-dialogs', false, '../' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

?>(
	function(){
	
		var icon_url = '../wp-content/plugins/dialogs/includes/images/interactions-tinymce.png';
	
		tinymce.create(
			"tinymce.plugins.DialogShortcodes",
			{
				
				createControl:function(d,e)
				{
				
					if( d=="dialog_shortcodes_button"){
						d=e.createSplitButton( "dialog_shortcodes_button",{
							title:"<?php _e( 'Insert Dialog Shortcode', 'tk-dialogs'); ?>",
							image:icon_url,
							// icons:false,
							onclick: function() {
								// tinyMCE.activeEditor.windowManager.alert( 'Button was clicked!' );					
							}
						});
						
						var a=this;
						
						d.onRenderMenu.add( function( c, b ){
							b.add({
								title	: 	'<?php _e( 'Add Dialog', 'tk-dialogs'); ?>',
								onclick: function() {
									tinyMCE.activeEditor.windowManager.open({
										url: "../wp-content/plugins/dialogs/components/admin/tinymce-dialog.php",
										width: 300,
										height: 220,
										inline: 1,
										popup_css: false
									}); 
								}					
							});
							
							b.add({
								title	: 	'<?php _e( 'Add List of Dialogs', 'tk-dialogs'); ?>',
								onclick: function() {
									tinyMCE.activeEditor.windowManager.open({
										url: "../wp-content/plugins/dialogs/components/admin/tinymce-list-dialogs.php",
										width: 300,
										height: 240,
										inline: 1,
										popup_css: false
									}); 
								}					
							});
							
							// a.addImmediate(b,"Dialog", '[dialog id="" width="" height=""]Click me[/dialog]');
						});
						return d
					
					} // End IF Statement
					
					return null
				},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				
			}
		);
		
		tinymce.PluginManager.add( "DialogShortcodes", tinymce.plugins.DialogShortcodes);
	}
)();