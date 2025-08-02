<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';
$otp = $_POST['otp'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($email) || empty($otp) || empty($new_password) || empty($confirm_password)) {
    die("❗ Please fill in all fields.");
}

if ($new_password !== $confirm_password) {
    die("❌ Passwords do not match.");
}

// Fetch OTP and expiry
$stmt = $conn->prepare("SELECT otp_code, otp_expires_at FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($db_otp, $expires_at);
$stmt->fetch();
$stmt->close();

if (!$db_otp || $otp !== $db_otp) {
    die("❌ Invalid OTP.");
}

if (strtotime($expires_at) < time()) {
    die("⏰ OTP has expired.");
}

// Update password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expires_at = NULL WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ss", $hashed_password, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "✅ Password reset successful. <a href='login.html'>Login now</a>";
} else {
    echo "❌ Failed to update password. Please try again.";
}
?>
