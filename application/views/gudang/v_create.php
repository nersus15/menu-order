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
											<form action="<?= base_url("gudang/create") ?>" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<label for="nama">Nama Gudang</label>
													<input type="text" class="form-control <?= form_error('nama') ? 'is-invalid' : ''; ?>" name="nama" id="nama" placeholder="Nama Gudang">
													<?= form_error('nama', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="wilayah">Wilayah</label>
													<select name="wilayah" id="wilayah" class="form-control select2 <?= form_error('wilayah') ? 'is-invalid' : ''; ?>">
														<option value="" disabled selected>--Wilayah--</option>
														<?php foreach ($wilayah as $v) : ?>
															<option data-level="<?= $v['level']?>" value="<?= $v["id"] ?>"><?= ($v['level'] == '1' ? 'Prov. ' :( $v['level'] == '3' ?  'Kec. ' : '') ).  kapitalize($v["nama"]) ?></option>
														<?php endforeach; ?>
													</select>
													<?= form_error('wilayah', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="alamat">Alamat Gudang</label>
													<textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat Gudang"></textarea>
												</div>
                                                <div class="form-group">
													<label for="admin">Admin</label>
													<select multiple name="admin[]" id="admin" class="form-control select2 <?= form_error('admin') ? 'is-invalid' : ''; ?>">
														<!-- <option value="" disabled selected>--Admin Gudang--</option> -->
														<?php foreach ($admin as $v) : ?>
															<option value="<?= $v["id_user"] ?>"><?= $v["user_name"] . ' - ' . ($v['level_wilayah'] == '1' ? 'Prov. ' :( $v['level_wilayah'] == '3' ?  'Kec. ' : '') ) . kapitalize($v['nama_wilayah']) ?></option>
														<?php endforeach; ?>
													</select>
                                                </div>
                                                <div class="form-group">
													<label for="wilayah">Staff</label>
													<select multiple name="staff[]" id="staff" class="form-control select2 <?= form_error('staff') ? 'is-invalid' : ''; ?>">
														<!-- <option value="" disabled selected>--Staff Gudang--</option> -->
														<?php foreach ($staff as $v) : ?>
                                                            <option value="<?= $v["id_user"] ?>"><?= $v["user_name"] . ' - ' . ($v['level_wilayah'] == '1' ? 'Prov. ' :( $v['level_wilayah'] == '3' ?  'Kec. ' : '') ) . kapitalize($v['nama_wilayah']) ?></option>
														<?php endforeach; ?>
													</select>
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
