This folder contains a static-ready `index.html` built from the template sources so you can run the demo without Vite.

How to use:

- Open `static/index.html` in your browser (double-click). If anything is blocked by your browser (CORS for some resources) run a simple static server:

  python3 -m http.server 8000

  Then open: http://localhost:8000/index.html

Notes:

- The page uses compiled CSS/JS via CDN (jsdelivr) so you don't need Vite to build assets.
- Some local JS files (e.g. `../src/assets/static/js/pages/dashboard.js`) are included and expect `ApexCharts` which is loaded from CDN.
- If you want fully offline usage, replace CDN links in `static/index.html` with local compiled assets (from releases) or download the compiled files and serve them from `static/assets/...`.

If you want, I can:

- Generate static copies for other pages (e.g., `table.html`, `component-card.html`), or
- Update original `src/*.html` files in-place to be static (destructive).
