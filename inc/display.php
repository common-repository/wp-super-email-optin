<?php 



function wp_super_optin_form($error = 0)

{

$url = get_option('home');
$url = superoptin_addLastCharacter($url);



 ?> <div id="wp_super_optin">
<? if (get_option('wp_super_optin_show_video')==1) {
	
	
$video = get_option('wp_super_optin_video_id');


$path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

$path = str_replace("inc/","",$path);

$video_url = $path. "videos/" . $video; 

// Get width
$width = get_option('wp_super_optin_video_width');

$height = get_option('wp_super_optin_video_height');

echo "<object width='".$width."' height='".$height."'
 classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' 
  codebase='http://www.apple.com/qtactivex/qtplugin.cab'>
  <param name='src' value='".$video_url."'>
  <param name='controller' value='true'>
  <param name='autoplay' value='true'>
  <embed src='".$video_url."' width='".$width."' height='".$height."'  
    autoplay='true' controller='false' 
    pluginspage='http://www.apple.com/quicktime/download/'>
  </embed>
</object>";
}
?>
 
 <form name="wp_super_optin" method="post" action="<?php echo $url; ?>">

 	<?php if (isset($_GET["wp_super_optin_error"])) {

		$error = superoptin_sanitize($_GET["wp_super_optin_error"]);

		echo "<div style='width:80%;background-color: #FFCCCC; margin: 5px;font-weight'>Error: ". $error ."</div>";

	} ?>

	<label class="wp-email-capture-name">Name:</label> <input name="wp-email-capture-name" type="text" class="wp-email-capture-name"><br/>

	<label class="wp-email-capture-email">Email:</label> <input name="wp-email-capture-email" type="text" class="wp-email-capture-email"><br/>

	<input type="hidden" name="wp_capture_action" value="1">

<input name="Submit" type="submit" value="Submit" class="wp-email-capture-submit">

</form>

</div>

<?php 

}



function wp_super_optin_form_page($error = 0)

{

$url = get_option('home');
$url = superoptin_addLastCharacter($url);




 $display .= "<div id='wp_super_optin_2'>";
 
 if (get_option('wp_super_optin_show_video')==1) {
	
	
$video = get_option('wp_super_optin_video_id');

$video_url = superoptin_PluginUrl()."/". $video; 

// Get width
$width = get_option('wp_super_optin_video_width');

$height = get_option('wp_super_optin_video_height');

 $display .=  "<object width '".$width."' height='".$height."'
 classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' 
  codebase='http://www.apple.com/qtactivex/qtplugin.cab'>
  <param name='src' value='".$video_url."'>
  <param name='controller' value='true'>
  <param name='autoplay' value='false'>
  <embed src='".$video_url."' width='".$width."' height='".$height."'  
    autoplay='true' controller='false'
    pluginspage='http://www.apple.com/quicktime/download/'>
  </embed>
</object>";
}
 
 $display .= "<form name='wp_super_optin_display' method='post' action='" . $url ."'>\n";

 	if (isset($_GET["wp_super_optin_error"])) {

		$error = superoptin_sanitize($_GET["wp_super_optin_error"]);

		$display .= "<div style='width:80%;background-color: #FFCCCC; margin: 5px;font-weight'>Error: ". $error ."</div>\n";

	} 

	$display .= "<label class='wp-email-capture-name'>Name:</label> <input name='wp-email-capture-name' type='text' class='wp-email-capture-name'><br/>\n";

	$display .= "<label class='wp-email-capture-email'>Email:</label> <input name='wp-email-capture-email' type='text' class='wp-email-capture-email'><br/>\n";

	$display .= "<input type='hidden' name='wp_capture_action' value='1'>\n";

	$display .= "<input name='Submit' type='submit' value='Submit' class='wp-email-capture-submit'></form></div>\n";




	return $display;

}



function wp_super_optin_display_form_in_post($content)

{

	$get_form =wp_super_optin_form_page();

	$content = str_replace("[wp_super_optin_form]", $get_form, $content);

	return $content;

}





?>