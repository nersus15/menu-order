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
					<div class="section-header">
						<h1><?= $title; ?></h1>
					</div>
					<div class="row">
						<div class="col-12">
							<?php $flash = $this->session->flashdata('message');
							if (!empty($flash)) {
								echo '<div class="alert alert-' . $flash['type'] . '">' . $flash['message'] . '</div>';
								unset($_SESSION['message']);
							}
							?>
						</div>
						<div class="col-sm-12 col-md-4">
							<div class="card mb-3" style="max-width: 540px;">
								<div class="row no-gutters">
									<div class="col-md-12">
										<img style="width: 100%; height: auto" src="<?= base_url("assets/img/avatar/" . sessiondata('login', "user_avatar")) ?>" class="card-img" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
									</div>
									<div class="col-md-12">
										<div class="card-body">
											<h5 class="card-title"><?= sessiondata('login', "user_name"); ?></h5>
											<p class="card-text"><?= sessiondata('login', "user_email"); ?></p>
											<p class="card-text">Alamat: <?= sessiondata('login', 'user_address') . (sessiondata('login', "willevel") == '1' ? 'Prov. ' : (sessiondata('login', 'willevel') == 3 ? 'Kec. ' : '')) . kapitalize(sessiondata('login', 'wilnama')); ?></p>
											<?php if (is_login('staff')) : ?>
												<p class="card-text">Gudang: </p>
												<ul>
													<?php foreach ($gudang as $v) : ?>
														<li><?= $v['nama'] . " - " . ($v['level_wilayah_gudang'] == '1' ? 'Prov. ' : ($v['level_wilayah_gudang'] == '3' ?  'Kec. ' : '')) . kapitalize($v['nama_wilayah_gudang']) ?></li>
													<?php endforeach ?>
												</ul>
											<?php endif ?>
											<p class="card-text"><small class="text-muted">Didaftarkan Pada : <?= date('d M Y', strtotime(sessiondata('login', "created_at"))); ?></small></p>
											<?php if (sessiondata('login', "user_role") == "admin") : ?>
												<span class="badge badge-success">Admin</span>
											<?php else : ?>
												<span class="badge badge-warning">Staff</span>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-8">
							<div class="card">
								<div class="card-body">
									<h5>Edit Profil</h5>
									<hr>
									<form action="<?= base_url("profile/editprofile") ?>" method="post" enctype="multipart/form-data">
										<div class="form-group">
											<label for="user_name">Nama</label>
											<input type="text" id="user_name" name="user_name" class="form-control <?= form_error('user_name') ? 'is-invalid' : ''; ?>" value="<?= sessiondata('login', "user_name"); ?>">
											<?= form_error('user_name', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
										</div>
										<div class="form-group">
											<label for="user_email">Email</label>
											<input type="text" id="user_email" name="user_email" class="form-control <?= form_error('user_email') ? 'is-invalid' : ''; ?>" value="<?= sessiondata('login', "user_email"); ?>">
											<?= form_error('user_email', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
										</div>
										<div class="form-group">
											<label for="user_phone">Nomor HP</label>
											<input type="text" id="user_phone" name="user_phone" class="form-control <?= form_error('user_phone') ? 'is-invalid' : ''; ?>" value="<?= sessiondata('login', "user_phone"); ?>">
											<?= form_error('user_phone', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
										</div>
										<div class="form-group">
											<label for="user_address">Alamat</label>
											<textarea name="user_address" id="user_address" rows="10" class="form-control <?= form_error('user_address') ? 'is-invalid' : ''; ?>"><?= sessiondata('login', 'user_address'); ?></textarea>
											<?= form_error('user_address', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
										</div>
										<div class="form-group">
											<label for="user_avatar">Avatar</label>
											<input type="file" id="user_avatar" name="user_avatar" class="form-control">
											<small class="text-muted">Kosongkan jika tidak ingin merubah</small>
										</div>
										<div class="form-action">
											<button type="submit" class="btn btn-primary">Update profile</button>
											<a href="<?= base_url() ?>" class="btn btn-warning">Kembali</a>
										</div>
									</form>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<h5>Ganti Password</h5>
									<hr>
									<form action="<?= base_url("profile/changepassword") ?>" method="post">
										<div class="form-group">
											<label for="current_password">Password sekarang</label>
											<input type="password" id="current_password" name="current_password" class="form-control <?= form_error('current_password') ? 'is-invalid' : ''; ?>">
											<?= form_error('current_password', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
										</div>
										<div class="form-group">
											<label for="new_password">Password baru</label>
											<input type="password" id="new_password" name="new_password" class="form-control <?= form_error('new_password') ? 'is-invalid' : ''; ?>">
											<?= form_error('new_password', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
										</div>
										<div class="form-group">
											<label for="password_confirm">Konfirmasi password</label>
											<input type="password" id="password_confirm" name="password_confirm" class="form-control <?= form_error('password_confirm') ? 'is-invalid' : ''; ?>">
											<?= form_error('password_confirm', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
										</div>
										<div class="form-action">
											<button type="submit" class="btn btn-primary">Ganti Password</button>
											<a href="<?= base_url("dashboard") ?>" class="btn btn-warning">Kembali</a>
										</div>
									</form>
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