<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->
<?php
// $daftar_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
// $Tmasuk = 0;
// $Tkeluar = 0;
// $Tbulanan = [];
// foreach ($daftar_bulan as $b) {
// 	$Tbulanan[$b] = 0;
// }
// foreach ($transaksi as $k => $v) {
// 	if ($v['jenis'] == 'masuk') $Tmasuk = $Tmasuk + 1;
// 	if ($v['jenis'] == 'keluar') $Tkeluar = $Tkeluar + 1;

// 	$tgl = $v['transaksi_date'];
// 	$bulan = intval(substr($tgl, 6, 7));
// 	if (substr($tgl, 0, 4) !=  date('Y')) continue;

// 	$Tbulanan[$daftar_bulan[$bulan - 1]] = $Tbulanan[$daftar_bulan[$bulan - 1]] + 1;
// }
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