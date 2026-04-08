<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Label TnJ 108</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 5px;
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            height: 29.7cm;
        }

        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            page-break-inside: avoid;
            font-size: 11px;
            width: 20%;
            height: 36.2px;
        }

        .nama-barang {
            font-weight: bold;
            font-size: 10px;
            display: block;
            word-wrap: break-word;
            line-height: 1.3;
            margin-bottom: 3px;
        }

        .harga-barang {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            display: block;
        }

        .kosong {
            background-color: #fafafa;
        }

        @page {
            margin: 3mm;
            size: A4 portrait;
        }
    </style>
</head>
<body>

<table>
    @php
        $colIndex = 0;
    @endphp
    @foreach($labels as $label)
        @if($colIndex % 5 == 0)
            <tr>
        @endif

        <td @if(!$label) class="kosong" @endif>
            @if($label)
                <span class="nama-barang">{{ substr($label->nama_barang, 0, 20) }}</span>
                <span class="harga-barang">Rp {{ number_format($label->harga, 0, ',', '.') }}</span>
            @endif
        </td>

        @php $colIndex++; @endphp

        @if($colIndex % 5 == 0)
            </tr>
        @endif
    @endforeach
</table>

</body>
</html>