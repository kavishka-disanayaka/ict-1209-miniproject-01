<?php
session_start();
require_once 'includes/db.php';

$recipe = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$recipe) {
    header("Location: recipes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($recipe['title']) ?> — FlavorSync</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- Custom Site Styles -->
  <link rel="stylesheet" href="css/recipes.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg fs-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 2C9 6 7 9 7 12.5C7 16.09 9.24 19 12 19C14.76 19 17 16.09 17 12.5C17 9 15 6 12 2Z" fill="#E8A33D"/>
        <path d="M12 8C10.5 10 9.5 11.5 9.5 13.3C9.5 15.3 10.6 17 12 17" stroke="#2F5233" stroke-width="1.4" stroke-linecap="round"/>
      </svg>
      FlavorSync
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#fsNav" aria-controls="fsNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="fsNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="recipes.php">Recipes</a></li>
        <li class="nav-item"><a class="nav-link" href="fridge-search.php">Fridge Search</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['username'])): ?>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <a class="btn-login" href="auth/logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
          </li>
        <?php else: ?>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <a class="btn-login" href="auth/login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ================= PAGE HEADER / HERO ================= -->
<header class="fs-page-header">
  <div class="container">
    <a href="recipes.php" class="text-decoration-none fw-semibold small mb-3 d-inline-flex align-items-center gap-1" style="color:#F0C98A;">
        <i class="bi bi-arrow-left"></i> Back to all recipes
    </a>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h1 class="mt-1 mb-2 text-white" style="font-family: 'Fraunces', serif; font-size: 2.5rem;"><?= htmlspecialchars($recipe['title']) ?></h1>
            <div class="d-flex align-items-center gap-3 text-white-50 small">
                <span><i class="bi bi-clock me-1"></i> 25 mins prep</span>
                <span>·</span>
                <span><i class="bi bi-bar-chart me-1"></i> Easy Level</span>
                <span>·</span>
                <span class="text-warning fw-bold"><i class="bi bi-star-fill me-1"></i> 4.8 (120 reviews)</span>
            </div>
        </div>
        <div>
            <span class="badge rounded-pill px-3 py-2 fs-6 fw-bold" style="background-color: #E8A33D; color: #1C241B;">
                <i class="bi bi-fire me-1"></i> Popular Meal
            </span>
        </div>
    </div>
  </div>
</header>

<!-- ================= MAIN DETAILS SECTION ================= -->
<main class="container py-5">
  <div class="row g-4">

    <!-- Left Column: Media & Scalable Ingredients -->
    <div class="col-lg-5">
      <div class="fs-card p-0 mb-4 overflow-hidden border-0 shadow-sm rounded-4">
        <!-- Visual Media Box matching site design -->
        <div class="fs-card-media media-1 p-5 text-center d-flex align-items-center justify-content-center position-relative" style="min-height: 220px;">
          <span class="fs-card-time position-absolute top-0 end-0 m-3"><i class="bi bi-pie-chart-fill me-1"></i> Interactive</span>
          <h2 class="text-white fw-bold display-6 m-0" style="font-family: 'Fraunces', serif; drop-shadow: 0 2px 4px rgba(0,0,0,0.3);"><?= htmlspecialchars($recipe['title']) ?></h2>
        </div>

        <div class="p-4 bg-white">
          <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
            <div>
              <h3 class="h5 fw-bold mb-0" style="font-family: 'Fraunces', serif; color: var(--ink-main, #212529);">Ingredients</h3>
              <small class="text-muted">Live serving size scaler</small>
            </div>
            
            <!-- Serving Scaler Widget -->
            <div class="d-flex align-items-center gap-2 bg-light p-1 rounded-pill border">
              <button type="button" class="btn btn-sm btn-white rounded-circle shadow-sm border px-2 py-0 fw-bold" onclick="scaleIngredients(-1)">-</button>
              <span id="servings-count" class="fw-bold px-2 text-dark">1</span>
              <button type="button" class="btn btn-sm btn-white rounded-circle shadow-sm border px-2 py-0 fw-bold" onclick="scaleIngredients(1)">+</button>
            </div>
          </div>

          <!-- Base Ingredients Display -->
          <div class="p-3 rounded-3 mb-3" style="background-color: #FAF9F6; border: 1px dashed #E8A33D;">
            <span class="fs-tag mb-2 d-inline-block">Base Ingredients</span>
            <p id="ingredients-text" class="fw-semibold text-dark mb-0 fs-6" style="line-height: 1.7;">
              <?= htmlspecialchars($recipe['ingredients']) ?>
            </p>
          </div>

          <button type="button" class="btn btn-outline-secondary w-100 btn-sm rounded-3 py-2 fw-semibold">
            <i class="bi bi-cart-plus me-1"></i> Add to Shopping List
          </button>
        </div>
      </div>
    </div>

    <!-- Right Column: Step-by-Step Cook Mode & Voice Assistant -->
    <div class="col-lg-7">
      <div class="fs-card p-4 p-md-5 border-0 shadow-sm rounded-4 bg-white h-100 d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <span class="eyebrow text-uppercase fw-bold" style="color:#E8A33D; letter-spacing:1px; font-size: 0.8rem;">Hands-Free Mode</span>
            <h2 class="h3 fw-bold m-0" style="font-family: 'Fraunces', serif;">Interactive Cook Mode</h2>
          </div>
          <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill">
            <i class="bi bi-mic-fill me-1"></i> Voice Enabled
          </span>
        </div>
        
        <p class="text-muted small mb-4">Follow instructions step-by-step or let Voice Read-Aloud guide you while cooking[cite: 1].</p>

        <!-- Instructions Box -->
        <div class="p-4 rounded-4 mb-4 border flex-grow-1" style="background-color: #FAF9F6; border-left: 5px solid #2F5233 !important;">
          <h4 class="h6 text-uppercase fw-bold text-muted mb-2"><i class="bi bi-list-check me-1"></i> Cooking Method</h4>
          <p id="instruction-text" class="fs-5 text-dark mb-0 fw-medium" style="line-height: 1.8;">
            <?= htmlspecialchars($recipe['instructions']) ?>
          </p>
        </div>

        <!-- Voice Action Button -->
        <button type="button" class="btn btn-fs-primary w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="readAloud()">
          <i class="bi bi-volume-up-fill fs-5"></i> Read Aloud (Voice Assistant)
        </button>
      </div>
    </div>

  </div>
</main>

<!-- ================= FOOTER ================= -->
<footer class="fs-footer mt-auto">
  <div class="container text-center">
    <p class="small mb-0 text-white-50">© 2026 FlavorSync. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
    let currentServings = 1;

    function scaleIngredients(change) {
        if (currentServings + change >= 1) {
            currentServings += change;
            document.getElementById('servings-count').innerText = currentServings;
        }
    }

    function readAloud() {
        const text = document.getElementById('instruction-text').innerText;
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.rate = 0.9;
            window.speechSynthesis.speak(utterance);
        } else {
            alert("Speech Synthesis is not supported in your browser.");
        }
    }
</script>
</body>
</html>