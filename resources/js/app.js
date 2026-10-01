// Global helper untuk menu sidebar demo dashboard.
if (typeof window.setActiveMenu !== 'function') {
    window.setActiveMenu = function (menuName) {
        const breadcrumb = document.getElementById('breadcrumb-page');

        if (breadcrumb && menuName) {
            breadcrumb.textContent = menuName;
        }
    };
}
