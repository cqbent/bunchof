<?php
/**
 * Widget class extended for Simple Popup Plugin
 *
 * @since 2.8.0
 */
class simple_popup_Widget extends WP_Widget {

    function simple_popup_Widget() {
        $widget_ops = array('classname' => 'widget_simple_popup', 'description' => __( 'Simple Popup Plugin') );
        $this->WP_Widget('simple_popup', __('Popup Links'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'height' => 550, 'width' => 750, 'clicktext'=>'Click Here', 'url'=>'') );
        $title = apply_filters('widget_title', $instance['title']);
        $out='';
        if ( $instance['urls'] ) {
            $urls = unserialize( $instance['urls'] );
            if (is_array($urls)) {
                foreach ($urls as $pop) {
                    if ( !empty( $pop['url'] ) ) {
                        $out .= "<li><a href='{$pop['url']}' onClick='return popitup(this.href,{$pop['width']},{$pop['height']});' target='_blank'>{$pop['clicktext']}</a></li>\n";
                    }
                }
            }
            if ($out) {
                echo $before_widget;
                if ($title) echo $before_title . $title . $after_title;
                echo "\n<ul>\n$out\n</ul>\n";
                echo $after_widget;
            }
        }
    }

    function update( $new_instance, $old_instance ) {
        //$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $newurls=array();
        $xnewurls=array();
        if (is_array($new_instance['urls'])) {
            foreach ( $new_instance['urls'] as $key => $val) {
                $kk = explode('_',$key);
                if ($kk[1]=='url' && !(empty($val)||substr($val,0,1)=='/'||substr(strtolower($val),0,4)=='http')) $val="http://$val";
                $newurls[$kk[0]][$kk[1]]=$val;
            }
            foreach ($newurls as $url) {
                if ($url['url']) $xnewurls[] = $url;
            }
        }
        $instance['urls'] = serialize( $xnewurls );
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '','urls'=>'') );
        $title = esc_attr( $instance['title'] );
        $urls = ($instance['urls'] != '') ? unserialize( $instance['urls'] ) : array();
        $width=(get_option('popup_window_width')==null)?'750':get_option('popup_window_width');
        $height=(get_option('popup_window_height')==null)?'550':get_option('popup_window_height');
        $urls[]=array('clicktext'=>'Click Here', 'url'=>'', 'width' => $width, 'height' => $height);
        echo '<p><label for="'.$this->get_field_id('title').'">'.__('Title:').'</label> <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title."\" /></p><hr>\n";
        $id=1;
        foreach ($urls as $pop) {
            $clicktext = esc_attr( $pop['clicktext'] );
            $url = esc_attr( $pop['url'] );
            $width = esc_attr( $pop['width'] );
            $height = esc_attr( $pop['height'] );
            echo "<div style='float:right;'><b>#$id</b></div>\n";
            echo "<p><label for=\"".$this->get_field_id('urls')."[{$id}_clicktext]\">"._e('Link Text:')."</label>
            <input class=\"widefat\" id=\"".$this->get_field_id('urls')."[{$id}_clicktext]\" name=\"".$this->get_field_name('urls')."[{$id}_clicktext]\" type=\"text\" value=\"$clicktext\" /></p>\n";
            echo "<p><label for=\"".$this->get_field_id('urls')."[{$id}_url]\">"._e('URL:')."</label>
            <input class=\"widefat\" id=\"".$this->get_field_id('urls')."[{$id}_url]\" name=\"".$this->get_field_name('urls')."[{$id}_url]\" type=\"text\" value=\"$url\" /></p>\n";
            echo "<p>Width: <input size=\"4\" id=\"".$this->get_field_id('urls')."[{$id}_width]\" name=\"".$this->get_field_name('urls')."[{$id}_width]\" type=\"text\" value=\"$width\" />\n";
            echo "&nbsp;&nbsp;Height: <input size=\"4\" id=\"".$this->get_field_id('urls')."[{$id}_height]\" name=\"".$this->get_field_name('urls')."[{$id}_height]\" type=\"text\" value=\"$height\" /></p>\n";
            echo "<hr>\n";
            $id++;
        }
        echo '<p><small>To Delete, clear URL and click Save.</small></p>';
    }
}