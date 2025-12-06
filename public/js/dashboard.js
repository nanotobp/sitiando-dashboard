document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const btn = document.getElementById('theme-toggle');

    if (!btn) return;

    // cargar tema guardado
    const saved = localStorage.getItem('sitiando_theme');
    if (saved === 'dark') {
        body.classList.remove('theme-light');
        body.classList.add('theme-dark');
        updateLabel(true);
    }

    btn.addEventListener('click', () => {
        const isDark = body.classList.contains('theme-dark');
        if (isDark) {
            body.classList.remove('theme-dark');
            body.classList.add('theme-light');
            localStorage.setItem('sitiando_theme', 'light');
            updateLabel(false);
        } else {
            body.classList.remove('theme-light');
            body.classList.add('theme-dark');
            localStorage.setItem('sitiando_theme', 'dark');
            updateLabel(true);
        }
    });

    function updateLabel(dark) {
        const icon = btn.querySelector('.theme-icon');
        const label = btn.querySelector('.theme-label');
        if (!icon || !label) return;

        if (dark) {
            icon.textContent = '🌙';
            label.textContent = 'Modo oscuro';
        } else {
            icon.textContent = '🌞';
            label.textContent = 'Modo claro';
        }
    }
});
