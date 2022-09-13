<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/main/_head"); ?>
<!-- ./head -->
<style>
	.c-overlay {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 8; /* Specify a stack order in case you're using a different order for other elements */
   }
  .c-overlay-text{
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 50px;
    color: white;
    transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
  }
</style>
<body>
	<div class="c-overlay">
		<div class="c-overlay-text">Loading</div>
	</div>
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
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped" id="table-meja">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th>Kode</th>
													<th>Status</th>
													<th>QR Code</th>
													<?php if(is_login('Kasir')): ?>
														<th>Aksi</th>
													<?php endif ?>
												</tr>
											</thead>
											<tbody>
												<?php if(!empty($meja)): ?>
													<?php foreach ($meja as $m) : ?>
														<tr>
															<td><?= $m['id'] ?></td>
															<td><?= $m['kode'] ?></td>
															<td><span class="badge badge-<?= $m['status'] == 'KOSONG' ? 'success' : 'danger' ?>"><?= $m['status'] ?></span></td>
															<td><img style="width: 100%;" src="<?= base_url('assets/img/qr/' . $m['kode'] . '.png') ?>"></td>
															<?php if(is_login('Kasir')): ?>
															<td class="action">
																<button type="button" data-id="<?= $m['id'] ?>" data-kode="<?= $m['kode'] ?>" class="remove btn btn-danger btn-xs ml-2"><i class="fas fa-minus"></i></button>
															</td>
															<?php endif ?>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr id="none">
														<td colspan="5" style="text-align: center;">
															<button type="button" id="add-first" class=" btn btn-primary btn-xs"><i class="fas fa-plus"></i></button>
														</td>
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
			reindex();
			function reindex(){
				var tr = $("#table-meja tbody tr");
				if(tr.length > 1){
					$('#table-meja tbody #add').remove();
					$(tr[tr.length - 1]).find('.action').prepend(
						'<button type="button" id="add" class="btn btn-primary btn-xs mr-2"><i class="fas fa-plus"></i></button>'
					)
				}else if(tr.length == 1){
					$(tr[tr.length - 1]).find('.action').prepend(
						'<button type="button" id="add" class="btn btn-primary btn-xs mr-2"><i class="fas fa-plus"></i></button>'
					)
				}else if(tr.length == 0){
					$('#table-meja tbody').append(
						'<tr id="none">' +
							'<td colspan="5" style="text-align: center;">' +
								'<button type="button" id="add-first" class=" btn btn-primary btn-xs"><i class="fas fa-plus"></i></button>' +
							'</td>' +
						'</tr>'
					);
				}
				$("#add, #add-first").off('click');
				$("#add, #add-first").click(add);
				$(".remove").off('click');
				$(".remove").click(remove);
			}
			function add(){
				showLoading();
				var body = $("#table-meja tbody");
				$.post(path + 'meja/add').then(function(data, status){
					if(status == 'success'){
						$('#none').remove();
						var row = '<tr>' +
								'<td>' + data['id'] + '</td>'+
								'<td>' + data['kode'] + '</td>'+
								'<td><span class="badge badge-success' + '">' + data['status'] + '</span></td>'+
								'<td><img style="width: 100%;" src="' + path + 'assets/img/qr/' + data['kode'] + '.png' + '"></td>'+
								'<td class="action">' +
									'<button type="button" data-id="'+ data['id'] +'" data-kode="'+ data['kode'] +'" class="remove btn btn-danger btn-xs"><i class="fas fa-minus"></i></button>' +
								'</td>'+
							'</tr>';
						body.append(row);
						reindex();
					}
					endLoading();

				}).fail(function(data){
					
				});
			}

			function remove(){
				showLoading();
				var body = $("#table-meja tbody");
				var row = $(this).parent().parent();
				var id = $(this).data('id');
				var kode = $(this).data('kode');
				console.log(kode)
				$.post(path + 'meja/remove/' + id + '/' + kode).then(function(data, status){
					if(status == 'success'){
						$(row).remove();
						reindex();
					}
					endLoading();
				}).fail(function(data){
					
				});
				
			}
		});

	
	</script>
</body>

</html>
