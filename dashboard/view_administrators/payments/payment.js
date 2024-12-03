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
    const tableBody = document.querySelector("table tbody");

    if (editingRow) {
      const cells = editingRow.querySelectorAll("td");
      cells[1].textContent = paymentName;
      cells[2].textContent = paymentStatus;
      cells[3].textContent = paymentDate;

      document.getElementById("paymentName").value = "";
      document.getElementById("paymentStatus").value = "";
      document.getElementById("paymentDate").value = "";
      document.getElementById("addingPayment").textContent = "Save";

      editingRow = null;
    } else {
      const newRow = document.createElement("tr");

      const idCell = document.createElement("td");
      idCell.textContent = currentPaymentID;
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

      const actionCell = document.createElement("td");
      actionCell.innerHTML = `
        <button class="btn btn-warning btn-sm editBtn">Edit</button>
        <button class="btn btn-danger btn-sm deleteBtn">Delete</button>
      `;
      newRow.appendChild(actionCell);

      tableBody.insertBefore(newRow, tableBody.lastElementChild);
      currentPaymentID++;

      document.getElementById("paymentName").value = "";
      document.getElementById("paymentStatus").value = "";
      document.getElementById("paymentDate").value = "";
    }
  } else {
    alert("Please fill all fields.");
  }

  const deleteBtns = document.querySelectorAll(".deleteBtn");
  deleteBtns.forEach(btn => {
    btn.addEventListener("click", function() {
      const row = this.closest("tr");
      row.remove();
    });
  });

  const editBtns = document.querySelectorAll(".editBtn");
  editBtns.forEach(btn => {
    btn.addEventListener("click", function() {
      const row = this.closest("tr");
      const cells = row.querySelectorAll("td");

      document.getElementById("paymentName").value = cells[1].textContent;
      document.getElementById("paymentStatus").value = cells[2].textContent;
      document.getElementById("paymentDate").value = cells[3].textContent;

      editingRow = row;
      document.getElementById("addingPayment").textContent = "Update";
    });
  });
});

// ini digunakan unntuk menampilkan select pembayaran status 
document.getElementById('paymentStatus').addEventListener('change', function() {
  alert('Status pembayaran: ' + this.options[this.selectedIndex].text);
});