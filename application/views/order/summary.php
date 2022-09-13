<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/auth/_head"); ?>
<!-- ./head -->
<style>
	
</style>
<body>
	<div id="app">
		<section class="section">
			<div class="container mt-5 mb-5">
				
				<div class="row">
					<div class="col-12">
						<div class="login-brand">
							<img src="<?= base_url("assets/img/logo.png") ?>" alt="logo" width="100" class="shadow-light rounded-circle">
						</div>

						<?php
							$flash = $this->session->flashdata('message');
							if(!empty($flash)){
								echo '<div class="alert alert-'. $flash['type'] .'">' . $flash['message'] . '</div>';
								unset($_SESSION['message']);
							}
						?>
						<div class="card card-primary">
							<div class="card-header">
								<h4>Pesanan Atas Nama <?= $atasnama?></h4>
                                <?php if($status == 'OPEN'):?>
					            <a href="<?= base_url('order/sign/' . $kode_meja . '/' . $token) ?>" class="btn btn-primary" id="order" style="text-align: center;">Tambah Pesanan</a>
                                <?php else: ?>
					            <a href="<?= base_url('order') ?>" class="btn btn-primary" id="order" style="text-align: center;">Pesan Lagi</a>
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
					</div>
				</div>
                <?php if($status == 'OPEN'):?>
                    <div class="row" style="justify-content: center;">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-qr"> Bayar Tagihan</button>
                    </div>
                    <?php else: ?>
                    <div class="row" style="justify-content: center;">
                        <h3>Sudah Dibayar</h3>
                    </div>

                 <?php endif ?>
				
			</div>
			<!-- Modal -->
            <div class="modal fade" id="modal-qr" tabindex="-1" aria-labelledby="modal-qrLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-qrLabel">Tunjukkan kepada kasir untuk membaya</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="<?= base_url('assets/img/qr/' . $token . '.png') ?>" style="width: 100%;" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>
		</section>
	</div>
	<!-- <?php $this->load->view("components/auth/_footer"); ?> -->

	<?php $this->load->view("components/auth/_scripts"); ?>

    <script>
		
        $(document).ready(function(){
			var history = <?= json_encode($pesanan) ?>;
            $("#modal-qr").on('shown.bs.modal', function(){
                setTimeout(function(){
                    $(".modal-backdrop").remove();
                }, 500)
            })
		});
		
    </script>
</body>

</html>
