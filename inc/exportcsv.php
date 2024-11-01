<?php





function wp_super_optin_export()

{

	global $wpdb;

	

	$csv_output .= "Name,Email";

	$csv_output .= "\n";



	 

   	$table_name = $wpdb->prefix . "wp_super_optin_registered_members";

   	$sql = "SELECT name, email FROM " . $table_name;

   	$results = $wpdb->get_results($wpdb->prepare($sql));

		foreach ($results as $result) {
	
			$csv_output .= $result->name ."," . $result->email ."\n";
	
		}



	$filename = $file."_".date("Y-m-d_H-i",time());

	header("Content-type: application/vnd.ms-excel");

	header("Content-disposition: csv" . date("Y-m-d") . ".csv");

	header( "Content-disposition: filename=".$filename.".csv");

	print $csv_output;

	exit;

}


function wp_super_optin_export_csv_email_original()
{

	global $wpdb;

	

	$csv_output .= "Name,Email";

	$csv_output .= "\n";


   	$table_name = $wpdb->prefix . "wp_super_optin_registered_members";

   	$sql = "SELECT name, email FROM " . $table_name;

   	$results = $wpdb->get_results($wpdb->prepare($sql));

 	foreach ($results as $result) {

		$csv_output .= $result->name ."," . $result->email ."\n";

	}



	$filename = $file."_".date("Y-m-d_H-i",time());

	
	//print $csv_output;

	$path = dirname(__FILE__);
//echo "The file name is". $path;



//$path = $_SERVER['DOCUMENT_ROOT'];;// WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

$path = str_replace("inc","",$path);

$file = $path .'csv/'.$filename.'.csv';

$file_contents = file_get_contents($file);

$fh = fopen($file, "w");
//$file_contents = $csv_output;
fwrite($fh, $file_contents);
fclose($fh);

return $file;

// Fahd Murtaza 11:37 PM. Masqaţ, OMN 36°/ 30°°F°C
}

//echo wp_super_optin_export_csv_email();



?>