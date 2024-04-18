<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QR Code Siswa</title>
</head>

<body>
    <h4> Data QR Code Kelas : {{ $selected_classroom->name }}</h4>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>No.</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>QR Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $key => $data)
                <tr>
                    <td align="center">{{ $key + 1 }}</td>
                    <td align="center">{{ $data->nisn }}</td>
                    <td align="center">{{ $data->name }}</td>
                    <td align="center" style="padding: 24px 0;">
                        {!! QrCode::size(128)->generate($data->nisn) !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.print()
    </script>

</body>

</html>
