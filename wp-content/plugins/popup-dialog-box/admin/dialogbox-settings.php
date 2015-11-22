<?php
	// Load the options
$xyz_tinymce=get_option('xyz_dbx_tinymce');
if($xyz_tinymce==1)
{
	require( dirname( __FILE__ ) . '/tinymce_filters.php' );
}
	if (isset($_POST['xyz_dbx_html']))
	{
		$xyz_dbx_iframe=$_POST['xyz_dbx_iframe'];
		$xyz_dbx_repeat_interval_timing=$_POST['xyz_dbx_repeat_interval_timing'];
		$xyz_dbx_html=stripslashes($_POST['xyz_dbx_html']);
		$xyz_dbx_title=$_POST['xyz_dbx_title'];
		if($xyz_dbx_title=="")
		{
			
			$xyz_dbx_title="Title";
		}
		$xyz_dbx_title_color=$_POST['xyz_dbx_title_color'];
		$xyz_dbx_display_position=$_POST['xyz_dbx_display_position'];
		$xyz_dbx_top=abs(intval($_POST['xyz_dbx_top']));
	 $xyz_dbx_bottom=abs(intval($_POST['xyz_dbx_bottom']));
		$xyz_dbx_width=abs(intval($_POST['xyz_dbx_width']));
		$xyz_dbx_height=abs(intval($_POST['xyz_dbx_height']));
		  $xyz_dbx_left=abs(intval($_POST['xyz_dbx_left']));
		
		$xyz_dbx_right=abs(intval($_POST['xyz_dbx_right']));
		$xyz_dbx_delay=abs(intval($_POST['xyz_dbx_delay']));
		$xyz_dbx_page_count=abs(intval($_POST['xyz_dbx_page_count']));
		$xyz_dbx_repeat_interval=abs(intval($_POST['xyz_dbx_repeat_interval']));
		$xyz_dbx_mode=$_POST['xyz_dbx_mode'];
		$xyz_dbx_z_index=intval($_POST['xyz_dbx_z_index']);
		
	
		
		 $xyz_dbx_bg_color=$_POST['xyz_dbx_bg_color'];		
		$xyz_dbx_corner_radius=abs(intval($_POST['xyz_dbx_corner_radius']));
		$xyz_dbx_top_dim=$_POST['xyz_dbx_top_dim'];
		$xyz_dbx_left_dim=$_POST['xyz_dbx_left_dim'];
		$xyz_dbx_right_dim=$_POST['xyz_dbx_right_dim'];
		$xyz_dbx_bottom_dim=$_POST['xyz_dbx_bottom_dim'];
		$xyz_dbx_width_dim=$_POST['xyz_dbx_width_dim'];
		$xyz_dbx_height_dim=$_POST['xyz_dbx_height_dim'];
		$xyz_dbx_border_color=$_POST['xyz_dbx_border_color'];
		$xyz_dbx_border_width=$_POST['xyz_dbx_border_width'];
		$xyz_dbx_page_option=$_POST['xyz_dbx_page_option'];
		$xyz_dbx_close_button_option=$_POST['xyz_dbx_close_button_option'];
		$xyz_dbx_positioning=$_POST['xyz_dbx_positioning'];
		$xyz_dbx_position_predefined=$_POST['xyz_dbx_position_predefined'];
		
		
		
$old_page_count=get_option('xyz_dbx_page_count');
$old_repeat_interval=get_option('xyz_dbx_repeat_interval');
if(isset($_POST['xyz_dbx_cookie_resett']))
{
?>	
	<script language="javascript">

 var cookie_date = new Date ( );  // current date & time
 cookie_date.setTime ( cookie_date.getTime() - 1 );

  document.cookie = "_xyz_dbx_pc=; expires=" + cookie_date.toGMTString()+ ";path=/";
  document.cookie = "_xyz_dbx_until=; expires=" + cookie_date.toGMTString()+ ";path=/";


</script>
	
	
<?php 	
}
	
		update_option('xyz_dbx_html',$xyz_dbx_html);
		update_option('xyz_dbx_top',$xyz_dbx_top);
		update_option('xyz_dbx_width',$xyz_dbx_width);
		update_option('xyz_dbx_right',$xyz_dbx_right);
		update_option('xyz_dbx_bottom',$xyz_dbx_bottom);
		update_option('xyz_dbx_height',$xyz_dbx_height);
		update_option('xyz_dbx_left',$xyz_dbx_left);
		update_option('xyz_dbx_delay',$xyz_dbx_delay);
		update_option('xyz_dbx_page_count',$xyz_dbx_page_count);
		update_option('xyz_dbx_repeat_interval',$xyz_dbx_repeat_interval);
		update_option('xyz_dbx_repeat_interval_timing',$xyz_dbx_repeat_interval_timing);
		update_option('xyz_dbx_mode',$xyz_dbx_mode);
		update_option('xyz_dbx_z_index',$xyz_dbx_z_index);
		
		update_option('xyz_dbx_color',$xyz_dbx_color);
		update_option('xyz_dbx_corner_radius',$xyz_dbx_corner_radius);
		update_option('xyz_dbx_top_dim',$xyz_dbx_top_dim);
		update_option('xyz_dbx_height_dim',$xyz_dbx_height_dim);	
		update_option('xyz_dbx_left_dim',$xyz_dbx_left_dim);
		update_option('xyz_dbx_bottom_dim',$xyz_dbx_bottom_dim);
		update_option('xyz_dbx_right_dim',$xyz_dbx_right_dim);

		update_option('xyz_dbx_width_dim',$xyz_dbx_width_dim);
		update_option('xyz_dbx_border_color',$xyz_dbx_border_color);
		update_option('xyz_dbx_border_width',$xyz_dbx_border_width);
		update_option('xyz_dbx_bg_color',$xyz_dbx_bg_color);
		update_option('xyz_dbx_page_option',$xyz_dbx_page_option);
		update_option('xyz_dbx_close_button_option',$xyz_dbx_close_button_option);
		update_option('xyz_dbx_iframe',$xyz_dbx_iframe);
	
		update_option('xyz_dbx_display_position',$xyz_dbx_display_position);
		
		update_option('xyz_dbx_positioning',$xyz_dbx_positioning);
		
		update_option('xyz_dbx_title',$xyz_dbx_title);
		update_option('xyz_dbx_title_color',$xyz_dbx_title_color);
			update_option('xyz_dbx_position_predefined',$xyz_dbx_position_predefined);
		
		?><br>
		
<div  class="system_notice_area_style1" id="system_notice_area">Settings updated successfully.<span id="system_notice_area_dismiss">Dismiss</span></div>
<?php
}


