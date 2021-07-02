<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->
<!-- load jquery ui and jquery -->
<link rel="stylesheet" href="<?= base_url('assets/jquery-ui/jquery-ui.min.css'); ?>" /> <!-- Load file css jquery-ui -->
<script src="<?= base_url('assets/jquery.min.js'); ?>"></script> <!-- Load file jquery -->

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
						<h1>Laporan</h1>
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
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-4 mx-auto">
									<form action="<?= base_url("report/filtertransactionsreport") ?>" method="GET">
										<div class="form-group">
											<label for="reports">Jenis Laporan</label>
											<select name="reports" id="reports" class="form-control">
												<option value="" disabled selected>--Pilih Jenis Laporan--</option>
												<option value="1">Barang Keluar</option>
												<option value="2">Barang Masuk</option>
											</select>
										</div>
										<div class="form-group">
											<label for="filter">Filter Berdasarkan</label>
											<select name="filter" id="filter" class="form-control">
												<option value="" disabled selected>--Filter Laporan--</option>
												<option value="1">Tanggal</option>
												<option value="2">Bulan</option>
												<option value="3">Tahun</option>
											</select>
										</div>

										<div class="form-group" id="form-tanggal">
											<label for="date">Tanggal</label>
											<input type="text" name="tanggal" class="form-control input-tanggal">
										</div>

										<div class="form-group" id="form-bulan">
											<label for="month">Bulan</label>
											<select name="bulan" id="month" class="form-control">
												<option value="" disabled selected>Pilih Bulan</option>
												<option value="1">Januari</option>
												<option value="2">Februari</option>
												<option value="3">Maret</option>
												<option value="4">April</option>
												<option value="5">Mei</option>
												<option value="6">Juni</option>
												<option value="7">Juli</option>
												<option value="8">Agustus</option>
												<option value="9">September</option>
												<option value="10">Oktober</option>
												<option value="11">November</option>
												<option value="12">Desember</option>
											</select>
										</div>
										<div class="" id="form-tahun-keluar">
											<label for="years">Tahun</label>
											<select name="tahun" id="years" class="form-control">
												<option value="" disabled selected>Pilih Tahun</option>
												<?php foreach ($outcoming_year_options as $year) : ?>
													<option value="<?= $year["tahun"]; ?>"><?= $year["tahun"] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="" id="form-tahun-masuk">
											<label for="years">Tahun</label>
											<select name="tahun" id="years" class="form-control">
												<option value="" disabled selected>Pilih Tahun</option>
												<?php foreach ($incoming_year_options as $year) : ?>
													<option value="<?= $year["tahun"]; ?>"><?= $year["tahun"] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<hr>
										<div class="form-action">
											<button type="submit" class="btn btn-primary btn-block">Buat Laporan</button>
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

	<script src="<?= base_url('assets/jquery-ui/jquery-ui.min.js'); ?>"></script> <!-- Load file plugin js jquery-ui -->

	<!-- <script>
	const flashData = $('.flash-data').data('flashdata');
			if (flashData) {
				swal({
					title: 'Gagal',
					text: flashData,
					icon: 'error'
				});
			}
	</script> -->

	<script>

		$(document).ready(function() { // Ketika halaman selesai di load


			$('.input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd' // Set format tanggalnya jadi yyyy-mm-dd
			});

			$('#form-tanggal, #form-bulan, #form-tahun-keluar, #form-tahun-masuk').hide(); // Sebagai default kita sembunyikan form filter tanggal, bulan & tahunnya

			$('#reports').change(function() {
				if ($(this).val() == '1') {
					$('#filter').change(function() { // Ketika user memilih filter
						if ($(this).val() == '1') { // Jika filter nya 1 (per tanggal)
							$('#form-tanggal').show(); // Tampilkan form tanggal
							$('#form-bulan, #form-tahun-keluar, #form-tahun-masuk').hide(); // Sembunyikan form bulan dan tahun
						} else if ($(this).val() == '2') { // Jika filternya 3 (per tahun)
							$('#form-tanggal, #form-tahun-masuk').hide(); // Sembunyikan form tanggal dan bulan
							$('#form-bulan, #form-tahun-keluar').show(); // Tampilkan form tahun
						} else {
							$('#form-tanggal, #form-bulan, #form-tahun-masuk').hide();
							$('#form-tahun-keluar').show();
						}
						$('#form-tanggal input, #form-bulan select, #form-tahun-keluar select').val(''); // Clear data pada textbox tanggal, combobox bulan & tahun
					});
				} else {
					$('#filter').change(function() { // Ketika user memilih filter
						if ($(this).val() == '1') { // Jika filter nya 1 (per tanggal)
							$('#form-tanggal').show(); // Tampilkan form tanggal
							$('#form-bulan, #form-tahun-masuk, #form-tahun-keluar').hide(); // Sembunyikan form bulan dan tahun
						} else if ($(this).val() == '2') { // Jika filternya 3 (per tahun)
							$('#form-tanggal, #form-tahun-keluar').hide(); // Sembunyikan form tanggal dan bulan
							$('#form-bulan, #form-tahun-masuk').show(); // Tampilkan form tahun
						} else {
							$('#form-tanggal, #form-bulan, #form-tahun-keluar').hide();
							$('#form-tahun-masuk').show();
						}
						$('#form-tanggal input, #form-bulan select, #form-tahun-masuk select').val(''); // Clear data pada textbox tanggal, combobox bulan & tahun
					});
				}
			});

		})
	</script>

</body>

</html>
