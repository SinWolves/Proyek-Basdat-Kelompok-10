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

let currentPaymentID = 1; // Start with ID 1
let editingRow = null; // To keep track of the row being edited

document.getElementById("addingPayment").addEventListener("click", function(event) {
  // Prevent form from submitting
  event.preventDefault();

  // Get values from the input fields
  const paymentName = document.getElementById("paymentName").value;
  const paymentStatus = document.getElementById("paymentStatus").value;
  const paymentDate = document.getElementById("paymentDate").value;

  // Validate inputs
  if (paymentName && paymentStatus && paymentDate) {
    // Get the table body where new rows will be added
    const tableBody = document.querySelector("table tbody");

    // If editing an existing row
    if (editingRow) {
      // Update the cells in the existing row
      const cells = editingRow.querySelectorAll("td");
      cells[1].textContent = paymentName;
      cells[2].textContent = paymentStatus;
      cells[3].textContent = paymentDate;

      // Reset the form and button
      document.getElementById("paymentName").value = "";
      document.getElementById("paymentStatus").value = "";
      document.getElementById("paymentDate").value = "";
      document.getElementById("addingPayment").textContent = "Save";

      // Clear the reference to the row being edited
      editingRow = null;
    } else {
      // Create a new row if not editing an existing row
      const newRow = document.createElement("tr");

      // Create and append table cells with the input data and auto-incremented ID
      const idCell = document.createElement("td");
      idCell.textContent = currentPaymentID;  // Set the auto-incremented ID
      newRow.appendChild(idCell);

      const nameCell = document.createElement("td");
      nameCell.textContent = paymentName;
      newRow.appendChild(nameCell);

      const statusCell = document.createElement("td");
      statusCell.textContent = paymentStatus;
      newRow.appendChild(statusCell);

      const dateCell = document.createElement("td");
      dateCell.textContent = paymentDate;
      newRow.appendChild(dateCell);

      // Add action cell (Edit and Delete buttons)
      const actionCell = document.createElement("td");
      actionCell.innerHTML = `
        <button class="btn btn-warning btn-sm editBtn">Edit</button>
        <button class="btn btn-danger btn-sm deleteBtn">Delete</button>
      `;
      newRow.appendChild(actionCell);

      // Append the new row to the table
      tableBody.insertBefore(newRow, tableBody.lastElementChild);  // Insert before the "No more data available" row

      // Increment the manager ID for the next entry
      currentPaymentID++;

      // Clear the form fields after adding the data
      document.getElementById("paymentName").value = "";
      document.getElementById("paymentStatus").value = "";
      document.getElementById("paymentDate").value = "";
    }
  } else {
    alert("Please fill all fields.");
  }

  // Add functionality to Delete Button
  const deleteBtns = document.querySelectorAll(".deleteBtn");
  deleteBtns.forEach(btn => {
    btn.addEventListener("click", function() {
      const row = this.closest("tr");
      row.remove();  // Remove the row from the table
    });
  });

  // Add functionality to Edit Button
  const editBtns = document.querySelectorAll(".editBtn");
  editBtns.forEach(btn => {
    btn.addEventListener("click", function() {
      const row = this.closest("tr");
      const cells = row.querySelectorAll("td");

      // Fill the form fields with the current row data
      document.getElementById("paymentName").value = cells[1].textContent;
      document.getElementById("paymentStatus").value = cells[2].textContent;
      document.getElementById("paymentDate").value = cells[3].textContent;

      // Set the editing row reference
      editingRow = row;

      // Change the "Save" button to "Update"
      document.getElementById("addingPayment").textContent = "Update";
    });
  });
});