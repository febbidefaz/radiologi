<script>
    function openInsertLain() {

        $('#lainID').val('');

        $('#lainTgl').val(
            '{{ date('Y-m-d') }}'
        );

        $('#lainNama').val('');

        $('#lainBiaya').val(0);

        $('#lainPot').val(0);


        $('#btnHapusLain').hide();


        $('#btnSimpanLain')
            .attr(
                'onclick',
                'simpanInsertLain()'
            )
            .html(
                '<i class="fas fa-save mr-1"></i> Simpan'
            );


        $('#modalInsertLain').modal('show');

    }

    function openEditLain(lainID, tgl, nama, biaya, pot) {

        $('#lainID').val(lainID);

        $('#lainTgl').val(tgl);

        $('#lainNama').val(nama);

        $('#lainBiaya').val(biaya);

        $('#lainPot').val(pot);


        $('#btnHapusLain').show();


        $('#btnSimpanLain')
            .attr(
                'onclick',
                'simpanEditLain()'
            )
            .html(
                '<i class="fas fa-save mr-1"></i> Simpan Perubahan'
            );


        $('#modalInsertLain').modal('show');

    }

    function simpanInsertLain() {

        if (!$('#lainNama').val()) {

            notifWarning(
                'Perhatian',
                'Nama biaya lain-lain wajib diisi.'
            );

            return;

        }


        $.ajax({

            url: "{{ route('lain.insert') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                ID: "{{ $pasien->ID ?? '' }}",

                TGL: $('#lainTgl').val(),

                Lain: $('#lainNama').val(),

                BiayaLain: $('#lainBiaya').val(),

                Pot: $('#lainPot').val() || 0,

                KlasID: null,

                RoomID: null

            },

            success: function() {

                location.reload();

            },

            error: function(xhr) {

                notifError(
                    'Gagal',
                    xhr.responseJSON?.message ??
                    'Gagal menambahkan biaya lain-lain.'
                );

            }

        });

    }

    function simpanEditLain() {

        $.ajax({

            url: "{{ route('lain.update') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                ID: "{{ $pasien->ID ?? '' }}",

                Lain_ID: $('#lainID').val(),

                TGL: $('#lainTgl').val(),

                Lain: $('#lainNama').val(),

                BiayaLain: $('#lainBiaya').val(),

                Pot: $('#lainPot').val() || 0

            },

            success: function() {

                location.reload();

            },

            error: function(xhr) {

                notifError(
                    'Gagal',
                    xhr.responseJSON?.message ??
                    'Gagal mengubah biaya lain-lain.'
                );

            }

        });

    }

    async function hapusLain() {

        const result =
            await konfirmasiAksi({

                title: 'Hapus Biaya Lain-lain?',

                text: 'Data biaya lain-lain ini akan dihapus.',

                confirmText: 'Ya, Hapus',

                icon: 'warning'
            });


        if (!result.isConfirmed) {
            return;
        }


        $.ajax({

            url: "{{ route('lain.delete') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                ID: "{{ $pasien->ID ?? '' }}",

                Lain_ID: $('#lainID').val()
            },


            success: function() {

                notifSuccess(
                    'Biaya lain-lain berhasil dihapus'
                ).then(() => {

                    location.reload();

                });
            },


            error: function(xhr) {

                notifError(
                    'Gagal',
                    xhr.responseJSON?.message ??
                    'Gagal menghapus biaya lain-lain.'
                );
            }

        });
    }
</script>
