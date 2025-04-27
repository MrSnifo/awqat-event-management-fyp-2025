const toggleButton = document.getElementById('theme-toggle');
const body = document.body;
const themeIcon = document.getElementById('theme-icon');

toggleButton.addEventListener('click', () => {
    body.classList.toggle('bg-dark');
    body.classList.toggle('bg-light');
    
    // Changer d'icône
    if (body.classList.contains('bg-dark')) {
        themeIcon.classList.remove('fas', 'fa-sun');
        themeIcon.classList.add('fas', 'fa-moon');
    } else {
        themeIcon.classList.remove('fas', 'fa-moon');
        themeIcon.classList.add('fas', 'fa-sun');
    }
});

// Fonction de confirmation de suppression
function confirmDelete(userId) {
    const confirmation = confirm("Are you sure you want to delete this user?");
    if (confirmation) {
        alert("User " + userId + " deleted.");
    }
}