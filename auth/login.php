<?php
session_start();
require_once '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Prepared Statement මගින් User ව සොයා ගැනීම
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Password එක Verify කිරීම
        if ($user && password_verify($password, $user['password'])) {
            // Session Fixation වලින් ආරක්ෂා වීමට session_regenerate_id() භාවිතය
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Login වූ පසු Home Page (index.php) එකට Redirect කිරීම
            header("Location: ../index.php");
            exit();
        } else {
            $message = "Invalid email or password!";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — FlavorSync</title>
  <meta name="description" content="Log in to FlavorSync — your advanced digital recipe book.">

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

  <!-- ============================= LOGIN PAGE ============================= -->
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
          <h2>Cook smarter with what's already in your kitchen.</h2>
          <p>Log in to pick up your saved recipes, favourites, and pantry preferences right where you left off.</p>
        </div>

        <ul class="fs-auth-visual__list">
          <li><i class="bi bi-check-circle-fill"></i> Ingredient-aware smart search</li>
          <li><i class="bi bi-check-circle-fill"></i> Live recipe scaling</li>
          <li><i class="bi bi-check-circle-fill"></i> Hands-free guided cooking</li>
        </ul>
      </div>

      <!-- Right: login form panel -->
      <div class="fs-auth-form-panel">
        <h1>Welcome back</h1>
        <p class="fs-section-sub">Log in to continue to your recipe book.</p>

        <?php if ($message): ?>
            <div class="alert alert-danger py-2 small text-center mb-3"><?= $message ?></div>
        <?php endif; ?>

        <form action="login.php" method="post" novalidate>

          <!-- Email -->
          <div class="mb-3">
            <label for="loginEmail" class="fs-form-label">Email address</label>
            <input type="email" class="form-control fs-form-control" id="loginEmail" name="email"
                   placeholder="you@example.com" required>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label for="loginPassword" class="fs-form-label">Password</label>
            <div class="fs-input-group">
              <input type="password" class="form-control fs-form-control" id="loginPassword" name="password"
                     placeholder="Enter your password" required>
              <button type="button" class="fs-input-toggle" id="togglePassword" aria-label="Show password">
                <i class="bi bi-eye" id="togglePasswordIcon"></i>
              </button>
            </div>
          </div>

          <!-- Remember me / Forgot password -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check fs-form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="#" class="fs-link-mango">Forgot password?</a>
          </div>

          <!-- Submit -->
          <button type="submit" class="btn btn-fs-primary">Log In</button>

          <!-- Divider -->
          <div class="fs-divider">or continue with</div>

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
            Don't have an account? <a href="register.php" class="fs-link-mango">Sign up</a>
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