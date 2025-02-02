<?php 

/**

Validate an email address.

Provide email address (raw input)

Returns true if the email address has the email 

address format and the domain exists.

*/

if(!function_exists('checkdnsrr'))
{
    function checkdnsrr($hostName, $recType = '')
    {
     if(!empty($hostName)) {
       if( $recType == '' ) $recType = "MX";
       exec("nslookup -type=$recType $hostName", $result);
       // check each line to find the one that starts with the host
       // name. If it exists then the function succeeded.
       foreach ($result as $line) {
         if(eregi("^$hostName",$line)) {
           return true;
         }
       }
       // otherwise there was no mail handler for the domain
       return false;
     }
     return false;
    }
}

function superoptin_addLastCharacter($url)
{
	$last = $url[strlen($url)-1]; 
	if ($last != "/")
	{
	$url = $url . "/";
	}
	return $url;
}


function superoptin_validEmail($email)

{

   $isValid = true;

   $atIndex = strrpos($email, "@");

   if (is_bool($atIndex) && !$atIndex)

   {

      $isValid = false;

   }

   else

   {

      $domain = substr($email, $atIndex+1);

      $local = substr($email, 0, $atIndex);

      $localLen = strlen($local);

      $domainLen = strlen($domain);

      if ($localLen < 1 || $localLen > 64)

      {

         // local part length exceeded

         $isValid = false;

      }

      else if ($domainLen < 1 || $domainLen > 255)

      {

         // domain part length exceeded

         $isValid = false;

      }

      else if ($local[0] == '.' || $local[$localLen-1] == '.')

      {

         // local part starts or ends with '.'

         $isValid = false;

      }

      else if (preg_match('/\\.\\./', $local))

      {

         // local part has two consecutive dots

         $isValid = false;

      }

      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))

      {

         // character not valid in domain part

         $isValid = false;

      }

      else if (preg_match('/\\.\\./', $domain))

      {

         // domain part has two consecutive dots

         $isValid = false;

      }

      else if

(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',

                 str_replace("\\\\","",$local)))

      {

         // character not valid in local part unless 

         // local part is quoted

         if (!preg_match('/^"(\\\\"|[^"])+"$/',

             str_replace("\\\\","",$local)))

         {

            $isValid = false;

         }

      }

      if ($isValid && !(checkdnsrr($domain,"MX") || 

 checkdnsrr($domain,"A")))

      {

         // domain not found in DNS

         $isValid = false;

      }

   }

   return $isValid;

}



?>