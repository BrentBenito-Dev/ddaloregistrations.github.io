<?php
use PHPMailer\PHPMailer\PHPMailer;

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



if(isset($_POST['studentRegistration'])){

    $firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_SPECIAL_CHARS);
    $firstName = preg_replace('/[^a-zA-Z]/', '', $firstName);

    $lastName = filter_var($_POST['lastName'], FILTER_SANITIZE_SPECIAL_CHARS);
    $lastName = preg_replace('/[^a-zA-Z]/', '', $lastName);

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $affiliation = "student";
    $verificationCode = rand(999999, 111111);

    $insert = "INSERT INTO tbluser(firstName, lastName, email, affiliation, verificationCode) VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $insert);

    mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $email, $affiliation, $verificationCode);

    mysqli_begin_transaction($con);

    try {
        mysqli_stmt_execute($stmt);
        $select = "SELECT * FROM tbluser WHERE email = ?";
        $stmt = mysqli_prepare($con, $select);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);
        $guid = base64_encode($row['email']);
        $update = "UPDATE tbluser SET qrId = ? WHERE email = ?";
        $stmt = mysqli_prepare($con, $update);
        mysqli_stmt_bind_param($stmt, "ss", $guid, $email);
        mysqli_stmt_execute($stmt);
        mysqli_commit($con);
        session_start();
        $_SESSION['email'] = $email;
        header("location: ../client_email_verification/client_email_verification_index.php");
        sendmail($email, $verificationCode);
        exit();
    } catch (mysqli_sql_exception $e) {
        echo '<script language="javascript">
        alert("User or email already exists")
        window.location.href="client_guest_registration_index.php";
        </script>';
    }

}
    /*catch ( mysqli_sql_exception $e){
       
    }
    */


    function sendmail($email, $verificationCode)
    {
        $email = $email;
        $verificationCode = $verificationCode;
    
        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";
        $mail = new PHPMailer();
    
        //SMTP Settings
        $mail->isSMTP();
        // $mail->SMTPDebug = 3;  Keep It commented this is used for debugging                          
        $mail->Host = "smtp.gmail.com"; // smtp address of your email
        $mail->SMTPAuth = true;
        $mail->Username = "benito.brentneo1@gmail.com";
        $mail->Password = "htznibdmefmjbtpf";
        $mail->Port = 587;  // port
        $mail->SMTPSecure = "tls";  // tls or ssl
        $mail->smtpConnect([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
    
        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom("benito.brentneo1@gmail.com", "D-DALO");
        $mail->addAddress($email); // enter email address whom you want to send
        $mail->Subject = ("This is your verification code");
        $mail->Body = $verificationCode;
        if (!$mail->send()) {
            echo "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }
    }
