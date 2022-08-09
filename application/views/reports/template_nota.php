<!DOCTYPE html>
<html>

<head>
    <style>
        h1 {
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
        }

        #laporan {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
        }

        #laporan td,
        #laporan th {
            border: 1px solid #ddd;
            padding: 5px;
        }

        #laporan tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #laporan th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<?php
$bulan = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
]
?>

<body>
    <h1>Status Pengiriman Barang </h1>
    <h3><?= $status ?> - <?= $tgl ?></h3>
    <hr>

    <div style="margin-top: 10px;">
        <h5>Detail</h5>
        <ul style="list-style: none;">
            <li>Kode Barang: <b><?= $kode_barang ?></b></li>
            <li>Nama Barang: <b><?= $nama_barang ?></b></li>
            <li>Jumlah Dikirim: <b><?= $jumlah_kirim ?></b></li>
            <li>Jumlah Diterima: <b><?= $jumlah_terima ?></b></li>
            <li>Tgl Dikirim: <b><?= $tgl_kirim ?></b></li>
            <li>Tgl Diterima: <b><?= $tgl_terima ?></b></li>
        </ul>
    </div>
    <table style="width: 100%;" border="1px" cellspacing="0">
        <thead>
            <tr>
                <th>Pengirim</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?= $pengirim['nama'] ?>
                </td>
                <td>
                    <?= $penerima['nama'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $pengirim['gudang'] ?>
                </td>
                <td>
                    <?= $penerima['gudang'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $pengirim['alamat'] ?>
                </td>
                <td>
                    <?= $penerima['alamat'] ?>
                </td>
            </tr>
        </tbody>
    </table>
    <div>
        <h5>Keterangan</h5>
        <article style="border: 1px solid black; padding: 10px;" name="" id="" cols="30" rows="50"><?= (isset($keterangan) && !empty($keterangan)) ? $keterangan : '<br>' ?></article>
    </div>
</body>

</html>