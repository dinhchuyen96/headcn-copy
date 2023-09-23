<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
@livewireStyles
<style>
    .head {
        display: flex;
        justify-content: space-between;
    }

    .left-store-print {
        max-width: 265px;
    }

    .row-print {
        height: 10%;
    }

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

    .head-table-print {
        height: 20px;
    }

    .footer-info {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .table-left {
        width: 49.5%;
        padding-right: 10px;
    }

    .table-right {
        width: 49.5%;
    }

    button {
        height: 30px;
        width: 120px;
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
    @livewire('service.print-list-service',['id'=>$id])
    @livewireScripts
</body>

</html>
