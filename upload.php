<?php
include('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES['json_file']['error'] === UPLOAD_ERR_OK) {
        $json_data2 = explode(PHP_EOL,file_get_contents($_FILES['json_file']['tmp_name']));
		$json_data=$json_data2[0];
		
        if ($json_data !== false && json_decode($json_data) !== null) {
            $api_key = $_POST['api_key'];
            $gpt_model_name = $_POST['gpt_model_name'];

            $unique_id = rand(1000, 9999) . '-' . time();
            $filename = $unique_id . '.json';
            $upload_path = 'uploads/' . $filename;

            if (move_uploaded_file($_FILES['json_file']['tmp_name'], $upload_path)) {
               
                if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                }

				$status="Pending";
                $stmt = $db->prepare("INSERT INTO uploaded_json (unique_id, api_key, gpt_model_name,status) VALUES (?, ?, ?, ?)");
               $stmt->bind_param('ssss', $unique_id, $api_key, $gpt_model_name, $status);
			   
                if ($stmt->execute()) {
						
                    header("Location: finetune.php?id=" . $unique_id);
                } else {
                    unlink($upload_path);
                    header("Location: error.php?q=4");
                }

                $stmt->close();
                $db->close();
            } else {
                header("Location: error.php?q=3");
            }
        } else {
            header("Location: error.php?q=2");
        }
    } else {
        header("Location: error.php?q=1");
    }
} else {
    header("Location: index.php");
}
?>
