@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')
        @yield('content_top_nav_left')
    </ul>

    <ul class="navbar-nav ml-auto">

        @yield('content_top_nav_right')

        {{-- ============================= --}}
        {{-- NOTIFIKASI SP RADIOLOGI --}}
        {{-- TAMPIL LANGSUNG DI NAVBAR --}}
        {{-- ============================= --}}

        {{-- 
        <li class="nav-item d-flex align-items-center mr-2" id="notifSPWrapper">

            <span class="nav-link px-2" id="notifSPTotalBox">

                <i class="fas fa-bell mr-1"
                    style="
                    color:#efba0c !important;
                    font-size:16px;
                ">
                </i>

                <span id="notifSPTotal" class="badge"
                    style="
                    background-color:#3457c0 !important;
                    color:#ffffff !important;
                    border:none !important;
                    min-width:22px;
                    padding:4px 7px;
                    font-size:12px;
                    font-weight:700;
                    border-radius:10px;
                ">
                    0
                </span>

            </span>


            <a href="{{ route('igd.index') }}" class="nav-link px-2" id="notifInlineIGD">

                <strong>IGD</strong>

                <span class="badge badge-danger" id="notifSPIGD">
                    0
                </span>

            </a>


            <a href="{{ route('rawatinap.index') }}" class="nav-link px-2" id="notifInlineRI">

                <strong>RI</strong>

                <span class="badge badge-primary" id="notifSPRI">
                    0
                </span>

            </a>


            <a href="{{ route('rawatjalan.index') }}" class="nav-link px-2" id="notifInlineRJ">

                <strong>RJ</strong>

                <span class="badge badge-success" id="notifSPRJ">
                    0
                </span>

            </a>


            <a href="#" class="nav-link px-2" id="notifInlinePA">

                <strong>PA</strong>

                <span class="badge badge-warning" id="notifSPPA">
                    0
                </span>

            </a>

        </li>
 --}}

        {{-- RIGHT SIDEBAR --}}
        @if ($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif


        {{-- ============================= --}}
        {{-- USER --}}
        {{-- ============================= --}}
        <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" href="#">

                <i class="far fa-user-circle mr-1"></i>

            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <span class="dropdown-item dropdown-header">

                    <strong>
                        {{ session('userrad_nama', 'User') }}
                    </strong>

                    <br>

                </span>

                <div class="dropdown-divider"></div>

                <a href="#" class="dropdown-item"
                    onclick="
                        event.preventDefault();
                        $('.dropdown-menu').removeClass('show');
                        $('#modalGantiPassword').modal('show');
                    ">

                    <i class="fas fa-user-edit mr-2"></i>

                    Update Profile

                </a>

                <div class="dropdown-divider"></div>

                <a href="#" class="dropdown-item text-center text-danger"
                    onclick="
                        event.preventDefault();
                        document.getElementById('logout-form').submit();
                    ">

                    <i class="fas fa-power-off mr-1"></i>

                    Logout

                </a>

                <form id="logout-form" action="{{ route('rad.logout') }}" method="POST" style="display:none;">

                    @csrf

                </form>

            </div>

        </li>

    </ul>
</nav>

@if (session('success'))
    <div id="successAlert" class="alert alert-success alert-dismissible fade show"
        style="position: fixed; top: 70px; right: 20px; z-index: 9999; min-width: 300px;">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>

    <script>
        setTimeout(function() {
            $('#successAlert').fadeOut('slow');
        }, 3000);
    </script>
@endif

@if ($errors->any())
    <div id="errorAlert" class="alert alert-danger alert-dismissible fade show"
        style="position: fixed; top: 70px; right: 20px; z-index: 9999; min-width: 300px;">
        <i class="fas fa-exclamation-circle"></i>
        {{ $errors->first() }}

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>

    <script>
        setTimeout(function() {
            $('#errorAlert').fadeOut('slow');
        }, 3000);
    </script>
@endif

<div class="modal fade" id="modalGantiPassword" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header text-white" style="background:#3F66D6;">
                <h5 class="modal-title">
                    <i class="fas fa-key mr-2"></i>
                    Ganti Password
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('rad.profile.password.update') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ session('userrad_id') }}">

                <div class="modal-body">
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password_baru" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group mb-0">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" class="form-control form-control-sm"
                            required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-sm text-white"
                        style="background:#3F66D6;border-color:#3F66D6;">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $('#modalGantiPassword').on('shown.bs.modal', function() {
        $('.dropdown-menu').removeClass('show');
    });
</script>
