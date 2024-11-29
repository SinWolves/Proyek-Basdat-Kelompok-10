const roomsContainer = document.getElementById("roomsContainer");
  const addRoomButton = document.getElementById("addRoomButton");

  // Event listener untuk menambah kamar baru
  addRoomButton.addEventListener("click", function () {
    // Membuat elemen baru untuk entri kamar
    const newRoomEntry = document.createElement("div");
    newRoomEntry.className = "d-flex align-items-center mb-2 room-entry";

    // Menambahkan elemen baru untuk dropdown tipe kamar dan jumlah kamar
    newRoomEntry.innerHTML = `
      <div class="d-flex flex-column flex-grow-1 me-2">
        <label class="section-title" for="roomType">Room Type</label>
        <select class="form-select" name="roomType">
          <option value="" disabled selected>Select Room Type</option>
          <option value="executive">Executive Suite</option>
          <option value="luxury">Luxury Suite</option>
          <option value="presidential">Presidential Suite</option>
        </select>
      </div>
      <div class="d-flex flex-column flex-grow-1 me-2">
        <label class="section-title" for="roomQuantity">Quantity</label>
        <input type="number" class="form-control" name="roomQuantity" placeholder="Enter Quantity" min="1">
      </div>
      <button type="button" class="btn btn-danger btn-sm remove-room">Remove</button>
    `;

    // Menambahkan elemen baru ke dalam kontainer
    roomsContainer.appendChild(newRoomEntry);

    // tombol Remove
    const removeButton = newRoomEntry.querySelector(".remove-room");
    removeButton.addEventListener("click", function () {
      newRoomEntry.remove();
    });
  });

  // fungsi Remove 
  document.querySelectorAll(".remove-room").forEach(button => {
    button.addEventListener("click", function () {
      button.parentElement.remove();
    });
  });



  // Harga per malam untuk setiap tipe kamar
  const roomPrices = {
    executive: 3500000,
    luxury: 7500000,
    presidential: 15000000
  };

  // enghitung lama menginap
  function calculateNights(checkIn, checkOut) {
    const checkInDate = new Date(checkIn);
    const checkOutDate = new Date(checkOut);
    const timeDifference = checkOutDate - checkInDate;
    return timeDifference > 0 ? timeDifference / (1000 * 60 * 60 * 24) : 0; // Konversi ke jumlah hari
  }

  // menghitung total harga
  function calculateTotalPrice() {
    const checkIn = document.getElementById("checkInDate").value;
    const checkOut = document.getElementById("checkOutDate").value;
    const nights = calculateNights(checkIn, checkOut);

    if (nights <= 0) {
      document.getElementById("TotalPrice").value = "Invalid Dates";
      return;
    }

    let totalPrice = 0;
    const roomEntries = document.querySelectorAll(".room-entry");

    roomEntries.forEach((entry) => {
      const roomType = entry.querySelector("select[name='roomType']").value;
      const roomQuantity = parseInt(entry.querySelector("input[name='roomQuantity']").value) || 0;

      if (roomType && roomQuantity > 0) {
        totalPrice += roomPrices[roomType] * roomQuantity * nights;
      }
    });

    // total harga ke input Total Price
    document.getElementById("TotalPrice").value = `IDR ${totalPrice.toLocaleString("id-ID")}`;
  }

  //  perhitungan otomatis
  document.getElementById("checkInDate").addEventListener("change", calculateTotalPrice);
  document.getElementById("checkOutDate").addEventListener("change", calculateTotalPrice);
  document.getElementById("roomsContainer").addEventListener("input", calculateTotalPrice);

  // Emenambahkan kamar baru
  document.getElementById("addRoomButton").addEventListener("click", function () {
    const roomsContainer = document.getElementById("roomsContainer");

    const newRoomEntry = document.createElement("div");
    newRoomEntry.className = "d-flex align-items-center mb-2 room-entry";

    newRoomEntry.innerHTML = `
      <div class="d-flex flex-column flex-grow-1 me-2">
        <label class="section-title" for="roomType">Room Type</label>
        <select class="form-select" name="roomType">
          <option value="" disabled selected>Select Room Type</option>
          <option value="executive">Executive Suite</option>
          <option value="luxury">Luxury Suite</option>
          <option value="presidential">Presidential Suite</option>
        </select>
      </div>
      <div class="d-flex flex-column flex-grow-1">
        <label class="section-title" for="roomQuantity">Quantity</label>
        <input type="number" class="form-control" name="roomQuantity" placeholder="Enter Quantity" min="1">
      </div>
      <button type="button" class="btn btn-danger btn-sm remove-room">Remove</button>
    `;

    roomsContainer.appendChild(newRoomEntry);

    // tombol Remove
    const removeButton = newRoomEntry.querySelector(".remove-room");
    removeButton.addEventListener("click", function () {
      newRoomEntry.remove();
      calculateTotalPrice(); 
    });

    // input baru
    newRoomEntry.addEventListener("input", calculateTotalPrice);
  });