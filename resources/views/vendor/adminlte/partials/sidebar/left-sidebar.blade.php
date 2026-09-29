<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    @if (config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    <div class="sidebar">

        @if (session('userrad_id'))
            <div class="user-panel-rad">

                <div class="user-icon-rad">
                    <i class="fas fa-x-ray"></i>
                </div>


                <div class="user-info-rad">

                    <div class="user-name-rad">
                        {{ session('userrad_nama') }}
                    </div>


                    <div class="user-role-rad">
                        {{ session('userrad_role') }}
                    </div>

                </div>

            </div>
        @endif



        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>

                @foreach ($adminlte->menu('sidebar') as $item)
                    @include('adminlte::partials.sidebar.menu-item', ['item' => $item])

                    @if (($item['text'] ?? '') === 'Rawat Jalan')
                        <li class="nav-item">

                            <a href="#modalCariPasien" class="nav-link" data-toggle="modal"
                                data-target="#modalCariPasien">

                                <i class="nav-icon fas fa-search"></i>
                                <p>Cari ID</p>

                            </a>

                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>



{{-- ========================================= --}}
{{-- MODAL CARI PASIEN --}}
{{-- ========================================= --}}
<div class="modal fade" id="modalCariPasien" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">
                    Cari Pasien
                </h4>

                <button type="button" class="close" data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <div class="modal-body">

                <input type="text" id="cariPasien" class="form-control" placeholder="Ketikkan ID pasien"
                    autocomplete="off">

                <div id="hasilCariPasien" class="mt-3">
                </div>

            </div>


            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">

                    Batal

                </button>


                <button type="button" class="btn btn-primary" onclick="cariPasien()">

                    Cari

                </button>

            </div>

        </div>

    </div>

</div>


<style>
    .user-panel-rad {
        margin: 18px 10px 15px 10px;
        padding: 12px;

        display: flex;
        align-items: center;
        gap: 12px;

        background: linear-gradient(135deg,
                #0369A1,
                #0EA5E9);

        border-radius: 10px;

        box-shadow:
            0 4px 12px rgba(14, 165, 233, .28);
    }

    .user-icon-rad {
        width: 45px;
        height: 45px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(255, 255, 255, .20);

        color: #ffffff;

        font-size: 22px;
    }

    .user-info-rad {
        color: white;
        line-height: 1.2;
    }

    .user-name-rad {
        font-size: 16px;
        font-weight: 700;
    }

    .user-role-rad {
        margin-top: 4px;
        font-size: 13px;
        opacity: .85;
    }

    .nav-sidebar .nav-item>.nav-link.active {
        background-color: #0EA5E9 !important;
        color: #ffffff !important;

        border-radius: 8px;

        box-shadow:
            0 4px 10px rgba(14, 165, 233, .30);
    }

    .nav-sidebar .nav-item>.nav-link.active i {
        color: #ffffff !important;
    }

    .nav-sidebar .nav-item>.nav-link:hover {
        background-color: rgba(14, 165, 233, .16) !important;
        color: #ffffff !important;
    }

    .nav-sidebar .nav-treeview>.nav-item>.nav-link.active {
        background-color: #0284C7 !important;
        color: #ffffff !important;
    }

    .form-control:focus {
        border-color: #0EA5E9;

        box-shadow:
            0 0 0 .15rem rgba(14, 165, 233, .16);
    }

    .btn-primary {
        background-color: #0EA5E9 !important;
        border-color: #0EA5E9 !important;
    }

    .btn-primary:hover {
        background-color: #0284C7 !important;
        border-color: #0284C7 !important;
    }
</style>
