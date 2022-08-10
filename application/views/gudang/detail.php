<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->
<?php
	$myGudang = [];
	foreach(sessiondata('login', 'gudang') as $mgudang){
		$myGudang[] = $mgudang['id'];
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
						<p><a href="<?= base_url('gudang') ?>"><i class="text-primary fas fa-arrow-left"></i> Kembali</a></p>
						<h1 class="ml-5">Gudang  <?= str_replace('Gudang', '', kapitalize($gudang[0]['nama'])) ?></h1>
					</div>
					<div class="row">
						<div class="col-12">
							<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="detail-gudang" data-toggle="tab" href="#detail-gudang-content" role="tab" aria-controls="detail-gudang" aria-selected="true">Detail Gudang</a>
								</li>
								<?php if(in_array($gudang[0]['id'], $myGudang)): ?>
								<li class="nav-item">
									<a class="nav-link" id="transaksi" data-toggle="tab" href="#transaksi-content" role="tab" aria-controls="transaksi" aria-selected="false">Transaksi</a>
								</li>
								<?php endif ?>
								<li class="nav-item">
									<a class="nav-link" id="items" data-toggle="tab" href="#items-content" role="tab" aria-controls="items" aria-selected="false">Barang</a>
								</li>
							</ul>
						</div>
						<div class="tab-content col-12" id="myTabContent">
							<div class="tab-pane fade show active" id="detail-gudang-content" role="tabpanel" aria-labelledby="detail-gudang">
								<?php $this->load->view('gudang/sub/detail', array('gudang' => $gudang[0])) ?>
							</div>
							<?php if(in_array($gudang[0]['id'], $myGudang)): ?>
							<div class="tab-pane fade" id="transaksi-content" role="tabpanel" aria-labelledby="transaksi">
								<?php $this->load->view('gudang/sub/transaksi', array('transaksi' => $transaksi)) ?>
							</div>
							<?php endif?>
							<div class="tab-pane fade" id="items-content" role="tabpanel" aria-labelledby="items">
								<?php $this->load->view('gudang/sub/items', array('items' => $gudang[0]['items'])) ?>
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

</body>

</html>