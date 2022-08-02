<div class="col-12">
    <div class="card col-12">
        <div class="card-header">
            <h3>Detail Gudang</h3>
        </div>
        <div class="card-body">
            <div class="col-12">
                <div class="row">
                    <p>Nama Gudang: <b> <?= kapitalize($gudang['nama']) ?></b></p>
                </div>
                <div class="row">
                    <p>Wilayah Gudang: <b><?= kapitalize(($gudang['level_wilayah_gudang'] == '1' ? 'Prov. ' : ($gudang['level_wilayah_gudang'] == '3' ? 'Kec. ' : null)) . $gudang['nama_wilayah_gudang']) ?></b></p>
                </div>
            </div>
            <h4>Admin Gudang</h4>
            <div class="row">
                <?php if (!empty($gudang['admin'])) : ?>
                    <ol style="">
                        <?php foreach ($gudang['admin'] as $admin) : ?>
                            <li class="mt-3">
                                <p class="m-0">Nama: <b><?= kapitalize($admin['user_name']) ?></b></p>
                                <p class="m-0">Alamat: <b><?= kapitalize($admin['user_address']) ?></b></p>
                                <p class="m-0">Email: <b><?= $admin['user_email'] ?></b></p>
                                <p class="m-0">No. Telp: <b><?= $admin['user_phone'] ?></b></p>
                                <p class="m-0">Didaftarkan Pada: <b><?= date('D, d M Y', strtotime($admin['created_at'])) ?></b></p>
                            </li>
                        <?php endforeach ?>
                    </ol>
                <?php else : ?>
                    <h5 class="col-12 mb-5" style="text-align: center">No Data</h5>
                <?php endif ?>

            </div>
            <h4>Staff Gudang</h4>
            <div class="row">
                <?php if (!empty($gudang['staff'])) : ?>
                    <ol style="">
                        <?php foreach ($gudang['staff'] as $staff) : ?>
                            <li class="mt-3">
                                <p class="m-0">Nama: <b><?= kapitalize($staff['user_name']) ?></b></p>
                                <p class="m-0">Alamat: <b><?= kapitalize($staff['user_address']) ?></b></p>
                                <p class="m-0">Email: <b><?= $staff['user_email'] ?></b></p>
                                <p class="m-0">No. Telp: <b><?= $staff['user_phone'] ?></b></p>
                                <p class="m-0">Didaftarkan Pada: <b><?= date('D, d M Y', strtotime($staff['created_at'])) ?></b></p>
                            </li>
                        <?php endforeach ?>
                    </ol>
                <?php else : ?>
                    <h5 class="col-12 mb-5" style="text-align: center">No Data</h5>
                <?php endif ?>
            </div>
            <h4>Jenis Barang</h4>
            <div>
                <?php if (!empty($gudang['items'])) : ?>
                    <ol class="row">
                        <?php foreach ($gudang['items'] as $items) : ?>
                            <li class="col-sm-12 col-md-6 mt-3">
                                <p class="m-0">Kode Barang: <b><?= kapitalize($items['item_code']) ?></b></p>
                                <p class="m-0">Nama Barang: <b><?= kapitalize($items['item_name']) ?></b></p>
                                <p class="m-0">Deskripsi Barang: <b><?= kapitalize($items['item_description']) ?></b></p>
                                <p class="m-0">Kategori Barang: <b><?= kapitalize($items['category_name']) ?></b></p>
                                <p class="m-0">Stok Barang: <b><?= kapitalize($items['item_stock'] . ' ' . $items['unit_name']) ?></b></p>
                                <p class="m-0">Harga Barang: <b><?= rupiah_format($items['item_price']) . ' / ' . kapitalize($items['unit_name']) ?></b></p>
                            </li>
                        <?php endforeach ?>
                    </ol>
                <?php else : ?>
                    <h5 class="col-12 mb-5" style="text-align: center">No Data</h5>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>