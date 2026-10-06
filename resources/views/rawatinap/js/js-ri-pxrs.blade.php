<script>
    function openUpdatePxRS() {

        $('#modalUpdatePxRS')
            .modal('show');
    }


    $(document).on(
        'submit',
        '#formUpdatePxRS',
        function(e) {

            e.preventDefault();

            const form =
                $(this);

            const uPx =
                $('#uPx').val();


            if (!uPx) {

                notifWarning(
                    'PxRS belum dipilih',
                    'Silakan pilih PxRS terlebih dahulu.'
                );

                return;
            }


            $('#btnSimpanPxRS')
                .prop('disabled', true)
                .html(`
                    <i class="fas fa-spinner fa-spin mr-1"></i>
                    Menyimpan...
                `);


            $.ajax({

                url: form.attr('action'),

                type: 'POST',

                data: form.serialize(),


                success: function(res) {

                    if (!res.success) {

                        notifError(
                            'Gagal',
                            res.message ??
                            'Gagal memperbarui PxRS.'
                        );

                        return;
                    }


                    $('#modalUpdatePxRS')
                        .modal('hide');


                    notifSuccess(
                        'PxRS berhasil diperbarui'
                    );


                    setTimeout(function() {

                        location.reload();

                    }, 500);

                },


                error: function(xhr) {

                    console.log(
                        xhr.responseText
                    );


                    notifError(
                        'Gagal',
                        xhr.responseJSON?.message ??
                        'Gagal memperbarui PxRS.'
                    );

                },


                complete: function() {

                    $('#btnSimpanPxRS')
                        .prop('disabled', false)
                        .html(`
                            <i class="fas fa-save mr-1"></i>
                            Simpan
                        `);

                }

            });

        }
    );
</script>
