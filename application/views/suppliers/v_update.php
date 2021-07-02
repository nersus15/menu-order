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
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-8 mx-auto">
											<form action="<?= base_url("supplier/update/" . $supplier["id_supplier"]) ?>" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<label for="supplier_code">Kode Supplier</label>
													<input type="text" class="form-control" name="supplier_code" id="supplier_code" placeholder="Kode Supplier" value="<?= $supplier["supplier_code"] ?>" readonly>
												</div>
												<div class="form-group">
													<label for="supplier_name">Nama Supplier</label>
													<input type="text" class="form-control <?= form_error('supplier_name') ? 'is-invalid' : ''; ?>" name="supplier_name" id="supplier_name" placeholder="Nama Supplier" value="<?= $supplier["supplier_name"] ?>">
													<?= form_error('supplier_name', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="supplier_email">Email Supplier</label>
													<input type="text" class="form-control <?= form_error('supplier_email') ? 'is-invalid' : ''; ?>" name="supplier_email" id="supplier_email" placeholder="Email Supplier" value="<?= $supplier["supplier_email"] ?>">
													<?= form_error('supplier_email', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="supplier_phone">Phone Supplier</label>
													<input type="text" class="form-control <?= form_error('supplier_phone') ? 'is-invalid' : ''; ?>" name="supplier_phone" id="supplier_phone" placeholder="Phone Supplier" value="<?= $supplier["supplier_phone"] ?>">
													<?= form_error('supplier_phone', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="supplier_address">Alamat Supplier</label>
													<textarea name="supplier_address" id="supplier_address" rows="3" class="form-control <?= form_error('supplier_address') ? 'is-invalid' : ''; ?>"><?= $supplier["supplier_address"] ?></textarea>
													<?= form_error('supplier_address', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<hr>
												<div class="form-action">
													<button type="submit" class="btn btn-primary btn-lg">Simpan Data</button>
													<button type="reset" class="btn btn-warning btn-lg">Reset Form</button>
												</div>
											</form>
										</div>
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
