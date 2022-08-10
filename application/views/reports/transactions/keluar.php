<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        h1 {
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            font-size: 13px;
        }

        table td,
        table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>
    <?php
    if (!empty($sgudang) && $sgudang != 'semua' && !empty($gudang)) {
        $gudang = array_filter($gudang, function ($arr) use ($sgudang) {
            return $sgudang == $arr['id'];
        });
    }
    ?>
    <?php foreach ($gudang as $g) : ?>
        <?php
        $namaGudang = strpos(strtolower($g['nama']), 'gudang') === false ? 'Gudang ' . $g['nama'] : $g['nama'];
        if ($g['level_wilayah_gudang'] == '1')
            $namaGudang .= ' - Prov. ' . $g['nama_wilayah_gudang'];
        elseif ($g['level_wilayah_gudang'] == 3)
            $namaGudang .= ' - Kec. ' . $g['nama_wilayah_gudang'];

        ?>
        <h1><?= "Laporan Transaksi Keluar <br>" . kapitalize($namaGudang) . '</br>' . (!empty($tgl) ? $tgl[0] . ' sd ' . $tgl[1] : null) ?></h1>
        <br>
        <hr>
        <br>
        <?php if (!empty($g['transaksi'])) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">
                            No
                        </th>
                        <th>Pencatat</th>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Keluar</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($g['transaksi'] as $v) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $v['nama_pencatat'] ?></td>
                            <td><?= $v["transaksi_date"] ?></td>
                            <td><?= $v["transaksi_code"] ?></td>
                            <td><?= $v["item_name"] ?></td>
                            <td><?= $v["transaksi_qty"] ?></td>
                            <?php if ($v['jenis'] == 'keluar') : ?>
                                <td><?= !empty($v['tujuan']) && strlen($v['tujuan']) > 8 ? $v['tujuan'] : $v["namagudang_tujuan"] .  " - " . ($v['lvlwil_tujuan'] == '1' ? 'Prov. ' : ($v['lvlwil_tujuan'] == '3' ? 'Kec. ' : '-')) . kapitalize($v['namawil_tujuan']) ?></td>
                            <?php else : ?>
                                <td>-</td>
                            <?php endif ?>
                            <td>
                                <?php if (empty($v['dihapus'])) : ?>
                                    <?= $v['verified'] == 1 ? 'Terverifikasi' : ($v['verified'] == 2 ? 'Pending - ' . $v['keterangan'] : '-') ?>
                                <?php else : ?>
                                    <?= ($v['verified'] == 1 ? 'Terverifikasi' : ($v['verified'] == 2 ? 'Pending - ' . $v['keterangan'] : '-')) . '<b>' .  'Dihapus Pada: ' . substr($v['dihapus'], 0, 10) . ' Oleh ' . $v['nama_penghapus'] . '</b>'  ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">
                            No
                        </th>
                        <th>Pencatat</th>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Keluar</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="11">
                            <h1>Tidak Ada Data</h1>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif ?>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    <?php endforeach ?>
</body>

</html>