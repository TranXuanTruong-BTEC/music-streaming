@if(auth()->check() && auth()->user()->isAdmin())
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Quản lý Website Âm nhạc</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="{{ url('/') }}" class="logo">
                        <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
                             class="navbar-brand" height="20" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#quanly">
                                <i class="fas fa-layer-group"></i>
                                <p>Quản lý</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="quanly">
                                <ul class="nav nav-collapse">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.artists') }}">
                                            <span class="sub-item">Quản lý Nghệ sĩ</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#">
                                            <span class="sub-item">Quản lý Album</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- Separate Music Section -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#amnhac">
                                <i class="fas fa-music"></i>
                                <p>Âm nhạc</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="amnhac">
                                <ul class="nav nav-collapse">
                                    <li class="nav-item">
                                        <a href="#">
                                            <span class="sub-item">Danh sách Bài hát</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#">
                                            <span class="sub-item">Thể loại</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#">
                                            <span class="sub-item">Playlist</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- End Music Section -->
                         <!-- New Account Management Section -->
        <li class="nav-item">
            <a data-bs-toggle="collapse" href="#quanlytaikhoan">
                <i class="fas fa-users"></i>
                <p>Quản lý Tài khoản</p>
                <span class="caret"></span>
            </a>
            <div class="collapse" id="quanlytaikhoan">
                <ul class="nav nav-collapse">
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}">
                            <span class="sub-item">Danh sách Người dùng</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="sub-item">Phân quyền</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="sub-item">Cài đặt Tài khoản</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
                        <!-- AI Management Section -->
        <li class="nav-item">
            <a data-bs-toggle="collapse" href="#quanlyai">
                <i class="fas fa-robot"></i>
                <p>Quản lý AI</p>
                <span class="caret"></span>
            </a>
            <div class="collapse" id="quanlyai">
                <ul class="nav nav-collapse">
                    <li class="nav-item">
                        <a href="#">
                            <span class="sub-item">Cấu hình AI</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="sub-item">Đào tạo mô hình</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="sub-item">Phân tích dữ liệu AI</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <span class="sub-item">Tích hợp AI</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
                        <!-- End AI Management Section -->
        <li class="nav-item">
            <a href="#">
                <i class="fas fa-chart-bar"></i>
                <p>Thống kê & Báo cáo</p>
            </a>
        </li>
                        <li class="nav-item">
                            <a href="#">
                                <i class="fas fa-cog"></i>
                                <p>Cài đặt Hệ thống</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Navbar -->
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="{{ url('/') }}" class="logo">
                            <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
                                 class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </nav>

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                   aria-expanded="false">
                                    <i class="fa fa-search"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input type="text" placeholder="Search ..." class="form-control" />
                                        </div>
                                    </form>
                                </ul>
                            </li>
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                   aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/profile.jpg') }}" alt="..."
                                             class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="{{ asset('assets/img/profile.jpg') }}" alt="image profile"
                                                         class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <a href="{{ url('profile') }}"
                                                       class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
            <!-- Main Content -->
            <div class="content">
                @yield('content')
            </div>
            <!-- End Main Content -->

        </div>
        <!-- End Main Panel -->
    </div>
<!-- Core JS Files -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Other JS plugins and scripts -->
@yield('scripts')
</body>
</html>
@else
<div class="container mt-5">
    <div class="alert alert-danger" role="alert">
        Bạn không có quyền truy cập trang này. Vui lòng <a href="{{ route('login') }}">đăng nhập</a> với tài khoản có quyền admin.
    </div>
</div>
@endif
