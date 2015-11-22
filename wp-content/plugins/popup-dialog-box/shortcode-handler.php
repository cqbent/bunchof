<?php

$page_option=get_option('xyz_dbx_page_option');
if($page_option==3)
{

add_shortcode( 'xyz_dbx_default_code', 'dbx_lightbox_display' );
}

?>