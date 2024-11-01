<?php 

function wp_super_optin_menus() {
$plugin_page =  add_options_page('WP Super Optin  Options', 'WP Super Optin', 8, 'wpsuperoptinoptions', 'wp_super_optin_options');
}



function wp_super_optin_options() {

  echo '<div class="wrap">';

  echo '<h2>WP Super Optin  Options</h2>';

  ?>
<?php

  

  echo '<fieldset class="options"><legend>Options</legend>';

  ?>
</p>

<form method="post" action="options.php">
  <?php wp_nonce_field('update-options'); ?>
  <?php settings_fields( 'wp-email-capture-group' ); ?>
  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row" style="width:400px">Page to redirect to on sign up.. Create this page on your server. (full web address ie: http://www.domain.com/Confirm-Email/)</th>
        <td><input type="text" name="wp_super_optin_signup" class="regular-text code" value="<?php echo get_option('wp_super_optin_signup'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" style="width:400px"><label>Page to redirect to on confirmation of email address..  Create this page on your server.  (full web address ie: http://www.domain.com/Download-Report/)</label></th>
        <td><input type="text" name="wp_super_optin_redirection" class="regular-text code" value="<?php echo get_option('wp_super_optin_redirection'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" style="width:400px"><label>From Which Email Address</label></th>
        <td><input type="text" name="wp_super_optin_from" class="regular-text code"  value="<?php echo get_option('wp_super_optin_from'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" style="width:400px"><label>From Which Name</label></th>
        <td><input type="text" name="wp_super_optin_from_name" class="regular-text code"  value="<?php echo get_option('wp_super_optin_from_name'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" style="width:400px">Subject of Email</th>
        <td><input type="text" name="wp_super_optin_subject" class="regular-text code"  value="<?php echo get_option('wp_super_optin_subject'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" style="width:400px"><label>Body of Email<br>
            (use %NAME% to use the form's &quot;Name&quot; field in their welcome email) </label></th>
        <td><textarea name="wp_super_optin_body" style="width: 25em;"><?php echo get_option('wp_super_optin_body'); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" style="width:400px">Select the video you want to display over the Email Optin Box.  Upload your own .MOV file or get more: wpEmailOptin.com </th>
        <td><?php
  
$directoryname = WP_SUPER_OPTIN_PATH . '/videos';

//echo $directoryname."";

$results = superoptin_getDirectoryList ($directoryname);


?>
          <select name="wp_super_optin_video_id" id="wp_super_optin_video_id">
            <?
$vid_id= get_option('wp_super_optin_video_id');
foreach ($results as $key=>$value){
	echo "<option value='".$value."'"; 
	 if ($vid_id == $value) { echo " selected"; }
	echo ">".$value."</option>";
	}
?>
          </select></td>
      </tr>
      <tr>
        <th>Video Width (usually 220 pixels)</th>
        <td><input type="text" value="<? echo get_option('wp_super_optin_video_width')?>" name="wp_super_optin_video_width" id="wp_super_optin_video_width" />
          px </td>
      </tr>
      <tr>
        <th>Video Height (usually 175 pixels)</th>
        <td><input type="text" value="<? echo get_option('wp_super_optin_video_height')?>" name="wp_super_optin_video_height" id="wp_super_optin_video_height" />
          px </td>
      </tr>
      <tr valign="top">
        <th scope="row" style="width:400px">Show video over Optin Box  (Uncheck to HIDE video)</th>
        <td><input type="checkbox" name="wp_super_optin_show_video" id="wp_super_optin_show_video" value="1"
  <?php 

if (get_option('wp_super_optin_show_video') == 1) { echo "checked"; }
elseif(get_option('wp_super_optin_show_video') == 0){ echo ""; } 
else {
	 echo "checked";
	} 
 ?>
   />
          <label for="show_video"></label></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="page_options" value="wp_super_optin_redirection,wp_super_optin_from,wp_super_optin_subject,wp_super_optin_signup,wp_super_optin_body,wp_super_optin_from_name,wp_super_optin_link" />
  <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </p>
  <?php // print_r(wp_get_schedules()); ?><br />
<br />
<br />
<?php
$whenNext = wp_next_scheduled('email_scheduled_csv');
//echo "This is when it will happen next ".  date('j F Y g:i:s A',$whenNext). " and the current time is " .  date('j F Y g:i:s A',time());

?>
</form>
<?php wp_super_optin_writetable();

   echo '<a name="list"></a><h3>Export</h3>';

  	echo '<form name="wp_super_optin_export" action="'. $_SERVER["REQUEST_URI"] . '#list" method="post">';

	echo '<label>Use the button below to export your list as a CSV file.</label>';

	echo '<input type="hidden" name="wp_super_optin_export" />';

	echo '<div class="submit"><input type="submit" value="Export List" /></div>';

	echo "</form>";

	$tempemails =wp_super_optin_count_temp();

	echo "<a name='truncate'></a><h3>Temporary e-mails</h3>\n";

	echo '<form name="wp_super_optin_truncate" action="'. $_SERVER["REQUEST_URI"] . '#truncate" method="post">';

	echo '<label>There are '. $tempemails . ' e-mail addresses that have been unconfirmed. Delete them to save space below.</label>';

	echo '<input type="hidden" name="wp_super_optin_truncate"/>';

	echo '<div class="submit"><input type="submit" value="Delete Unconfirmed e-mail Addresses" /></div>';

	echo "</form>";

echo "<a name='emptyallemails'></a><h3>Delete Current List</h3>\n";

	echo '<form name="wp_super_optin_delete" action="'. $_SERVER["REQUEST_URI"] . '#delete" method="post">';

	echo '<label>Want to delete the entire list? Click the link below. <strong>WARNING: </strong> this will delete all confirmed emails, so make sure you have a backup.</label>';

	echo '<input type="hidden" name="wp_super_optin_delete"/>';

	echo '<div class="submit"><input type="submit" value="Delete Confirmed e-mail Addresses" /></div>';

	echo "</form></fieldset>";
?>
<p>&nbsp;</p>
<?php }



function wp_super_optin_options_process() { // whitelist options

  register_setting( 'wp-email-capture-group', 'wp_super_optin_signup' );

  register_setting( 'wp-email-capture-group', 'wp_super_optin_redirection' );

  register_setting( 'wp-email-capture-group', 'wp_super_optin_from' );

  register_setting( 'wp-email-capture-group', 'wp_super_optin_subject' );

  register_setting( 'wp-email-capture-group', 'wp_super_optin_body' );

  register_setting( 'wp-email-capture-group', 'wp_super_optin_link');

  register_setting( 'wp-email-capture-group', 'wp_super_optin_from_name' );
register_setting( 'wp-email-capture-group', 'wp_super_optin_spam_free' );
register_setting( 'wp-email-capture-group', 'wp_super_optin_spamfree_affiliate_id');

register_setting( 'wp-email-capture-group', 'wp_super_optin_video_id');

register_setting( 'wp-email-capture-group', 'wp_super_optin_video_height');

register_setting( 'wp-email-capture-group', 'wp_super_optin_video_width');

register_setting( 'wp-email-capture-group', 'wp_super_optin_video_height');

register_setting( 'wp-email-capture-group', 'wp_super_optin_show_video');



  if(isset($_REQUEST['wp_super_optin_export'])) {wp_super_optin_export();

  }


   if(isset($_REQUEST['wp_super_optin_deleteid'])) {
	$wpemaildeleteid = $_POST['wp_super_optin_deleteid'];wp_super_optin_deleteid($wpemaildeleteid);
  }
  

  if(isset($_REQUEST['wp_super_optin_truncate'])) {wp_super_optin_truncate();

  }

  if(isset($_REQUEST['wp_super_optin_delete'])) {wp_super_optin_delete();

  }

}

?>
