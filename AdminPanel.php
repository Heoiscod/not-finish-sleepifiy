<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="Styles/adminpanel.css">
</head>
<body>

  <header>
    <h1>Admin Panel</h1>
    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
  </header>

  <div class="container">

    <!-- Upload Music Section -->
    <div class="section">
      <h2>Upload Music (MP3)</h2>
      <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="musicFile" accept=".mp3" required>
        <button type="submit" class="upload-btn">Upload</button>
      </form>                                           
    </div>

    <!-- Editable Panel -->
    <div class="section">
      <h2>Edit Panel</h2>
      <div class="editable-panel" contenteditable="true">
        This is an editable panel. You can change this content anytime.
      </div>
    </div>

    <!-- Registered Users -->
    <div class="section">
      <h2>Registered Users</h2>
      <?php include 'fetch_users.php'; ?>
    </div>

  </div>

</body>
</html>
