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
        if(!empty($sgudang) && $sgudang != 'semua' && !empty($gudang)){
            $gudang = array_filter($gudang, function($arr) use($sgudang){
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
        <h1><?= "Laporan Barang <br>" . kapitalize($namaGudang) . '</br>' . (!empty($tgl) ? $tgl[0] . ' sd ' . $tgl[1] : null) ?></h1>
        <br>
        <hr>
        <br>
        <?php if (!empty($g['items'])) : ?>
            <table class="table table-striped" id="table-1">
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
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($g['items'] as $item) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $item["item_code"] ?></td>
                            <td><?= $item["item_name"] ?></td>
                            <td><?= $item["item_stock"] ?></td>
                            <td><?= $item["unit_name"] ?></td>
                            <td><?= rupiah_format($item["item_price"]) ?> / <?= $item["unit_name"] ?></td>
                            <td><?= $item["item_description"] ?></td>
                            <td><?= $item["category_name"] ?></td>
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
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Stock</th>
                        <th>Satuan</th>
                        <th>Biaya</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8">
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