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
											<form action="<?= base_url("user/create/" . sandi($role)) ?>" method="post" enctype="multipart/form-data">
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
													<label for="wilayah">Wilayah Kerja</label>
													<select name="wilayah" id="wilayah" class="form-control select2 <?= form_error('wilayah') ? 'is-invalid' : ''; ?>">
														<option value="" disabled selected>--Wilayah--</option>
														<?php foreach ($wilayah as $v) : ?>
															<option data-level="<?= $v['level']?>" value="<?= $v["id"] ?>"><?= ($v['level'] == '1' ? 'Prov. ' :( $v['level'] == '3' ?  'Kec. ' : '') ).  kapitalize($v["nama"]) ?></option>
														<?php endforeach; ?>
													</select>
													<?= form_error('wilayah', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="gudang">Gudang</label>
													<select <?= $role == 'admin' ? 'multiple':null ?>  name="gudang[]" id="gudang" class="form-control select2 <?= form_error('gudang') ? 'is-invalid' : ''; ?>">
													<?php if($role == 'staff'): ?>	
														<option value="" disabled selected>--Pilih Gudang--</option>
													<?php endif ?>
														<?php foreach ($gudang as $v) : ?>
															<option data-level="<?= $v->level_wilayah?>" value="<?= $v->id ?>"><?= $v->nama . " - " . ($v->level_wilayah == '1' ? 'Prov. ' :( $v->level_wilayah == '3' ?  'Kec. ' : '') ).  kapitalize($v->wilayah_gudang) ?></option>
														<?php endforeach; ?>
													</select>
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
											<?php endif ?>
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
