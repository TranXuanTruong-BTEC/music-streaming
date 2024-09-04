function setTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
        document.getElementById('theme-toggle-dark-icon').classList.add('hidden');
        document.getElementById('theme-toggle-light-icon').classList.remove('hidden');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
        document.getElementById('theme-toggle-light-icon').classList.add('hidden');
        document.getElementById('theme-toggle-dark-icon').classList.remove('hidden');
    }
}

// On page load or when changing themes, best to add inline in `head` to avoid FOUC
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    setTheme('dark');
} else {
    setTheme('light');
}

// Whenever the user explicitly chooses to respect the OS preference
localStorage.removeItem('theme');

document.getElementById('theme-toggle').addEventListener('click', function() {
    if (localStorage.theme === 'dark') {
        setTheme('light');
    } else {
        setTheme('dark');
    }
});
