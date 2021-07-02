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
											<form action="<?= base_url("customer/create") ?>" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<label for="customer_code">Kode Customer</label>
													<input type="text" class="form-control" name="customer_code" id="customer_code" placeholder="Kode Customer" value="<?= $customer_code ?>" readonly>
												</div>
												<div class="form-group">
													<label for="customer_name">Nama Customer</label>
													<input type="text" class="form-control <?= form_error('customer_name') ? 'is-invalid' : ''; ?>" name="customer_name" id="customer_name" placeholder="Nama Customer">
													<?= form_error('customer_name', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="customer_email">Email Customer</label>
													<input type="text" class="form-control <?= form_error('customer_email') ? 'is-invalid' : ''; ?>" name="customer_email" id="customer_email" placeholder="Email Customer">
													<?= form_error('customer_email', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="customer_phone">Phone Customer</label>
													<input type="text" class="form-control <?= form_error('customer_phone') ? 'is-invalid' : ''; ?>" name="customer_phone" id="customer_phone" placeholder="Phone Customer">
													<?= form_error('customer_phone', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="customer_address">Alamat Customer</label>
													<textarea name="customer_address" id="customer_address" rows="3" class="form-control <?= form_error('customer_address') ? 'is-invalid' : ''; ?>"></textarea>
													<?= form_error('customer_address', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
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
