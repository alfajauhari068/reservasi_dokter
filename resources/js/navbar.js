function initNavbar() {
    function setHidden(el, hidden) {
        if (!el) return;
        if (hidden) {
            el.classList.add('hidden');
            el.style.display = 'none';
        } else {
            el.classList.remove('hidden');
            el.style.display = '';
        }
    }

    const profileBtn = document.getElementById('profileDropdownBtn');
    const profileMenu = document.getElementById('profileDropdownMenu');
    const notifBtn = document.getElementById('notificationBtn');
    const notifMenu = document.getElementById('notificationMenu');
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobilePanel = document.getElementById('mobileMenuPanel');

    function closeAll() {
        setHidden(profileMenu, true);
        if (profileBtn) profileBtn.setAttribute('aria-expanded', 'false');
        setHidden(notifMenu, true);
        if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
    }

    // Profile dropdown
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = profileMenu.classList.contains('hidden');
            closeAll();
            setHidden(profileMenu, isHidden);
            profileBtn.setAttribute('aria-expanded', String(isHidden));
        });
    }

    // Notification dropdown
    if (notifBtn && notifMenu) {
        notifBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = notifMenu.classList.contains('hidden');
            closeAll();
            setHidden(notifMenu, isHidden);
            notifBtn.setAttribute('aria-expanded', String(isHidden));
        });
    }

    // Close dropdowns on document click
    document.addEventListener('click', closeAll);

    // Mobile menu
    if (mobileBtn && mobilePanel) {
        mobileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = mobilePanel.classList.contains('hidden');
            setHidden(mobilePanel, isHidden);
        });

        mobilePanel.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Realtime clock
    const clockEl = document.getElementById('nav-realtime-clock');
    const mobileClockEl = document.getElementById('mobileClock');

    function pad(n) { return String(n).padStart(2, '0'); }

    function updateClock() {
        const d = new Date();
        const time = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ' WIB';
        if (clockEl) clockEl.textContent = time;
        if (mobileClockEl) mobileClockEl.textContent = time;
    }

    updateClock();
    setInterval(updateClock, 1000);
}

// Initialize immediately if DOM is ready, or wait for it
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNavbar);
} else {
    initNavbar();
}
