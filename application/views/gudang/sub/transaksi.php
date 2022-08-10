<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3>Detail Transaksi</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th class="text-center">
                                No
                            </th>
                            <th>Pencatat</th>
                            <th>Jenis Transaksi</th>
                            <th>Tanggal</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Masuk</th>
                            <th>Jumlah Keluar</th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($transaksi as $v) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $v['nama_pencatat'] ?></td>
                                <td><?= kapitalize("transaksi " . $v['jenis']) ?></td>
                                <td><?= $v["transaksi_date"] ?></td>
                                <td><?= $v["transaksi_code"] ?></td>
                                <td><?= $v["item_name"] ?></td>
                                <td><?= $v['jenis'] == 'keluar' ? '-' : $v["transaksi_qty"] ?></td>
                                <td><?= $v['jenis'] == 'masuk' ? '-' : $v["transaksi_qty"] ?></td>
                                <td><?= $v['jenis'] == 'keluar' ? '-' :  $v["namagudang_asal"] .  " - " . ($v['lvlwil_asal'] == '1' ? 'Prov. ' : ($v['lvlwil_asal'] == '3' ? 'Kec. ' : '-')) . kapitalize($v['namawil_asal']) ?></td>
                                <?php if ($v['jenis'] == 'keluar') : ?>
                                    <td><?= !empty($v['tujuan']) && strlen($v['tujuan']) > 8 ? $v['tujuan'] : $v["namagudang_tujuan"] .  " - " . ($v['lvlwil_tujuan'] == '1' ? 'Prov. ' : ($v['lvlwil_tujuan'] == '3' ? 'Kec. ' : '-')) . kapitalize($v['namawil_tujuan']) ?></td>
                                <?php else : ?>
                                    <td><?= $v['verified'] == 1 ? 'Terverifikasi' : ($v['verified'] == 2 ? 'Pending - ' . $v['keterangan'] : '-') ?></td>
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
            </div>
        </div>
    </div>
</div>