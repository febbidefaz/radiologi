@extends('adminlte::page')

@section('title', 'User rad')

@section('content_header')
    <h1>User rad</h1>
@stop


@section('js')

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {

            $('#tableUserRad').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: true,
                searching: true,
                paging: true,
                pageLength: 10,

                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",

                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });



        });
    </script>

@stop


@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        :root {
            --rad-primary: #0284C7;
            --rad-primary-dark: #0369A1;
            --rad-primary-light: #F0F9FF;

            --rad-success: #16A34A;
            --rad-danger: #DC3545;
        }


        /* ===============================
               CARD
            =============================== */

        .card {
            border: 0;
            border-radius: 12px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, .08);
        }


        .card-header {
            background: var(--rad-primary);
            color: #ffffff;

            border-radius:
                12px 12px 0 0 !important;

            border-bottom: 0;

            font-weight: 600;
        }


        /* ===============================
               FORM
            =============================== */

        .form-control,
        .custom-select {
            border-radius: 8px;

            border:
                1px solid #d7dce1;

            box-shadow: none;
        }


        .form-control:focus,
        .custom-select:focus {
            border-color:
                var(--rad-primary);

            box-shadow:
                0 0 0 .15rem rgba(2, 132, 199, .15);
        }


        /* ===============================
               BUTTON RADIOLOGI
            =============================== */

        .btn-rad {
            background:
                var(--rad-primary);

            border-color:
                var(--rad-primary);

            color: #ffffff;

            border-radius: 8px;

            font-weight: 600;
        }


        .btn-rad:hover {
            background:
                var(--rad-primary-dark);

            border-color:
                var(--rad-primary-dark);

            color: #ffffff;
        }


        /* ===============================
               TABLE
            =============================== */

        #tableUserRad thead {
            background:
                var(--rad-primary);

            color: #ffffff;
        }


        #tableUserRad thead th {
            border-color:
                rgba(255, 255, 255, .20);

            vertical-align: middle;
        }


        #tableUserRad tbody tr:hover {
            background:
                var(--rad-primary-light);
        }


        /* ===============================
               BADGE
            =============================== */

        .badge-success,
        .badge-rad {
            background:
                var(--rad-success);

            color: #ffffff;

            border-radius: 20px;

            padding: 6px 10px;

            font-size: 12px;
        }


        .badge-danger {
            border-radius: 20px;

            padding: 6px 10px;
        }


        /* ===============================
               MODAL
            =============================== */

        .modal-content {
            border: 0;
            border-radius: 12px;
        }


        .modal-header {
            background:
                var(--rad-primary);

            color: #ffffff;

            border-bottom: 0;
        }


        .modal-header .close {
            color: #ffffff !important;
            opacity: .85;
        }


        .modal-header .close:hover {
            opacity: 1;
        }


        .modal-footer {
            border-top: 0;
        }


        /* ===============================
               DATATABLE
            =============================== */

        div.dataTables_wrapper div.dataTables_filter input,

        div.dataTables_wrapper div.dataTables_length select {
            border-radius: 8px;
        }


        .page-item.active .page-link {
            background:
                var(--rad-primary);

            border-color:
                var(--rad-primary);
        }


        .page-link {
            color:
                var(--rad-primary);
        }


        .page-link:hover {
            color:
                var(--rad-primary-dark);
        }


        /* ===============================
               ALERT
            =============================== */

        .alert-success {
            border-left:
                5px solid var(--rad-success);
        }
    </style>


@stop


