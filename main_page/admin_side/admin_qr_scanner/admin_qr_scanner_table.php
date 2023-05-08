<?php

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


?>




<table class="table table-bordered">
    <thead>
        <tr>
            <td>Attendance Id</td>
            <td>User Id</td>
            <td>Time In</td>
            <td>Time Out</td>
            <td>Status</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql = "SELECT attendanceId, Id, timeIn, timeOut, status FROM tblattendance";
            $query = $con->query($sql);
            while ($row = $query->fetch_assoc()){
        ?>        
            <tr>
                <td><?php echo $row['attendanceId'];?></td>
                <td><?php echo $row['Id'];?></td>
                <td><?php echo $row['timeIn'];?></td>
                <td><?php echo $row['timeOut'];?></td>
                <td><?php echo $row['status'];?></td>
            </tr>
        <?php
            }
        ?>
    </tbody>
</table>