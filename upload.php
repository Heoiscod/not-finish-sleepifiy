<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["musicFile"])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["musicFile"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if ($fileType != "mp3") {
        echo "Only MP3 files are allowed.";
        exit;
    }

    if (move_uploaded_file($_FILES["musicFile"]["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO music_uploads (filename, filepath, uploaded_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $fileName, $targetFile);

        if ($stmt->execute()) {
            echo "File uploaded and saved successfully.";
        } else {
            echo "Failed to save file in database.";
        }

        $stmt->close();
    } else {
        echo "Failed to upload file.";
    }

    $conn->close();
}
?>
