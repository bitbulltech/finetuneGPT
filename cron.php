<?php
include('config.php');
 
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $stmt = $db->prepare("SELECT * FROM uploaded_json WHERE status = 'Pending' limit 1 ");
    
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($row = $result->fetch_assoc()) {
		$api_key = $row['api_key'];
		$unique_id = $row['unique_id'];
		$gpt_model_name = $row['gpt_model_name'];
		$upload_path = "uploads/".$row['unique_id'].".json";
		//echo "python3 uploadFile.py " . escapeshellarg($api_key) . " " . escapeshellarg($gpt_model_name) . " " . escapeshellarg($upload_path);
		
		$output = shell_exec("python3 ".$absolute_path."uploadFile.py " . escapeshellarg($api_key) . " " . escapeshellarg($gpt_model_name) . " " . escapeshellarg($upload_path));
		print_r($output);
		$ot=explode(PHP_EOL,$output);
		if($ot[0]){
		$file_id=$ot[0];
		$job_id=$ot[1];
		$status="Processing";
		$remark="";
		$stmt = $db->prepare("update uploaded_json set file_id ='$file_id', job_id ='$job_id', status='$status'  WHERE unique_id = '$unique_id' ");
		$stmt->execute();
		}else{
		$status="Failed";
		$stmt = $db->prepare("update uploaded_json set remark='$output', status='$status'  WHERE unique_id = '$unique_id' ");
		$stmt->execute();
		}
		

		
	}


    $stmt = $db->prepare("SELECT * FROM uploaded_json WHERE status = 'Processing' limit 10 ");
    
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($row = $result->fetch_assoc()) {
		$api_key = $row['api_key'];
		$unique_id = $row['unique_id'];
		$job_id = $row['job_id'];
		$output = shell_exec("python3 ".$absolute_path."updateStatus.py " . escapeshellarg($api_key) . " " . escapeshellarg($job_id));
		$ot=json_decode(trim($output),true);
		// echo "<pre>"; print_r($ot);
		if($ot['status'] == "failed"){
			$remark="";
			if(isset($ot['error']['message'])){
				$remark=$ot['error']['message'];
			}
		$stmt = $db->prepare("update uploaded_json set remark='$remark', status='Failed', other_info='$output'  WHERE unique_id = '$unique_id' ");
		$stmt->execute();
		}
		if($ot['status'] == "succeeded"){ 
		 
		$fine_tuned_model=$ot['fine_tuned_model'];
		$stmt = $db->prepare("update uploaded_json set remark='', output_model='$fine_tuned_model', status='Success', other_info='$output'  WHERE unique_id = '$unique_id' ");
		$stmt->execute();
		}
	}
?>