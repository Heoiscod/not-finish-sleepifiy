<?php
include 'db.php';

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $token = $_POST['token'];

    if ($new_password !== $confirm) {
        die("Passwords don't match.");
    }

    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($email, $expires_at);
    $stmt->fetch();

    if (!$email || strtotime($expires_at) < time()) {
        die("Token invalid or expired.");
    }

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hashed, $email);
    $update->execute();

    $conn->query("DELETE FROM password_resets WHERE email = '$email'");

    echo "Password reset successful. <a href='login.html'>Login</a>";
    exit();
}
?>

<!-- Reset Form -->
<form method="POST">
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
  <label>New Password:</label>
  <input type="password" name="password" required>
  <label>Confirm Password:</label>
  <input type="password" name="confirm" required>
  <button type="submit">Reset Password</button>
</form>
