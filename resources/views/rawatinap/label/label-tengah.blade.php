<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Print Label</title>
    <link rel="stylesheet" href="{{ asset('css/print-inpatient-letter-1-0-1.css') }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <style>
        body {
            margin: 0px;
            padding: 0px;
        }

        .page {
            width: 180mm;
            height: 135mm;
            background: white;
            display: flex;
            flex-wrap: wrap;
        }

        html {
            margin-top: 3mm;
            padding: 0;
        }

        .page {
            display: flex;
            flex-wrap: wrap;
            gap: 3mm;
            margin-left: 300px;
        }

        .label {
            width: 60mm;
            height: 24mm;
            margin-right: 15mm;
            margin-bottom: 0.2mm;
        }

        .id {
            font-size: 15px !important;
            color: rgb(0, 102, 255) !important;
            font-weight: bold !important;
        }

        .name {
            font-size: 15px !important;
            color: rgb(0, 102, 255) !important;
            font-weight: bold !important;
        }

        .addr {
            font-size: 11px !important;
            color: rgb(0, 102, 255) !important;
            font-weight: bold !important;
        }

        .label tr:last-child {
            font-size: 8px !important;
        }
    </style>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        crossorigin="anonymous">

</head>

<body onload="window.print()" style="display:flex;justify-content: center;">
    <div class="page">
        @for ($i = 0; $i < 14; $i++)
            <div class="label">
                <table>
                    <tr>
                        <td class="id">{{ $patient['ID'] }} {{ $patient['RegNum'] }}
                            {{ date('d/m/Y', strtotime($patient['Tanggal_Lahir'])) }}</td>
                    </tr>
                    <tr>
                        <td class="name">{{ $patient['Nama'] }}</td>
                    </tr>
                    <tr>
                        <td class="addr">{{ $patient['Addr'] }}</td>
                    </tr>
                </table>
            </div>
        @endfor
    </div>
</body>

</html>
