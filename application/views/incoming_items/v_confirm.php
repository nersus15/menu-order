<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <!-- navbar -->
            <?php $this->load->view("components/main/_navbar"); ?>
            <!-- ./navbar -->
            <!-- sidebar -->
            <?php $this->load->view("components/main/_sidebar"); ?>
            <!-- ./sidebar -->

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header d-flex justify-content-between">
                        <h1><?= $title; ?></h1>
                        
                    </div>
                    <!-- alert flashdata -->
                    <?php
                    $flash = $this->session->flashdata('message');
                    if (!empty($flash)) {
                        echo '<div class="alert alert-' . $flash['type'] . '">' . $flash['message'] . '</div>';
                        unset($_SESSION['message']);
                    }
                    ?>
                    <!-- end alert flashdata -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="table-1">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        No
                                                    </th>
                                                    <th>Tanggal</th>
                                                    <th>Kode Transaksi</th>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                    <th>Asal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php foreach ($incoming_items as $incoming_item) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $incoming_item["transaksi_date"] ?></td>
                                                        <td><?= $incoming_item["transaksi_code"] ?></td>
                                                        <td><?= $incoming_item["item_code"] ?></td>
                                                        <td><?= $incoming_item["item_name"] ?></td>
                                                        <td><?= $incoming_item["transaksi_qty"] ?></td>
                                                        <td><?= $incoming_item["namagudang"] .  " - " . ($incoming_item['lvlwil'] == '1' ? 'Prov. ' : ($incoming_item['lvlwil'] == '3' ? 'Kec. ' : null)) . kapitalize($incoming_item['namawil']) ?></td>
                                                        <td>
                                                            <?php if($incoming_item['verified'] == 0): ?>
                                                                <a data-toggle="tooltip" data-placement="top" title="Confirm" href="<?= base_url("incomingitem/act/confirm/" . $incoming_item["id_transaksi"]) ?>" class="btn btn-icco btn-success"><i class="fas fa-check"></i></a>
                                                                <a data-toggle="tooltip" data-id="<?= $incoming_item['id_transaksi'] ?>" id="pending" data-placement="top" title="Pending" href="<?= base_url("incomingitem/act/pending/" . $incoming_item["id_transaksi"]) ?>" class="btn btn-icco btn-info"><i class="fas fa-hourglass"></i></a>
                                                            <?php else: ?>
                                                                <p>Sudah diproses</p>
                                                            <?php endif?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div id="modal-pending" class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pending Barang Masuk</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="post">
                                <div class="modal-body">
                                    <input type="hidden" name="pengirim" id="pengirim">
                                    <input type="hidden" name="id_transaksi" id="id_transaksi">
                                    <input type="hidden" name="barang" id="barang">
                                    <div class="form-group">
                                        <label for="">Jumlah yang dikirim</label>
                                        <input class="form-control" type="number" name="dikirim" readonly id="dikirim">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jumlah yang diterima</label>
                                        <input class="form-control" type="number" min="0" name="diterima" id="ditermia">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- footer -->
            <?php $this->load->view("components/main/_footer"); ?>
            <!-- ./footer -->
        </div>
    </div>

    <!-- scripts -->
    <?php $this->load->view("components/main/_scripts", $flash_data); ?>
    <!-- ./scripts -->
    <script>
        $(document).ready(function() {
            var transaksi = toArray(<?= json_encode($incoming_items) ?>);
            $("#pending").click(function(e) {
                e.preventDefault();
                var idTransaksi = $(this).data('id');
                var data = transaksi.filter(arr => arr.id_transaksi == idTransaksi);
                data = data[0];
                console.log(data);
                var url = $(this).attr('href');
                console.log(url);


                $("#modal-pending").modal('show');

                // OnShow
                $("#modal-pending").on('shown.bs.modal', function() {
                    var form = $("#modal-pending form");
                    console.log(form);
                    form.prop('action', url);
                    $("#dikirim").val(data.transaksi_qty);
                    $("#id_transaksi").val(data.id_transaksi);
                    $("#pengirim").val(data.gudang);
                    $("#barang").val(data.id_items);

                });

            });


        });
    </script>
</body>

</html>