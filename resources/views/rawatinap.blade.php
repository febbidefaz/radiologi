{{-- @extends('adminlte::page') --}}
@extends('layouts.rad-admin')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('title', 'Rawat Inap')


@section('content_header')

    <div class="d-flex align-items-center">

        <h1 class="mb-0 mr-2">
            Data Rawat Inap
        </h1>

        <span class="badge shadow-sm px-3 py-2" id="totalPasienHeader"
            style="
            font-size:14px;
            font-weight:1000;
            border-radius:10px;
            letter-spacing:0.5px;       
        ">

            0 Pasien

        </span>

    </div>

@stop


@section('content')

    <div class="card shadow-sm">

        <div class="card-body p-0">

            <div class="table-wrap">

                <table id="tblRawatInap" class="table table-hover table-striped table-bordered nowrap">

                    <thead class="bg-info">

                        <tr>
                            <th style="width:40px">PxRS</th>
                            <th style="width:40px">ID</th>
                            <th style="width:40px">NoRM</th>
                            <th style="width:50px">Nama Pasien</th>
                            <th style="width:50px">Ruang</th>
                            <th style="width:50px">T.Masuk</th>
                            <th style="width:60px">NoSEP</th>
                            <th style="width:10px">Kls</th>
                            <th style="width:100px">Alamat</th>
                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>

@stop


@push('css')
    <style>
        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 70vh;
            position: relative;
        }

        #tblRawatInap {
            min-width: 800px !important;
            width: max-content !important;
        }

        #tblRawatInap th,
        #tblRawatInap td {
            white-space: nowrap;
            vertical-align: middle;
        }

        #tblRawatInap thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #0284C7 !important;
            color: white;
        }

        #tblRawatInap tbody tr:hover td {
            background: #eef2ff !important;
        }

        #totalPasienHeader {
            background: #0284C7 !important;
            color: white !important;
        }
    </style>
@endpush


@push('js')
    <script>
        let tableRawatInap;


        const userId = "{{ session('userrad_id') ?? 'guest' }}";

        const keyPrefix = "rawat_inap_" + userId + "_";


        $(function() {


            tableRawatInap = $("#tblRawatInap").DataTable({


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


                    return savedState

                        ?
                        JSON.parse(savedState)

                        :
                        null;

                },


                ajax: {

                    url: "{{ route('rawatinap.data') }}",

                    type: "GET",

                    dataSrc: "data",

                    error: function(xhr) {

                        console.log(xhr.responseText);

                        alert('Gagal mengambil data rawat inap');

                    }

                },


                columns: [

                    {
                        data: 'PxRS',
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
                        data: 'RoomName',
                        defaultContent: '-'
                    },

                    {
                        data: 'TIN',

                        defaultContent: '-',

                        render: function(data, type) {


                            if (!data) return '-';


                            let tgl = new Date(data);


                            if (type === 'sort' || type === 'type') {

                                return tgl.getTime();

                            }


                            let dd = String(
                                tgl.getDate()
                            ).padStart(2, '0');


                            let mm = String(
                                tgl.getMonth() + 1
                            ).padStart(2, '0');


                            let yyyy = tgl.getFullYear();


                            return dd + '/' + mm + '/' + yyyy;

                        }

                    },


                    {
                        data: 'NoSEP',
                        defaultContent: '-'
                    },


                    {
                        data: 'Plavon_kls',
                        defaultContent: '-'
                    },


                    {
                        data: 'Addr',
                        defaultContent: '-'
                    },

                ],


                createdRow: function(row, data) {


                    $(row).css(
                        'cursor',
                        'pointer'
                    );


                    $(row).on(
                        'click',
                        function() {


                            window.location.href =

                                "{{ route('rawatinap.index') }}/" + data.ID;

                        }
                    );

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


                    let total = this.api()
                        .page
                        .info()
                        .recordsDisplay;


                    $('#totalPasienHeader')
                        .html(
                            total.toLocaleString('id-ID') +
                            ' Pasien'
                        );

                },


                lengthMenu: [

                    [50, 100, 150, 200, -1],

                    [50, 100, 150, 200, "Semua"]

                ],


                language: {

                    processing: "Memuat data...",

                    search: "Cari:",

                    lengthMenu: "Tampilkan _MENU_ data",

                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

                    infoEmpty: "Tidak ada data",

                    zeroRecords: "Data pasien tidak ditemukan",

                    emptyTable: "Tidak ada pasien rawat inap",

                    paginate: {

                        previous: "Sebelumnya",

                        next: "Berikutnya"

                    }

                }


            });


        });
    </script>
@endpush
