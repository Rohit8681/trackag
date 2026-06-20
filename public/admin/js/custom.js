// Sidebar hover flyout
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector('.app-sidebar');
    if (!sidebar) return;

    const keepFlyoutOpen = item => {
        clearTimeout(item.sidebarCloseTimer);
        item.classList.add('sidebar-hover-open');

        let parent = item.parentElement?.closest('.nav-item');
        while (parent && sidebar.contains(parent)) {
            clearTimeout(parent.sidebarCloseTimer);
            parent.classList.add('sidebar-hover-open');
            parent = parent.parentElement?.closest('.nav-item');
        }
    };

    const closeFlyoutLater = item => {
        clearTimeout(item.sidebarCloseTimer);
        item.sidebarCloseTimer = setTimeout(() => {
            item.classList.remove('sidebar-hover-open');
        }, 220);
    };

    const setupNestedFlyouts = container => {
        container.querySelectorAll('.nav-item').forEach(item => {
            if (item.dataset.flyoutReady === '1') return;

            const directSubmenus = Array.from(item.children).filter(child => child.classList?.contains('nav-treeview'));
            const link = item.querySelector(':scope > .nav-link');

            if (!directSubmenus.length || !link) return;

            const nestedFlyout = document.createElement('div');
            nestedFlyout.className = 'sidebar-sub-flyout';
            directSubmenus.forEach(submenu => nestedFlyout.appendChild(submenu));
            item.appendChild(nestedFlyout);
            item.dataset.flyoutReady = '1';

            setupNestedFlyouts(nestedFlyout);

            link.addEventListener('click', event => {
                event.preventDefault();
                event.stopImmediatePropagation();
            }, true);

            item.addEventListener('mouseenter', () => {
                const itemRect = item.getBoundingClientRect();
                const previousDisplay = nestedFlyout.style.display;
                const previousVisibility = nestedFlyout.style.visibility;

                nestedFlyout.style.visibility = 'hidden';
                nestedFlyout.style.display = 'block';

                const flyoutHeight = Math.min(nestedFlyout.getBoundingClientRect().height, window.innerHeight * 0.7, 560);
                const availableBottom = window.innerHeight - 12;
                const top = Math.min(itemRect.top, availableBottom - flyoutHeight);

                nestedFlyout.style.display = previousDisplay;
                nestedFlyout.style.visibility = previousVisibility;

                nestedFlyout.style.setProperty('--sidebar-sub-flyout-left', `${itemRect.right + 2}px`);
                nestedFlyout.style.setProperty('--sidebar-sub-flyout-top', `${Math.max(12, top)}px`);
                keepFlyoutOpen(item);
            });

            item.addEventListener('mouseleave', () => closeFlyoutLater(item));
            nestedFlyout.addEventListener('mouseenter', () => keepFlyoutOpen(item));
            nestedFlyout.addEventListener('mouseleave', () => closeFlyoutLater(item));
        });
    };

    sidebar.querySelectorAll('.sidebar-menu > .nav-item').forEach(item => {
        const directSubmenus = Array.from(item.children).filter(child => child.classList?.contains('nav-treeview'));
        const link = item.querySelector(':scope > .nav-link');

        if (!directSubmenus.length || !link) return;

        const flyout = document.createElement('div');
        flyout.className = 'sidebar-flyout';
        directSubmenus.forEach(submenu => flyout.appendChild(submenu));
        item.appendChild(flyout);
        item.dataset.flyoutReady = '1';

        setupNestedFlyouts(flyout);

        link.addEventListener('click', event => {
            event.preventDefault();
            event.stopImmediatePropagation();
        }, true);

        item.addEventListener('mouseenter', () => {
            const sidebarRect = sidebar.getBoundingClientRect();
            const itemRect = item.getBoundingClientRect();
            const previousDisplay = flyout.style.display;
            const previousVisibility = flyout.style.visibility;

            flyout.style.visibility = 'hidden';
            flyout.style.display = 'block';

            const flyoutHeight = Math.min(flyout.getBoundingClientRect().height, window.innerHeight * 0.7, 560);
            const availableBottom = window.innerHeight - 12;
            const top = Math.min(itemRect.top, availableBottom - flyoutHeight);

            flyout.style.display = previousDisplay;
            flyout.style.visibility = previousVisibility;

            flyout.style.setProperty('--sidebar-flyout-left', `${sidebarRect.right + 2}px`);
            flyout.style.setProperty('--sidebar-flyout-top', `${Math.max(12, top)}px`);
            keepFlyoutOpen(item);
        });

        item.addEventListener('mouseleave', () => closeFlyoutLater(item));
        flyout.addEventListener('mouseenter', () => keepFlyoutOpen(item));
        flyout.addEventListener('mouseleave', () => closeFlyoutLater(item));
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
