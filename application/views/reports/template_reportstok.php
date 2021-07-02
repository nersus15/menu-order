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

<body>
    <h1>Rekap Data Stok Barang</h1>
    <hr>
    <table id="laporan">
        <tr>
            <th class="text-center">
                No
            </th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Stock</th>
            <th>Satuan</th>
            <th>Biaya</th>
            <th>Deskripsi</th>
            <th>Kategori</th>
        </tr>
        <?php $no = 1;
        foreach ($items as $item) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $item["item_code"] ?></td>
                <td><?= $item["item_name"] ?></td>
                <td><?= $item["item_stock"] ?></td>
                <td><?= $item["unit_name"] ?></td>
                <td><?= $item["item_price"] ?> / <?= $item["unit_name"] ?></td>
                <td><?= $item["item_description"] ?></td>
                <td><?= $item["category_name"] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>