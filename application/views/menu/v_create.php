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
											<?php if(!isset($role) || !in_array($role, ['admin', 'staff'])): ?>
												<h4>Ilegal Aksess</h4>
											<?php else: ?>
											<form action="<?= base_url("meja/_create") ?>" method="post">
												<div class="form-group">
													<label for="user_name">QR Code</label>
													<input type="text" class="form-control <?= form_error('user_name') ? 'is-invalid' : ''; ?>" name="user_name" id="user_name" placeholder="Nama User">
													<?= form_error('user_name', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
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
