<script>
    let labItems = [];
    const sudahBayar = @json(!empty($pasien->TglByr));

    // ============================================================
    // SWEETALERT GLOBAL
    // ============================================================

    function notifWarning(title, text = '') {

        return Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    }


    function notifError(title, text = '') {

        return Swal.fire({
            icon: 'error',
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    }


    function notifInfo(title, text = '') {

        return Swal.fire({
            icon: 'info',
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    }


    function notifSuccess(title) {

        return Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: title,
            showConfirmButton: false,
            timer: 1800,
            timerProgressBar: true
        });
    }


    function konfirmasiAksi({
        title,
        text,
        confirmText = 'Ya, Lanjutkan',
        icon = 'question'
    }) {

        return Swal.fire({
            title: title,
            html: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check mr-1"></i> ' +
                confirmText,
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true
        });
    }

    function formatRupiahLab(value) {

        return Number(value || 0).toLocaleString('id-ID');
    }

    function formatTanggalInputLab(value) {

        if (!value) return '';

        const d = new Date(value);

        if (isNaN(d.getTime())) {
            return String(value).substring(0, 10);
        }

        const year = d.getFullYear();

        const month =
            String(d.getMonth() + 1).padStart(2, '0');

        const day =
            String(d.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }
</script>
