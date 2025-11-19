(function () {
  // Simple sidebar toggler for static pages (no Vite)
  const DESKTOP_THRESHOLD = 1200;

  function isDesktop() {
    return window.innerWidth > DESKTOP_THRESHOLD;
  }

  function createBackdrop(hideFn) {
    deleteBackdrop();
    if (isDesktop()) return;
    const backdrop = document.createElement("div");
    backdrop.className = "sidebar-backdrop";
    backdrop.addEventListener("click", hideFn);
    document.body.appendChild(backdrop);
  }

  function deleteBackdrop() {
    const b = document.querySelector(".sidebar-backdrop");
    if (b) b.remove();
  }

  function showSidebar(sidebar) {
    // console.log("sidebar-toggle: showSidebar called", sidebar);
    sidebar.classList.add("active");
    sidebar.classList.remove("inactive");
    // keep .sidebar-wrapper in sync if present
    const wrapper = sidebar.querySelector(".sidebar-wrapper");
    if (wrapper) {
      wrapper.classList.add("active");
      wrapper.classList.remove("inactive");
    }
    createBackdrop(() => hideSidebar(sidebar));
    document.body.style.overflowY = "hidden";
  }

  function hideSidebar(sidebar) {
    // console.log("sidebar-toggle: hideSidebar called", sidebar);
    sidebar.classList.remove("active");
    sidebar.classList.add("inactive");
    const wrapper = sidebar.querySelector(".sidebar-wrapper");
    if (wrapper) {
      wrapper.classList.remove("active");
      wrapper.classList.add("inactive");
    }
    deleteBackdrop();
    document.body.style.overflowY = "auto";
  }

  function toggleSidebar(e) {
    if (e) e.preventDefault();
    // console.log("sidebar-toggle: toggleSidebar event", e);
    const sidebar = document.getElementById("sidebar");
    if (!sidebar) return;
    const isActive = sidebar.classList.contains("active");
    if (isActive) hideSidebar(sidebar);
    else showSidebar(sidebar);
  }

  function onResize() {
    // console.log("sidebar-toggle: onResize, isDesktop=", isDesktop());
    const sidebar = document.getElementById("sidebar");
    if (!sidebar) return;
    if (isDesktop()) {
      sidebar.classList.add("active");
      sidebar.classList.remove("inactive");
      const wrapper = sidebar.querySelector(".sidebar-wrapper");
      if (wrapper) {
        wrapper.classList.add("active");
        wrapper.classList.remove("inactive");
      }
      deleteBackdrop();
      document.body.style.overflowY = "auto";
    } else {
      // keep current state; do not force hide on resize
    }
  }

  if (document.readyState !== "loading") {
    init();
  } else {
    window.addEventListener("DOMContentLoaded", init);
  }

  function init() {
    // console.log("sidebar-toggle: init");
    const sidebar = document.getElementById("sidebar");
    if (!sidebar) return;

    // Set initial desktop state
    if (isDesktop()) {
      sidebar.classList.add("active");
      const wrapper = sidebar.querySelector(".sidebar-wrapper");
      if (wrapper) wrapper.classList.add("active");
    }

    // bind toggles
    document
      .querySelectorAll(".burger-btn, .sidebar-hide")
      .forEach((el) => el.addEventListener("click", toggleSidebar));

    // handle resize
    window.addEventListener("resize", onResize);
  }

  // expose a safe global toggle for file:// usage and simple inline handlers
  window.toggleSidebarForStatic = function (e) {
    try {
      toggleSidebar(e);
    } catch (err) {
      console.log("sidebar-toggle: toggleSidebarForStatic error", err);
    }
  };
})();
