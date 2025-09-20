// Submenu Toggle Animation
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.nav-item > .nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const next = this.nextElementSibling;
            if (next && next.classList.contains('nav-treeview')) {
                e.preventDefault();
                next.classList.toggle('show');
            }
        });
    });
});

// Sidebar Collapse Button
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById('toggleSidebar');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            document.querySelector('.app-sidebar').classList.toggle('collapsed');
        });
    }
});
