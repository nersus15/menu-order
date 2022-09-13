<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<script src="<?= base_url('assets/modules/chartjs/chart.min.js') ?>" ></script>
<!-- ./head -->
<?php
$daftar_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
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
									<div class="card-icon bg-warning">
										<i class="fas fa-users"></i>
									</div>
									<div class="card-wrap">
										<div class="card-header">
											<h4>Jumlah Kasir</h4>
										</div>
										<div class="card-body">
											<?= $kasir ?>
										</div>
									</div>
								</div>
							</a>
						</div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<a href="<?= base_url("meja") ?>">
								<div class="card card-statistic-1">
									<div class="card-icon bg-danger">
										<i class="fas fa-users"></i>
									</div>
									<div class="card-wrap">
										<div class="card-header">
											<h4>Jumlah Meja</h4>
										</div>
										<div class="card-body">
											<?= $meja ?>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<a href="<?= base_url("menu") ?>">
								<div class="card card-statistic-1">
									<div class="card-icon bg-success">
										<i class="fas fa-cube"></i>
									</div>
									<div class="card-wrap">
										<div class="card-header">
											<h4>Jumlah Menu</h4>
										</div>
										<div class="card-body">
											<?= $menu ?>
										</div>
									</div>
								</div>
							</a>
						</div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<a href="<?= base_url("report/data/transaksi") ?>">
								<div class="card card-statistic-1">
									<div class="card-icon bg-info">
										<i class="fas fa-cube"></i>
									</div>
									<div class="card-wrap">
										<div class="card-header">
											<h4>Jumlah Penjualan</h4>
										</div>
										<div class="card-body">
											<?= $penjualan ?>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h5>Grafik penjualan tahun ini</h5>
                            <?php $this->load->view('dashboard/sub/grafik_tahunan');?>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5>Grafik penjualan bulan ini</h5>
                            <?php $this->load->view('dashboard/sub/grafik_bulanan');?>
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