?>
<style type="text/css">
label{
cursor:default;


}
</style>
<script type="text/javascript">
 
  jQuery(document).ready(function() {
    
    jQuery('#dbxbordercolorpicker').hide();
    jQuery('#dbxbordercolorpicker').farbtastic("#xyz_dbx_border_color");
    jQuery("#xyz_dbx_border_color").click(function(){jQuery('#dbxbordercolorpicker').slideToggle();});
    
    jQuery('#dbxbgcolorpicker').hide();
    jQuery('#dbxbgcolorpicker').farbtastic("#xyz_dbx_bg_color");
    jQuery("#xyz_dbx_bg_color").click(function(){jQuery('#dbxbgcolorpicker').slideToggle();});
    jQuery('#lbxdbxcolorpicker').hide();
    jQuery('#lbxdbxcolorpicker').farbtastic("#xyz_dbx_title_color");
    jQuery("#xyz_dbx_title_color").click(function(){jQuery('#lbxdbxcolorpicker').slideToggle();});
    
  });
  function bgchange()
  {
	  var v;
v=document.getElementById('xyz_dbx_page_option').value;
if(v==1)
{
	document.getElementById('automatic').style.display='';

	document.getElementById('shortcode').style.display='none';		
}

if(v==3)

{
	document.getElementById('shortcode').style.display='';	
	
	document.getElementById('automatic').style.display='none';
}
  }
  function cdcheck()
  {

	 var display_mech;
	 display_mech=document.getElementById('xyz_dbx_mode').value;
	 if(display_mech=="delay_only")
	 {
		 
		 document.getElementById('xyz_dbx_delaysec').style.display='';
		 document.getElementById('xyz_dbx_pages').style.display='none';
	 }
	 if(display_mech=="page_count_only")
	 {
		 
		 document.getElementById('xyz_dbx_delaysec').style.display='none';
		 document.getElementById('xyz_dbx_pages').style.display='';
	 }
	 if(display_mech=="both")
	 {
		 
		 document.getElementById('xyz_dbx_delaysec').style.display='';
		 document.getElementById('xyz_dbx_pages').style.display='';
	 }


  }

  function dbxdispcoord()
  {
	  var disp_position;
	 disp_position=document.getElementById('xyz_dbx_display_position').value;
	

	
if(disp_position==1)
{
	 document.getElementById('xyz_dbx_pos_left').style.display='';	
	 document.getElementById('xyz_dbx_pos_top').style.display='';
	 document.getElementById('xyz_dbx_pos_right').style.display='none';	
	 document.getElementById('xyz_dbx_pos_bottom').style.display='none';
}

if(disp_position==2)
{
	 document.getElementById('xyz_dbx_pos_left').style.display='none';	
	 document.getElementById('xyz_dbx_pos_top').style.display='';
	 document.getElementById('xyz_dbx_pos_right').style.display='';	
	 document.getElementById('xyz_dbx_pos_bottom').style.display='none';
}
if(disp_position==3)
{
	 document.getElementById('xyz_dbx_pos_left').style.display='';	
	 document.getElementById('xyz_dbx_pos_top').style.display='none';
	 document.getElementById('xyz_dbx_pos_right').style.display='none';	
	 document.getElementById('xyz_dbx_pos_bottom').style.display='';
}
if(disp_position==4)
{
	 document.getElementById('xyz_dbx_pos_left').style.display='none';	
	 document.getElementById('xyz_dbx_pos_top').style.display='none';
	 document.getElementById('xyz_dbx_pos_right').style.display='';	
	 document.getElementById('xyz_dbx_pos_bottom').style.display='';
}



	
  }
  function dbxdisplaydef()
  {
	  var disp_positioning;
	 disp_positioning=document.getElementById('xyz_dbx_positioning').value;
		
		 
			 document.getElementById('xyz_dbx_position').style.display='';
			 if(disp_positioning==1)
			 {
				 document.getElementById('xyz_dbx_predefined').style.display='none';
				 document.getElementById('xyz_dbx_display_pos').style.display=''; 
				 dbxdispcoord();
				 
			 }
			 else

			 {	

				 document.getElementById('xyz_dbx_predefined').style.display='';
				 document.getElementById('xyz_dbx_display_pos').style.display='none';
				 
				 document.getElementById('xyz_dbx_pos_left').style.display='none';	
				 document.getElementById('xyz_dbx_pos_top').style.display='none';
				 document.getElementById('xyz_dbx_pos_right').style.display='none';	
				 document.getElementById('xyz_dbx_pos_bottom').style.display='none';
				
				 document.getElementById('xyz_dbx_pos_width').style.display='';	
				 document.getElementById('xyz_dbx_pos_height').style.display='';
				
		 }
 
  }
