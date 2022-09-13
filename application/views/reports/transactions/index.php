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
        <?php if(empty($orders)):?>
            <h2>Tidak ada data</h2>
        <?php else:?>
            <?php foreach ($orders as $value):?>
        <h3><?= "Pesanan Atas Nama " . kapitalize($value['atasnama']) . '<br>' . $value['tanggal'] ?></h3>
        <br>
        <hr>
        <br>
        <table class="table" id="table-order">
        <thead>
            <tr>
                <th class="text-center">
                    No
                </th>
                <th>Atas Nama</th>
                <th>Meja</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($value['pesanan'])) : $total = 0;  $no = 1; ?>
                <?php foreach ($value['pesanan'] as $token => $order) : ?>
                    <tr>
                        <td><?= $no ?> </td>
                        <td><?= kapitalize($value['atasnama']) ?> </td>
                        <td><?= $value['meja'] ?> </td>
                        <td><?= kapitalize($order['nama']) ?> </td>
                        <td><?= rupiah_format($order['harga']) ?> </td>
                        <td><?= $order['jumlah'] ?> </td>
                        <td><?= rupiah_format($order['sub_total']) ?> </td>

                    </tr>
                <?php $no++; $total = $total + $order['sub_total'];
                endforeach ?>
                <tr>
                    <td colspan="6"><b>Total</b></td>
                    <td><?= rupiah_format($total) ?></td>
                </tr>
            <?php else : ?>
                <tr id="none">
                    <td colspan="4" style="text-align: center;">
                        <p>Tidak Ada Data</p>
                    </td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
    <?endforeach?>
        <?php endif?>
</body>