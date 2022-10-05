<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->
<style>
	table{
		border-collapse:separate; 
  		border-spacing: 0 1em;
	}
</style>
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
							<!-- Button trigger modal -->
						<?php if(is_login('Kasir')): ?>
						<button type="button" id="tambah" class="btn btn-primary">
							Tambah Menu
						</button>
						<?php endif ?>
					</div>
					<!-- alert flashdata -->
					<?php
						$flash = $this->session->flashdata('message');
						if(!empty($flash)){
							echo '<div class="alert alert-'. $flash['type'] .'">' . $flash['message'] . '</div>';
							unset($_SESSION['message']);
						}
					?>
					<!-- end alert flashdata -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table" id="table-menu">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th>Nama</th>
													<th>Harga</th>
													<th>Jenis</th>
													<th>Gambar</th>
													<?php if(is_login('Kasir')): ?>
													<th>Aksi</th>
													<?php endif ?>
												</tr>
											</thead>
											<tbody>
												<?php if(!empty($menus)): $n = 1; ?>
													<?php foreach ($menus as $k => $m) : ?>
														<tr style="background-color: #F0EFEF;" class="group mt-2 mb-2">
															<td colspan="6" style="height: 30px; text-align: center;"><?= kapitalize($k) ?></td>
														</tr>
														<?php foreach($m as $v): ?>
															<tr class="m-5">
																<td><?= $n ?></td>
																<td><?= kapitalize($v['nama']) ?></td>
																<td><?= rupiah_format($v['harga']) ?></td>
																<td><?= kapitalize($v['jenis']) ?></td>
																<td>
																	<?php if(!empty($v['gambar']) && file_exists(get_path(ASSET_PATH . 'img/products/' . $v['gambar']))): ?>
																		<img style="width: 100px;" src="<?= base_url('assets/img/products/' . $v['gambar']) ?>">
																	<?php else:?>
																		Tidak ada gambar
																	<?php endif?>
																</td>
																<?php if(is_login('Kasir')): ?>
																<td class="action">
																	<button type="button" data-id="<?= $v['id'] ?>" class="update btn btn-warning btn-xs ml-2"><i class="fas fa-pencil-alt"></i></button>
																	<button type="button" data-id="<?= $v['id'] ?>" class="remove btn btn-danger btn-xs ml-2"><i class="fas fa-minus"></i></button>
																</td>
																<?php endif ?>
															</tr>
														<?php $n++; endforeach ?>
													<?php endforeach; ?>
												<?php else: ?>
													<tr id="none">
														<td colspan="6" style="text-align: center;">
															<p>Tidak Ada Data</p>
														</td>
													</tr>
												<?php endif ?>
											</tbody>
										</table>
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

	<!-- Modal -->
	<div class="modal fade" id="modal-form-menu" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal-form-menuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="" method="POST" enctype="multipart/form-data" action="" id="form-menu">
				<div class="modal-header">
					<h5 class="modal-title" id="modal-form-menuLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Nama Menu </label>
						<input class="form-control" required id="nama" name="nama" type="text"/>
					</div>
					<div class="form-group">
						<label>Harga (<small>dalam Rupiah</small>)</label>
						<input class="form-control" required id="harga" name="harga" type="number" min="0" />
					</div>
					<div class="form-group">
						<label>Jenis</label>
						<select class="form-control" required id="jenis" name="jenis">
							<option value="MAKANAN" selected id="jenis-makanan">Makanan</option>
							<option value="MINUMAN" id="jenis-minuman">Minuman</option>
							<option value="LAIN-LAIN" id="jenis-lain">Lain lain</option>
						</select>
					</div>
					<div class="form-group" style="display: none;">
						<label>Gambar saat ini</label>
						<img style="width: 100%;" src="" id="prevgambar"/>
					</div>
					<div class="form-group">
						<label>Gambar (<small>Opsional</small>)</label>
						<input class="form-control" id="gambar" name="gambar" type="file" accept=".png, .jpg, .jpeg"/>
					</div>
					<div class="form-group">
						<label>Keterangan (<small>Opsional</small>)</label>
						<textarea class="form-control" id="keterangan" name="keterangan"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</form>
		</div>
	</div>
	</div>
	<!-- scripts -->
	<?php $this->load->view("components/main/_scripts", $this->session->flashdata('message')); ?>
	<!-- ./scripts -->
	<script>
		var menus = <?= json_encode($tmp) ?>;
		$(document).ready(function() {
			$("#tambah, .update").click(function(){
				$("#modal-form-menu").modal('show');
				$("#modal-form-menu").on('shown.bs.modal', {edit: $(this).attr('id') != 'tambah', id: $(this).data('id')}, onShown);
			});
			
			function onShown(e){
				var data = e.data,
					form = $("#form-menu"),
					nama = $("#nama"),
					harga = $("#harga"),
					jenis = $("#jenis"),
					keterangan = $("#keterangan"),
					prevgambar = $("#prevgambar");
				if(data.edit){
					$("#modal-form-menuLabel").text("Update Menu")
					form.prop('action', path + 'menu/update/' + data.id);
					var id = data.id;
					var menu = menus.filter(e => e.id == id)[0];
					nama.val(menu.nama);
					harga.val(menu.harga);
					keterangan.val(menu.keterangan);
					$("#jenis option[value='" + menu.jenis + "']").prop('selected', true);
					jenis.trigger('change');
					$("#old-gambar").remove();
					if(menu.gambar){
						form.append("<input id='old-gambar' type='hidden' name='old_gambar' value='"+menu.gambar+"'>")
						prevgambar.parent().show();
						prevgambar.prop('src', path + 'assets/img/products/' + menu.gambar);
					}
				}else{
					$("#modal-form-menuLabel").text("Tambah Menu")
					form.prop('action', path + 'menu/create');
					prevgambar.parent().hide();
					nama.val("");
					harga.val("");
					keterangan.val("");
				}
			}
			$(".remove").click(function(){
				var bool = confirm("Yakin ingin menghapus menu?");
				if(bool)
					window.location.href = path + 'menu/delete/' + $(this).data('id');
			});
		});
	
	</script>
</body>

</html>
