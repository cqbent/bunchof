<?php
function aeopt($type)
{
	global $aeopt;
	$code = $aeopt->generateAd($type, 0);
	if($code)
	{
		echo(html_entity_decode($code));
	} else {
		echo('<!--adsense ad injection by Adsense Explosion (http://adsensexplosion.wordpress.com/) failed - tried to add more than 3 ads per page -->');
	}
}
class adsense_plugin_explosion_Widget extends WP_Widget
{
   function adsense_plugin_explosion_Widget()
   {
      $widget_ops = array('classname' => 'adsense_plugin_explosion_Widget', 'description' => 'Use this widget to add one of your Adsense Explosion as a widget. ');
      $this->WP_Widget('aeopt', 'Adsense Explosion', $widget_ops);
   }
   function widget($args, $instance)
   {
      extract($args, EXTR_SKIP);
      echo $before_widget;
      if ((!isset($_SESSION['adsensexplosion_noad'])) || ($_SESSION['adsensexplosion_noad']=='')) {
          $title = apply_filters('widget_title', $instance['title']);
          $adtype = empty($instance['adtype']) ? '8' : apply_filters('widget_adtype', $instance['adtype']);
          if(!empty($title))
          {
             echo $before_title . $title . $after_title;
          }
          aeopt($adtype);
      } else {
		if (isset($_SESSION['adsensexplosion_noad']))
			echo $_SESSION['adsensexplosion_noad'];
	  }
      echo $after_widget;
   }
   function update($new_instance, $old_instance)
   {
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['adtype'] = strip_tags($new_instance['adtype']);
      return $instance;
   }
   function form($instance)
   {
      $instance = wp_parse_args((array)$instance, array('title' => '', 'adtype' => ''));
      $title = strip_tags($instance['title']);
      $adtype = strip_tags($instance['adtype']);
?>

<p>
  Title: <input class="widefat"
    name="<?php echo $this->get_field_name('title'); ?>" type="text"
    value="<?php echo attribute_escape($title); ?>" />
</p>

<p>
  Ad Type: (1-8) <input class="widefat"
    name="<?php echo $this->get_field_name('adtype'); ?>" type="text"
    value="<?php echo attribute_escape($adtype); ?>" />
</p>

<?php
   }
}
?>