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

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $token = bin2hex(random_bytes(50));

    $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
    if (!$stmt) {
        die("Prepare failed (update): " . $conn->error);
    }
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'skadoshguy24@gmail.com';
        $mail->Password = 'rtnw shvx zjzj yhzk'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('skadoshguy24@gmail.com', 'Admin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link';
        $mail->Body = "Click the link below to reset your password:<br><br>
                       <a href='http://localhost/reset_password.php?token=$token'>Reset Password</a>";

        $mail->send();
        echo "Reset link sent! Check your email.";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo "Email not found.";
}
?>
