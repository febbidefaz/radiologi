@extends('layouts.rad-admin')

@section('title', 'Detail Rawat Inap')

@section('content_header')

    <div class="d-flex align-items-center">

        <a href="{{ route('rawatinap.index') }}" class="btn btn-secondary btn-sm mr-3">

            <i class="fas fa-arrow-left"></i>

        </a>

        <h1 class="mb-0">
            Detail Rawat Inap
        </h1>

    </div>

@stop


@section('content')

    @if (!$pasien)

        <div class="alert alert-warning">
            Data pasien tidak ditemukan.
        </div>
    @else
        {{-- ====================================================== --}}
        {{-- IDENTITAS PASIEN --}}
        {{-- ====================================================== --}}

        <div class="card shadow-sm mb-3">

            <div class="card-header pasien-header">

                <h3 class="card-title mb-0">

                    <i class="fas fa-user-injured mr-2"></i>

                    {{ $pasien->Nama }} --- {{ $pasien->Addr }}

                </h3>

            </div>

            <div class="card-body py-3">

                <div class="row align-items-start">

                    {{-- ID --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">ID</div>
                        <div class="patient-info-value font-weight-bold">
                            {{ $pasien->ID ?? '-' }}
                        </div>
                    </div>


                    {{-- NO SEP --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">No SEP</div>

                        <div class="patient-info-value font-weight-bold text-info" style="cursor:pointer;"
                            onclick="showSepDetail('{{ $pasien->NoSEP }}')">

                            {{ $pasien->NoSEP ?? '-' }}
                        </div>
                    </div>


                    {{-- NO RM --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">No RM</div>

                        <div class="patient-info-value font-weight-bold">
                            {{ $pasien->RegNum ?? '-' }}
                        </div>
                    </div>


                    {{-- LABEL --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">
                            Label
                        </div>

                        <div class="patient-info-value">
                            <div class="dropdown">

                                <a href="javascript:void(0)" class="label-link label-block dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                    <i class="fas fa-tag mr-1"></i>
                                    Label
                                </a>

                                <div class="dropdown-menu">

                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="openLabelPrint(
                                            '{{ route('rawatinap.label.tengah', ['id' => $pasien->ID]) }}'
                                        )">
                                        Label Tengah
                                    </a>

                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="openLabelPrint(
                                            '{{ route('rawatinap.label.samping', ['id' => $pasien->ID]) }}'
                                        )">
                                        Label Samping
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- PXRS --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">
                            PxRS
                        </div>

                        <div class="patient-info-value">

                            <strong class="text-primary" style="cursor:pointer;" onclick="openUpdatePxRS()"
                                title="Klik untuk mengubah PxRS">

                                <span id="textPxRS">
                                    {{ $pasien->PxRS ?? '-' }}
                                </span>

                            </strong>

                        </div>
                    </div>


                    {{-- TANGGAL MASUK --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">
                            Tanggal Masuk
                        </div>

                        <div class="patient-info-value">
                            {{ $pasien->Tanggal ? date('d/m/Y', strtotime($pasien->Tanggal)) : '-' }}
                        </div>
                    </div>


                    {{-- JAM MASUK --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">
                            Jam Masuk
                        </div>

                        <div class="patient-info-value">
                            {{ $pasien->Jam_masuk ? date('H:i', strtotime($pasien->Jam_masuk)) : '-' }}
                        </div>
                    </div>


                    {{-- TANGGAL BAYAR --}}
                    <div class="col patient-info-item">
                        <div class="patient-info-label">
                            Tanggal Bayar
                        </div>

                        <div class="patient-info-value">
                            {{ $pasien->TglByr ? date('d/m/Y', strtotime($pasien->TglByr)) : '-' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- TAB --}}
        {{-- ====================================================== --}}

        <div class="card shadow-sm">

            <div class="card-header p-0 pt-1">

                <ul class="nav nav-tabs" id="inapDetailTabs">

                    <li class="nav-item">

                        <a class="nav-link active" data-toggle="pill" href="#tab-rad">

                            <i class="fas fa-vials mr-1"></i>
                            Radiologi

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" data-toggle="pill" href="#tab-lain">

                            <i class="fas fa-list-alt mr-1"></i>
                            Lain-lain

                        </a>

                    </li>

                </ul>

            </div>


            <div class="card-body p-0">

                <div class="tab-content">


                    {{-- ====================================================== --}}
                    {{-- TAB LAB --}}
                    {{-- ====================================================== --}}

                    <div class="tab-pane fade show active" id="tab-rad">
                        <div class="p-2 border-bottom bg-light">
                            <button type="button" class="btn btn-success btn-sm" onclick="openInsertRad()"
                                @if (!empty($pasien->TglByr)) disabled @endif
                                title="{{ !empty($pasien->TglByr) ? 'Transaksi sudah dibayar' : 'Tambah Radiologi' }}">

                                <i class="fas fa-plus-circle mr-1"></i>

                                Tambah Radiologi

                            </button>

                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped mb-0">

                                <thead>

                                    <tr>
                                        <th>ID Rad</th>
                                        <th>Tanggal</th>
                                        <th>Dokter</th>
                                        <th class="text-right">
                                            Total
                                        </th>
                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse ($rad as $r)
                                        @php

                                            $totalRad = collect($radDetail[$r->IDRad] ?? [])->sum('Biaya');

                                        @endphp


                                        <tr class="row-click" onclick="openDetailRad({{ $r->IDRad }})">

                                            <td>
                                                {{ $r->IDRad ?? '-' }}
                                            </td>


                                            <td>

                                                {{ $r->TRad ? date('d/m/Y', strtotime($r->TRad)) : '-' }}

                                            </td>


                                            <td>
                                                {{ $r->Dokter ?? '-' }}
                                            </td>


                                            <td class="text-right font-weight-bold">

                                                Rp
                                                {{ number_format($totalRad, 0, ',', '.') }}

                                            </td>

                                        </tr>


                                    @empty

                                        <tr>

                                            <td colspan="4" class="text-center text-muted py-4">

                                                Tidak ada data radiologi.

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>



                    </div>



                    {{-- ====================================================== --}}
                    {{-- TAB LAIN-LAIN --}}
                    {{-- ====================================================== --}}

                    <div class="tab-pane fade" id="tab-lain">

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped mb-0">

                                <thead>

                                    <tr>

                                        <th>No</th>

                                        <th>Nama</th>

                                        <th>Tanggal</th>

                                        <th class="text-right">
                                            Tarif
                                        </th>

                                        <th class="text-right">
                                            Disc (%)
                                        </th>

                                        <th class="text-right">
                                            Jml Disc
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse ($rad as $r)
                                        @php

                                            $totalRad = collect($radDetail[$r->IDRad] ?? [])->sum('Biaya');

                                        @endphp


                                        <tr class="row-click" onclick="openDetailRad({{ $r->IDRad }})">

                                            <td>
                                                {{ $r->IDRad ?? '-' }}
                                            </td>


                                            <td>

                                                {{ $r->TRad ? date('d/m/Y', strtotime($r->TRad)) : '-' }}

                                            </td>


                                            <td>
                                                {{ $r->Dokter ?? '-' }}
                                            </td>


                                            <td class="text-right font-weight-bold">

                                                Rp
                                                {{ number_format($totalRad, 0, ',', '.') }}

                                            </td>

                                        </tr>


                                    @empty

                                        <tr>

                                            <td colspan="4" class="text-center text-muted py-4">

                                                Tidak ada data Radiologi.

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                                <tfoot>

                                    <tr class="font-weight-bold bg-light">

                                        <td colspan="2">

                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="openInsertLain()">

                                                <i class="fas fa-plus-circle mr-1"></i>

                                                Tambah Lain-lain

                                            </button>

                                        </td>


                                        <td class="text-right">
                                            Total
                                        </td>


                                        <td class="text-right">

                                            Rp
                                            {{ number_format(collect($lainlain)->sum('BiayaLain'), 0, ',', '.') }}

                                        </td>


                                        <td></td>


                                        <td class="text-right">

                                            Rp
                                            {{ number_format(
                                                collect($lainlain)->sum(function ($l) {
                                                    return ($l->BiayaLain ?? 0) * ($l->Pot ?? 0);
                                                }),
                                                0,
                                                ',',
                                                '.',
                                            ) }}

                                        </td>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- MODALS --}}
        @include('rawatinap.modal.modal-ri-rad')
        {{--    @include('rawatinap.modal.modal-ri-pilih-lab')
        @include('rawatinap.modal.modal-ri-file-lab') --}}
        @include('rawatinap.modal.modal-ri-lain')
        @include('rawatinap.modal.modal-ri-sep')
        @include('rawatinap.modal.modal-ri-pxrs')



    @endif




@stop

@section('css')

    <style>
        /* WARNA SAMA DENGAN RAWAT INAP */

        .pasien-header,
        #inapDetailTabs .nav-link.active,
        table thead th,
        .modal-modern {

            background: #0284C7 !important;
            color: white !important;

        }


        table thead th {

            white-space: nowrap;
            vertical-align: middle;

        }


        table tbody td {

            vertical-align: middle;

        }


        .row-click {

            cursor: pointer;

        }


        .row-click:hover td {

            background: #eef2ff !important;

        }


        #inapDetailTabs .nav-link {

            color: #0284C7;

        }


        #inapDetailTabs .nav-link.active {

            border-color: #0284C7 !important;

        }


        .kategori-lab {

            background: #eef2ff;

            color: #0284C7;

            font-weight: 700;

        }


        .table-responsive::-webkit-scrollbar {

            height: 9px;

        }


        .table-responsive::-webkit-scrollbar-track {

            background: #eef2ff;

            border-radius: 10px;

        }


        .table-responsive::-webkit-scrollbar-thumb {

            background: #8fa8f2;

            border-radius: 10px;

        }


        .table-responsive::-webkit-scrollbar-thumb:hover {

            background: #0284C7;

        }

        .sep-print-preview {
            background: #fff;
            color: #000;
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        .sep-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .sep-logo img {
            width: 240px;
            height: auto;
        }

        .sep-title {
            flex: 1;
            text-align: center;
            line-height: 1.2;
            font-weight: 800;
        }

        .sep-title div {
            font-size: 28px;
        }

        .sep-title small {
            display: block;
            font-size: 22px;
            margin-top: 5px;
            font-weight: 800;
        }

        .sep-table td {
            border: none !important;
            padding: 2px 4px !important;
            vertical-align: top;
            font-size: 15px;
        }

        .sep-table td:first-child {
            width: 140px;
            white-space: nowrap;
        }

        .sep-table td:nth-child(2) {
            width: 10px;
        }

        .sep-note {
            margin-top: 10px;
            font-size: 13px;
            line-height: 1.6;
        }

        #modalFormLab .modal-dialog {
            max-width: 1500px !important;
        }

        #modalFormLab .form-group {
            margin-bottom: 7px;
        }

        #modalFormLab label {
            font-size: 14px;
            font-weight: 600;
        }

        #modalFormLab .form-control-sm {
            font-size: 14px;
        }

        #modalFormLab .modal-body {
            font-size: 15px;
        }

        .lab-info-box {
            min-height: 95px;
            padding: 8px 10px;
            border: 1px solid #dbe3f5;
            background: #f8faff;
            border-radius: 5px;
        }

        .lab-table-wrap {
            max-height: 420px;
            overflow-y: auto;
        }

        #tableInputLab thead th {
            position: sticky;
            top: 0;
            z-index: 2;

            background: #0284C7 !important;
            color: white !important;

            vertical-align: middle;
        }

        #tableInputLab td {
            vertical-align: middle;
        }

        #labTotalBiaya,
        #labTotalPotongan {
            font-size: 18px;
        }

        .lab-info-box {
            min-height: 150px;
            max-height: 210px;
            overflow-y: auto;

            padding: 10px 12px;

            border: 1px solid #dbe3f5;
            background: #f8faff;
            border-radius: 5px;
        }

        #labInfoPemeriksaan {
            font-size: 13px;
            line-height: 1.2;
            font-weight: 400;
        }

        #modalFormLab .modal-footer {
            width: 100%;
            border-top: 1px solid #dee2e6;
            padding: 12px 20px;
            background: #fff;
        }

        #modalFormLab .modal-footer .btn {
            min-width: 100px;
        }

        .umur-modern {
            display: flex;
            gap: 6px;
            width: 100%;
        }

        .umur-box {
            flex: 1;
            min-width: 0;

            background: #f7f9ff;
            border: 1px solid #dbe3f5;
            border-radius: 8px;

            padding: 5px 4px;

            text-align: center;

            transition: all .2s ease;
        }

        .umur-box:hover {
            background: #eef2ff;
            border-color: #aebff3;
        }

        .umur-value {
            font-size: 14px;
            font-weight: 700;
            color: #0284C7;
            line-height: 1.2;
        }

        .umur-label {
            margin-top: 1px;
            font-size: 9px;
            font-weight: 600;
            color: #8a94a6;
            text-transform: uppercase;
            letter-spacing: .2px;
        }

        /* FOTO LAB */
        .foto-lab-card {
            position: relative;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            padding: 6px;
            height: 100%;
        }

        .foto-lab-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
        }

        .foto-lab-name {
            font-size: 11px;
            margin-top: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .foto-lab-delete {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        #tableInputLab .checkbox-kritis {
            accent-color: #dc3545;
        }

        .patient-info-item {
            display: flex;
            flex-direction: column;
        }

        .patient-info-item {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .patient-info-label {
            height: 18px;
            margin: 0 0 4px 0;

            font-size: 13px;
            line-height: 18px;

            color: #6c757d;

            white-space: nowrap;
        }

        .patient-info-value {
            height: 24px;

            display: flex;
            align-items: center;

            margin: 0;
            padding: 0;

            font-size: 16px;
            line-height: 24px;

            white-space: nowrap;
        }

        .label-link {
            display: inline-flex;
            align-items: center;

            height: 24px;

            margin: 0;
            padding: 0;

            font-size: 16px;
            line-height: 24px;
            font-weight: 700;

            color: #007bff;
            text-decoration: none;
        }

        .label-link:hover,
        .label-link:focus {
            color: #0056b3;
            text-decoration: none;
        }

        .label-link.dropdown-toggle::after {
            margin-left: 6px;
            vertical-align: middle;
        }

        .label-block {
            display: inline-flex;
            align-items: center;

            height: 24px;
            padding: 0 8px;

            background: #0284C7;
            color: #fff !important;

            border-radius: 4px;

            font-size: 13px;
            font-weight: 600;
            line-height: 24px;

            text-decoration: none !important;
        }

        .label-block:hover,
        .label-block:focus {
            background: #0069d9;
            color: #fff !important;
            text-decoration: none !important;
        }

        .label-block.dropdown-toggle::after {
            margin-left: 6px;
        }
    </style>

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('rawatinap.js.js-ri-global')

    @include('rawatinap.js.js-ri-rad-main')

    {{-- @include('rawatinap.js.js-ri-lab-group')

    @include('rawatinap.js.js-ri-lab-sp')

    @include('rawatinap.js.js-ri-lab-lis')

    @include('rawatinap.js.js-ri-lab-file') --}}

    @include('rawatinap.js.js-ri-lain')

    @include('rawatinap.js.js-ri-sep')

    @include('rawatinap.js.js-ri-pxrs')

    @include('rawatinap.js.js-ri-label')

@stop
