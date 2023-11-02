<?php
include('config.php');
if (isset($_GET['id'])) {
    $unique_id = $_GET['id'];
    
   
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $stmt = $db->prepare("SELECT * FROM uploaded_json WHERE unique_id = ?");
    $stmt->bind_param('s', $unique_id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Request Details</h5><br><br>
                        <p><strong>Save URL for future use</strong> <input type="text" class="form-control" value="<?php echo $base_url;?>finetune.php?id=<?php echo  $row['unique_id'];?>" disabled ></p>
                        <p><strong>Unique ID:</strong> <?php echo  $row['unique_id'];?></p>
                        <p><strong>Timestamp:</strong> <?php echo  $row['timestamp'];?></p>
                        <p><strong>File ID:</strong> <?php echo  $row['file_id'];?></p>
                        <p><strong>Job ID:</strong> <?php echo  $row['job_id'];?></p>
                        <p><strong>Remark:</strong> <?php echo  $row['remark'];?></p>
                        <p><strong>Status:</strong> <?php echo  $row['status'];?></p>
                       <p><strong>Trained Model:</strong> <?php echo  $row['output_model'];?></p>
					   <p>
					   <?php if($row['other_info'] != ""){
						   ?>
						   <strong>Other Information:</strong> 
						   <?php 
						   $ot=json_decode(trim($row['other_info']),true);
						   foreach($ot as $k => $ov){
							   if(is_array($ov)){
								    foreach($ov as $ok => $ov2){
										echo "<p>".$ok.": ".$ov2."</p>";
									}
							   }else{
									echo "<p>".$k.": ".$ov."</p>";
							   }
							   
						   }
					   }
					   ?>
					   </p>
                    </div>
					<div class="card-footer">
						<a href="<?php echo $base_url;?>" class="btn btn-dark btn-sm">Back</a> &nbsp;&nbsp;&nbsp;&nbsp;
						<a href="<?php echo $base_url;?>finetune.php?id=<?php echo  $row['unique_id'];?>"  class="btn btn-primary btn-sm">Refresh</a> &nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($row['status'] == "Processing"){ ?> 
						<a href="<?php echo $base_url;?>cancel.php?id=<?php echo  $row['unique_id'];?>"  class="btn btn-warning btn-sm">Cancel Job</a>  &nbsp;&nbsp;&nbsp;&nbsp;
						<?php } ?>
						<?php if($row['status'] == "Success"){ ?> 
						<a href="<?php echo $base_url;?>delete.php?id=<?php echo  $row['unique_id'];?>&model=<?php echo  $row['output_model'];?>"  class="btn btn-danger btn-sm">Delete Trained Model</a>  
						<?php } ?>
					</div>
                </div>
				<br><br>
				 <div class="card">
                    <div class="card-header">
						<a href="<?php echo $base_url;?>deleteEntry.php?id=<?php echo  $row['unique_id'];?>"  class="btn btn-warning btn-sm">Delete complete entry</a> 
					</div>
					<div class="card-body">
						It will remove the complete entry of this request, which hold the information like api key, file id, job id, trained model name, status etc.<br>
						It doesn't remove any data on chatGPT side (File id, job id, trained model remain exist in chatGPT server).
					</div>
				</div>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
    } else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Not Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5">
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">File Not Found</h4>
                    <p>The requested file was not found.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
    }

    $stmt->close();
    $db->close();
} else {
    header("Location: index.php");
}
?>
