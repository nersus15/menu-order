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
						<a href="<?= base_url("supplier/create") ?>" class="btn btn-primary btn-lg">Tambah Supplier</a>
					</div>
					<!-- alert flashdata -->
					<?php $flash = $this->session->flashdata('message');
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
													<th>Kode Supplier</th>
													<th>Nama Supplier</th>
													<th>E-mail Supplier</th>
													<th>No HP Supplier</th>
													<th>Alamat Supplier</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($suppliers as $supplier) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $supplier["supplier_code"] ?></td>
														<td><?= $supplier["supplier_name"] ?></td>
														<td><?= $supplier["supplier_email"] ?></td>
														<td><?= $supplier["supplier_phone"] ?></td>
														<td><?= $supplier["supplier_address"] ?></td>

														<td>
															<!-- <a href="" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a> -->
															<a href="<?= base_url("supplier/update/" . $supplier["id_supplier"]) ?>" class="btn btn-icon btn-warning"><i class="fas fa-pencil-alt"></i></a>
															<a href="<?= base_url("supplier/delete/" . $supplier["id_supplier"]) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
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
