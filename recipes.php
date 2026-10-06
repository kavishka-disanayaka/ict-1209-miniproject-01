<?php
session_start();
require_once 'includes/db.php';

// Database එකෙන් සියලුම Recipes ලබා ගැනීම
$stmt = $conn->prepare("SELECT * FROM recipes ORDER BY id DESC");
$stmt->execute();
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_recipes = count($recipes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recipes — FlavorSync</title>
<meta name="description" content="Browse every FlavorSync recipe — filter by diet, time and difficulty, or search by name.">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">

<!-- Site styles -->
<link rel="stylesheet" href="css/recipes.css">
</head>
<body>

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
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="recipes.php">Recipes</a></li>
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

<!-- ================= PAGE HEADER ================= -->
<header class="fs-page-header">
  <div class="container">
    <span class="eyebrow" style="color:#F0C98A;">The full collection</span>
    <h1 class="mt-2 mb-3">Every recipe, one shelf.</h1>
    <p class="mb-4">Browse the whole FlavorSync library, or narrow it down by diet, cook time and difficulty until only dinner is left standing.</p>
    <form class="fs-search-bar" style="max-width:560px;" role="search" onsubmit="return applyFilters(event)">
      <label for="recipeSearch" class="visually-hidden">Search recipes by name</label>
      <input type="search" id="recipeSearch" placeholder="Search recipes — “egg”, “kottu”, “stir fry”…">
      <button type="submit">Search</button>
    </form>
  </div>
</header>

<!-- ================= MAIN ================= -->
<main class="container py-5">
  <div class="row g-4">

    <!-- ---------- Filters sidebar ---------- -->
    <aside class="col-lg-3">
      <div class="fs-filter-panel" style="position:sticky; top:96px;">
        <h2>Filter recipes</h2>

        <fieldset class="fs-filter-group">
          <legend>Category</legend>
          <div class="d-flex flex-wrap gap-2">
            <button type="button" class="fs-chip active" data-filter="category" data-value="all">All</button>
            <button type="button" class="fs-chip" data-filter="category" data-value="breakfast">Breakfast</button>
            <button type="button" class="fs-chip" data-filter="category" data-value="lunch">Lunch</button>
            <button type="button" class="fs-chip" data-filter="category" data-value="dinner">Dinner</button>
            <button type="button" class="fs-chip" data-filter="category" data-value="dessert">Dessert</button>
          </div>
        </fieldset>

        <fieldset class="fs-filter-group">
          <legend>Diet</legend>
          <label class="fs-radio"><input type="radio" name="diet" value="all" checked> Any diet</label>
          <label class="fs-radio"><input type="radio" name="diet" value="veg"> Vegetarian</label>
          <label class="fs-radio"><input type="radio" name="diet" value="nonveg"> Non-veg</label>
        </fieldset>

        <fieldset class="fs-filter-group">
          <legend>Time</legend>
          <label class="fs-radio"><input type="radio" name="time" value="all" checked> Any time</label>
          <label class="fs-radio"><input type="radio" name="time" value="20"> Under 20 min</label>
          <label class="fs-radio"><input type="radio" name="time" value="40"> Under 40 min</label>
          <label class="fs-radio"><input type="radio" name="time" value="60"> 60 min +</label>
        </fieldset>

        <fieldset class="fs-filter-group">
          <legend>Difficulty</legend>
          <label class="fs-radio"><input type="radio" name="difficulty" value="all" checked> Any level</label>
          <label class="fs-radio"><input type="radio" name="difficulty" value="easy"> Easy</label>
          <label class="fs-radio"><input type="radio" name="difficulty" value="medium"> Medium</label>
          <label class="fs-radio"><input type="radio" name="difficulty" value="hard"> Hard</label>
        </fieldset>

        <button type="button" class="btn btn-fs-primary w-100 mt-1" onclick="resetFilters()">Reset filters</button>
      </div>
    </aside>

    <!-- ---------- Results ---------- -->
    <section class="col-lg-9">
      <div class="fs-results-bar">
        <span id="resultsCount"><strong><?= $total_recipes ?></strong> recipes found</span>
        <div class="d-flex align-items-center gap-2">
          <label for="sortSelect" class="small fw-semibold mb-0 text-nowrap" style="color:var(--ink-soft);">Sort:</label>
          <select id="sortSelect" class="fs-sort-select" onchange="applyFilters()">
            <option value="popular">Most popular</option>
            <option value="rating">Highest rated</option>
            <option value="quick">Quickest first</option>
          </select>
        </div>
      </div>

      <div class="row g-4" id="recipeGrid">
        <?php if ($total_recipes > 0): ?>
          <?php foreach ($recipes as $index => $recipe): ?>
            <!-- Dynamic Card -->
            <div class="col-sm-6 col-xl-4 recipe-card" 
                 data-name="<?= htmlspecialchars(strtolower($recipe['title'])) ?>" 
                 data-category="<?= htmlspecialchars($recipe['category'] ?? 'dinner') ?>" 
                 data-diet="<?= htmlspecialchars($recipe['diet'] ?? 'veg') ?>" 
                 data-time="<?= htmlspecialchars($recipe['time'] ?? '20') ?>" 
                 data-difficulty="<?= htmlspecialchars($recipe['difficulty'] ?? 'easy') ?>" 
                 data-rating="<?= htmlspecialchars($recipe['rating'] ?? '4.5') ?>">
              <article class="fs-card">
                <div class="fs-card-media media-<?= ($index % 9) + 1 ?>">
                  <span class="fs-card-time"><?= htmlspecialchars($recipe['time'] ?? '20') ?> min</span>
                  <span><?= htmlspecialchars($recipe['title']) ?></span>
                </div>
                <div class="fs-card-body">
                  <h3><?= htmlspecialchars($recipe['title']) ?></h3>
                  <div class="fs-card-meta"><span class="fs-stars">★ <?= htmlspecialchars($recipe['rating'] ?? '4.5') ?></span><span>·</span><span style="text-transform: capitalize;"><?= htmlspecialchars($recipe['difficulty'] ?? 'easy') ?></span></div>
                  <div class="fs-card-tags"><span class="fs-tag">Ingredients</span></div>
                  <p class="small text-muted mt-2 mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    <?= htmlspecialchars($recipe['ingredients']) ?>
                  </p>
                  <a href="recipe-detail.php?id=<?= $recipe['id'] ?>" class="fs-card-btn mt-3">View Recipe →</a>
                </div>
              </article>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Empty state -->
      <div class="fs-empty <?= $total_recipes > 0 ? 'd-none' : '' ?>" id="emptyState">
        <h3>No recipes match that combination</h3>
        <p class="mb-3">Try clearing a filter or searching a broader ingredient or dish name.</p>
        <button type="button" class="btn btn-fs-primary" onclick="resetFilters()">Reset filters</button>
      </div>

      <nav class="fs-pagination" aria-label="Recipe pages">
        <button type="button" class="active" aria-current="page">1</button>
        <button type="button">2</button>
        <button type="button">3</button>
        <button type="button" aria-label="Next page">›</button>
      </nav>
    </section>
  </div>
</main>

<!-- ================= FOOTER ================= -->
<footer class="fs-footer">
  <div class="container">
    <div class="row gy-4">
      <div class="col-md-4">
        <h5>FlavorSync</h5>
        <p class="small mt-2 mb-0">Cook smarter with what you already have. Ingredient-aware search, live scaling, and hands-free guided cooking in one app.</p>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="text-white fw-bold small text-uppercase mb-3">Explore</h6>
        <ul class="list-unstyled small d-flex flex-column gap-2">
          <li><a href="recipes.php">All Recipes</a></li>
          <li><a href="fridge-search.php">Fridge Search</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="text-white fw-bold small text-uppercase mb-3">About</h6>
        <ul class="list-unstyled small d-flex flex-column gap-2">
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="text-white fw-bold small text-uppercase mb-3">Follow along</h6>
        <div class="d-flex gap-2">
          <a href="#" class="fs-social" aria-label="Facebook">FB</a>
          <a href="#" class="fs-social" aria-label="Instagram">IG</a>
          <a href="#" class="fs-social" aria-label="X / Twitter">X</a>
        </div>
      </div>
    </div>
    <hr class="my-4">
    <p class="small mb-0 text-center">© 2026 FlavorSync. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="js/recipes.js"></script>
</body>
</html>