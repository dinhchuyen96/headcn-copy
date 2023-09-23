<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{asset('assets/favicon.ico')}}" type="image/x-icon">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" type="text/css" rel="stylesheet" />
</head>
@livewireStyles
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    td,
    th {
        border: 1px solid black;
        height: 19px;
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

<body>
    @livewire('service.maintain-list-print-check-no')
    @livewireScripts
</body>

</html>
