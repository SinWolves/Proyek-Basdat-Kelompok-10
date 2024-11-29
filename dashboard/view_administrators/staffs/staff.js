let currentStaffID = 1; // Start with ID 1
let editingRow = null; // To keep track of the row being edited

document.getElementById("addingStaff").addEventListener("click", function(event) {
  // Prevent form from submitting
  event.preventDefault();

  // Get values from the input fields
  const staffName = document.getElementById("staffName").value;
  const departmentName = document.getElementById("departmentName").value;
  const staffNumber = document.getElementById("staffNumber").value;
  const staffAddress = document.getElementById("staffAddress").value;

  // Validate inputs
  if (staffName && departmentName && staffNumber && staffAddress) {
    // Get the table body where new rows will be added
    const tableBody = document.querySelector("table tbody");

    // If editing an existing row
    if (editingRow) {
      // Update the cells in the existing row
      const cells = editingRow.querySelectorAll("td");
      cells[1].textContent = staffName;
      cells[2].textContent = departmentName;
      cells[3].textContent = staffNumber;
      cells[4].textContent = staffAddress;

      // Reset the form and button
      document.getElementById("staffName").value = "";
      document.getElementById("departmentName").value = "";
      document.getElementById("staffNumber").value = "";
      document.getElementById("staffAddress").value = "";
      document.getElementById("addingStaff").textContent = "Save";

      // Clear the reference to the row being edited
      editingRow = null;
    } else {
      // Create a new row if not editing an existing row
      const newRow = document.createElement("tr");

      // Create and append table cells with the input data and auto-incremented ID
      const idCell = document.createElement("td");
      idCell.textContent = currentStaffID;  // Set the auto-incremented ID
      newRow.appendChild(idCell);

      const nameCell = document.createElement("td");
      nameCell.textContent = staffName;
      newRow.appendChild(nameCell);

      const departmentCell = document.createElement("td");
      departmentCell.textContent = departmentName;
      newRow.appendChild(departmentCell);

      const phoneCell = document.createElement("td");
      phoneCell.textContent = staffNumber;
      newRow.appendChild(phoneCell);

      const addressCell = document.createElement("td");
      addressCell.textContent = staffAddress;
      newRow.appendChild(addressCell);

      // Add action cell (Edit and Delete buttons)
      const actionCell = document.createElement("td");
      actionCell.innerHTML = `
        <button class="btn btn-warning btn-sm editBtn">Edit</button>
        <button class="btn btn-danger btn-sm deleteBtn">Delete</button>
      `;
      newRow.appendChild(actionCell);

      // Append the new row to the table
      tableBody.insertBefore(newRow, tableBody.lastElementChild);  // Insert before the "No more data available" row

      // Increment the staff ID for the next entry
      currentStaffID++;

      // Clear the form fields after adding the data
      document.getElementById("staffName").value = "";
      document.getElementById("departmentName").value = "";
      document.getElementById("staffNumber").value = "";
      document.getElementById("staffAddress").value = "";
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
      document.getElementById("staffName").value = cells[1].textContent;
      document.getElementById("departmentName").value = cells[2].textContent;
      document.getElementById("staffNumber").value = cells[3].textContent;
      document.getElementById("staffAddress").value = cells[4].textContent;

      // Set the editing row reference
      editingRow = row;

      // Change the "Save" button to "Update"
      document.getElementById("addingStaff").textContent = "Update";
    });
  });
});