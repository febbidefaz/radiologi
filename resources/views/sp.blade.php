@extends('layouts.rad-admin')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('title', 'Surat Permintaan Radiologi')


@section('content_header')

    <div class="d-flex align-items-center">

        <h1 class="mb-0 mr-2 sp-title"></i>
            Surat Permintaan Radiologi
        </h1>

        <span class="badge shadow-sm px-3 py-2" id="totalSP"
            style="background:#3f66d6;
                color:white;
                font-size:14px;
                font-weight:700;
                border-radius:10px;
            ">
            0 Pasien
        </span>

    </div>

@stop


@section('content')

    <div class="card shadow-sm sp-card">

        <div class="card-body p-0">

            <div class="table-wrap">

                <table id="tblSP" class="table table-hover table-striped table-bordered nowrap">

                    <thead>

                        <tr>
                            <th>PxRS</th>
                            <th>No</th>
                            <th>ID</th>
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Ket</th>
                            <th>Ruang</th>
                            <th>Tanggal</th>
                            <th>Alamat</th>
                            <th>Dokter</th>
                            <th>NIK</th>
                            <th>Telepon</th>
                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>

@stop


@section('css')

    <style>
        :root {
            --sp-primary: #3F66D6;
            --sp-primary-dark: #3457C0;
            --sp-soft: #EEF2FF;
            --sp-border: #D5DDF8;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 70vh;
            position: relative;
        }

        #tblSP {
            min-width: 1200px !important;
            width: max-content !important;
        }

        #tblSP th,
        #tblSP td {
            white-space: nowrap;
            vertical-align: middle;
        }

        #tblSP thead th {
            position: sticky;
            top: 0;
            z-index: 2;

            background: var(--sp-primary) !important;
            color: white !important;

            border-color: var(--sp-primary-dark) !important;
        }

        #tblSP tbody tr {
            cursor: pointer;
        }

        #tblSP tbody tr:hover td {
            background: var(--sp-soft) !important;
        }

        #tblSP tbody td {
            border-color: #e5e7eb;
        }

        .table-wrap::-webkit-scrollbar {
            height: 10px;
        }

        .table-wrap::-webkit-scrollbar-thumb {
            background: #8FA8F2;
            border-radius: 10px;
        }

        /* Search focus */
        #tblSP_filter input:focus {
            border-color: var(--sp-primary) !important;
            box-shadow: 0 0 0 .15rem rgba(63, 102, 214, .15) !important;
        }

        /* Pagination aktif */
        .dataTables_wrapper .page-item.active .page-link {
            background-color: var(--sp-primary) !important;
            border-color: var(--sp-primary) !important;
            color: #fff !important;
        }

        .dataTables_wrapper .page-link {
            color: var(--sp-primary);
        }

        /* Length select focus */
        .dataTables_wrapper select:focus {
            border-color: var(--sp-primary) !important;
            box-shadow: 0 0 0 .15rem rgba(63, 102, 214, .15) !important;
        }

        /* Card */
        .sp-card {
            border-top: 4px solid var(--sp-primary);
        }

        .sp-title {
            color: #3F66D6;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .sp-title i {
            color: #3F66D6;
        }
    </style>

@stop

@section('js')

    <script>
        let tableSP;

        $(function() {

            tableSP = $('#tblSP').DataTable({

                processing: true,

                ajax: {
                    url: "{{ route('sp.data') }}",
                    type: "GET",
                    dataSrc: "data",

                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Gagal mengambil data Surat Permintaan');
                    }
                },

                columns: [

                    {
                        data: 'PxRS',
                        defaultContent: '-'
                    },

                    {
                        data: 'NO',
                        defaultContent: '-'
                    },

                    {
                        data: 'ID',
                        defaultContent: '-'
                    },

                    {
                        data: 'RegNum',
                        defaultContent: '-'
                    },

                    {
                        data: 'Nama',
                        defaultContent: '-'
                    },

                    {
                        data: 'KetInap',
                        defaultContent: '-',

                        render: function(data) {

                            if (data === 'IGD') {
                                return '<span class="badge badge-danger">IGD</span>';
                            }

                            if (data === 'RI') {
                                return '<span class="badge badge-primary">RI</span>';
                            }

                            if (data === 'RJ') {
                                return '<span class="badge badge-success">RJ</span>';
                            }

                            return data ?? '-';
                        }
                    },

                    {
                        data: 'RoomName',
                        defaultContent: '-'
                    },

                    {
                        data: 'TGL',
                        defaultContent: '-',

                        render: function(data, type) {

                            if (!data) return '-';

                            let tgl = new Date(data);

                            if (type === 'sort' || type === 'type') {
                                return tgl.getTime();
                            }

                            let dd = String(tgl.getDate()).padStart(2, '0');
                            let mm = String(tgl.getMonth() + 1).padStart(2, '0');
                            let yyyy = tgl.getFullYear();

                            return dd + '/' + mm + '/' + yyyy;
                        }
                    },

                    {
                        data: 'Addr',
                        defaultContent: '-'
                    },

                    {
                        data: 'Dokter',
                        defaultContent: '-'
                    },

                    {
                        data: 'NIK',
                        defaultContent: '-'
                    },

                    {
                        data: 'Telepon',
                        defaultContent: '-'
                    }

                ],

                responsive: false,
                autoWidth: false,

                paging: true,
                pageLength: 100,

                ordering: true,

                /*
                 * NO berada di kolom ke-2,
                 * index DataTables = 1
                 */
                order: [
                    [1, 'desc']
                ],

                drawCallback: function() {

                    let total = this.api()
                        .page
                        .info()
                        .recordsDisplay;

                    $('#totalSP').html(
                        total.toLocaleString('id-ID') +
                        ' Pasien'
                    );
                },

                lengthMenu: [
                    [50, 100, 200, -1],
                    [50, 100, 200, 'Semua']
                ],

                language: {

                    processing: "Memuat data...",

                    search: "Cari:",

                    lengthMenu: "Tampilkan _MENU_ data",

                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

                    zeroRecords: "Data Surat Permintaan tidak ditemukan",

                    emptyTable: "Tidak ada Surat Permintaan",

                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }

            });


            /*
            |--------------------------------------------------------------------------
            | KLIK BARIS -> DETAIL SP
            |--------------------------------------------------------------------------
            */

            $('#tblSP tbody').on('click', 'tr', function() {

                const data = tableSP.row(this).data();

                if (!data || !data.NO) {
                    return;
                }

                let url =
                    "{{ route('sp.detail', ['no' => '__NO__']) }}";

                url = url.replace(
                    '__NO__',
                    encodeURIComponent(data.NO)
                );

                window.location.href = url;
            });

        });
    </script>

@stop
