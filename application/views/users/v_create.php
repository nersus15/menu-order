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
											<form action="<?= base_url("user/create") ?>" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<label for="user_name">Nama User</label>
													<input type="text" class="form-control <?= form_error('user_name') ? 'is-invalid' : ''; ?>" name="user_name" id="user_name" placeholder="Nama User">
													<?= form_error('user_name', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_email">Email User</label>
													<input type="text" class="form-control <?= form_error('user_email') ? 'is-invalid' : ''; ?>" name="user_email" id="user_email" placeholder="Email User">
													<?= form_error('user_email', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_phone">Phone User</label>
													<input type="text" class="form-control <?= form_error('user_phone') ? 'is-invalid' : ''; ?>" name="user_phone" id="user_phone" placeholder="Phone User">
													<?= form_error('user_phone', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_address">Alamat User</label>
													<textarea name="user_address" id="user_address" rows="3" class="form-control <?= form_error('user_address') ? 'is-invalid' : ''; ?>"></textarea>
													<?= form_error('user_address', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_avatar">Avatar</label>
													<input type="file" class="form-control" name="user_avatar" id="user_avatar">
												</div>
												<div class="form-group">
													<label for="user_role">Hak Akses</label>
													<select name="user_role" id="user_role" class="form-control <?= form_error('user_role') ? 'is-invalid' : ''; ?>">
														<option value="" disabled selected>--Pilih Hak Akses--</option>
														<option value="admin">Admin</option>
														<option value="staff">Staff</option>
													</select>
													<?= form_error('user_role', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_password">Password</label>
													<input type="password" class="form-control <?= form_error('user_password') ? 'is-invalid' : ''; ?>" name="user_password" id="user_password" placeholder="Buat Password">
													<?= form_error('user_password', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_password_confirm">Konfirmasi Password</label>
													<input type="password" class="form-control <?= form_error('user_password_confirm') ? 'is-invalid' : ''; ?>" name="user_password_confirm" id="user_password_confirm" placeholder="Konfirmasi Password">
													<?= form_error('user_password_confirm', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
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
