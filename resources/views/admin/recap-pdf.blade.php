<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Tahunan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 11px; color: #1a1a2e; }
        .header { text-align: center; border-bottom: 3px double #0f2557; padding-bottom: 10px; margin-bottom: 16px; }
        .header h1 { font-size: 15px; text-transform: uppercase; letter-spacing: 1px; color: #0f2557; }
        .header p { font-size: 10px; color: #555; margin-top: 2px; }
        .meta { display: inline-block; width: 100%; margin-bottom: 10px; font-size: 10px; }
        .meta .left { float: left; }
        .meta .right { float: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #999; padding: 5px 6px; }
        th { background: #eef1f7; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.4px; color: #0f2557; }
        td.num, th.num { text-align: right; }
        tfoot td { background: #eef1f7; font-weight: bold; }
        .footer { margin-top: 24px; font-size: 9px; color: #777; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kantor Wilayah Kementerian Hukum dan HAM Bengkulu</h1>
        <p>Rekapitulasi Pelaporan Notaris — {{ $regionName }}</p>
    </div>

    <div class="meta">
        <span class="left"><strong>Periode:</strong> Rekapitulasi Tahunan</span>
        <span class="right"><strong>Dicetak:</strong> {{ $printedAt }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tahun</th>
                <th class="num">Laporan</th>
                <th class="num">Akta</th>
                <th class="num">Disahkan</th>
                <th class="num">Dibukukan</th>
                <th class="num">Wasiat</th>
                <th class="num">Protes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($years as $row)
                <tr>
                    <td>{{ $row->report_year }}</td>
                    <td class="num">{{ number_format($row->total_laporan) }}</td>
                    <td class="num">{{ number_format($row->total_akta) }}</td>
                    <td class="num">{{ number_format($row->total_disahkan) }}</td>
                    <td class="num">{{ number_format($row->total_dibukukan) }}</td>
                    <td class="num">{{ number_format($row->total_wasiat) }}</td>
                    <td class="num">{{ number_format($row->total_protes) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;">Belum ada data rekapitulasi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak dari Sistem Informasi Pelaporan Notaris
    </div>
</body>
</html>
