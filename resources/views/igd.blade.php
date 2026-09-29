@extends('layouts.rad-admin')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('title', 'IGD')

@section('content_header')

    <div class="d-flex align-items-center">

        <h1 class="mb-0 mr-2">
            Data Pasien IGD
        </h1>

        <span class="badge shadow-sm px-3 py-2" id="totalPasienHeader"
            style="font-size:14px;font-weight:1000;border-radius:10px;letter-spacing:0.5px;">
            0 Pasien
        </span>

    </div>

@stop

@section('content')

    <div class="card shadow-sm">

        <div class="card-header bg-light">

            <div class="row align-items-center">

                <div class="col-md-3">
                    <div class="form-group row mb-0">
                        <label class="col-sm-4 col-form-label">TGL Awal</label>
                        <div class="col-sm-8">
                            <input type="text" id="tglAwal" class="form-control datepicker" value="{{ date('d-m-Y') }}"
                                autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row mb-0">
                        <label class="col-sm-4 col-form-label">TGL Akhir</label>
                        <div class="col-sm-8">
                            <input type="text" id="tglAkhir" class="form-control datepicker" value="{{ date('d-m-Y') }}"
                                autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-lab-filter btn-block" onclick="reloadIGD()">
                        <i class="fas fa-search"></i>
                        Tampilkan
                    </button>
                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-wrap">
                <table id="tblIGD" class="table table-hover table-striped table-bordered nowrap">
                    <thead class="bg-info">
                        <tr>
                            <th>PxRS</th>
                            <th>ID</th>
                            <th>NoRM</th>
                            <th>Nama Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>No SEP</th>
                            <th>Alamat</th>
                            <th>Follow Up</th>
                            <th>Sub Layanan</th>

                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>

        </div>

    </div>

@stop

@section('css')

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">

    <style>
        /* HEADER TABEL */
        #tblIGD thead th {
            position: sticky;
            top: 0;
            z-index: 2;

            background: #0284C7 !important;
            color: white !important;
        }

        /* HOVER BARIS */
        #tblIGD tbody tr:hover td {
            background: #F0F9FF !important;
        }

        /* BADGE JUMLAH PASIEN */
        #totalPasienHeader {
            background: #0284C7 !important;
            color: white !important;
        }

        /* TOMBOL TAMPILKAN */
        .btn-lab-filter {
            background: #0284C7 !important;
            border-color: #0284C7 !important;
            color: white !important;
        }

        .btn-lab-filter:hover,
        .btn-lab-filter:focus {
            background: #0369A1 !important;
            border-color: #0369A1 !important;
            color: white !important;
        }

        /* INPUT FOCUS */
        .form-control:focus {
            border-color: #0284C7 !important;

            box-shadow:
                0 0 0 .15rem rgba(2, 132, 199, .15) !important;
        }

        /* MENU SIDEBAR AKTIF */
        .nav-sidebar .nav-link.active {
            background: #0284C7 !important;
            color: white !important;
        }

        /* MENU SIDEBAR HOVER */
        .nav-sidebar .nav-link:hover {
            background: #0369A1 !important;
            color: white !important;
        }

        /* SCROLLBAR */
        .table-wrap::-webkit-scrollbar {
            height: 9px;
            width: 9px;
        }

        .table-wrap::-webkit-scrollbar-track {
            background: #F0F9FF;
            border-radius: 10px;
        }

        .table-wrap::-webkit-scrollbar-thumb {
            background: #7DD3FC;
            border-radius: 10px;
        }

        .table-wrap::-webkit-scrollbar-thumb:hover {
            background: #0284C7;
        }
    </style>

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.id.min.js">
    </script>
    <script>
        let tableIGD;

        const userId = "{{ session('userlab_id') ?? 'guest' }}";
        const keyPrefix = "igd_" + userId + "_";

        function simpanFilterIGD() {

            localStorage.setItem(
                keyPrefix + "tgl_awal",
                $('#tglAwal').val()
            );

            localStorage.setItem(
                keyPrefix + "tgl_akhir",
                $('#tglAkhir').val()
            );
        }

        function loadFilterIGD() {

            let savedAwal = localStorage.getItem(
                keyPrefix + "tgl_awal"
            );

            let savedAkhir = localStorage.getItem(
                keyPrefix + "tgl_akhir"
            );

            if (savedAwal) {
                $('#tglAwal').val(savedAwal);
            }

            if (savedAkhir) {
                $('#tglAkhir').val(savedAkhir);
            }
        }

        $(function() {

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'id'
            });

            loadFilterIGD();

            tableIGD = $("#tblIGD").DataTable({

                processing: true,
                stateSave: true,

                stateSaveCallback: function(settings, data) {
                    localStorage.setItem(
                        keyPrefix + "datatable_state",
                        JSON.stringify(data)
                    );
                },

                stateLoadCallback: function(settings) {
                    let savedState = localStorage.getItem(
                        keyPrefix + "datatable_state"
                    );

                    return savedState ? JSON.parse(savedState) : null;
                },

                ajax: {
                    url: "{{ route('igd.data') }}",
                    data: function(d) {
                        d.tgl_awal = toDbDate($('#tglAwal').val());
                        d.tgl_akhir = toDbDate($('#tglAkhir').val());
                    }
                },

                columns: [{
                        data: 'PxRS'
                    },
                    {
                        data: 'ID'
                    },
                    {
                        data: 'RegNum'
                    },
                    {
                        data: 'Nama'
                    },
                    {
                        data: 'Dokter'
                    },
                    {
                        data: 'Tanggal',
                        render: function(data) {
                            if (!data) return '-';

                            let tgl = new Date(data);
                            let dd = String(tgl.getDate()).padStart(2, '0');
                            let mm = String(tgl.getMonth() + 1).padStart(2, '0');
                            let yyyy = tgl.getFullYear();

                            return dd + '/' + mm + '/' + yyyy;
                        }
                    },
                    {
                        data: 'NoSEP'
                    },
                    {
                        data: 'Addr'
                    },
                    {
                        data: 'FollowUp'
                    },
                    {
                        data: 'SubLayanan'
                    }
                ],

                createdRow: function(row, data) {
                    $(row).css('cursor', 'pointer');

                    $(row).on('click', function() {

                        simpanFilterIGD();

                        window.location.href =
                            "{{ route('igd.detail', ':id') }}".replace(':id', data.ID);
                    });
                },

                responsive: false,
                autoWidth: false,
                scrollX: false,

                paging: true,
                pageLength: 200,

                ordering: true,
                order: [
                    [1, 'desc']
                ],

                drawCallback: function(settings) {
                    let total = settings.fnRecordsTotal();
                    $('#totalPasienHeader').html(total + ' Pasien');
                },

                lengthMenu: [
                    [50, 100, 150, 200, -1],
                    [50, 100, 150, 200, "Semua"]
                ],

                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }

            });

            $('#tglAwal,#tglAkhir').on('change', function() {
                simpanFilterIGD();
            });

        });

        function reloadIGD() {

            simpanFilterIGD();

            tableIGD.ajax.reload();
        }

        function toDbDate(tgl) {
            if (!tgl) return '';

            let p = tgl.split('-'); // 12-06-2026

            if (p.length !== 3) return tgl;

            return p[2] + '-' + p[1] + '-' + p[0]; // 2026-06-12
        }
    </script>
@stop
