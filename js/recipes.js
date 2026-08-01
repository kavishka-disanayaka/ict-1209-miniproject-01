/* FlavorSync — Recipes page interactivity
   Category chips + diet/time/difficulty radios + search + sort
   all filter the same in-page card grid, no reload. */

document.addEventListener('DOMContentLoaded', () => {
  const grid = document.getElementById('recipeGrid');
  const cards = Array.from(grid.querySelectorAll('.recipe-card'));
  const resultsCount = document.getElementById('resultsCount');
  const emptyState = document.getElementById('emptyState');
  const searchInput = document.getElementById('recipeSearch');
  const sortSelect = document.getElementById('sortSelect');
  const categoryChips = document.querySelectorAll('.fs-chip[data-filter="category"]');

  function getActiveValue(name) {
    if (name === 'category') {
      const active = document.querySelector('.fs-chip[data-filter="category"].active');
      return active ? active.dataset.value : 'all';
    }
    const checked = document.querySelector(`input[name="${name}"]:checked`);
    return checked ? checked.value : 'all';
  }

  function sortVisible(visible) {
    const mode = sortSelect ? sortSelect.value : 'popular';
    const sorted = [...visible];

    if (mode === 'rating') {
      sorted.sort((a, b) => Number(b.dataset.rating) - Number(a.dataset.rating));
    } else if (mode === 'quick') {
      sorted.sort((a, b) => Number(a.dataset.time) - Number(b.dataset.time));
    }
    // 'popular' keeps original DOM order

    sorted.forEach(card => grid.appendChild(card));
  }

  // Defined on window BEFORE any listener references them, and before the
  // inline onsubmit="applyFilters(event)" / onclick="resetFilters()" in the HTML can fire.
  window.applyFilters = function (event) {
    if (event) event.preventDefault();

    const query = (searchInput?.value || '').trim().toLowerCase();
    const category = getActiveValue('category');
    const diet = getActiveValue('diet');
    const time = getActiveValue('time');
    const difficulty = getActiveValue('difficulty');

    let visible = [];

    cards.forEach(card => {
      const matchesQuery = !query || card.dataset.name.includes(query);
      const matchesCategory = category === 'all' || card.dataset.category === category;
      const matchesDiet = diet === 'all' || card.dataset.diet === diet;
      const matchesTime = time === 'all' || Number(card.dataset.time) <= Number(time);
      const matchesDifficulty = difficulty === 'all' || card.dataset.difficulty === difficulty;

      const isMatch = matchesQuery && matchesCategory && matchesDiet && matchesTime && matchesDifficulty;
      card.style.display = isMatch ? '' : 'none';
      if (isMatch) visible.push(card);
    });

    sortVisible(visible);

    resultsCount.innerHTML = `<strong>${visible.length}</strong> recipe${visible.length === 1 ? '' : 's'} found`;
    emptyState.classList.toggle('d-none', visible.length !== 0);
    grid.classList.toggle('d-none', visible.length === 0);

    return false;
  };

  window.resetFilters = function () {
    if (searchInput) searchInput.value = '';
    categoryChips.forEach(c => c.classList.remove('active'));
    document.querySelector('.fs-chip[data-filter="category"][data-value="all"]')?.classList.add('active');
    document.querySelectorAll('.fs-filter-panel input[value="all"]').forEach(r => (r.checked = true));
    if (sortSelect) sortSelect.value = 'popular';
    applyFilters();
  };

  // Now that applyFilters/resetFilters exist, wire up the listeners.

  // Category chips: single-select, toggled by click
  categoryChips.forEach(chip => {
    chip.addEventListener('click', () => {
      categoryChips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      applyFilters();
    });
  });

  // Radio groups (diet / time / difficulty) re-filter on change
  document.querySelectorAll('.fs-filter-panel input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', applyFilters);
  });

  if (searchInput) searchInput.addEventListener('input', applyFilters);
  if (sortSelect) sortSelect.addEventListener('change', applyFilters);

  // pre-select category chip if page was opened as recipes.html?category=breakfast
  const urlCategory = new URLSearchParams(window.location.search).get('category');
  if (urlCategory) {
    const matchingChip = document.querySelector(`.fs-chip[data-filter="category"][data-value="${urlCategory}"]`);
    if (matchingChip) {
      categoryChips.forEach(c => c.classList.remove('active'));
      matchingChip.classList.add('active');
    }
  }
 
  applyFilters();
});