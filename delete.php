<?php
include('config.php');
 
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$model=$_GET['model'];
	
    $stmt = $db->prepare("SELECT * FROM uploaded_json WHERE status = 'Success' and unique_id='$id'  ");
    
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($row = $result->fetch_assoc()) {
		$api_key = $row['api_key'];
		$unique_id = $row['unique_id'];
		$job_id = $row['job_id'];
		$output = shell_exec("python3 delete.py " . escapeshellarg($api_key) . " " . escapeshellarg($model));
		
			$stmt = $db->prepare("update uploaded_json set remark='Model Deleted by user', status='Deleted'  WHERE unique_id = '$unique_id' ");
		$stmt->execute();
	}
	
}
$base_url2=$base_url."finetune.php?id=".$id;
header("location: $base_url2");
?>