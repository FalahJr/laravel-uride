<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        {{-- <img src="/mazer/assets/static/images/logo/logo.svg" alt="Logo" /> --}}
                        Uride
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="sidebar-toggler x">
                    <button type="button" aria-label="Close sidebar"
                        class="sidebar-hide d-xl-none d-block btn btn-ghost">
                        <i class="bi bi-x bi-middle"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                <li class="sidebar-item active">
                    <a href="{{ url('/home') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-layout-text-window-reverse"></i>
                        <span>Tables</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-card-text"></i>
                        <span>Components</span>
                    </a>
                </li>
                @php $driverActive = request()->routeIs('drivers.*'); @endphp
                <li class="sidebar-item has-sub {{ $driverActive ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>Manajemen Driver</span>
                    </a>
                    <ul class="submenu {{ $driverActive ? 'active' : 'submenu-closed' }}">
                        <li class="submenu-item {{ request()->routeIs('drivers.index') ? 'active' : '' }}">
                            <a href="{{ route('drivers.index') }}" class="submenu-link">Data Driver</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('drivers.pengajuan') ? 'active' : '' }}">
                            <a href="{{ route('drivers.pengajuan') }}" class="submenu-link">Pengajuan Driver</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
