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
    <h1>Rekap Data Stok Barang Bulan <?= $bulan[$def_bulan - 1] ?> <?= $def_tahun ?></h1>
    <hr>
    <table id="laporan">
        <thead>
            <tr>
                <th class="text-center">
                    No
                </th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stock</th>
                <th>Satuan</th>
                <th>Biaya</th>
                <th>Total</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0;
            if (empty($items)) : ?>
                <tr>
                    <td style="text-align: center;" colspan="9">Tidak Ada Data pada Bulan <?= $bulan[$def_bulan - 1] . " " . $def_tahun ?> </td>
                </tr>
                <?php
            else :
                $no = 1;
                foreach ($items as $item) : $total += ($item["item_price"] * $item["item_stock"]); ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item["item_code"] ?></td>
                        <td><?= $item["item_name"] ?></td>
                        <td><?= $item["item_stock"] ?></td>
                        <td><?= $item["unit_name"] ?></td>
                        <td><?= rupiah_format($item["item_price"]) ?> / <?= $item["unit_name"] ?></td>
                        <td><?= rupiah_format($item["item_price"] * $item["item_stock"]) ?></td>
                        <td><?= $item["item_description"] ?></td>
                        <td><?= $item["category_name"] ?></td>
                    </tr>
            <?php endforeach;
            endif ?>
            <?php if (count($items) > 0) : ?>
                <tr>
                    <td colspan="6"><b>Total</b></td>
                    <td colspan="3"><b><?= rupiah_format($total) ?></b></td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</body>

</html>