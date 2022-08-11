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
										<div class="col-sm-12 col-md-8 mx-auto">
											<form action="<?= base_url("item/update/" . $item["id_item"]) ?>" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<div class="form-group">
														<label for="item_code">Kode Barang</label>
														<input type="text" class="form-control" name="item_code" id="item_code" placeholder="Kode Barang" value="<?= $item["item_code"] ?>" readonly>
													</div>
													<label for="id_category">Kategori</label>
													<select name="id_category" id="id_category" class="form-control select2">
														<option value="" disabled selected>--Kategori Barang--</option>
														<?php foreach ($categories as $category) : ?>
															<?php if ($category["id_category"] == $item["id_category"]) : ?>
																<option value="<?= $category["id_category"] ?>" selected><?= $category["category_name"] ?></option>
															<?php else : ?>
																<option value="<?= $category["id_category"] ?>"><?= $category["category_name"] ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													</select>
													<?= form_error('id_category', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="id_unit">Satuan</label>
													<select name="id_unit" id="id_unit" class="form-control select2">
														<option value="" disabled selected>--Satuan Barang--</option>
														<?php foreach ($units as $unit) : ?>
															<option value="<?= $unit["id_unit"] ?>"><?= $unit["unit_name"] ?></option>
															<?php if ($unit["id_unit"] == $item["id_unit"]) : ?>
																<option value="<?= $unit["id_unit"] ?>" selected><?= $unit["unit_name"] ?></option>
															<?php else : ?>
																<option value="<?= $unit["id_unit"] ?>"><?= $unit["unit_name"] ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													</select>
													<?= form_error('id_unit', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="item_name">Nama Barang</label>
													<input type="text" class="form-control <?= form_error('item_name') ? 'is-invalid' : ''; ?>" name="item_name" id="item_name" placeholder="Nama Barang" value="<?= $item["item_name"] ?>">
													<?= form_error('item_name', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="item_image">Gambar Barang</label>
													<div class="row">
														<div class="col-sm-4">
															<img src="<?= base_url("assets/uploads/items/" . $item["item_image"]) ?>" width="100%">
														</div>
														<div class="col-sm-8 align-self-center">
															<input type="file" class="form-control" name="item_image" id="item_image">
															<small class="text-muted">Kosongkan jika tidak ingin merubah gambar barang</small>
														</div>
													</div>
												</div>
												<!-- <div class="form-group">
													<label for="item_stock">Stok Barang</label>
													<input type="number" class="form-control <?= form_error('item_stock') ? 'is-invalid' : ''; ?>" name="item_stock" id="item_stock" placeholder="Stok Barang" value="<?= $item["item_stock"] ?>">
												</div> -->
												<?= form_error('item_stock', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												<div class="form-group">
													<label for="item_price">Harga Barang</label>
													<input type="number" class="form-control <?= form_error('item_price') ? 'is-invalid' : ''; ?>" name="item_price" id="item_price" placeholder="Harga Barang" value="<?= $item["item_price"] ?>">
													<?= form_error('item_price', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="item_descriptrion">Deskripsi Barang <sup class="text-muted">(Opsional)</sup></label>
													<textarea name="item_description" id="item_description" rows="3" class="form-control"><?= $item["item_description"] ?></textarea>
												</div>
												<hr>
												<div class="form-action">
													<button type="submit" class="btn btn-primary btn-lg">Simpan Data</button>
													<a href="<?= base_url('item') ?>" class="btn btn-warning btn-lg">Batal</a>
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
