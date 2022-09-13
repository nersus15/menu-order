<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->
<?php if(isset($old))
	extract($old);
?>
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
											<form action="<?= base_url("user/add") ?>" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<label for="nama">Nama Lengkap</label>
													<input required type="text" value="<?= isset($nama) ? $nama : null ?>" class="form-control <?= form_error('nama') ? 'is-invalid' : ''; ?>" name="nama" id="nama" placeholder="Nama User">
													<?= form_error('nama', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="username">Username</label>
													<input required type="text" value="<?= isset($username) ? $username : null ?>" class="form-control <?= form_error('username') ? 'is-invalid' : ''; ?>" name="username" id="username" placeholder="username User">
													<?= form_error('username', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_avatar">Avatar</label>
													<input type="file" class="form-control" name="user_avatar" id="user_avatar">
												</div>
												<div class="form-group">
													<label for="hp">No. Hp</label>
													<input required type="text" value="<?= isset($hp) ? $hp : null ?>" class="form-control <?= form_error('hp') ? 'is-invalid' : ''; ?>" name="hp" id="hp" placeholder="hp User">
													<?= form_error('hp', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="alamat">Alamat</label>
													<textarea class="form-control <?= form_error('alamat') ? 'is-invalid' : ''; ?>" name="alamat" id="alamat" placeholder="alamat User"><?= isset($alamat) ? $alamat : null ?></textarea>
													<?= form_error('alamat', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_password">Password</label>
													<input required type="password" value="<?= isset($password) ? $password : null ?>" class="form-control <?= form_error('user_password') ? 'is-invalid' : ''; ?>" name="user_password" id="user_password" placeholder="Buat Password">
													<?= form_error('user_password', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="user_password_confirm">Konfirmasi Password</label>
													<input required type="password" value="<?= isset($password) ? $password : null ?>" class="form-control <?= form_error('user_password_confirm') ? 'is-invalid' : ''; ?>" name="user_password_confirm" id="user_password_confirm" placeholder="Konfirmasi Password">
													<?= form_error('user_password_confirm', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<hr>
												<div class="form-action">
													<button type="submit" class="btn btn-primary btn-lg">Simpan Data</button>
													<a href="<?= base_url('user') ?>" class="btn btn-warning btn-lg">Batal</a></div>
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
