<script>
    // ============================================================
    // RADIOLOGI RAWAT INAP
    // ============================================================

    const radHeaders = @json($rad ?? []);
    const radDetails = @json($radDetail ?? []);


    // ============================================================
    // HELPER
    // ============================================================

    function radNotif(icon, title, message) {

        if (typeof Swal !== 'undefined') {

            Swal.fire({
                icon: icon,
                title: title,
                text: message
            });

            return;
        }

        alert(title + '\n' + message);
    }


    function radEscapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }

        return $('<div>')
            .text(String(value))
            .html();
    }


    function radRupiah(value) {

        return new Intl.NumberFormat(
            'id-ID'
        ).format(
            Number(value || 0)
        );
    }


    function radFormatTanggal(value) {

        if (!value) {
            return '';
        }

        const str = String(value);


        // SQL / ISO
        // 2026-09-30...
        if (/^\d{4}-\d{2}-\d{2}/.test(str)) {

            const parts =
                str.substring(0, 10).split('-');

            return (
                parts[2] +
                '/' +
                parts[1] +
                '/' +
                parts[0]
            );
        }


        const d = new Date(value);

        if (isNaN(d.getTime())) {
            return '';
        }


        return (
            String(d.getDate()).padStart(2, '0') +
            '/' +
            String(d.getMonth() + 1).padStart(2, '0') +
            '/' +
            d.getFullYear()
        );
    }


    // ============================================================
    // DETAIL RADIOLOGI
    // ============================================================

    function openDetailRad(idRad) {

        console.log(
            'OPEN RADIOLOGI:',
            idRad
        );


        if (!idRad) {

            radNotif(
                'warning',
                'ID Radiologi kosong',
                'Data Radiologi tidak dapat dibuka.'
            );

            return;
        }


        // ========================================================
        // HEADER
        // ========================================================

        const header =
            radHeaders.find(function(item) {

                return (
                    String(item.IDRad) ===
                    String(idRad)
                );

            });


        if (!header) {

            console.error(
                'Header Radiologi tidak ditemukan:',
                idRad,
                radHeaders
            );


            radNotif(
                'warning',
                'Data tidak ditemukan',
                'Radiologi ID ' +
                idRad +
                ' tidak ditemukan.'
            );

            return;
        }


        // ========================================================
        // DETAIL
        // ========================================================

        const detail =
            radDetails[idRad] ??
            radDetails[String(idRad)] ?? [];


        console.log(
            'HEADER RAD:',
            header
        );

        console.log(
            'DETAIL RAD:',
            detail
        );


        // ========================================================
        // ID RADIOLOGI
        // ========================================================

        $('#radIDRad')
            .val(idRad);

        $('#radIDRadView')
            .val(idRad);


        // ========================================================
        // TITLE
        // ========================================================

        const sudahBayar = !!header.TglByr;


        $('#radTitle').html(

            '<i class="fas fa-x-ray mr-2"></i>' +

            (
                sudahBayar ?
                'Detail Radiologi ' :
                'Edit Radiologi '
            ) +

            '<small class="ml-2 font-weight-normal">' +

            'ID Rad : ' +
            radEscapeHtml(idRad) +

            (
                sudahBayar ?
                ' - Sudah Dibayar' :
                ''
            ) +

            '</small>'
        );


        $('#radSubTitle')
            .text(
                'Pemeriksaan Radiologi Rawat Inap'
            );


        // ========================================================
        // TANGGAL
        // ========================================================

        $('#radTanggal').val(

            header.TRadC ??

            radFormatTanggal(
                header.TRad
            )

        );


        // ========================================================
        // DOKTER
        // ========================================================
        //
        // value option = Dokter.ID
        //

        $('#radDokter')
            .val(
                header.DokterID ?? ''
            );


        // ========================================================
        // READ BY
        // ========================================================
        //
        // ReadBy menggunakan master dokter yang sama.
        //

        $('#radReadBy')
            .val(
                header.ReadBy ?? ''
            );


        // ========================================================
        // ALAT
        // ========================================================
        //
        // value option = Alat.AlatID
        //

        $('#radAlat')
            .val(
                header.AlatID ?? ''
            );


        // ========================================================
        // INFO ALAT
        // ========================================================

        $('#radAlatInfo')
            .val(
                header.AlatName ??
                detail?.[0]?.AlatName ??
                '-'
            );


        // ========================================================
        // RUANG RADIOLOGI
        // ========================================================

        $('#radRoom')
            .val(
                header.RoomName ??
                detail?.[0]?.RoomName ??
                '-'
            );


        // ========================================================
        // STATUS
        // ========================================================

        $('#radStatus')
            .val(
                sudahBayar ?
                'Sudah Dibayar' :
                'Belum Dibayar'
            );


        // Warna status

        $('#radStatus')
            .removeClass(
                'text-success text-danger'
            );


        if (sudahBayar) {

            $('#radStatus')
                .addClass(
                    'text-success'
                );

        } else {

            $('#radStatus')
                .addClass(
                    'text-danger'
                );
        }


        // ========================================================
        // INFO PEMERIKSAAN
        // ========================================================

        const infoPemeriksaan =
            (detail || [])
            .map(function(item, index) {

                return (
                    (index + 1) +
                    '. ' +
                    (
                        item.Periksa ??
                        '-'
                    )
                );

            })
            .join('\n');


        $('#radInfoPemeriksaan')
            .val(
                infoPemeriksaan
            );


        // ========================================================
        // JUMLAH PEMERIKSAAN
        // ========================================================

        $('#radJumlahPemeriksaan')
            .text(
                detail.length +
                ' pemeriksaan'
            );


        $('#radTotalItem')
            .text(
                detail.length
            );


        // ========================================================
        // RENDER DETAIL
        // ========================================================

        renderRadDetail(
            detail
        );


        // ========================================================
        // LOCK JIKA SUDAH BAYAR
        // ========================================================

        setRadEditMode(
            sudahBayar
        );


        // ========================================================
        // OPEN MODAL
        // ========================================================

        $('#modalFormRad')
            .modal('show');
    }


    // ============================================================
    // MODE EDIT / LOCK
    // ============================================================

    function setRadEditMode(sudahBayar) {

        /*
        |--------------------------------------------------------------------------
        | Sudah dibayar
        |--------------------------------------------------------------------------
        | Dokter, Read By dan Alat tidak boleh diubah.
        */

        $('#radDokter')
            .prop(
                'disabled',
                sudahBayar
            );


        $('#radReadBy')
            .prop(
                'disabled',
                sudahBayar
            );


        $('#radAlat')
            .prop(
                'disabled',
                sudahBayar
            );
    }


    // ============================================================
    // RENDER DETAIL RADIOLOGI
    // ============================================================

    function renderRadDetail(items) {

        const tbody =
            $('#radDetailBody');


        tbody.empty();


        if (
            !items ||
            items.length === 0
        ) {

            tbody.html(`
                <tr>

                    <td
                        colspan="5"
                        class="text-center text-muted py-4"
                    >

                        <i class="fas fa-x-ray fa-2x mb-2 d-block"></i>

                        Tidak ada detail pemeriksaan Radiologi.

                    </td>

                </tr>
            `);


            $('#radTotalBiaya')
                .text(
                    'Rp 0'
                );


            $('#radJumlahPemeriksaan')
                .text(
                    '0 pemeriksaan'
                );


            $('#radTotalItem')
                .text(
                    '0'
                );


            return;
        }


        let total = 0;


        items.forEach(
            function(item, index) {

                const biaya =
                    Number(
                        item.Biaya || 0
                    );


                total += biaya;


                tbody.append(`

                    <tr>

                        <td class="text-center">

                            ${index + 1}

                        </td>


                        <td>

                            ${radEscapeHtml(
                                item.Periksa ?? '-'
                            )}

                        </td>


                        <td>

                            ${radEscapeHtml(
                                item.AlatName ?? '-'
                            )}

                        </td>


                        <td>

                            ${radEscapeHtml(
                                item.RoomName ?? '-'
                            )}

                        </td>


                        <td class="text-right font-weight-bold">

                            Rp ${radRupiah(
                                biaya
                            )}

                        </td>

                    </tr>

                `);

            }
        );


        // ========================================================
        // TOTAL
        // ========================================================

        $('#radTotalBiaya')
            .text(
                'Rp ' +
                radRupiah(total)
            );


        $('#radJumlahPemeriksaan')
            .text(
                items.length +
                ' pemeriksaan'
            );


        $('#radTotalItem')
            .text(
                items.length
            );
    }


    // ============================================================
    // INSERT RADIOLOGI
    // ============================================================

    function openInsertRad() {

        // Bersihkan field transaksi

        $('#radIDRad')
            .val('');

        $('#radIDRadView')
            .val('');

        $('#radDokter')
            .val('');

        $('#radReadBy')
            .val('');

        $('#radAlat')
            .val('');

        $('#radAlatInfo')
            .val('');

        $('#radRoom')
            .val('');

        $('#radStatus')
            .val('Belum Dibayar');

        $('#radInfoPemeriksaan')
            .val('');

        $('#radDetailBody')
            .html(`
                <tr>

                    <td
                        colspan="5"
                        class="text-center text-muted py-4"
                    >

                        <i class="fas fa-x-ray fa-2x mb-2 d-block"></i>

                        Belum ada pemeriksaan Radiologi.

                    </td>

                </tr>
            `);


        $('#radTotalBiaya')
            .text(
                'Rp 0'
            );

        $('#radTotalItem')
            .text(
                '0'
            );

        $('#radJumlahPemeriksaan')
            .text(
                '0 pemeriksaan'
            );


        // Tanggal sekarang

        const now =
            new Date();


        const tanggal =
            String(
                now.getDate()
            ).padStart(2, '0') +
            '/' +
            String(
                now.getMonth() + 1
            ).padStart(2, '0') +
            '/' +
            now.getFullYear();


        $('#radTanggal')
            .val(
                tanggal
            );


        // Aktifkan dropdown

        $('#radDokter')
            .prop(
                'disabled',
                false
            );

        $('#radReadBy')
            .prop(
                'disabled',
                false
            );

        $('#radAlat')
            .prop(
                'disabled',
                false
            );


        // Title

        $('#radTitle')
            .html(
                '<i class="fas fa-x-ray mr-2"></i>' +
                'Tambah Radiologi'
            );


        $('#radSubTitle')
            .text(
                'Input Pemeriksaan Radiologi Rawat Inap'
            );


        $('#modalFormRad')
            .modal('show');
    }


    // ============================================================
    // PRINT KWITANSI
    // ============================================================

    function printRadKwitansiModal() {

        const idRad =
            $('#radIDRad').val();


        if (!idRad) {

            radNotif(
                'warning',
                'ID Radiologi belum tersedia',
                'Silakan pilih data Radiologi terlebih dahulu.'
            );

            return;
        }


        const url =
            "{{ url('/rad/printkwitansi') }}/" +
            encodeURIComponent(
                idRad
            );


        window.open(
            url,
            '_blank'
        );
    }


    // ============================================================
    // PRINT RADIOLOGI
    // ============================================================

    function printRadModal() {

        const idRad =
            $('#radIDRad').val();


        if (!idRad) {

            radNotif(
                'warning',
                'ID Radiologi belum tersedia',
                'Silakan pilih data Radiologi terlebih dahulu.'
            );

            return;
        }


        const url =
            "{{ url('/rad/print') }}/" +
            encodeURIComponent(
                idRad
            );


        window.open(
            url,
            '_blank'
        );
    }
</script>
