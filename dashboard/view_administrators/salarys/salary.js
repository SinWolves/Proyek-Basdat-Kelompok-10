const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.overlay');
const closeIcon = document.querySelector('.close-icon');


menuToggle.addEventListener('click', function () {
  sidebar.classList.toggle('active'); 
  overlay.classList.toggle('active'); 
});


closeIcon.addEventListener('click', function () {
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
});

overlay.addEventListener('click', function () {
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
});