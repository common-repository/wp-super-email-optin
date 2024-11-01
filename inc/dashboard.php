<?php



function wp_super_optin_dashboard_widget() {

	// Display whatever it is you want to showwp_super_optin_writetable(3, "<strong>Last Three Members To Join</strong><br/><br/>");

	$tempemails =wp_super_optin_count_temp();	

	echo '<br/><br/><a name="list"></a><strong>Export</strong>';

  	echo '<form name="wp_super_optin_export" action="'. $_SERVER["REQUEST_URI"] . '#list" method="post">';

	echo '<label>Use the button below to export your list as a CSV file.</label>';

	echo '<input type="hidden" name="wp_super_optin_export" />';

	echo '<div class="submit"><input type="submit" value="Export List" /></div>';

	echo "</form><br/><br/";

	$tempemails =wp_super_optin_count_temp();

	echo "<a name='truncate'></a><strong>Temporary e-mails</strong>\n";

	echo '<form name="wp_super_optin_truncate" action="'. $_SERVER["REQUEST_URI"] . '#truncate" method="post">';

	echo '<label>There are '. $tempemails . ' e-mail addresses that have been unconfirmed. Delete them to save space below.</label>';

	echo '<input type="hidden" name="wp_super_optin_truncate"/>';

	echo '<div class="submit"><input type="submit" value="Delete Unconfirmed e-mail Addresses" /></div>';

	echo "</form>";



} 



function wp_super_optin_add_dashboard_widgets() {

	wp_add_dashboard_widget('wp_super_optin_dashboard_widget', 'WP Super Optin - At A Glance', 'wp_super_optin_dashboard_widget');	

} 





?>