</script>
<div >
<?php  $xyz_dbx_top_dim=get_option('xyz_dbx_top_dim');
$xyz_dbx_left_dim=get_option('xyz_dbx_left_dim');
$xyz_dbx_right_dim=get_option('xyz_dbx_right_dim');
$xyz_dbx_bottom_dim=get_option('xyz_dbx_bottom_dim');
$xyz_dbx_height_dim=get_option('xyz_dbx_height_dim');
$xyz_dbx_width_dim=get_option('xyz_dbx_width_dim');
$xyz_dbx_close_button_option=get_option('xyz_dbx_close_button_option');
$xyz_dbx_cookie_resett=get_option('xyz_dbx_cookie_resett');
$xyz_dbx_iframe=get_option('xyz_dbx_iframe');

$xyz_dbx_display_position=get_option('xyz_dbx_display_position');
$xyz_dbx_positioning=get_option('xyz_dbx_positioning');
$xyz_dbx_position_predefined=get_option('xyz_dbx_position_predefined');

?>
<h2>Dialogbox  Settings</h2>
<form method="post" >

<table  class="widefat" style="width:98%">
<tr valign="top" >
<td  scope="row" style="width: 50%" ><h3>  Title</h3></td>
<td></td>
</tr>

<tr valign="top" id="xyz_dbx">

