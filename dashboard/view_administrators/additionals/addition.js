let currentServiceID = 1; // Start with ID 1
let editingRow = null; // To keep track of the row being edited

document.getElementById("addingService").addEventListener("click", function(event) {
  // Prevent form from submitting
  event.preventDefault();

  // Get values from the input fields
  const serviceName = document.getElementById("serviceName").value;
  const serviceDescription = document.getElementById("serviceDescription").value;
  const servicePrice = document.getElementById("servicePrice").value;

  // Validate inputs
  if (serviceName && serviceDescription && servicePrice) {
    // Get the table body where new rows will be added
    const tableBody = document.querySelector("table tbody");

    // If editing an existing row
    if (editingRow) {
      // Update the cells in the existing row
      const cells = editingRow.querySelectorAll("td");
      cells[1].textContent = serviceName;
      cells[2].textContent = serviceDescription;
      cells[3].textContent = servicePrice;

      // Reset the form and button
      document.getElementById("serviceName").value = "";
      document.getElementById("serviceDescription").value = "";
      document.getElementById("servicePrice").value = "";
      document.getElementById("addingService").textContent = "Save";

      // Clear the reference to the row being edited
      editingRow = null;
    } else {
      // Create a new row if not editing an existing row
      const newRow = document.createElement("tr");

      // Create and append table cells with the input data and auto-incremented ID
      const idCell = document.createElement("td");
      idCell.textContent = currentServiceID;  // Set the auto-incremented ID
      newRow.appendChild(idCell);

      const nameCell = document.createElement("td");
      nameCell.textContent = serviceName;
      newRow.appendChild(nameCell);

      const descriptionCell = document.createElement("td");
      descriptionCell.textContent = serviceDescription;
      newRow.appendChild(descriptionCell);

      const priceCell = document.createElement("td");
      priceCell.textContent = servicePrice;
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

      // Increment the manager ID for the next entry
      currentServiceID++;

      // Clear the form fields after adding the data
      document.getElementById("serviceName").value = "";
      document.getElementById("serviceDescription").value = "";
      document.getElementById("servicePrice").value = "";
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
      document.getElementById("serviceName").value = cells[1].textContent;
      document.getElementById("serviceDescription").value = cells[2].textContent;
      document.getElementById("servicePrice").value = cells[3].textContent;

      // Set the editing row reference
      editingRow = row;

      // Change the "Save" button to "Update"
      document.getElementById("addingService").textContent = "Update";
    });
  });
});