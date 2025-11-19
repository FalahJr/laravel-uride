<header class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button type="button" aria-label="Toggle sidebar" class="burger-btn btn btn-ghost d-block d-xl-none me-2">
                <i class="bi bi-justify fs-3"></i>
            </button>
            <button type="button" aria-label="Toggle sidebar" class="burger-btn btn btn-ghost d-none d-xl-block">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-ghost me-2" aria-label="Notifications">
                <i class="bi bi-bell fs-4"></i>
            </button>

            <button type="button" class="btn btn-ghost me-2" aria-label="Messages">
                <i class="bi bi-envelope fs-4"></i>
            </button>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none" id="userDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="/mazer/assets/static/images/faces/1.jpg" alt="User Avatar" class="rounded-circle"
                        width="40" height="40">
                    <span class="ms-2 d-none d-md-inline">Hi,
                        {{ auth()->user()->nama_lengkap ?? auth()->user()->username }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
</header>
