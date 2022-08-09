<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3>Detail Barang</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-barang">
                    <thead>
                        <tr>
                            <th class="text-center">
                                No
                            </th>
                            <th width="150">Gambar</th>
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
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <img src="<?= base_url("assets/uploads/items/" . $item["item_image"]) ?>" width="100%">
                                </td>
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
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#table-barang").dataTable();
    });
</script>