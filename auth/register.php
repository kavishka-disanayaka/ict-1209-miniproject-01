<?php
require_once '../includes/db.php';

$message = '';
$message_type = 'danger';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Password එක Encrypt (Hash) කිරීම
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepared Statement භාවිතයෙන් SQL Injection වලක්වා ගැනීම
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        try {
            if ($stmt->execute()) {
                $message = "Registration successful! <a href='login.php' class='fs-link-mango fw-bold'>Log in here</a>";
                $message_type = "success";
            }
        } catch (PDOException $e) {
            $message = "Email already exists or an error occurred!";
            $message_type = "danger";
        }
    } else {
        $message = "Please fill in all fields.";
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up — FlavorSync</title>
  <meta name="description" content="Create an account on FlavorSync — your advanced digital recipe book.">

  <!-- Bootstrap 5.3 CSS (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons (CDN) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts: Fraunces (display) + Plus Jakarta Sans (body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Custom Styles -->
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>

  <!-- ============================= REGISTER PAGE ============================= -->
  <main class="fs-auth-page">

    <a href="../index.php" class="fs-auth-back">
      <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <div class="fs-auth-shell">

      <!-- Left: brand / visual panel -->
      <div class="fs-auth-visual">
        <a href="../index.php" class="navbar-brand fs-brand">
          <i class="bi bi-egg-fried"></i> FlavorSync
        </a>

        <div class="fs-auth-visual__quote">
          <h2>Start cooking smarter today.</h2>
          <p>Create a free account to save custom recipes, generate automated shopping lists, and scale ingredients seamlessly.</p>
        </div>

        <ul class="fs-auth-visual__list">
          <li><i class="bi bi-check-circle-fill"></i> Save & bookmark your favourite meals</li>
          <li><i class="bi bi-check-circle-fill"></i> Dynamic ingredient scaling per serving</li>
          <li><i class="bi bi-check-circle-fill"></i> Voice read-aloud cook mode</li>
        </ul>
      </div>

      <!-- Right: register form panel -->
      <div class="fs-auth-form-panel">
        <h1>Create account</h1>
        <p class="fs-section-sub">Join FlavorSync and organize your kitchen.</p>

        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?> py-2 small mb-3"><?= $message ?></div>
        <?php endif; ?>

        <form action="register.php" method="post" novalidate>

          <!-- Username -->
          <div class="mb-3">
            <label for="registerUsername" class="fs-form-label">Username</label>
            <input type="text" class="form-control fs-form-control" id="registerUsername" name="username"
                   placeholder="Your name or handle" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="registerEmail" class="fs-form-label">Email address</label>
            <input type="email" class="form-control fs-form-control" id="registerEmail" name="email"
                   placeholder="you@example.com" required>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label for="registerPassword" class="fs-form-label">Password</label>
            <div class="fs-input-group">
              <input type="password" class="form-control fs-form-control" id="registerPassword" name="password"
                     placeholder="Create a strong password" required>
            </div>
          </div>

          <!-- Submit -->
          <button type="submit" class="btn btn-fs-primary mt-2">Sign Up</button>

          <!-- Divider -->
          <div class="fs-divider">or sign up with</div>

          <!-- Social login -->
          <div class="fs-social-login mb-3">
            <button type="button" class="btn-fs-social">
              <i class="bi bi-google"></i> Google
            </button>
            <button type="button" class="btn-fs-social">
              <i class="bi bi-facebook"></i> Facebook
            </button>
          </div>

          <p class="fs-auth-footer-text">
            Already have an account? <a href="login.php" class="fs-link-mango">Log in</a>
          </p>

        </form>
      </div>

    </div>

  </main>

  <!-- Bootstrap 5.3 JS Bundle (CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="../js/script.js"></script>

</body>
</html>