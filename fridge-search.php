<?php
session_start();
require_once 'includes/db.php';

$matched_recipes = [];
$searched_ingredients = "";

// Search bar එකෙන් ingredients එවූ විට ක්‍රියාත්මක වන Backend Logic එක
if (isset($_GET['ingredients']) && !empty(trim($_GET['ingredients']))) {
    $searched_ingredients = trim($_GET['ingredients']);
    
    // User ඇතුළත් කළ ද්‍රව්‍ය Comma (,) මගින් වෙන් කරගැනීම
    $ingredients_list = array_map('trim', explode(',', $searched_ingredients));
    
    // Dynamic Parameterized SQL Query එක සකස් කිරීම
    $sql = "SELECT * FROM recipes WHERE ";
    $conditions = [];
    $params = [];

    foreach ($ingredients_list as $index => $ingredient) {
        $param_key = ":ingred_" . $index;
        $conditions[] = "ingredients LIKE " . $param_key;
        $params[$param_key] = '%' . $ingredient . '%';
    }

    $sql .= implode(' OR ', $conditions);
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $matched_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What's in My Fridge? — FlavorSync</title>
    <meta name="description" content="Find recipes using the ingredients you already have in your kitchen.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">

    <!-- Site styles -->
    <link rel="stylesheet" href="css/recipes.css">
    <link rel="stylesheet" href="css/style.css">
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
        <li class="nav-item"><a class="nav-link" href="recipes.php">Recipes</a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="fridge-search.php">Fridge Search</a></li>
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

<!-- ================= PAGE HEADER & SEARCH ================= -->
<header class="fs-page-header">
  <div class="container text-center">
    <span class="eyebrow" style="color:#F0C98A;">Ingredient-Aware Match</span>
    <h1 class="mt-2 mb-3">What's in your fridge?</h1>
    <p class="mb-4 mx-auto" style="max-width: 600px;">Enter the ingredients you have on hand, separated by commas. We’ll find meals you can cook right now without wasting food.</p>
    
    <form class="fs-search-bar mx-auto" style="max-width: 620px;" method="GET" action="fridge-search.php">
      <label for="ingredientSearch" class="visually-hidden">Search by ingredients</label>
      <input type="search" id="ingredientSearch" name="ingredients" placeholder="Type ingredients — e.g. “egg, rice, onion”" value="<?= htmlspecialchars($searched_ingredients) ?>" required>
      <button type="submit">Find Recipes</button>
    </form>
  </div>
</header>

<!-- ================= MAIN RESULTS SECTION ================= -->
<main class="container py-5">
    <?php if (!empty($matched_recipes)): ?>
        <div class="fs-results-bar mb-4">
            <span id="resultsCount"><strong><?= count($matched_recipes) ?></strong> matched recipes found</span>
        </div>

        <div class="row g-4">
            <?php foreach ($matched_recipes as $index => $recipe): ?>
                <div class="col-sm-6 col-lg-4">
                    <article class="fs-card h-100">
                        <div class="fs-card-media media-<?= ($index % 9) + 1 ?>">
                            <span class="fs-card-time">Smart Match</span>
                            <span><?= htmlspecialchars($recipe['title']) ?></span>
                        </div>
                        <div class="fs-card-body d-flex flex-column">
                            <h3><?= htmlspecialchars($recipe['title']) ?></h3>
                            <div class="fs-card-meta mb-2">
                                <span class="fs-stars">★ 4.6</span>
                                <span>·</span>
                                <span>Easy</span>
                            </div>
                            <div class="fs-card-tags mb-3">
                                <span class="fs-tag">Matched Ingredients</span>
                            </div>
                            <p class="small text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <strong>Ingredients:</strong> <?= htmlspecialchars($recipe['ingredients']) ?>
                            </p>
                            <a href="recipe-detail.php?id=<?= $recipe['id'] ?>" class="fs-card-btn mt-auto">View Recipe & Cook Mode →</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif (isset($_GET['ingredients'])): ?>
        <div class="fs-empty text-center py-5">
            <h3>No matching recipes found</h3>
            <p class="mb-4">We couldn't find any recipes containing <strong>"<?= htmlspecialchars($searched_ingredients) ?>"</strong>.</p>
            <a href="fridge-search.php" class="btn btn-fs-primary">Clear Search & Try Again</a>
        </div>
    <?php else: ?>
        <div class="text-center py-5 text-muted">
            <p class="fs-5">Enter your kitchen ingredients above to discover customized recipe ideas!</p>
        </div>
    <?php endif; ?>
</main>

<!-- ================= FOOTER ================= -->
<footer class="fs-footer mt-auto">
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
</body>
</html>