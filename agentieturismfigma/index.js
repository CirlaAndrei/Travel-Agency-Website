
document.querySelectorAll('.custom-dropdown').forEach(function(dropdown) {
    var selected = dropdown.querySelector('.dropdown-selected');
    var options = dropdown.querySelector('.dropdown-options');
    var hiddenInput = dropdown.querySelector('input[type="hidden"]');

    selected.addEventListener('click', function() {
      dropdown.classList.toggle('active');
    });

    options.querySelectorAll('li').forEach(function(option) {
      option.addEventListener('click', function() {
        selected.textContent = option.textContent;
        hiddenInput.value = option.dataset.value;
        dropdown.classList.remove('active');
      });
    });

    document.addEventListener('click', function(e) {
      if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
      }
    });
  });
document.addEventListener('click', function(event) {
        var toggle = document.getElementById('toggle');
        var nav = document.querySelector('.nav');

        // If menu is open and click is outside the nav
        if (toggle.checked && !nav.contains(event.target)) {
            toggle.checked = false;
        }
    });
