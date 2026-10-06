{{-- ============================================================
     MODAL INSERT / UPDATE / DETAIL RADIOLOGI
     DESAIN MENGIKUTI MODAL LAB SEBELUMNYA
============================================================ --}}

<div class="modal fade" id="modalFormRad" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg">


            {{-- ======================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header modal-modern">

                <div>

                    <h5 class="modal-title font-weight-bold mb-0" id="radTitle">

                        <i class="fas fa-x-ray mr-2"></i>

                        Radiologi

                    </h5>


                    <small id="radSubTitle">

                        Pemeriksaan Radiologi Rawat Inap

                    </small>

                </div>


                <button type="button" class="close text-white" data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>



            {{-- ======================================================
                 BODY
            ====================================================== --}}

            <div class="modal-body bg-light">


                {{-- ID RAD --}}

                <input type="hidden" id="radIDRad">



                {{-- ==================================================
                     BAGIAN ATAS
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-3">

                    <div class="card-body py-3">

                        <div class="row">


                            {{-- =====================================
                                 KOLOM 1 - PASIEN
                            ====================================== --}}

                            <div class="col-md-3">


                                {{-- ID REG --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        ID Reg
                                    </label>


                                    <div class="col-sm-5">

                                        <input type="text" id="radIDReg" class="form-control form-control-sm"
                                            value="{{ $pasien->ID ?? '' }}" readonly>

                                    </div>

                                </div>



                                {{-- NO RM --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        No RM
                                    </label>


                                    <div class="col-sm-5">

                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $pasien->RegNum ?? '' }}" readonly>

                                    </div>

                                </div>



                                {{-- PASIEN --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        Pasien
                                    </label>


                                    <div class="col-sm-9">

                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $pasien->Nama ?? '' }}" readonly>

                                    </div>

                                </div>



                                {{-- ALAMAT --}}

                                <div class="form-group row mb-0">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        Alamat
                                    </label>


                                    <div class="col-sm-9">

                                        <input type="text" id="radAlamat" class="form-control form-control-sm"
                                            value="{{ $pasien->Addr ?? '' }}" readonly>

                                    </div>

                                </div>



                                {{-- UMUR --}}

                                <div class="form-group row mb-3 mt-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        Umur
                                    </label>


                                    <div class="col-sm-8">

                                        @php

                                            $umurTahun = '';
                                            $umurBulan = '';
                                            $umurHari = '';

                                            if (!empty($pasien->Tanggal_Lahir)) {
                                                $lahir = \Carbon\Carbon::parse($pasien->Tanggal_Lahir);

                                                $sekarang = \Carbon\Carbon::now();

                                                $diff = $lahir->diff($sekarang);

                                                $umurTahun = $diff->y;

                                                $umurBulan = $diff->m;

                                                $umurHari = $diff->d;
                                            }

                                        @endphp


                                        <div class="umur-modern">


                                            <div class="umur-box">

                                                <div class="umur-value" id="radUmurTahun">
                                                    {{ $umurTahun }}
                                                </div>

                                                <div class="umur-label">
                                                    Tahun
                                                </div>

                                            </div>



                                            <div class="umur-box">

                                                <div class="umur-value" id="radUmurBulan">
                                                    {{ $umurBulan }}
                                                </div>

                                                <div class="umur-label">
                                                    Bulan
                                                </div>

                                            </div>



                                            <div class="umur-box">

                                                <div class="umur-value" id="radUmurHari">
                                                    {{ $umurHari }}
                                                </div>

                                                <div class="umur-label">
                                                    Hari
                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                </div>

                            </div>



                            {{-- =====================================
                                 KOLOM 2 - DOKTER / WAKTU
                            ====================================== --}}

                            <div class="col-md-3 border-left">


                                {{-- ID RAD --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        Dokter
                                    </label>

                                    <div class="col-sm-9">

                                        <select id="radDokter" class="form-control form-control-sm">

                                            <option value="">
                                                -- Pilih Dokter --
                                            </option>

                                            @foreach ($dokterList ?? [] as $d)
                                                <option value="{{ $d->ID }}">
                                                    {{ $d->DokterAlias }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>



                                <div class="form-group row mb-2">

                                    <label class="col-sm-4 col-form-label col-form-label-sm">
                                        Alat
                                    </label>

                                    <div class="col-sm-8">

                                        <select id="radAlat" class="form-control form-control-sm">

                                            <option value="">
                                                -- Pilih Alat --
                                            </option>

                                            @foreach ($alatList ?? [] as $a)
                                                <option value="{{ $a->AlatID }}">
                                                    {{ $a->AlatName }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                <div class="form-group row mb-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        Read By
                                    </label>

                                    <div class="col-sm-9">

                                        <select id="radReadBy" class="form-control form-control-sm">

                                            <option value="">
                                                -- Pilih Dokter --
                                            </option>

                                            @foreach ($dokterList ?? [] as $d)
                                                <option value="{{ $d->ID }}">
                                                    {{ $d->DokterAlias }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>



                                {{-- TANGGAL --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        Tanggal
                                    </label>


                                    <div class="col-sm-7">

                                        <input type="text" id="radTanggal" class="form-control form-control-sm"
                                            readonly>

                                    </div>

                                </div>



                                {{-- STATUS --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-3 col-form-label col-form-label-sm">
                                        Status
                                    </label>


                                    <div class="col-sm-7">

                                        <input type="text" id="radStatus"
                                            class="form-control form-control-sm font-weight-bold" value="Radiologi"
                                            readonly>

                                    </div>

                                </div>

                            </div>



                            {{-- =====================================
                                 KOLOM 3 - RUANG / ORDER
                            ====================================== --}}

                            <div class="col-md-3 border-left">

                                <div class="form-group row mb-2">



                                    <label class="col-sm-4 col-form-label col-form-label-sm">
                                        No SP
                                    </label>




                                    <div class="col-sm-8">

                                        <select id="radNoSP" class="form-control form-control-sm">

                                            <option value="">
                                                -- Pilih No SP --
                                            </option>

                                            @foreach ($spRadList ?? [] as $sp)
                                                <option value="{{ $sp->NO }}">

                                                    {{ $sp->NO }}
                                                    -
                                                    {{ $sp->Nama }}
                                                    -
                                                    {{ $sp->Register }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                {{-- PX RUJUKAN --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-4 col-form-label col-form-label-sm">
                                        PX Rujukan
                                    </label>


                                    <div class="col-sm-8">

                                        <input type="text" id="radRujukan" class="form-control form-control-sm"
                                            value="{{ $pasien->Rujukan ?? '' }}" readonly>

                                    </div>


                                    {{-- RUANG RAWAT --}}

                                    <div class="form-group row mb-2">

                                        <label class="col-sm-4 col-form-label col-form-label-sm">
                                            Kelas/Ruang
                                        </label>


                                        <div class="col-sm-8">

                                            <input type="text" id="radRuangRawat"
                                                class="form-control form-control-sm"
                                                value="{{ $pasien->RoomName ?? '' }}" readonly>

                                        </div>

                                    </div>

                                </div>







                                {{-- RUANG RADIOLOGI --}}

                                <div class="form-group row mb-2">

                                    <label class="col-sm-4 col-form-label col-form-label-sm">
                                        Ruang Rad
                                    </label>


                                    <div class="col-sm-8">

                                        <input type="text" id="radRoom" class="form-control form-control-sm"
                                            readonly>

                                    </div>

                                </div>

                            </div>



                            {{-- =====================================
                                 KOLOM 4 - INFORMASI
                            ====================================== --}}

                            <div class="col-md-3 border-left">


                                <div class="form-group mb-1">

                                    <div class="font-weight-bold text-primary mb-1">
                                        INFO PEMERIKSAAN
                                    </div>


                                    <textarea id="radInfoPemeriksaan" class="form-control form-control-sm" rows="7" readonly
                                        placeholder="Belum ada pemeriksaan dipilih."></textarea>

                                </div>


                            </div>


                        </div>

                    </div>

                </div>



                {{-- ======================================================
                     MASTER / DETAIL PEMERIKSAAN
                     GAYA SAMA SEPERTI MODAL LAB
                ====================================================== --}}

                <div class="card border-0 shadow-sm mb-3">


                    <div class="card-header py-2 bg-white">


                        <div class="d-flex align-items-center flex-wrap" style="gap:5px;">

                            <strong>

                                <i class="fas fa-x-ray text-primary mr-1"></i>

                                Pemeriksaan Radiologi

                            </strong>


                            <span class="badge badge-light border ml-2" id="radJumlahPemeriksaan">
                                0 pemeriksaan
                            </span>


                        </div>

                    </div>



                    <div class="card-body p-0">


                        <div class="table-responsive lab-table-wrap">


                            <table class="table table-bordered table-sm mb-0" id="tableInputRad">

                                <thead>

                                    <tr>

                                        <th width="40" class="text-center">
                                            No
                                        </th>


                                        <th style="min-width:280px">
                                            Pemeriksaan
                                        </th>


                                        <th style="min-width:180px">
                                            Alat
                                        </th>


                                        <th style="min-width:180px">
                                            Ruangan
                                        </th>


                                        <th width="140" class="text-right">
                                            Biaya
                                        </th>

                                    </tr>

                                </thead>



                                <tbody id="radDetailBody">

                                    <tr id="radEmptyRow">

                                        <td colspan="5" class="text-center text-muted py-4">

                                            <i class="fas fa-x-ray fa-2x mb-2 d-block"></i>

                                            Belum ada pemeriksaan Radiologi.

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>



                {{-- ======================================================
                     BAGIAN BAWAH
                     SAMA MODELNYA DENGAN LAB
                ====================================================== --}}

                <div class="card border-0 shadow-sm mb-0">

                    <div class="card-body py-2">

                        <div class="row align-items-center">


                            {{-- INFO --}}

                            <div class="col-md-6">

                                <div class="d-flex align-items-center">

                                    <i class="fas fa-info-circle text-info mr-2"></i>

                                    <small class="text-muted">

                                        Detail pemeriksaan berdasarkan
                                        transaksi Radiologi pasien.

                                    </small>

                                </div>

                            </div>



                            {{-- JUMLAH PEMERIKSAAN --}}

                            <div class="col-md-3">

                                <div class="d-flex align-items-center justify-content-md-end">

                                    <small class="text-muted mr-2">

                                        Jumlah Pemeriksaan

                                    </small>


                                    <div class="font-weight-bold" id="radTotalItem">

                                        0

                                    </div>

                                </div>

                            </div>



                            {{-- TOTAL BIAYA --}}

                            <div class="col-md-3">

                                <div class="d-flex align-items-center justify-content-md-end">

                                    <small class="text-muted mr-2">

                                        Total Biaya

                                    </small>


                                    <div class="font-weight-bold text-primary" id="radTotalBiaya">

                                        Rp 0

                                    </div>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>


            </div>



            {{-- ======================================================
                 FOOTER
            ====================================================== --}}

            <div class="modal-footer bg-white d-flex justify-content-end">


                <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>

                    Tutup

                </button>



                <button type="button" class="btn btn-info btn-sm mr-2" id="btnPrintKwitansiRad"
                    onclick="printRadKwitansiModal()">

                    <i class="fas fa-print mr-1"></i>

                    Print Kwitansi

                </button>



                <button type="button" class="btn btn-primary btn-sm" id="btnPrintRad" onclick="printRadModal()">

                    <i class="fas fa-print mr-1"></i>

                    Print Radiologi

                </button>


            </div>


        </div>

    </div>

</div>
