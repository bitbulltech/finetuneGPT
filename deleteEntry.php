<?php
include('config.php');
 
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
if(isset($_GET['id'])){
	$id=$_GET['id'];
	
    $stmt = $db->prepare("delete FROM uploaded_json WHERE unique_id='$id'  ");
    
    $stmt->execute();
    
	
}
$base_url2=$base_url;
header("location: $base_url2");
?>