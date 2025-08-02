<?php
session_start();

$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['account_type'] = $user['account_type'];

        // Redirect based on account type
        if ($user['account_type'] === 'admin') {
            header("Location: AdminPanel.php");
        } else {
            header("Location: WelcomePage.php");
        }
        exit();
    } else {
        header("Location: Login.html?error=invalid");
        exit();
    }
} else {
    header("Location: Login.html?error=invalid");
    exit();
}


