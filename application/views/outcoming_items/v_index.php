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
						<a href="<?= base_url("outcomingitem/create") ?>" class="btn btn-primary btn-lg">Tambah Barang Keluar</a>
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
							<div class="card mt-2">
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
													<th>Jumlah Keluar</th>
													<th>Tujuan</th>
													<th>Nota</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($outcoming_items as $outcoming_item) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $outcoming_item["transaksi_date"] ?></td>
														<td><?= $outcoming_item["transaksi_code"] ?></td>
														<td><?= $outcoming_item["item_code"] ?></td>
														<td><?= $outcoming_item["item_name"] ?></td>
														<td><?= $outcoming_item["transaksi_qty"] ?></td>
														<td><?= !empty($outcoming_item['tujuan']) && strlen($outcoming_item['tujuan']) > 8 ? kapitalize($outcoming_item['tujuan']) : $outcoming_item["namagudang"] .  " - " . ($outcoming_item['lvlwil'] == '1' ? 'Prov. ' : ($outcoming_item['lvlwil'] == '3' ? 'Kec. ' : null)) . kapitalize($outcoming_item['namawil']) ?></td>
														<td>
															<?php if (!empty($outcoming_item['nota'])) : ?>
																<a href="<?= base_url('assets/nota/' . $outcoming_item['nota']) ?>" target="_blank">Lihat Nota</a>
															<?php endif ?>
														</td>
														<td>
															<a href="<?= base_url("outcomingitem/delete/" . $outcoming_item["id_transaksi"]) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
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
			</div>
			<!-- footer -->
			<?php $this->load->view("components/main/_footer"); ?>
			<!-- ./footer -->
		</div>
	</div>


	<!-- scripts -->
	<?php $this->load->view("components/main/_scripts", $this->session->flashdata('message')); ?>
	<!-- ./scripts -->
</body>

</html>