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
											<form action="<?= base_url("incomingitem/create") ?>" method="post">
												<div class="form-group">
													<label for="incoming_item_code">Kode Transaksi</label>
													<input type="text" class="form-control" name="incoming_item_code" id="incoming_item_code" placeholder="Kode Barang" value="<?= $incoming_item_code ?>" readonly>
												</div>
												<div class="form-group">
													<label for="gudang_asal">Gudang Asal</label>
													<select name="gudang_asal" id="gudang_asal" class="form-control select2">
														<option value="" disabled selected>--Pilih Gudang--</option>
														<?php foreach ($gudang as $v) : ?>
															<option data-level="<?= $v['level_wilayah']?>" value="<?= $v['id'] ?>"><?= $v['nama'] . " - " . ($v['level_wilayah'] == '1' ? 'Prov. ' :( $v['level_wilayah'] == '3' ?  'Kec. ' : '') ).  kapitalize($v['wilayah_gudang']) ?></option>
														<?php endforeach; ?>
													</select>
													<?= form_error('gudang_asal', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
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
													<label for="id_items">Harga</label>
													<input type="hidden" name="old_price" id="old-price">
													<input type="hidden" name="old_stok" id="old-stok">
													<input class="form-control" type="text" name="incoming_price" id="incoming-price">
												</div>
												<div class="form-group">
													<label for="item_stock">Stock Barang</label>
													<input type="number" class="form-control" name="item_stock" id="ItemStock" placeholder="Stock Barang" readonly>
												</div>
												<div class="form-group">
													<label for="incoming_item_qty">Jumlah Stok Masuk</label>
													<div class="input-group">
														<input type="number" class="form-control <?= form_error('incoming_item_qty') ? 'is-invalid' : '' ?>" name="incoming_item_qty" id="IncomingItemQty" placeholder="Jumlah Stok Masuk">
														<div class="input-group-append">
															<span class="input-group-text" id="unitName">Satuan</span>
														</div>
														<?= form_error('incoming_item_qty', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
													</div>
												</div>
												<div class="form-group">
													<label for="item_stock_total">Jumlah Total Stock</label>
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

			var total = null;
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
				var stockIn = $('#IncomingItemQty').val();
				var totalStock = stock + stockIn;
				total = totalStock;
				$('#ItemStockTotal').val(totalStock);
				$("#incoming-price, #old-price").val(barang.item_price)

				$("#old-stok").val(barang.item_stock)

				var unitName = barang.unit_name;
				$('#unitName').text(unitName);

			})

			$('#IncomingItemQty').keyup(function() {
				var value = $(this).val();
				if (!value)
					value = 0
				$('#ItemStockTotal').val(parseInt(value) + parseInt(total));
			});

		});
	</script>


</body>

</html>
