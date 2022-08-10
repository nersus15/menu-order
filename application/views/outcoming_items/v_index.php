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
						<a href="<?= base_url("outcomingitem/create") ?>" class="btn btn-primary btn-lg">Tambah Barang Keluar</a>
					</div>
					<!-- alert flashdata -->
					<?php
					$flash = $this->session->flashdata('message');
					if (!empty($flash)) {
						echo '<div class="alert alert-' . $flash['type'] . '">' . $flash['message'] . '</div>';
						unset($_SESSION['message']);
					}
					?>
					<!-- end alert flashdata -->
					<div class="row">
						<div class="col-12">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#export-data">
								Export Data
							</button>
							<div class="card mt-2">
								<div class="card-body">
									<div class="table-responsive">

										<table class="table table-striped" id="table-1">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th>Tanggal</th>
													<th>Kode Transaksi</th>
													<th>Kode Barang</th>
													<th>Nama Barang</th>
													<th>Jumlah Keluar</th>
													<th>Tujuan</th>
													<th>Nota</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($outcoming_items as $outcoming_item) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $outcoming_item["transaksi_date"] ?></td>
														<td><?= $outcoming_item["transaksi_code"] ?></td>
														<td><?= $outcoming_item["item_code"] ?></td>
														<td><?= $outcoming_item["item_name"] ?></td>
														<td><?= $outcoming_item["transaksi_qty"] ?></td>
														<td><?= !empty($outcoming_item['tujuan']) && strlen($outcoming_item['tujuan']) > 8 ? kapitalize($outcoming_item['tujuan']) : $outcoming_item["namagudang"] .  " - " . ($outcoming_item['lvlwil'] == '1' ? 'Prov. ' : ($outcoming_item['lvlwil'] == '3' ? 'Kec. ' : null)) . kapitalize($outcoming_item['namawil']) ?></td>
														<td>
															<?php if (!empty($outcoming_item['nota'])) : ?>
																<a href="<?= base_url('assets/nota/' . $outcoming_item['nota']) ?>" target="_blank">Lihat Nota</a>
															<?php endif ?>
														</td>
														<td>
															<a href="<?= base_url("outcomingitem/delete/" . $outcoming_item["id_transaksi"]) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<div class="modal" tabindex="-1" id="export-data" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="form-group">
										<input checked type="radio" name="jenis" id="jenis-semua" value="semua">
										<label for="jenis-semua">Semua</label>
									</div>
									<div class="form-group ml-4">
										<input type="radio" name="jenis" id="jenis-filter" value="filter">
										<label for="jenis-filter">Filter</label>
									</div>
								</div>
								<div style="display: none;" class="form-group" id="filter">
									<input type="hidden" name="filter_val" id="filter-val">
									<label for="">Tanggal Data</label>
									<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
										<i class="fa fa-calendar"></i>&nbsp;
										<span></span> <i class="fa fa-caret-down"></i>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" id="export">Export</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
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
			$(function() {
				var start = moment().subtract(29, 'days');
				var end = moment();

				function cb(start, end) {
					$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
					$("#filter-val").val(start.format('YYYY-MM-DD') + '_' + end.format('YYYY-MM-DD'));
				}

				$('#reportrange').daterangepicker({
					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					}
				}, cb);

				cb(start, end);

			});
			$("input[name='jenis']").change(function(){
				var val =$(this).val();
				if(val == 'semua'){
					$("#filter").hide();
				}else if(val == 'filter'){
					$("#filter").show();
				}
			});
			$("#export").click(function(){
				if($("input[name='jenis']:checked").val() == 'semua'){
					window.open(path + 'report/transaksi/keluar/', '_blank');
				}else{
					window.open(path + 'report/transaksi/keluar/' + $("#filter-val").val(), '_blank');
				}
			});
		});
	</script>
</body>

</html>