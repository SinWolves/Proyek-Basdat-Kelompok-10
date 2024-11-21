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


const editHotelBtn = document.getElementById('editHotelBtn');
const hotelNameInput = document.getElementById('hotelName');

editHotelBtn.addEventListener('click', function () {
  if (hotelNameInput.hasAttribute('readonly')) {
    hotelNameInput.removeAttribute('readonly'); 
    editHotelBtn.textContent = 'Save'; 
  } else {
    hotelNameInput.setAttribute('readonly', 'readonly'); 
    editHotelBtn.textContent = 'Edit'; 
  }
});


const editAddressBtn = document.getElementById('editAddressBtn');
const hotelAddressInput = document.getElementById('hotelAddress');

editAddressBtn.addEventListener('click', function () {
  if (hotelAddressInput.hasAttribute('readonly')) {
    hotelAddressInput.removeAttribute('readonly'); 
    editAddressBtn.textContent = 'Save'; 
  } else {
    hotelAddressInput.setAttribute('readonly', 'readonly'); 
    editAddressBtn.textContent = 'Edit'; 
  }
});