@section('content')


    <div class="mb-2 text-left">

        <button type="button" class="btn btn-rad btn-sm" data-toggle="modal" data-target="#modalTambahUser">

            <i class="fas fa-user-plus"></i>

            Tambah User

        </button>

    </div>



    {{-- MODAL TAMBAH USER --}}

    <div class="modal fade" id="modalTambahUser" tabindex="-1">

        <div class="modal-dialog modal-sm">

            <div class="modal-content border-0 shadow-lg">


                <div class="modal-header">

                    <h5 class="modal-title font-weight-bold">

                        <i class="fas fa-user-plus mr-2"></i>

                        Tambah User Rad

                    </h5>


                    <button type="button" class="close text-white" data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <form method="POST" action="{{ route('userrad.store') }}">

                    @csrf


                    <div class="modal-body">


                        <div class="form-group">

                            <label>Nama</label>

                            <input type="text" name="Nama" class="form-control" required>

                        </div>


                        <div class="form-group">

                            <label>Username</label>

                            <input type="text" name="Username" class="form-control" required>

                        </div>


                        <div class="form-group">

                            <label>Password</label>

                            <input type="password" name="Password" class="form-control" required>

                        </div>


                        <div class="form-group">

                            <label>Role</label>

                            <select name="Role" class="form-control" required>

                                <option value="admin">
                                    Admin
                                </option>

                                <option value="ka. radiologi">
                                    Ka. Radiologi
                                </option>

                                <option value="radiologi">
                                    Radiologi
                                </option>

                            </select>

                        </div>


                        <div class="form-group mb-0">

                            <label>Aktif</label>

                            <select name="Aktif" class="form-control">

                                <option value="1">
                                    Ya
                                </option>

                                <option value="0">
                                    Tidak
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="btn btn-rad btn-sm">

                            <i class="fas fa-save"></i>

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- TABEL USER RAD --}}

    <div class="card">

        <div class="card-body table-responsive">

            <table id="tableUserRad" class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>Nama</th>

                        <th>Username</th>

                        <th>Role</th>

                        <th>Aktif</th>

                        <th width="100">Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($users as $u)
                        <tr>

                            <td>
                                {{ $u->Nama }}
                            </td>

                            <td>
                                {{ $u->Username }}
                            </td>

                            <td>
                                {{ $u->Role }}
                            </td>

                            <td>

                                @if ($u->Aktif == 1)
                                    <span class="badge badge-rad">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        Nonaktif
                                    </span>
                                @endif

                            </td>

                            <td>

                                <button type="button" class="btn btn-rad btn-sm" data-toggle="modal"
                                    data-target="#modalEditUser{{ $u->ID }}">

                                    <i class="fas fa-edit"></i>

                                    Edit

                                </button>

                            </td>

                        </tr>



                        {{-- MODAL EDIT USER --}}

                        <div class="modal fade" id="modalEditUser{{ $u->ID }}" tabindex="-1">

                            <div class="modal-dialog modal-sm">

                                <div class="modal-content border-0 shadow-lg">


                                    <div class="modal-header">

                                        <h5 class="modal-title font-weight-bold">

                                            <i class="fas fa-user-edit mr-2"></i>

                                            Edit User Rad

                                        </h5>


                                        <button type="button" class="close text-white" data-dismiss="modal">

                                            <span>&times;</span>

                                        </button>

                                    </div>


                                    <form method="POST" action="{{ route('userrad.update', $u->ID) }}">

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-body">


                                            <div class="form-group">

                                                <label>Nama</label>

                                                <input type="text" name="Nama" class="form-control form-control-sm"
                                                    value="{{ $u->Nama }}" required>

                                            </div>


                                            <div class="form-group">

                                                <label>Username</label>

                                                <input type="text" name="Username" class="form-control form-control-sm"
                                                    value="{{ $u->Username }}" required>

                                            </div>


                                            <div class="form-group">

                                                <label>Password</label>

                                                <input type="password" name="Password" class="form-control form-control-sm"
                                                    placeholder="Kosongkan jika tidak diganti">

                                            </div>


                                            <div class="form-group">

                                                <label>Role</label>

                                                <select name="Role" class="form-control form-control-sm" required>

                                                    <option value="admin" {{ $u->Role == 'admin' ? 'selected' : '' }}>

                                                        Admin

                                                    </option>


                                                    <option value="ka. radiologi"
                                                        {{ $u->Role == 'ka. laboratorium' ? 'selected' : '' }}>

                                                        Ka. Radiologi

                                                    </option>


                                                    <option value= "radiologi"
                                                        {{ $u->Role == 'radiologi' ? 'selected' : '' }}>

                                                        Radiologi

                                                    </option>

                                                </select>

                                            </div>


                                            <div class="form-group mb-0">

                                                <label>Aktif</label>

                                                <select name="Aktif" class="form-control form-control-sm">

                                                    <option value="1" {{ $u->Aktif == 1 ? 'selected' : '' }}>

                                                        Ya

                                                    </option>


                                                    <option value="0" {{ $u->Aktif == 0 ? 'selected' : '' }}>

                                                        Tidak

                                                    </option>

                                                </select>

                                            </div>

                                        </div>


                                        <div class="modal-footer d-flex justify-content-between">


                                            <button type="submit" class="btn btn-danger btn-sm"
                                                form="deleteUser{{ $u->ID }}"
                                                onclick="return confirm('Yakin ingin menghapus user ini?')">

                                                <i class="fas fa-trash"></i>
                                                Hapus

                                            </button>


                                            <div>

                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-dismiss="modal">

                                                    Batal

                                                </button>


                                                <button type="submit" class="btn btn-rad btn-sm">

                                                    <i class="fas fa-save"></i>

                                                    Update

                                                </button>

                                            </div>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </tbody>

            </table>
            @foreach ($users as $u)
                <form id="deleteUser{{ $u->ID }}" action="{{ route('userrad.destroy', $u->ID) }}" method="POST"
                    style="display:none;">

                    @csrf
                    @method('DELETE')

                </form>
            @endforeach

        </div>

    </div>

@stop
