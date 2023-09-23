<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="logoPL.png" type="image/x-icon">
    <title> </title>
</head>
<style>
    .head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .content>h3,
    h2 {
        text-align: center;
    }

    .content>h2 {
        color: blue;
    }

    .footer-content {
        display: flex;
        justify-content: space-evenly;
        margin-top: 20px;
    }

    @media print {
        @page {
            margin: 0;
        }

        body {
            margin: 1.6cm;
        }
    }

</style>

<body>
    @livewire('motorbike.print-info-motorbike',['id'=>$id])
    @livewireScripts
</body>

</html>

