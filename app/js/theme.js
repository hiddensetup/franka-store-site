// theme.js 
document.addEventListener('DOMContentLoaded', function () {
    
    const storedTheme = localStorage.getItem('selectedTheme');
    if (storedTheme) {
        const htmlElement = document.querySelector('html');
        const themeIcon = document.getElementById('theme-icon');

        htmlElement.setAttribute('data-bs-theme', storedTheme);
        themeIcon.className = `bi bi-${storedTheme === 'light' ? 'moon' : 'sun'}`;
    }
});


function toggleTheme() {
    const htmlElement = document.querySelector('html');
    const themeIcon = document.getElementById('theme-icon');
    const newTheme = htmlElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';

    // Store the selected theme in local storage
    localStorage.setItem('selectedTheme', newTheme);

    htmlElement.setAttribute('data-bs-theme', newTheme);
    themeIcon.className = `bi bi-${newTheme === 'light' ? 'moon' : 'sun'}`;
}

