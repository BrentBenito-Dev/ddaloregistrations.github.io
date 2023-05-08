<?php
use PHPMailer\PHPMailer\PHPMailer;
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


    $otpCode = $_POST["otpCode"];
    $verifyCode = "SELECT * FROM tbluser WHERE verificationCode = '$otpCode' LIMIT 1";
    $codeResult = mysqli_query($con, $verifyCode);
    $fetchData = mysqli_fetch_assoc($codeResult);

    if (mysqli_num_rows($codeResult) > 0) {
        $fetchCode = $fetchData["verificationCode"];
        $email = $fetchData["email"];
        $code = 0;
        $status = "Verified";
        $updateOtp = "UPDATE tbluser SET verificationCode = $code, status = '$status' WHERE verificationCode= '$fetchCode'";
        $update_result = mysqli_query($con, $updateOtp);
        if ($update_result) {
            $info = "Congratulations, you have registered your account. - $email";
           
            $_SESSION["email"] = $email;
            $_SESSION["info"] = $info;
            header("Location: ../client_qr_generator/client_qr_generator_action.php");
            exit();
        } else {
            echo '<script type="javascript">alert("Failed while updating the code")</script>';
        }
    } else {
        echo '<script language="javascript">
        alert("Wrong Code")
        window.location.href="client_email_verification_index.php";
        </script>';
    }

    if (isset($_POST["resendOtp"])){
        $verificationCode = rand(999999, 111111);
        $sql = "UPDATE tbluser SET verificationCode = '$verificationCode' WHERE email = '$email'";
        $updateResult = mysqli_prepare($con, $sql);
        if($update_result){
            sendmail($email, $verificationCode);
        }


    }
    function sendmail($email, $verificationCode)
    {
       
    
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

    mysqli_close($con);
?>