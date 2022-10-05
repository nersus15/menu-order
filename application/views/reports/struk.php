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
    <div class="card card-primary">
        <div class="card-header">
            <h4>Pesanan Atas Nama <?= $atasnama?></h4>
            <h5>Tanggal:  <?= $tgl?></h5>
            <?php if($status == 'PROSES'):?>
                <span class="badge badge-pill badge-info">Diproses</span>
                <br>
            <?php endif ?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-menu">
                    <thead>
                        <tr>
                            <th class="text-center">
                                No
                            </th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pesanan)): $n = 1; $total = 0; ?>
                            <?php foreach ($pesanan as $k => $m) : ?>
                                <tr class="group">
                                    <td colspan="6" style="height: 30px;"><?= kapitalize($k) ?></td>
                                </tr>
                                <?php foreach($m as $v): ?>
                                    <tr>
                                        <td><?= $n ?></td>
                                        <td><?= kapitalize($v['nama']) ?></td>
                                        <td><?= rupiah_format($v['harga']) ?></td>
                                        <td><?= $v['jmlh'] ?></td>
                                        <td><?= rupiah_format($v['sub_total']) ?></td>
                                        
                                    </tr>
                                <?php $total += $v['sub_total']; $n++; endforeach ?>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4"><b>Total</b></td>
                                <td><?= rupiah_format($total) ?></td>
                            </tr>
                        <?php else: ?>
                            <tr id="none">
                                <td colspan="6" style="text-align: center;">
                                    <p>Tidak Ada Data</p>
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="row" style="justify-content: center;">
        <h3>Sudah Dibayar</h3>
    </div>
</body>