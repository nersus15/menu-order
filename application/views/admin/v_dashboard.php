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
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<a href="<?= base_url("gudang") ?>">
								<div class="card card-statistic-1">
									<div class="card-icon bg-info">
										<i class="fas fa-warehouse"></i>
									</div>
									<div class="card-wrap">
										<div class="card-header">
											<h4>Jumlah Gudang</h4>
										</div>
										<div class="card-body">
											<?= $gudang ?>
										</div>
									</div>
								</div>
							</a>
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
