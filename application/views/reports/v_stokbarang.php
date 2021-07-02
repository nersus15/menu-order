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
						<a id="cetak_pdf_stokbarang" href="<?= base_url('report/cetakstok')?>" target="_blank" class="btn btn-primary btn-lg">Cetak Pdf</a>
					</div>
					<!-- alert flashdata -->
					<div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"></div>
					<!-- end alert flashdata -->
					<div class="row">
						<div class="col-12">													
							</div>
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped" id="table-1">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th>Kode Barang</th>
													<th>Nama Barang</th>
													<th>Stock</th>
													<th>Satuan</th>
													<th>Biaya</th>
													<th>Total</th>
													<th>Deskripsi</th>
													<th>Kategori</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($items as $item) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $item["item_code"] ?></td>
														<td><?= $item["item_name"] ?></td>
														<td><?= $item["item_stock"] ?></td>
														<td><?= $item["unit_name"] ?></td>
														<td><?= rupiah_format($item["item_price"]) ?> / <?= $item["unit_name"] ?></td>
														<td><?= rupiah_format($item["item_price"] * $item["item_stock"]) ?></td>
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

    <script>
    </script>

</body>

</html>