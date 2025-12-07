document.addEventListener('DOMContentLoaded', () => {
    // THEME TOGGLE
    const body = document.body;
    const btn = document.getElementById('theme-toggle');
    const icon = btn ? btn.querySelector('.theme-icon') : null;
    const label = btn ? btn.querySelector('.theme-label') : null;

    const storedTheme = localStorage.getItem('sitiando-theme');
    if (storedTheme === 'dark') {
        body.classList.remove('theme-light');
        body.classList.add('theme-dark');
        if (icon) icon.textContent = '🌙';
        if (label) label.textContent = 'Modo oscuro';
    }

    if (btn) {
        btn.addEventListener('click', () => {
            const isDark = body.classList.toggle('theme-dark');
            body.classList.toggle('theme-light', !isDark);

            if (icon) icon.textContent = isDark ? '🌙' : '🌞';
            if (label) label.textContent = isDark ? 'Modo oscuro' : 'Modo claro';

            localStorage.setItem('sitiando-theme', isDark ? 'dark' : 'light');
        });
    }

    // Si en el futuro agregás botón de abrir/cerrar sidebar en mobile, lo manejamos acá.
});