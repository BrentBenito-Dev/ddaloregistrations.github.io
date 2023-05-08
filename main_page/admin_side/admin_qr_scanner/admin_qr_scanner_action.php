<?php
session_start();
// Connect to database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "dba9";

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// If database connection failed
if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['Id'])){

	$Id = $_POST['Id'];
	$time = date('H:i:s');

	$tableSelect = "SELECT * FROM tblattendance WHERE Id = '$Id' AND status = '0' AND timeOut IS NULL"; // added timeOut IS NULL check
	$query = $con->query($tableSelect);
	if($query-> num_rows > 0){
		$update = "UPDATE tblattendance SET timeOut = NOW(), status = '1' WHERE Id = '$Id' AND timeOut IS NULL";
		$query = $con->query($update);
		$_SESSION['success'] = 'Successfully Timed Out';
	}else{
		$sql = "INSERT INTO tblattendance(Id,timeIn,status) VALUES ('$Id', NOW(),'0')";
		if($con->query($sql)===TRUE){
			$_SESSION['success'] = 'Succesfully Timed In';
		
		}else{ 
			$_SESSION['error'] = $con->error;
		}
	}	
	header('Location: admin_qr_scanner_index.php');
}




mysqli_close($con);