<td scope="row" colspan="1"><label for="xyz_dbx_title"> Title </label>	</td><td>

<input name="xyz_dbx_title" type="text" id="xyz_dbx_title"  value="<?php print(get_option('xyz_dbx_title')); ?>" size="40" />

</td>

</tr>
<tr valign="top" id="xyz_dbx_color">
<td scope="row"><label for="xyz_dbx_title_color"> Title color </label></td>
<td><input name="xyz_dbx_title_color" type="text" id="xyz_dbx_title_color"  value="<?php print(get_option('xyz_dbx_title_color')); ?>" size="40" /> <div id="lbxdbxcolorpicker"></div> </td>
</tr>
<tr valign="top" id="lbx_dbx_font"> 
<td scope="row"><label for="lbx_dbx_title_font">Title font size </label></td><td style="color: red; ">Available in premium version only </td>
<tr valign="top" >
<td  scope="row" style="width: 50%" ><h3>  Content</h3></td>
<td></td>
</tr>

<tr valign="top">

<td scope="row" colspan="1"><label for="lbx_referar_message_show_option">Show referrer URL based messages? </label></td><td style="color: red; ">Available in premium version only </td>
</tr>

<tr valign="top" >
<td colspan="2" >

<?php the_editor(get_option('xyz_dbx_html'),'xyz_dbx_html');?></td>
</tr>

<tr valign="top" id="xyz_dbx_pos"><td scope="row" colspan="2"><h3> Position Settings</h3></td></tr>
<tr valign="top" id="xyz_dbx_position">
<td scope="row"><label for="xyz_dbx_positioning"> Positioning</label></td>
<td><select  name="xyz_dbx_positioning"   id="xyz_dbx_positioning"  onchange="dbxdisplaydef()">
<option value ="1" <?php if($xyz_dbx_positioning=='1') echo 'selected'; ?>>Manually edit</option>
<option value ="2" <?php if($xyz_dbx_positioning=='2') echo 'selected'; ?>>Predefined settings</option>

</select></td>
</tr>
<tr valign="top" id="xyz_dbx_display_pos">

<td scope="row" colspan="1"><label for="xyz_dbx_display_position"> Display position </label></td><td>


<select name="xyz_dbx_display_position" id="xyz_dbx_display_position"  onchange="dbxdispcoord()">

<option value ="1" <?php if($xyz_dbx_display_position=='1') echo 'selected'; ?> >From  Top Left</option>
<option value ="2" <?php if($xyz_dbx_display_position=='2') echo 'selected'; ?> >From Top Right </option>
<option value ="3" <?php if($xyz_dbx_display_position=='3') echo 'selected'; ?> >From Bottom Left</option>
<option value ="4" <?php if($xyz_dbx_display_position=='4') echo 'selected'; ?> >From Bottom Right</option>
</select>
</td>

</tr>
<tr valign="top" id="xyz_dbx_predefined">

<td scope="row" colspan="1"><label for="xyz_dbx_position_predefined"> Choose a position </label>	</td><td>


<select name="xyz_dbx_position_predefined" id="xyz_dbx_position_predefined"  >

<option value ="1" <?php if($xyz_dbx_position_predefined=='1') echo 'selected'; ?> >On top left corner </option>

<option value ="2" <?php if($xyz_dbx_position_predefined=='2') echo 'selected'; ?> >On left center </option>
<option value ="3" <?php if($xyz_dbx_position_predefined=='3') echo 'selected'; ?> >On bottom left cornor</option>


<option value ="4" <?php if($xyz_dbx_position_predefined=='4') echo 'selected'; ?> >On bottom center </option>

