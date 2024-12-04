let currentRoomID = 1; 
let editingRow = null; 

// Room data 
const rooms = {
  executive: { empty: 65, total: 65 },
  luxury: { empty: 25, total: 25 },
  presidential: { empty: 10, total: 10 },
};

// Function to update the availability for a room type 
function updateAvailability(type, change) {
  if (rooms[type].empty + change <= rooms[type].total && rooms[type].empty + change >= 0) {
    rooms[type].empty += change; // Add or subtract based on change
  } else {
    console.error(`Invalid operation: Cannot update empty rooms beyond total rooms.`);
  }
  document.getElementById(`${type}-empty`).value = rooms[type].empty;
}

// Initialize availability on page load
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("executive-empty").value = rooms.executive.empty;
  document.getElementById("executive-total").value = rooms.executive.total;
  document.getElementById("luxury-empty").value = rooms.luxury.empty;
  document.getElementById("luxury-total").value = rooms.luxury.total;
  document.getElementById("presidential-empty").value = rooms.presidential.empty;
  document.getElementById("presidential-total").value = rooms.presidential.total;

  const saveEditChanges = document.getElementById("saveEditChanges");
  const editRoomId = document.getElementById("editRoomId");
  const editRoomType = document.getElementById("editRoomType");
  const editRoomPrice = document.getElementById("editRoomPrice");

  // Function to reset the form
  function resetForm() {
    document.getElementById("roomType").value = "";
    document.getElementById("roomPrice").value = "";
    document.getElementById("addingRooms").textContent = "Save";
  }

  // Attach events to Edit and Delete buttons
  function attachButtonEvents() {
    document.querySelectorAll(".deleteBtn").forEach((btn) => {
      btn.addEventListener("click", function () {
        const row = this.closest("tr");
        const cells = row.querySelectorAll("td");
        const roomType = cells[1].textContent.trim().toLowerCase();

        updateAvailability(roomType, 1);
        row.remove();
      });
    });

    document.querySelectorAll(".editBtn").forEach((btn) => {
      btn.addEventListener("click", function () {
        const row = this.closest("tr");
        const cells = row.querySelectorAll("td");

        editingRow = row;

        editRoomId.value = cells[0].textContent.trim();
        editRoomType.value = cells[1].textContent.trim();
        editRoomPrice.value = cells[2].textContent.trim();

        const editModal = new bootstrap.Modal(document.getElementById("editModal"));
        editModal.show();
      });
    });
  }

  attachButtonEvents();

  // Save changes in modal
  saveEditChanges.addEventListener("click", () => {
    const roomId = editRoomId.value;
    const roomType = editRoomType.value.trim();
    const roomPrice = editRoomPrice.value.trim();

    if (roomType && roomPrice) {
      if (editingRow) {
        const cells = editingRow.querySelectorAll("td");
        cells[1].textContent = roomType;
        cells[2].textContent = roomPrice;

        // Fetch to send data to the server
        fetch("update_room.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `id=${roomId}&room=${encodeURIComponent(roomType)}&price=${encodeURIComponent(roomPrice)}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Success notification or log
              console.log("Room updated successfully");
            } else {
              alert("Failed to update room: " + data.error);
            }
          })
          .catch((err) => console.error("Error:", err));

        const editModal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
        editModal.hide();

        editingRow = null;
      } else {
        alert("Error: No row selected for editing.");
      }
    } else {
      alert("Please fill all fields.");
    }
  });
});
