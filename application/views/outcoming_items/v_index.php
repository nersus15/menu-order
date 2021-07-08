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
						<a href="<?= base_url("outcomingitem/create") ?>" class="btn btn-primary btn-lg">Tambah Barang Masuk</a>
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
													<th>Jumlah Keluar</th>
													<th>Customer</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($outcoming_items as $outcoming_item) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $outcoming_item["outcoming_item_date"] ?></td>
														<td><?= $outcoming_item["outcoming_item_code"] ?></td>
														<td><?= $outcoming_item["item_code"] ?></td>
														<td><?= $outcoming_item["item_name"] ?></td>
														<td><?= $outcoming_item["outcoming_item_qty"] ?></td>
														<td><?= $outcoming_item["customer_name"] ?></td>
														<td>
															<a href="<?= base_url("outcomingitem/delete/" . $outcoming_item["id_outcoming_item"]) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
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
