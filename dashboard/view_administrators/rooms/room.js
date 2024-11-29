let currentRoomID = 1; // Start with ID 1
let editingRow = null; // To keep track of the row being edited

// Room data (Initial setup)
const rooms = {
  executive: { empty: 65, total: 65 },
  luxury: { empty: 25, total: 25 },
  presidential: { empty: 10, total: 10 },
};

// Function to update the availability for a room type (increase or decrease)
function updateAvailability(type, change) {
  // Update the empty room count based on the change
  if (rooms[type].empty + change <= rooms[type].total && rooms[type].empty + change >= 0) {
    rooms[type].empty += change; // Add or subtract based on change
  } else {
    console.error(`Invalid operation: Cannot update empty rooms beyond total rooms.`);
  }
  document.getElementById(`${type}-empty`).value = rooms[type].empty;
}

// Update availability on initial load
document.getElementById('executive-empty').value = rooms.executive.empty;
document.getElementById('executive-total').value = rooms.executive.total;
document.getElementById('luxury-empty').value = rooms.luxury.empty;
document.getElementById('luxury-total').value = rooms.luxury.total;
document.getElementById('presidential-empty').value = rooms.presidential.empty;
document.getElementById('presidential-total').value = rooms.presidential.total;

// Event listener for adding rooms (new rooms or editing existing ones)
document.getElementById("addingRooms").addEventListener("click", function(event) {
  // Prevent form from submitting
  event.preventDefault();

  // Get values from the input fields and convert roomType to lowercase for case-insensitivity
  const roomType = document.getElementById("roomType").value.trim().toLowerCase();
  const roomPrice = document.getElementById("roomPrice").value;

  // Validate inputs
  if (roomType && roomPrice) {
    // Get the table body where new rows will be added
    const tableBody = document.querySelector("table tbody");

    // If editing an existing row
    if (editingRow) {
      // Get the previous room type from the row and adjust the availability for the old room type
      const previousRoomType = editingRow.querySelector("td").textContent.trim().toLowerCase();

      // Update the availability for the previous room type (increase the number of available rooms)
      updateAvailability(previousRoomType, 1); // Add back the previous room type

      // Update the row data (roomType and roomPrice)
      const cells = editingRow.querySelectorAll("td");
      cells[1].textContent = roomType;
      cells[2].textContent = roomPrice;

      // Update the availability for the new room type (decrease the number of available rooms)
      updateAvailability(roomType, -1); // Subtract from the new room type

      // Reset the form and change button text back to "Save"
      document.getElementById("roomType").value = "";
      document.getElementById("roomPrice").value = "";
      document.getElementById("addingRooms").textContent = "Save";

      // Clear the reference to the row being edited
      editingRow = null;
    } else {
      // If adding a new room (not editing)
      const newRow = document.createElement("tr");

      // Create and append table cells with the input data
      const idCell = document.createElement("td");
      idCell.textContent = currentRoomID;  // Set the auto-incremented ID
      newRow.appendChild(idCell);

      const typeCell = document.createElement("td");
      typeCell.textContent = roomType;
      newRow.appendChild(typeCell);

      const priceCell = document.createElement("td");
      priceCell.textContent = roomPrice;
      newRow.appendChild(priceCell);

      // Add action cell (Edit and Delete buttons)
      const actionCell = document.createElement("td");
      actionCell.innerHTML = `
        <button class="btn btn-warning btn-sm editBtn">Edit</button>
        <button class="btn btn-danger btn-sm deleteBtn">Delete</button>
      `;
      newRow.appendChild(actionCell);

      // Append the new row to the table
      tableBody.insertBefore(newRow, tableBody.lastElementChild);  // Insert before the "No more data available" row

      // Update the availability for the room type (decrease availability)
      updateAvailability(roomType, -1);

      // Increment the room ID for the next entry
      currentRoomID++;

      // Clear the form fields after adding the data
      document.getElementById("roomType").value = "";
      document.getElementById("roomPrice").value = "";
    }
  } else {
    alert("Please fill all fields.");
  }

  // Add functionality to Delete Button
  const deleteBtns = document.querySelectorAll(".deleteBtn");
  deleteBtns.forEach(btn => {
    btn.addEventListener("click", function() {
      const row = this.closest("tr");
      const cells = row.querySelectorAll("td");
      const roomType = cells[1].textContent.trim().toLowerCase();

      // Update availability for the room being deleted (increase the available rooms)
      updateAvailability(roomType, 1);

      // Remove the row from the table
      row.remove();
    });
  });

  // Add functionality to Edit Button
  const editBtns = document.querySelectorAll(".editBtn");
  editBtns.forEach(btn => {
    btn.addEventListener("click", function() {
      const row = this.closest("tr");
      const cells = row.querySelectorAll("td");

      // Fill the form fields with the current row data
      document.getElementById("roomType").value = cells[1].textContent;
      document.getElementById("roomPrice").value = cells[2].textContent;

      // Set the editing row reference
      editingRow = row;

      // Change the button text to "Update"
      document.getElementById("addingRooms").textContent = "Update";
    });
  });
});