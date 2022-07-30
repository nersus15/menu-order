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
											<form action="<?= base_url("user/update/". $user['id_user'] ."/" . sandi($role)) ?>" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<label for="wilayah">Wilayah Kerja</label>
													<select name="wilayah" id="wilayah" class="form-control select2 <?= form_error('wilayah') ? 'is-invalid' : ''; ?>">
														<option value="" disabled>--Wilayah--</option>
														<?php foreach ($wilayah as $v) : ?>
															<option <?= $v['id'] == $user['wilayah'] ?> data-level="<?= $v['level']?>" value="<?= $v["id"] ?>"><?= ($v['level'] == '1' ? 'Prov. ' :( $v['level'] == '3' ?  'Kec. ' : '') ).  kapitalize($v["nama"]) ?></option>
														<?php endforeach; ?>
													</select>
													<?= form_error('wilayah', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="gudang">Gudang</label>
													<select <?= $role == 'admin' ? 'multiple':null ?>  name="gudang[]" id="gudang" class="form-control select2 <?= form_error('gudang') ? 'is-invalid' : ''; ?>">
													<?php if($role == 'staff'): ?>	
														<option value="">--Pilih Gudang--</option>
													<?php endif ?>
														<?php foreach ($gudang as $v) : ?>
															<option <?= in_array($v->id, $user['gudang']) ? 'selected' : null ?> data-level="<?= $v->level_wilayah?>" value="<?= $v->id ?>"><?= $v->nama . " - " . ($v->level_wilayah == '1' ? 'Prov. ' :( $v->level_wilayah == '3' ?  'Kec. ' : '') ).  kapitalize($v->wilayah_gudang) ?></option>
														<?php endforeach; ?>
													</select>
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
