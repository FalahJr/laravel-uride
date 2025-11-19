// NOTE: original file uses an import for isDesktop; for the static copy we'll implement a simple check
function isDesktop() {
    return window.innerWidth >= 1200;
}

/* Minimal Sidebar functionality copied from template (static use) */
(function () {
    const burgerBtns = document.querySelectorAll(".burger-btn");
    burgerBtns.forEach((btn) =>
        btn.addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            if (!sidebar) return;
            if (sidebar.classList.contains("active")) {
                sidebar.classList.remove("active");
                sidebar.classList.add("inactive");
            } else {
                sidebar.classList.add("active");
                sidebar.classList.remove("inactive");
            }
        })
    );
})();
