// customer.js
document.addEventListener('DOMContentLoaded', function() {
    const addCustomerBtn = document.getElementById('toggleAddCustomerForm');
    const serviceFormContainer = document.getElementById('serviceFormContainer');
    
    // Initially hide the form
    serviceFormContainer.style.display = 'none';

    // Toggle form visibility
    addCustomerBtn.addEventListener('click', function() {
        if (serviceFormContainer.style.display === 'none') {
            serviceFormContainer.style.display = 'block';
            addCustomerBtn.textContent = 'Cancel';
        } else {
            serviceFormContainer.style.display = 'none';
            addCustomerBtn.textContent = '+ Add New Customer';
        }
    });
});