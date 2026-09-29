@extends('adminlte::page')


@section('css')

    <style>
        /* NAVBAR USER */
        .rad-navbar-user .nav-link {
            cursor: pointer;
        }

        .rad-navbar-user .dropdown-menu {
            min-width: 210px;
        }

        .rad-navbar-user .dropdown-header {
            font-weight: 600;
            font-size: 14px;
        }

        .rad-navbar-user .logout-button {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
            padding: .5rem 1rem;
            color: #dc3545;
        }

        .rad-navbar-user .logout-button:hover {
            background: #f8f9fa;
        }
    </style>

    @stack('css')

@stop

{{-- 
@push('js')
    <script>
        /*
                    |--------------------------------------------------------------------------
                    | CARI PASIEN
                    |--------------------------------------------------------------------------
                    */

        function cariPasien() {

            const id = $('#cariPasien').val().trim();

            if (!id) {

                $('#cariPasien').focus();

                toastr.warning('Masukkan ID pasien');

                return;
            }


            $('#hasilCariPasien').html(`

                <div class="text-center text-muted py-3">

                    <i class="fas fa-spinner fa-spin mr-1"></i>

                    Mencari data pasien...

                </div>

            `);


            $.ajax({

                url: "{{ route('rad.cari.pasien.id') }}",

                type: "GET",

                dataType: "json",

                data: {
                    id: id
                },


                success: function(res) {

                    if (!res.data) {

                        $('#hasilCariPasien').html(`

                            <div class="alert alert-warning mb-0">

                                <i class="fas fa-exclamation-triangle mr-1"></i>

                                Data pasien tidak ditemukan.

                            </div>

                        `);

                        return;
                    }


                    const p = res.data;


                    const urlRawatInap =
                        "{{ route('rawatinap.detail', ':id') }}"
                        .replace(':id', p.ID);


                    const urlRawatJalan =
                        "{{ route('rawatjalan.detail', ':id') }}"
                        .replace(':id', p.ID);


                    const urlIgd =
                        "{{ route('igd.detail', ':id') }}"
                        .replace(':id', p.ID);


                    $('#hasilCariPasien').html(`

                        <table class="table table-bordered table-sm mt-3">

                            <tr>
                                <th width="140">ID</th>
                                <td>${p.ID ?? '-'}</td>
                            </tr>

                            <tr>
                                <th>RM</th>
                                <td>${p.RegNum ?? '-'}</td>
                            </tr>

                            <tr>
                                <th>Nama</th>
                                <td>${p.Nama ?? '-'}</td>
                            </tr>

                            <tr>
                                <th>Alamat</th>
                                <td>${p.Addr ?? '-'}</td>
                            </tr>

                            <tr>
                                <th>Gender</th>
                                <td>
                                    ${
                                        p.Jenis_Kelamin === 'P'
                                        ? 'Perempuan'
                                        : 'Laki-laki'
                                    }
                                </td>
                            </tr>

                            <tr>
                                <th>Tanggal Lahir</th>

                                <td>
                                    ${
                                        p.Tanggal_Lahir
                                        ? p.Tanggal_Lahir.substring(0, 10)
                                        : '-'
                                    }
                                </td>

                            </tr>

                            <tr>
                                <th>Layanan</th>
                                <td>${p.Layanan ?? '-'}</td>
                            </tr>

                            <tr>
                                <th>Spesialis</th>
                                <td>${p.SubLayanan ?? '-'}</td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>${p.FollowUp ?? '-'}</td>
                            </tr>

                        </table>


                        <div class="mt-3">

                            <a href="${urlRawatInap}"
                               class="btn btn-success btn-sm mr-1">

                                <i class="fas fa-bed mr-1"></i>
                                Rawat Inap

                            </a>


                            <a href="${urlRawatJalan}"
                               class="btn btn-warning btn-sm mr-1">

                                <i class="fas fa-user-md mr-1"></i>
                                Rawat Jalan

                            </a>


                            <a href="${urlIgd}"
                               class="btn btn-danger btn-sm">

                                <i class="fas fa-ambulance mr-1"></i>
                                IGD

                            </a>

                        </div>

                    `);

                },


                error: function(xhr) {

                    $('#hasilCariPasien').html(`

                        <div class="alert alert-warning mb-0">

                            <i class="fas fa-exclamation-triangle mr-1"></i>

                            Data pasien tidak ditemukan.

                        </div>

                    `);

                    console.log(xhr.responseText);
                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | MODAL
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'shown.bs.modal',
            '#modalCariPasien',
            function() {

                $('#cariPasien').trigger('focus');

            }
        );


        $(document).on(
            'hidden.bs.modal',
            '#modalCariPasien',
            function() {

                $('#cariPasien').val('');

                $('#hasilCariPasien').html('');

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ENTER = CARI
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'keydown',
            '#cariPasien',
            function(e) {

                if (e.key === 'Enter') {

                    e.preventDefault();

                    cariPasien();

                }

            }
        );
    </script>


    @stack('js')
    @stack('scripts')
@endpush
--}}
