<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        @media print {
        @page {
            margin: 0;
        }

        body {
            margin: 1.2cm;
        }
    }

    </style>
</head>

<body>
    @livewire('ketoan.print-phieu-thu',['data'=>$data])
    @livewireScripts
</body>

</html>
