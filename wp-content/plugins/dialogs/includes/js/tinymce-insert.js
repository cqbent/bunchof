var wpDialogDialog = {
	init : function() {
		var f = document.forms[0];
	},
	
	insert : function() {
	
		dialog_id = document.forms[0].dialog_id.value;
		width = document.forms[0].width.value;
		height = document.forms[0].height.value;
		linktext = document.forms[0].linktext.value;
		
		str_width = '';
		
		if( width != '' && width != undefined){
			str_width = ' width="' + width +'"';
		}
		
		str_height = '';
		
		if( height != '' && height != undefined ){
			str_height = ' height="' + height +'"';
		}
		
		insertCont = ' [dialog id="' + dialog_id + '"' + str_width + str_height + ']' + linktext + '[/dialog]';
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, insertCont);
		
		tinyMCEPopup.close();
	}

} // wptabtitleDialog
tinyMCEPopup.onInit.add(wpDialogDialog.init, wpDialogDialog);

var wpListDialogsDialog = {
	init : function() {
		var f = document.forms[0];
	},
	
	insert : function() {
	
		cat_name = document.forms[0].cat_name.value;
		orderby = document.forms[0].orderby.value;
		order = document.forms[0].order.value;
		per_page = document.forms[0].per_page.value;
		
		str_cat_name = '';
		
		if( cat_name != '' && cat_name != undefined ){
			str_cat_name = ' cat_name="' + cat_name +'"';
		}
		
		str_orderby = '';
		
		if( orderby != '' && orderby != undefined ){
			str_orderby = ' orderby="' + orderby +'"';
		}
		
		str_order = '';
		
		if( order != '' && order != undefined ){
			str_order = ' order="' + order +'"';
		}
		
		str_per_page = '';
		
		if( per_page != '' && per_page != undefined ){
			str_per_page = ' per_page="' + per_page +'"';
		}
		
		
		insertCont = ' [list_dialogs' + str_cat_name + str_orderby + str_order + str_per_page + ']';
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, insertCont);
		
		tinyMCEPopup.close();
	}

} // wptabtitleDialog
tinyMCEPopup.onInit.add( wpListDialogsDialog.init, wpListDialogsDialog);