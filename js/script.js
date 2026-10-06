// FlavorSync — basic page interactions
 
document.addEventListener('DOMContentLoaded', function () {
 
  // Category chips: single-select toggle
  var chips = document.querySelectorAll('.chip');
 
  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chips.forEach(function (c) {
        c.classList.remove('active');
      });
      chip.classList.add('active');
    });
  });
 
});