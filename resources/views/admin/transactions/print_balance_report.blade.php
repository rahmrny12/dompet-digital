<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QR Code Siswa</title>

    <style>
        @page {
            size: landscape;
        }
    </style>
</head>

<body>
    <h4> Laporan Data Isi Ulang Saldo</h4>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>No.</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Nominal</th>
                <th>Biaya Layanan</th>
                <th>Nominal Bersih</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalNominal = 0;
            $totalServiceCharge = 0; ?>
            @foreach ($recharge as $key => $data)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $data->student->nisn }}</td>
                    <td>{{ $data->student->name }}</td>
                    <td>Rp. {{ number_format($data->amount, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($data->service_charge, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($data->amount - $data->service_charge, 0, ',', '.') }}</td>
                    <?php
                    $totalNominal += $data->amount;
                    $totalServiceCharge += $data->service_charge;
                    ?>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" align="end">Total : </td>
                <td>Rp. {{ number_format($totalNominal, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($totalServiceCharge, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($totalNominal - $totalServiceCharge, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <script>
        window.print()
    </script>

</body>

</html>
