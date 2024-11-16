// Toggle the sidebar and overlay
document.getElementById('menu-toggle').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar'); // Select sidebar
    const overlay = document.querySelector('.overlay'); // Select overlay
    sidebar.classList.toggle('show'); // Toggle the 'show' class
    overlay.classList.toggle('show-overlay'); // Toggle the 'show-overlay' class
});

// Close the sidebar when clicking the close icon
document.querySelector('.close-icon').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar'); // Select sidebar
    const overlay = document.querySelector('.overlay'); // Select overlay
    sidebar.classList.remove('show'); // Remove the 'show' class
    overlay.classList.remove('show-overlay'); // Remove the 'show-overlay' class
});

// Close the sidebar when clicking on the overlay
document.querySelector('.overlay').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar'); // Select sidebar
    const overlay = document.querySelector('.overlay'); // Select overlay
    sidebar.classList.remove('show'); // Remove the 'show' class
    overlay.classList.remove('show-overlay'); // Remove the 'show-overlay' class
});
