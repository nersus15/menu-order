<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->
<?php
$daftar_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$Tmasuk = 0;
$Tkeluar = 0;
$Tbulanan = [];
foreach ($daftar_bulan as $b) {
	$Tbulanan[$b] = 0;
}
foreach ($transaksi as $k => $v) {
	if ($v['jenis'] == 'masuk') $Tmasuk = $Tmasuk + 1;
	if ($v['jenis'] == 'keluar') $Tkeluar = $Tkeluar + 1;

	$tgl = $v['transaksi_date'];
	$bulan = intval(substr($tgl, 6, 7));
	if (substr($tgl, 0, 4) !=  date('Y')) continue;

	$Tbulanan[$daftar_bulan[$bulan - 1]] = $Tbulanan[$daftar_bulan[$bulan - 1]] + 1;
}
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
					<div class="section-header">
						<h1><?= $title; ?></h1>
					</div>
					<div class="row">
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<a href="<?= base_url("user") ?>">
								<div class="card card-statistic-1">
									<div class="card-icon bg-primary">
										<i class="fas fa-users"></i>
									</div>
									<div class="card-wrap">
										<div class="card-header">
											<h4>Jumlah Staff</h4>
										</div>
										<div class="card-body">
											<?= $staff ?>
										</div>
									</div>
								</div>
							</a>
						</div>

						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<a href="<?= base_url("item") ?>">
								<div class="card card-statistic-1">
									<div class="card-icon bg-success">
										<i class="fas fa-cube"></i>
									</div>
									<div class="card-wrap">
										<div class="card-header">
											<h4>Jumlah Barang</h4>
										</div>
										<div class="card-body">
											<?= $items ?>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-12">
							<div class="card card-statistic-1">
								<div class="card-icon bg-info">
									<i class="fas fa-handshake-angle"></i>
								</div>
								<div class="card-wrap">
									<div class="card-header row">
										<h4>Jumlah Transaksi</h4>
										<p style="color: black; font-size: 20px;" class="text-black float-right mr-5"><?= count($transaksi) ?></p>
									</div>
									<div class="card-body mt-4">
										<p style="font-size: 14px;">
											<a style="text-decoration: none;" class="more" data-toggle="collapse" href="#data-booking" role="button" aria-expanded="false" aria-controls="data-booking">
												Lihat detail <span class="float-right"><i class="fas fa-angle-right"></i></span>
											</a>
										</p>
										<hr class="col-8">
										<div class="collapse" id="data-booking">
											<div class="col-12">
												<a style="text-decoration: none;" href="<?= base_url('incomingitem') ?>">
													<p>Transaksi Masuk <span class="float-right"><?= $Tmasuk ?></span></p>
												</a>
												<a style="text-decoration: none" href="<?= base_url('outcomingitem') ?>">
													<p>Transaksi Keluar <span class="float-right"><?= $Tkeluar ?></span></p>
												</a>
												<p style="text-align: center;font-size: 15px;"> Transaksi Tahun Ini</p>
												<?php foreach ($Tbulanan as $bulan => $nilai) : ?>
													<p><?= $bulan ?> <span class="float-right"><?= $nilai ?></span></p>
													<hr>
												<?php endforeach ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<script>
						$(document).ready(function() {
							$(".more").click(function() {
								var collapse = $(this).find('i').hasClass('fa-angle-right')
								if (collapse) {
									$(this).find('i').removeClass('fa-angle-right');
									$(this).find('i').addClass('fa-angle-down');
								} else {
									$(this).find('i').removeClass('fa-angle-down');
									$(this).find('i').addClass('fa-angle-right');
								}
							});
						});
					</script>
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