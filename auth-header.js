document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('backend/api/auth-check.php');
        const data = await response.json();
        
        const authButtons = document.querySelector('.auth-buttons');
        if (authButtons) {
            if (data.loggedIn) {
                let html = `<span style="margin-right: 15px; color: white; font-weight: bold;">${data.name ? data.name : 'Профиль'}</span>`;
                if (data.role === 'admin') {
                    html += `<button onclick="window.location.href='backend/admin/dashboard.php'" style="margin-right: 10px; background-color: #2c3e50;">Админ-панель</button>`;
                }
                html += `<button onclick="window.location.href='logout.php'">Выйти</button>`;
                
                authButtons.style.display = 'flex';
                authButtons.style.alignItems = 'center';
                authButtons.innerHTML = html;
            } else {
                authButtons.innerHTML = `<button onclick="window.location.href='reg.html'">Войти</button>`;
            }
        }
    } catch (e) {
        console.error('Ошибка проверки сессии', e);
    }
});