<option value ="5" <?php if($xyz_dbx_position_predefined=='5') echo 'selected'; ?> >On bottom right corner </option>
<option value ="6" <?php if($xyz_dbx_position_predefined=='6') echo 'selected'; ?> >On right center</option>
<option value ="7" <?php if($xyz_dbx_position_predefined=='7') echo 'selected'; ?> >On top right corner </option>

<option value ="8" <?php if($xyz_dbx_position_predefined=='8') echo 'selected'; ?> >On top center </option>
<option value ="9" <?php if($xyz_dbx_position_predefined=='9') echo 'selected'; ?> >Absolute center</option>
</select>
</td>

</tr>
<tr valign="top" id="xyz_dbx_pos_top">
<td scope="row"><label for="xyz_dbx_top"> Top coordinate</label></td>
<td><input name="xyz_dbx_top" type="text" id="xyz_dbx_top"  value="<?php print(get_option('xyz_dbx_top')); ?>" size="40" /><select  name="xyz_dbx_top_dim"   id="xyz_dbx_top_dim" >
<option value ="px" <?php if($xyz_dbx_top_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_dbx_top_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr valign="top" id="xyz_dbx_pos_left">
<td scope="row"><label for="xyz_dbx_left"> Left coordinate</label></td>
<td><input name="xyz_dbx_left" type="text" id="xyz_dbx_left"  value="<?php print(get_option('xyz_dbx_left')); ?>" size="40" /><select  name="xyz_dbx_left_dim"   id="xyz_dbx_left_dim" >
<option value ="px" <?php if($xyz_dbx_left_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_dbx_left_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>

<tr valign="top" id="xyz_dbx_pos_right">
<td scope="row"><label for="xyz_dbx_right"> Right coordinate</label></td>
<td><input name="xyz_dbx_right" type="text" id="xyz_dbx_right"  value="<?php print(get_option('xyz_dbx_right')); ?>" size="40" /><select  name="xyz_dbx_right_dim"   id="xyz_dbx_right_dim" >
<option value ="px" <?php if($xyz_dbx_right_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_dbx_right_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr valign="top" id="xyz_dbx_pos_bottom">
<td scope="row"><label for="xyz_dbx_bottom"> Bottom coordinate</label></td>
<td><input name="xyz_dbx_bottom" type="text" id="xyz_dbx_bottom"  value="<?php print(get_option('xyz_dbx_bottom')); ?>" size="40" /><select  name="xyz_dbx_bottom_dim"   id="xyz_dbx_bottom_dim" >
<option value ="px" <?php if($xyz_dbx_bottom_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_dbx_bottom_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr valign="top" id="xyz_dbx_pos_width">
<td scope="row"><label for="xyz_dbx_width"> Width</label></td>
<td><input name="xyz_dbx_width" type="text" id="xyz_dbx_width"  value="<?php print(get_option('xyz_dbx_width')); ?>" size="40" /><select  name="xyz_dbx_width_dim"   id="xyz_dbx_width_dim" >
<option value ="px" <?php if($xyz_dbx_width_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_dbx_width_dim=='%') echo 'selected'; ?>>%</option>

</select>
</td>
</tr>
<tr valign="top" id="xyz_dbx_pos_height">
<td scope="row"><label for="xyz_dbx_height"> Height</label></td>
<td><input name="xyz_dbx_height" type="text" id="xyz_dbx_height"  value="<?php print(get_option('xyz_dbx_height')); ?>" size="40" /><select  name="xyz_dbx_height_dim"   id="xyz_dbx_height_dim" >
<option value ="px" <?php if($xyz_dbx_height_dim=='px') echo 'selected'; ?>>px</option>
<option value ="%" <?php if($xyz_dbx_height_dim=='%') echo 'selected'; ?>>%</option>

</select></td>
</tr>
<tr valign="top" ><td scope="row" colspan="1" ><h3> Effect Settings</h3></td><td style="color: red; "></td></tr>
<tr valign="top">
<td scope="row"><label for="lbx_draggable"> Draggabale option </label></td><td style="color: red; ">Available in premium version only </td></tr>
<tr valign="top" id="lbx_dbx_slide">
<td scope="row"><label for="lbx_slideable"> Slidable option </label></td><td style="color: red; ">Available in premium version only </td></tr>
<?php
$xyz_dbx_mode=get_option('xyz_dbx_mode');
$xyz_dbx_page_option=get_option('xyz_dbx_page_option');
$xyz_dbx_repeat_interval_timing=get_option('xyz_dbx_repeat_interval_timing');
?>
<tr valign="top"><td scope="row" colspan="2"><h3> Display Logic Settings</h3></td></tr>
<tr valign="top">
<td scope="row"><label for="xyz_dbx_mode"> Display logic </label></td>
<td><select  name="xyz_dbx_mode"   id="xyz_dbx_mode"  onchange="cdcheck()">
<option value ="delay_only" <?php if($xyz_dbx_mode=='delay_only') echo 'selected'; ?>>Based on delay </option>
<option value ="page_count_only" <?php if($xyz_dbx_mode=='page_count_only') echo 'selected'; ?>>Based on  number of pages browsed </option>
<option value ="both" <?php if($xyz_dbx_mode=='both') echo 'selected'; ?>>Based on both </option>
</select></td>
</tr>
<tr valign="top" id="xyz_dbx_delaysec">
<td scope="row"><label for="xyz_dbx_delay"> Delay in seconds before popup appears </label></td>
<td><input name="xyz_dbx_delay" type="text" id="xyz_dbx_delay"  value="<?php print(get_option('xyz_dbx_delay')); ?>" size="40" /> sec</td>
</tr>

<tr valign="top" id="xyz_dbx_pages">
<td scope="row"><label for="xyz_dbx_page_count">Number of pages to browse before popup appears</label></td>
<td><input name="xyz_dbx_page_count" type="text" id="xyz_dbx_page_count"  value="<?php print(get_option('xyz_dbx_page_count')); ?>" size="40" /> pages</td>
</tr>
<tr valign="top">
<td scope="row"><label for="xyz_dbx_repeat_interval"> Repeat interval for a user </label></td>
<td ><input name="xyz_dbx_repeat_interval" type="text" id="xyz_dbx_repeat_interval"  value="<?php print(get_option('xyz_dbx_repeat_interval')); ?>" size="40" /> 

<select name="xyz_dbx_repeat_interval_timing" id="xyz_dbx_repeat_interval_timing" >
<option value ="1" <?php if($xyz_dbx_repeat_interval_timing=='1') echo 'selected'; ?> >Hrs </option>

<option value ="2" <?php if($xyz_dbx_repeat_interval_timing=='2') echo 'selected'; ?> >Minutes </option>
</select>
</td>

</tr>
<tr valign="top" >
<td scope="row" colspan="1"><label for="lbx_display_trigger"> Display trigger</label></td><td style="color: red; ">Available in premium version only </td></tr>
				<tr valign="top" id="xyz_dbx_close">

<td scope="row" colspan="1"><label for="xyz_dbx_bgimage_option"> Close button option </label></td><td>


<select name="xyz_dbx_close_button_option" id="xyz_dbx_close_button_option"  >

<option value ="1" <?php if($xyz_dbx_close_button_option=='1') echo 'selected'; ?> >Yes </option>

<option value ="0" <?php if($xyz_dbx_close_button_option=='0') echo 'selected'; ?> >No </option>
</select>
</td>

</tr>
<tr valign="top">

<td scope="row" colspan="1"><label for="xyz_dbx_bgimage_option">Display as iframe </label></td><td>


<select name="xyz_dbx_iframe" id="xyz_dbx_iframe"  >

<option value ="1" <?php if($xyz_dbx_iframe=='1') echo 'selected'; ?> >Yes </option>

<option value ="0" <?php if($xyz_dbx_iframe=='0') echo 'selected'; ?> >No </option>
</select>
</td>

</tr>
<tr valign="top">

<td scope="row" colspan="1"><label for="lbx_display_device">Target display devices </label></td><td style="color: red; ">Available in premium version only </td></tr>
<tr valign="top"><td scope="row" colspan="1"><h3> Popup Closing Settings</h3></td><td style="color: red; "></td></tr>




<tr valign="top" id="lbx_close" >

<td scope="row" colspan="1"><label for="lbx_bgimage_option"> Close mode </label></td><td style="color: red; ">Available in premium version only </td></tr>
<tr valign="top" >

<td scope="row" colspan="1"><label for="lbx_bgimage_option"> Auto close after timeout </label></td><td style="color: red; ">Available in premium version only </td></tr>
<tr valign="top"><td scope="row" colspan="1"><h3> Javascript Callback Settings</h3></td><td style="color: red; "></td></tr>
<tr valign="top">
<td scope="row"><label for="lbx_show_callback">Callback on popup display</label></td>
<td style="color: red; ">Available in premium version only </td>
</tr>
<tr valign="top">
<td scope="row"><label for="lbx_hide_callback">Callback on popup hide</label></td>
<td style="color: red; ">Available in premium version only </td>
</tr>

<tr valign="top"><td scope="row" colspan="2"><h3> Style Settings</h3></td></tr>
<tr valign="top">
<td scope="row"><label for="xyz_dbx_z_index"> Z index</label></td>
<td><input name="xyz_dbx_z_index" type="text" id="xyz_dbx_z_index"  value="<?php print(get_option('xyz_dbx_z_index')); ?>" size="40" /> </td>
</tr>


<tr valign="top" >
<td scope="row"><label for="xyz_dbx_bg_color"> Background color</label></td>
<td><input name="xyz_dbx_bg_color" type="text" id="xyz_dbx_bg_color"  value="<?php print(get_option('xyz_dbx_bg_color')); ?>" size="40" /> <div id="dbxbgcolorpicker"></div> </td>
</tr>
<tr valign="top">
<td scope="row"><label for="xyz_dbx_border_color"> Border color</label></td>
<td><input name="xyz_dbx_border_color" type="text" id="xyz_dbx_border_color"  value="<?php print(get_option('xyz_dbx_border_color')); ?>" size="40" /> <div id="dbxbordercolorpicker"></div> </td>
</tr>
<tr valign="top">
<td scope="row"><label for="xyz_dbx_border_width">  Border  width </label></td>
<td><input name="xyz_dbx_border_width" type="text" id="xyz_dbx_border_width"  value="<?php print(get_option('xyz_dbx_border_width')); ?>" size="40" /> px </td>
</tr>
<tr valign="top" id="xyz_dbx_rad">
<td scope="row"><label for="xyz_dbx_corner_radius">  Border  radius </label></td>
<td><input name="xyz_dbx_corner_radius" type="text" id="xyz_dbx_corner_radius"  value="<?php print(get_option('xyz_dbx_corner_radius')); ?>" size="40" /> px </td>
</tr>

<tr valign="top"><td scope="row" colspan="2"><h3> Placement Settings</h3></td></tr>


<tr valign="top" id="pgopt" ><td scope="row"><label for="xyz_dbx_page_option"> Placement mechanism </label></td>
<td>
<select name="xyz_dbx_page_option" id="xyz_dbx_page_option" onchange="bgchange()">
<option value ="1" <?php if($xyz_dbx_page_option=='1') echo 'selected'; ?> >Automatic </option>

<option value ="3" <?php if($xyz_dbx_page_option=='3') echo 'selected'; ?> >Manual </option>
</select></td></tr>
<tr valign="top" id="automatic"  style="display: none"><td scope="row" ></td><td >(Popup will load in all pages)</td>

</tr>

<tr valign="top" id="shortcode"  style="display: none"><td scope="row"></td><td>Use this short code in your pages - [xyz_dbx_default_code]</td>
</tr>


<tr valign="top">
<td scope="row"><label for="xyz_lcookie_resett"><h3>Reset cookies ? </h3>
 </label></td>
<td><input name="xyz_dbx_cookie_resett" type="checkbox" id="xyz_dbx_cookie_resett"   <?php  echo 'checked'; ?> /> 
(This is just for your testing purpose. If you want to see a popup  immediately after you make changes in any settings, you have to reset the cookies.)
 </td>
</tr>
<tr>
<td scope="row"> </td>
<td><br>
<input type="submit" value=" Update Settings " /></td>
</tr>

</table>


</form>

</div>

<script type="text/javascript">
bgchange();
cdcheck();
dbxdisplaydef();
</script>
