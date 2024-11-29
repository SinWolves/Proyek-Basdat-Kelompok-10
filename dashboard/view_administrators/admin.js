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
