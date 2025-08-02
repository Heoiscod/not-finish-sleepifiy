<?php
$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_POST['token'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    die("Passwords do not match.");
}

$hashed = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
$stmt->bind_param("ss", $hashed, $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Password updated successfully. <a href='login.html'>Login now</a>";
} else {
    echo "Invalid or expired token.";
}
?>
