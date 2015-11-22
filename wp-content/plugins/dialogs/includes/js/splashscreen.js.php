<?php
header('Content-Type:text/javascript charset=utf-8;');

if(isset($_GET['url'])){
    $url = urldecode($_GET['url']);
    $caption = htmlentities(strip_tags($_GET['caption']));
}
?>
/* 
 * displays a splash screen from a url wich is given in tk_dialogs_splashscreen
 */
<?php echo "\n".'/*'.  var_export( $_SERVER['REQUEST_URI'], true ).'*/'."\n"; ?>
jQuery(document).ready(function(){
    tb_show('<?php echo $caption; ?>', '<?php echo $url; ?>', false);
});