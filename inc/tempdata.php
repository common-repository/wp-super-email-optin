<?php

function wp_super_optin_truncate()

{

	global $wpdb;

	$table_name = $wpdb->prefix . "wp_super_optin_temp_members";

   	$sql = "TRUNCATE " . $table_name;

	$result = $wpdb->query($wpdb->prepare($sql));

}

function wp_super_optin_delete()

{

	global $wpdb;

	$table_name = $wpdb->prefix . "wp_super_optin_registered_members";

   	$sql = "TRUNCATE " . $table_name;

	$result = $wpdb->query($wpdb->prepare($sql));

}


function wp_super_optin_count_temp()

{

	global $wpdb;

	$table_name = $wpdb->prefix . "wp_super_optin_temp_members";

	$sql = 'SELECT COUNT(*)

	FROM '. $table_name;

	$prep = $wpdb->prepare($sql);

	$result = $wpdb->get_var($prep);

	return $result;

}

?>