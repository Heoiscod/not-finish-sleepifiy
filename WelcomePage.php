<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sleepify - Music Library</title>
  <link rel="stylesheet" href="Styles/welcome.css">
</head>
<body>
  <!-- Gradient Background Layer -->
  <div class="gradient-background">
    <div class="gradient-sphere sphere-1"></div>
    <div class="gradient-sphere sphere-2"></div>
    <div class="gradient-sphere sphere-3"></div>
    <div class="glow"></div>
    <div class="grid-overlay"></div>
    <div class="noise-overlay"></div>
    <div class="particles-container" id="particles-container"></div>
  </div>

  <!-- Actual Content Layer -->
  <div class="content-container">
    <div class="logout-container">
      <form action="logout.php" method="post">
        <button type="submit" class="logout-button">Logout</button>
      </form>
    </div>

    <div class="header">
      <h1>Sleepify 🎵</h1>
      <p class="welcome-message">Welcome, <strong>@<?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
    </div>

    <div class="music-library">
      <h2>🎶 Music Library</h2>
      <ul class="song-list">
        <li>
          <span>1. Dreamscape</span>
          <audio controls>
            <source src="music/dreamscape.mp3" type="audio/mp3">
            Your browser does not support the audio element.
          </audio>
        </li>
        <li>
          <span>2. Rainy Night</span>
          <audio controls>
            <source src="music/rainy_night.mp3" type="audio/mp3">
            Your browser does not support the audio element.
          </audio>
        </li>
        <li>
          <span>3. Lullaby Waves</span>
          <audio controls>
            <source src="music/lullaby_waves.mp3" type="audio/mp3">
            Your browser does not support the audio element.
          </audio>
        </li>
      </ul>
    </div>
  </div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>