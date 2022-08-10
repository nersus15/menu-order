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
						<!-- <a href="<?= base_url("incomingitem/create") ?>" class="btn btn-primary btn-lg">Tambah Barang Masuk</a> -->
					</div>
					<!-- alert flashdata -->
						<?php
						$flash = $this->session->flashdata('message');
						if(!empty($flash)){
							echo '<div class="alert alert-'. $flash['type'] .'">' . $flash['message'] . '</div>';
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
													<th>Jumlah Masuk</th>
													<th>Asal</th>
													<th>Nota</th>
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
															<?php if(!empty($incoming_item['nota'])): ?>
																<a href="<?= base_url('assets/nota/' . $incoming_item['nota']) ?>" target="_blank">Lihat Nota</a>
															<?php endif?>
														</td>
														<td>
															<a href="<?= base_url("incomingitem/delete/" . $incoming_item["id_transaksi"]) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
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
	<?php $this->load->view("components/main/_scripts", $flash_data); ?>
	<!-- ./scripts -->

</body>

</html>
