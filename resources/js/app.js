import './bootstrap';

// Initialize theme store immediately
window.themeStore = {
    isDark: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),

    toggle() {
        this.isDark = !this.isDark;
        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        this.updateDOM();
        this.updateIcons();
    },

    updateDOM() {
        if (this.isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },

    updateIcons() {
        const lightIcon = document.querySelector('.theme-icon-light');
        const darkIcon = document.querySelector('.theme-icon-dark');

        if (lightIcon && darkIcon) {
            if (this.isDark) {
                lightIcon.style.display = 'none';
                darkIcon.style.display = 'block';
            } else {
                lightIcon.style.display = 'block';
                darkIcon.style.display = 'none';
            }
        }
    },

    init() {
        this.updateDOM();
        this.updateIcons();
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (!localStorage.getItem('theme')) {
                this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                this.updateDOM();
                this.updateIcons();
            }
        });
    }
};

// Set up theme toggle button handler immediately, without waiting for DOMContentLoaded
(function() {
    const handleThemeToggle = (e) => {
        const button = e.target.closest('[data-theme-toggle]');
        if (button) {
            e.preventDefault();
            e.stopPropagation();
            window.themeStore.toggle();
        }
    };

    // Use capture phase to ensure we catch the event
    document.addEventListener('click', handleThemeToggle, true);
})();

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.themeStore.init();

    // Wait for Alpine to be available, then create the store
    const checkAlpine = setInterval(() => {
        if (typeof Alpine !== 'undefined') {
            clearInterval(checkAlpine);
            Alpine.store('theme', window.themeStore);
        }
    }, 50);
});

// Also run init immediately in case DOMContentLoaded already fired
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.themeStore.init();
    });
} else {
    window.themeStore.init();
}
