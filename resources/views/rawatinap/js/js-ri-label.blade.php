<script>
    function openLabelPrint(url) {
        const width = 900;
        const height = 700;

        const left = Math.max(
            0,
            Math.round((window.screen.width - width) / 2)
        );

        const top = Math.max(
            0,
            Math.round((window.screen.height - height) / 2)
        );

        const popup = window.open(
            url,
            'labelTengahPrintPopup',
            [
                `width=${width}`,
                `height=${height}`,
                `left=${left}`,
                `top=${top}`,
                'resizable=yes',
                'scrollbars=yes',
                'toolbar=no',
                'menubar=no',
                'location=no',
                'status=no'
            ].join(',')
        );

        if (!popup) {
            alert('Popup diblokir browser. Silakan izinkan popup untuk aplikasi ini.');
            return;
        }

        popup.focus();
    }
</script>
