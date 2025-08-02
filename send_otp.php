<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';

$stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];

    $otp = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", time() + 300); // 5 mins

    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp, $expires_at, $email);
    $stmt->execute();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'skadoshguy24@gmail.com';
        $mail->Password   = 'rtnw shvx zjzj yhzk'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('skadoshguy24@gmail.com', 'Admin');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5;'>
                <h2>Hello <span style='color: #4a90e2;'>@$username</span>,</h2>
                <p>You (or someone else) requested to reset your password.</p>
                <p>Here is your One-Time Password (OTP):</p>
                <h1 style='color: #333;'>$otp</h1>
                <p>This OTP will expire in <strong>5 minutes</strong>.</p>
                <p>If you didn’t request this, you can ignore this email.</p>
                <br>
                <p>— Sleepify Support Team</p>
            </div>
        ";

        $mail->send();

       
        echo "
        <html>
        <head>
            <meta http-equiv='refresh' content='5;url=verifyotp.html?email=" . urlencode($email) . "' />
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f0f8ff;
                    text-align: center;
                    padding-top: 100px;
                }
                .message-box {
                    display: inline-block;
                    background: white;
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 0 20px rgba(0,0,0,0.1);
                }
                h2 {
                    color: #4a90e2;
                    margin-bottom: 10px;
                }
                p {
                    font-size: 16px;
                    color: #444;
                }
            </style>
        </head>
        <body>
            <div class='message-box'>
                <h2>✅ OTP Sent to Your Email!</h2>
                <p>Please check your inbox, @$username.</p>
                <p>You will be redirected to OTP Verification in 5 seconds...</p>
            </div>
        </body>
        </html>
        ";
        exit();
    } catch (Exception $e) {
        echo "❌ Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo "❌ Email not found.";
}
?>
