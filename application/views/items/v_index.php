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
						<?php if (is_login('admin')) : ?>
							<a href="<?= base_url("item/create") ?>" class="btn btn-primary btn-lg">Tambah Barang</a>
						<?php endif ?>
					</div>
					<!-- alert flashdata -->
					<?php
					$flash = $this->session->flashdata('message');
					if (!empty($flash)) {
						echo '<div class="alert alert-' . $flash['type'] . '">' . $flash['message'] . '</div>';
						unset($_SESSION['message']);
					}
					?>
					<!-- end alert flashdata -->
					<div class="row">
						<div class="col-12">
							<?php if (is_login('admin')) : ?>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kirim-barang">
									Kirim Barang
								</button>
							<?php endif ?>
							<div class="card mt-3">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped" id="table-1">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th width="150">Gambar</th>
													<th>Kode Barang</th>
													<th>Nama Barang</th>
													<?php if (is_login('staff')) : ?>
														<th>Stock</th>
													<?php endif ?>
													<th>Satuan</th>
													<th>Biaya</th>
													<th>Deskripsi</th>
													<th>Kategori</th>
													<?php if (is_login('admin')) : ?>
														<th>Aksi</th>
													<?php endif ?>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($items as $item) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td>
															<img src="<?= base_url("assets/uploads/items/" . $item["item_image"]) ?>" width="100%">
														</td>
														<td><?= $item["item_code"] ?></td>
														<td><?= $item["item_name"] ?></td>
														<?php if (is_login('staff')) : ?>
															<td><?= $item["item_stock"] ?></td>
														<?php endif ?>
														<td><?= $item["unit_name"] ?></td>
														<td><?= rupiah_format($item["item_price"]) ?> / <?= $item["unit_name"] ?></td>
														<td><?= $item["item_description"] ?></td>
														<td><?= $item["category_name"] ?></td>
														<?php if (is_login('admin')) : ?>
															<td>
																<!-- <a href="" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a> -->
																<a href="<?= base_url("item/update/" . $item["id_item"]) ?>" class="btn btn-icon btn-warning"><i class="fas fa-pencil-alt"></i></a>
																<a href="<?= base_url("item/delete/" . $item["id_item"]) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
															</td>
														<?php endif ?>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<div class="modal" tabindex="-1" role="dialog" id="kirim-barang">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Kirim Barang Ke Gudang</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form action="<?= base_url('item/kirim') ?>" method="POST">
								<div class="modal-body">
									<div class="form-group">
										<label for="">Gudang Tujuan</label>
										<select class="form-control" name="gudang[]" id="gudang" multiple>
											<?php foreach ($gudang as $v) : ?>
												<?php
												$namaGudang = strpos(strtolower($v['nama']), 'gudang') === false ? 'Gudang ' . $v['nama'] : $v['nama'];
												if ($v['level_wilayah_gudang'] == '1')
													$namaGudang .= ' - Prov. ' . $v['wilayah_gudang'];
												elseif ($v['level_wilayah_gudang'] == 3)
													$namaGudang .= ' - Kec. ' . $v['wilayah_gudang'];
												?>
												<option value="<?= $v['id'] ?>"><?= $namaGudang ?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="row" style="overflow-x: scroll;">
										<table class="table table-hover" id="barang-keluar">
											<thead>
												<tr>
													<th>Barang</th>
													<th>Jumlah</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<select name="barang[]" class="form-control select2 barang">
															<option value="" disabled selected>--Pilih Barang--</option>
															<?php foreach ($items as $item) : ?>
																<option value="<?= $item["id_item"] ?>"><?= $item["item_code"] ?> | <?= $item["item_name"] ?></option>
															<?php endforeach; ?>
														</select>
														<?= form_error('barang', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
													</td>
													<td>
														<div class="input-group">
															<input min="0" type="number" class="form-control <?= form_error('jumlah') ? 'is-invalid' : '' ?> OutcomingItemQty" name="jumlah[]" placeholder="Jumlah Stok Masuk">
															<div class="input-group-append">
																<span class="input-group-text unitName">Satuan</span>
															</div>
														</div>
														<div class="invalid-feedback errorValue">Jumlah Barang Keluar lebih besar dari stok barang</div>
													</td>
													<td>
														<button type="button" id="add" class="btn btn-primary btn-xs"><i class="fas fa-plus"></i></button>
														<button style="display: none;" type="button" id="remove" class="btn btn-danger btn-xs"><i class="fas fa-minus"></i></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary">Kirim</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- footer -->
			<?php $this->load->view("components/main/_footer"); ?>
			<!-- ./footer -->
		</div>
	</div>

	<!-- scripts -->
	<?php $this->load->view("components/main/_scripts", $this->session->flashdata('message')); ?>
	<!-- ./scripts -->
	<script>
		$(document).ready(function() {
			var dataItem = <?= json_encode($items) ?>;
			$("#barang-keluar").tableAppend({
				buttonadd: '#add',
				buttonremove: '#remove',
				addCallback: function(row, index) {
					initInput();
					var select2 = $(row).find('select.select2');
					select2.next().remove();
					select2.removeClass("select2-hidden-accessible")
					select2.select2();
					select2.parent().find('span').css('max-width', '155px')
				}
			});

			function initInput() {
				$('.barang').change(function(e) {
					e.preventDefault();
					var row = $(this).parent().parent();
					var index = $(row).data('index');
					var value = $(this).val();
					var barang = dataItem.filter(item => item.id_item == value)[0];
					if (barang.length == 0) {
						return
					}
					var satuan = $('tr.row-' + index + ' .unitName');

					var unitName = barang.unit_name;
					satuan.text(unitName);

				});
			}
			$("#kirim-barang").on('shown.bs.modal', function() {
				$("#gudang").select2();
			});
			initInput();
		});
	</script>
</body>

</html>