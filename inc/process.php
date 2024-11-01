<?php



function wp_super_optin_process()

{

  if(isset($_REQUEST['wp_capture_action'])) {wp_super_optin_signup();
  }

   if(isset($_GET['wp_email_confirm']) || isset($_REQUEST['wp_email_confirm'])) {
  		superoptin_wp_capture_email_confirm();
  }

}



function wp_super_optin_double_check_everything($name, $email)

{

	if (superoptin_wp_email_injection_chars($name) || superoptin_wp_email_injection_chars($email) || superoptin_wp_email_injection_chars($name) || superoptin_wp_email_injection_chars($email))

	{

		return FALSE;

	} else {

		return TRUE;
	}

}



function wp_super_optin_signup()

{

global $wpdb;



// Random confirmation code

$confirm_code=md5(uniqid(rand()));

$name = $_REQUEST['wp-email-capture-name'];

$email = $_REQUEST['wp-email-capture-email'];


if (!superoptin_validEmail($email))

{

	$url = $_SERVER['PHP_SELF'] . "?wp_super_optin_error=Not%20a%20valid%20email";

	header("Location: $url");	

	die();

}



if (wp_super_optin_double_check_everything($name, $email))

{

	// values sent from form

	$name = superoptin_sanitize($name);

	$email= superoptin_sanitize($email);

	$name = superoptin_wp_email_injection_test($name);

	$email = superoptin_wp_email_injection_test($email);

	$name = superoptin_wp_email_stripslashes($name);

	$email = superoptin_wp_email_stripslashes($email);

	$referrer = superoptin_sanitize($_SERVER['HTTP_REFERER']);

	$ip = superoptin_sanitize($_SERVER['REMOTE_ADDR']);

	$date = date("Y-m-d H-i");


	$sqlcheck = superoptin_checkIfPresent($email);



	if ($sqlcheck){

		

		$url = $_SERVER['PHP_SELF'] . "?wp_super_optin_error=User%20already%20present";

		header("Location: $url");

		die();

	}



	// Insert data into database

	$table_name = $wpdb->prefix . "wp_super_optin_temp_members";





	$sql="INSERT INTO ".$table_name."(confirm_code, name, email)VALUES('$confirm_code', '$name', '$email')";

	$result=$wpdb->query($wpdb->prepare($sql));

	

	// if suceesfully inserted data into database, send confirmation link to email

	if($result){



	// ---------------- SEND MAIL FORM ----------------



	// send e-mail to ...

	$to=$email;

	$siteurl = get_option('home');
	$siteurl = superoptin_addLastCharacter($siteurl);

	// Your subject

	$subject=get_option('wp_super_optin_subject');

	// From
	$header = "MIME-Version: 1.0\n" . "From: " . get_option('wp_super_optin_from_name') . " <" . get_option('wp_super_optin_from') . ">\n"; 
	$header .= "Content-Type: text/plain; charset=\"" . get_settings('blog_charset') . "\"\n";
	// Your message

	$message.= get_option('wp_super_optin_body') . "\n\n";

	$message.= $siteurl ."?wp_email_confirm=1&wp_super_optin_passkey=$confirm_code";
	$message .= "\n\n----\n";
	$message .= "This is an automated message that is generated because somebody with the IP address of " . $ip ." (possibly you) on ". $date ." filled out the form on the following page " . $referrer . "\n";
	$message .= "If you are sure this isn't you, please ignore this message, you will not be sent another message.";
	$message = str_replace("%NAME%", $name, $message);
	
	// send email

	$sentmail = wp_mail($to,$subject,$message,$header);

}

}



// if not found

else {

echo "Not found your email in our database";

}



// if your email succesfully sent

if($sentmail){

	$halfreg = get_option('wp_super_optin_signup');

	header("Location: $halfreg"); 

	die();

}

else {

	$url = $_SERVER['PHP_SELF'] . "?wp_super_optin_error=Email%20unable%20to%20be%20sent";

	header("Location: $url");

	die();

	//echo "<meta http-equiv='refresh' content='0;". $url . "?wp_super_optin_error=Email%20unable%20to%20be%sent'>";

}

}





function superoptin_wp_capture_email_confirm()

{

	global $wpdb;

	// Passkey that got from link

	$passkey=superoptin_sanitize($_GET['wp_super_optin_passkey']);

	$table_name = $wpdb->prefix . "wp_super_optin_temp_members";

	$sql1="SELECT id FROM $table_name WHERE confirm_code ='$passkey'";

	$result=$wpdb->get_var($wpdb->prepare($sql1));

	if ($result != '')

	{	

		$table_name2 = $wpdb->prefix . "wp_super_optin_registered_members";

		$sql2="SELECT * FROM $table_name WHERE confirm_code ='$passkey'";

		$rowresults = $wpdb->get_results($wpdb->prepare($sql2));

		foreach ($rowresults as $rowresult) {

		 $name = $rowresult->name;  

		 $email = $rowresult->email;

		 $sql3="INSERT INTO $table_name2(name, email)VALUES('$name', '$email')";

		 $result3=$wpdb->query($wpdb->prepare($sql3));

		}

	}

	else {

			$url = $url . "?wp_super_optin_error=Wrong%20confirmation%20code";

			header("Location: $url");

	}

		// if successfully moved data from table"temp_members_db" to table "registered_members" displays message "Your account has been activated" and don't forget to delete confirmation code from table "temp_members_db"

		

	if($result3){

			$sql4="DELETE FROM $table_name WHERE confirm_code = '$passkey'";

			$result4=$wpdb->query($wpdb->prepare($sql4));

			$fullreg = get_option('wp_super_optin_redirection');

			header("Location: $fullreg"); 
			
			

			echo "<meta http-equiv='refresh' content='0;". $fullreg ."'>"; 
			die();

	}



		

}





?>