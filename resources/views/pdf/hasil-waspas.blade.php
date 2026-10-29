<!DOCTYPE html>
<html>
<head>
    <title>Laporan WASPAS</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>
    LAPORAN HASIL PERHITUNGAN METODE WASPAS
</h2>

<p>
    Tanggal Penilaian :
    {{ $tanggal ? \Carbon\Carbon::parse($tanggal)->format('d-m-Y') : '-' }}
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Alternatif</th>
            <th>Nilai Qi</th>
            <th>Ranking</th>
        </tr>
    </thead>

    <tbody>
        @foreach($hasil as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item['alternatif'] }}</td>
            <td>{{ number_format($item['qi'], 4) }}</td>
            <td>{{ $item['rank'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<p>
    <strong>Alternatif Terbaik :</strong>
    {{ $hasil[0]['alternatif'] ?? '-' }}
</p>

</body>
</html>