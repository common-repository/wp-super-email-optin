<?php 



function wp_super_optin_install() {

   global $wpdb;

   global $wp_super_optin_db_version;

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

   $table_name = $wpdb->prefix . "wp_super_optin_registered_members";

   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      

      $sql = "CREATE TABLE " . $table_name . " (

	 	id INT( 255 ) NOT NULL AUTO_INCREMENT ,

		name TINYTEXT NOT NULL ,

		email TEXT NOT NULL ,

		PRIMARY KEY (id)

	);";

	dbDelta($sql);

	}

   $table_name = $wpdb->prefix . "wp_super_optin_temp_members";

   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      

      $sql = "CREATE TABLE " . $table_name . " (

	  id INT( 255 ) NOT NULL AUTO_INCREMENT ,

		name TINYTEXT NOT NULL ,

		email TEXT NOT NULL ,

	

	  confirm_code TEXT NOT NULL,

	  PRIMARY KEY (id)

	);";

	

	dbDelta($sql);

	



	}

	add_option('wp_super_optin_link', 1);

	add_option("wp_super_optin_db_version", $wp_super_optin_db_version);

}



?>