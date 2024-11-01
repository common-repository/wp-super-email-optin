<?php 



function wp_super_optin_writetable($limit = 0, $header = '')

{

   global $wpdb;

   $table_name = $wpdb->prefix . "wp_super_optin_registered_members";

   $sql = "SELECT id, name, email FROM " . $table_name;

	if ($limit != 0) {

	$sql .= " ORDER BY id DESC LIMIT 3"; 

	}

  $results = $wpdb->get_results($wpdb->prepare($sql));



	if ($header == '') {

	$header = "<h3>Members</h3>";

	}

	echo $header;

?> 

<table border="1">

<tr><td><strong>Name</strong></td><td colspan="2"><strong>Email</strong></td></tr>

<?php foreach ($results as $result) {
	if ($limit == 0) {
	$delid =wp_super_optin_formdelete($result->id, $result->email);
	}
	echo "<tr><td style='width: 300px;'>" . $result->name ."</td><td style='width: 300px;'>" . $result->email ."</td><td style='width: 300px;'>
	". $delid ."</td>

	</tr>";

}

 ?>

</table>

<?php 

}


function wp_super_optin_formdelete($id, $email)
{
	return "<form action='" . $_SERVER["REQUEST_URI"] . "#list' method='post'>
	   <input type='hidden' name='wp_super_optin_deleteid' value='". $id."'>
	   <input type='submit' value='Delete ". $email ."' style='width: 300px;'>
	   </form>"; 
}

function wp_super_optin_deleteid($id)
{
	global $wpdb;
	
 	$table_name = $wpdb->prefix . "wp_super_optin_registered_members";
	
	$sql = "DELETE FROM " . $table_name . " WHERE id = '" . $id ."'";

  	$result = $wpdb->query($wpdb->prepare($sql));

}
?>