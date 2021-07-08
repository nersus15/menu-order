<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); 
$bulan = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ]
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
						<a id="cetak_pdf_stokbarang" href="#" target="_blank" class="btn btn-primary btn-lg">Cetak Pdf</a>
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
							<div class="filter col-12 mb-4">
								<h5>Filter:</h5>
								<div class="row ml-4">
									<div class="form-group col-2">
										<label for="">Tahun</label>
										<select name="" id="f-tahun" class="form-control">
											<?php for ($i = intval($created_at['min']); $i <= intval($created_at['max']); $i++) : ?>
												<option <?= $i == $def_tahun ? "selected" : null ?> value="<?= $i ?>"><?= $i ?> </option>
											<?php endfor ?>
										</select>
									</div>
									<div class="form-group col-2">
										<label for="">Bulan</label>
										<select name="" id="f-bulan" class="form-control">
											<?php foreach ($bulan as $key => $value) : ?>
												<option <?= $key + 1 == $def_bulan ? "selected" : null ?> value="<?= $key + 1 ?>"><?= $value ?> </option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th>Kode Barang</th>
													<th>Nama Barang</th>
													<th>Stock</th>
													<th>Satuan</th>
													<th>Biaya</th>
													<th>Total</th>
													<th>Deskripsi</th>
													<th>Kategori</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; $total = 0; ?>
												<?php if(empty($items)): ?>
													<tr>
														<td style="text-align: center;" colspan="9">Tidak Ada Data pada Bulan <?= $bulan[$def_bulan - 1] . " " . $def_tahun ?> </td>
													</tr>
												<?php else: foreach ($items as $item) : $total += ($item["item_price"] * $item["item_stock"]); ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $item["item_code"] ?></td>
														<td><?= $item["item_name"] ?></td>
														<td><?= $item["item_stock"] ?></td>
														<td><?= $item["unit_name"] ?></td>
														<td><?= rupiah_format($item["item_price"]) ?> / <?= $item["unit_name"] ?></td>
														<td><?= rupiah_format($item["item_price"] * $item["item_stock"]) ?></td>
														<td><?= $item["item_description"] ?></td>
														<td><?= $item["category_name"] ?></td>
													</tr>
												<?php endforeach; endif?>
												<?php if(count($items) > 0): ?>
													<tr>
													<td colspan="6"><b>Total</b></td>
													<td colspan="3"><b><?= rupiah_format($total) ?></b></td>
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

	<!-- scripts -->
	<?php $this->load->view("components/main/_scripts", $this->session->flashdata('message')); ?>
	<!-- ./scripts -->

	<script>
		$(document).ready(function() {
			var base_url = "<?= base_url() ?>";
			$("#cetak_pdf_stokbarang").click(function(e){
				e.preventDefault();
				var bulan = $("#f-bulan").val();
				var tahun = $("#f-tahun").val();
				console.log(base_url + "report/cetakstok?t=" + tahun + "&b=" + bulan);
				window.open(base_url + "report/cetakstok?t=" + tahun + "&b=" + bulan, '_blank')
			})
			$("#f-tahun").change(function() {
				var bulan = $("#f-bulan").val();
				location.href = base_url + 'report/stok?b=' + bulan + '&t=' + $(this).val()
			})
			$("#f-bulan").change(function() {
				var tahun = $("#f-tahun").val();
				location.href = base_url + 'report/stok?b=' + $(this).val() + '&t=' + tahun
			})
		})
	</script>

</body>

</html>