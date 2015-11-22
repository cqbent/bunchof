<?php
class adsense_plugin_explosion_search_Widget extends WP_Widget
{
   function adsense_plugin_explosion_search_Widget()
   {
      $widget_ops = array('classname' => 'adsense_plugin_explosion_search_Widget', 'description' => 'Use this widget to add one of your Adsense Search as a widget. ');
      $this->WP_Widget('aeoptsearch', 'Adsense Search Explosion', $widget_ops);
   }
   function widget($args, $instance)
   {
   }
   function update($new_instance, $old_instance)
   {
   }
   function form($instance)
   {
   }
}
?>