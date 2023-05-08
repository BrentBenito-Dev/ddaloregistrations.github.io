
<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
require 'vendor/autoload.php';


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

$email = $_SESSION['email'];

$query = "SELECT * FROM tbluser WHERE email = '$email'";
$result = mysqli_query($con, $query);

// Fetch the qrId value
$row = mysqli_fetch_assoc($result);
$qrId = $row['qrId'];

// Generate the QR code
require_once 'vendor/autoload.php';



$qrText = "$qrId";
$builder = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(300)
        ->Margin(10)
        ->labelText('This is your QR Code Ticket')
        ->labelFont(new NotoSans(20))
        ->data($qrText);
$qrCode = $builder->build();

// Get the QR code image data
$qrImageData = $qrCode->getString();

sendmail($email, $qrImageData);
header('location: client_qr_generator_index.php');

function sendmail($email, $qrImageData){

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


    $mail->setFrom('benito.brentneo1@gmail.com', 'DDALO');
    $mail->addAddress($email);
    $mail->Subject = 'QR Code';
    $mail->Body = 'Please find attached your QR Ticket.';
    $mail->AltBody = 'Please find attached your QR Ticket.';

    // Attach the QR code image to the email
    $mail->addStringAttachment($qrImageData, 'qr_code.png', 'base64', 'image/png');

    if (!$mail->send()) {
        echo 'Error sending email: ' . $mail->ErrorInfo;
    } else {
        echo 'Email sent successfully!';
    }
}
?>