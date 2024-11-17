// Toggle the sidebar and overlay
document.getElementById('menu-toggle').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar'); 
    sidebar.classList.toggle('active'); 
    overlay.classList.toggle('active'); 
});

// Close the sidebar when clicking the close icon
document.querySelector('.close-icon').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar'); 
    const overlay = document.querySelector('.overlay'); 
    sidebar.classList.remove('active'); 
    overlay.classList.remove('active'); 
});

// Close the sidebar when clicking on the overlay
document.querySelector('.overlay').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar'); 
    const overlay = document.querySelector('.overlay'); 
    sidebar.classList.remove('active'); 
    overlay.classList.remove('active'); 
});
