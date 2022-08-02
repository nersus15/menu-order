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
											<form action="<?= base_url("outcomingitem/create") ?>" method="post">
												<div class="form-group">
													<label for="outcoming_item_code">Kode Transaksi</label>
													<input type="text" class="form-control" name="outcoming_item_code" id="outcoming_item_code" placeholder="Kode Transaksi" value="<?= $outcoming_item_code ?>" readonly>
												</div>
												<div class="form-group">
													<label for="jenis-tujuan">Jenis Tujuan</label>
													<div class="row">
														<div class="col-sm-6 col-md-2 form-group">
															<input type="radio" name="jenis_tujuan" id="jenis-tujuan-gudang" value="gudang" <?= form_error('tujuan') ? 'checked' : '' ?>>
															<label for="jenis-tujuan-gudang">Gudang</label>
														</div>
														<div class="col-sm-6 col-md-2 form-group">
															<input type="radio" name="jenis_tujuan" id="jenis-tujuan-toko" value="toko" <?= form_error('toko') ? 'checked' : '' ?>>
															<label for="jenis-tujuan-toko">Toko</label>
														</div>
													</div>
												</div>
												<div class="form-group" id="region-tujuan-gudang">
													<label for="tujuan">Gudang Tujuan</label>
													<select name="tujuan" id="tujuan" class="form-control select2  <?= form_error('tujuan') ? 'is-invalid' : '' ?>">
														<option value="" disabled selected>--Pilih Gudang--</option>
														<?php foreach ($gudang as $v) : ?>
															<option data-level="<?= $v['level_wilayah']?>" value="<?= $v['id'] ?>"><?= $v['nama'] . " - " . ($v['level_wilayah'] == '1' ? 'Prov. ' :( $v['level_wilayah'] == '3' ?  'Kec. ' : '') ).  kapitalize($v['wilayah_gudang']) ?></option>
														<?php endforeach; ?>
													</select>
													<?= form_error('tujuan', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group" style="display: none;" id="region-tujuan-toko">
													<label for="toko">Toko Tujuan</label>
													<input name="toko" id="toko" class="form-control <?= form_error('toko') ? 'is-invalid' : '' ?>" />
													<?= form_error('toko', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="id_items">Barang</label>
													<select name="id_items" id="ItemId" class="form-control select2">
														<option value="" disabled selected>--Pilih Barang--</option>
														<?php foreach ($items as $item) : ?>
															<option value="<?= $item["id_item"] ?>"><?= $item["item_code"] ?> | <?= $item["item_name"] ?></option>
														<?php endforeach; ?>
													</select>
													<?= form_error('id_items', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
												</div>
												<div class="form-group">
													<label for="item_stock">Stock Barang</label>
													<input type="number" class="form-control" name="item_stock" id="ItemStock" placeholder="Stock Barang" readonly>
												</div>
												<div class="form-group">
													<label for="outcoming_item_qty">Jumlah Barang Keluar</label>
													<div class="input-group">
														<input type="number" class="form-control <?= form_error('outcoming_item_qty') ? 'is-invalid' : '' ?>" name="outcoming_item_qty" id="OutcomingItemQty" placeholder="Jumlah Stok Masuk">
														<div class="input-group-append">
															<span class="input-group-text" id="unitName">Satuan</span>
														</div>
													</div>
													<div class="invalid-feedback" id="errorValue">Jumlah Barang Keluar lebih besar dari stok barang</div>
												</div>
												<div class="form-group">
													<label for="item_stock_total">Sisa Stock Barang</label>
													<input type="number" class="form-control" name="item_stock_total" id="ItemStockTotal" placeholder="Jumlah Total Stock" readonly>
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
	<script>
		$(document).ready(function() {

			var dataItem = <?= json_encode($items) ?>;
			$('#ItemId').change(function(e) {
				e.preventDefault();
				var value = $(this).val();
				var barang = dataItem.filter(item => item.id_item == value);
				if (barang.length == 0) {
					return
				}
				barang = barang[0];
				var stock = barang.item_stock;

				$('#ItemStock').val(stock);
				var stockOut = $('#OutcomingItemQty').val();
				if (!stockOut) {
					stockOut = 0;
				}

				stockOut = parseInt(stockOut);
				if (parseInt(stock) < stockOut) {
					$('#errorValue').show();
					$('button[type="submit"]').prop('disabled', true);
					$('#ItemStockTotal').val(stock);
					return
				} else {
					$('#errorValue').hide();
					$('button[type="submit"]').prop('disabled', false);
				}

				var totalStock = stock - stockOut;
				totalStock = parseInt(totalStock);
				$('#ItemStockTotal').val(totalStock);

				var unitName = barang.unit_name;
				$('#unitName').text(unitName);

			})

			$('#OutcomingItemQty').keyup(function() {
				var total = $('#ItemStock').val();
				var value = $(this).val();

				if (!total) {
					total = 0;
				}

				total = parseInt(total);

				if (!value)
					value = 0
				value = parseInt(value);
				if (value > total) {
					$('#errorValue').show();
					$('button[type="submit"]').prop('disabled', true);
					$('#ItemStockTotal').val(total);
					return
				} else {
					$('#errorValue').hide();
					$('button[type="submit"]').prop('disabled', false);
				}
				$('#ItemStockTotal').val(parseInt(total) - parseInt(value));
			});
			$("input[name='jenis_tujuan']").change(function(){
				var val = $(this).val();
				if(val == 'toko'){
					$("#region-tujuan-toko").show();
					$("#region-tujuan-gudang").hide();
				}else if(val == 'gudang'){
					$("#region-tujuan-toko").hide();
					$("#region-tujuan-gudang").show();
				}
			});
			$("input[name='jenis_tujuan']:checked").trigger('change');
		});
	</script>


</body>

</html>
