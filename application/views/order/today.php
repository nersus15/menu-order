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
										<table class="table" id="table-order">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th>Token</th>
													<th>Atas Nama</th>
													<th>Meja</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
                                                <?php if(!empty($orders)):  $no = 1;?>
                                                    <?php foreach($orders as $token => $order): ?>
                                                        <tr>
                                                            <td><?= $no ?> </td>
                                                            <td><?= $token ?> </td>
                                                            <td><?= $order['atasnama'] ?> </td>
                                                            <td><?= $order['meja'] ?> </td>
                                                            <td>
                                                                <button type="button" data-token="<?= $token ?>" class="detail btn btn-info btn-xs ml-2">Detail</button>
                                                            </td> 
                                                        </tr>    
                                                    <?php $no++; endforeach ?>
                                                <?php else: ?>
                                                    <tr id="none">
                                                        <td colspan="4" style="text-align: center;">
                                                            <p>Tidak Ada Data</p>
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

	<!-- Modal -->
	<div class="modal fade" id="modal-order" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">			
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
		</div>
	</div>
	</div>
	<!-- scripts -->
	<?php $this->load->view("components/main/_scripts", $this->session->flashdata('message')); ?>
	<!-- ./scripts -->
	<script>
		$(document).ready(function() {
            $(".detail").click(function(){
                $("#modal-order").off('shown.bs.modal');
                $("#modal-order").on('shown.bs.modal', {bayar: false, token: $(this).data('token')}, onShown);
                $("#modal-order").modal('show');
            });
 	
			function onShown(e){
				var modal = $("#modal-order"),
                    token = e.data.token,
                    bayar = e.data.bayar;
                var body = modal.find('.modal-body'), 
                    title = $("#modal-label");

                $(body).empty();
                if(!bayar){
                    showLoading();
                    $.get(path + 'ws/order/1/' + token, function(data){
                        title.html("Pesanan atas nama <b>" + data.data[0].atasnama +"</b>");
                        var tbody = '<div class="table-responsive"><table class="table table-striped" id="table-pesanan">' +
                                    '<thead>' +
                                       '<tr>' +
                                            '<th class="text-center"> No </th>' +
                                            '<th>Nama</th>' +
                                            '<th>Harga</th>' +
                                            '<th>Jumlah</th>' +
                                            '<th>Sub Total</th>' +
                                       ' </tr>' +
                                    '</thead>' +
                                    '<tbody>' +
                                    '</tbody>'+
                                '</table></div>'; 
                        $(body).append(tbody);
                        var rows = '';
                        var total = 0;
                        var no = 1;
                        data.data.forEach(e => {
                            total += parseInt(e.sub_total);
                            rows += '<tr>' +
                                    '<td>' + no + '</td>' +
                                    '<td>' + e.nama + '</td>' +
                                    '<td>' + e.harga + '</td>' +
                                    '<td>' + e.jmlh + '</td>' +
                                    '<td>Rp. ' + e.sub_total.toString().rupiahFormat() + '</td>' +
                                    '</tr>';
                            no++;
                        });
                        rows += '<tr>' +
                                    '<td colspan="4"><b>Total</b></td>' +
                                    '<td>Rp. '+ total.toString().rupiahFormat() +'</td>'
                                '</tr>';
                        $("#table-pesanan tbody").append(rows);
                        endLoading();
                    });
                }
			}
		});
	
	</script>
</body>

</html>
