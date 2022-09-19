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
                        <button type="button" id="pay" class="btn btn-primary">
							Bayar dengan scan
						</button>
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
	<div class="modal-dialog">
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
            var html5QrcodeScanner;
 	
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
                }else{
                    title.text("Scan untuk membayar");
                    body.append(
                        '<div class="card-body">' +
                                '<div style="width: 100%" id="reader"></div>' +
							'</div>' );
                        $("#reader__dashboard_section_swaplink").text("");
                        $("#reader__dashboard_section_swaplink").click(function(e){
                            e.preventDefault();
                        });
                        $("#reader__camera_permission_button").text("Izinkan untuk Mengakses Kamera")
                        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
                        function onScanSuccess(decodedText, decodedResult) {
                            showLoading();
                            html5QrcodeScanner.clear();
                            var d = decodedText.split('=');
                            $.post(path + 'order/pay/' + d[0] + '/' + d[1]).then(function(data){
                                endLoading();
                                alert("Berhasil melakukan pembayaran untuk meja " + d[1]);
                            });
                            $("#modal-order").modal('hide');
                            
                        }
                
                        html5QrcodeScanner.render(onScanSuccess);
                }
			}
            renderTable();
            function connect(token) {
                return new Promise(function(resolve, reject) {
                    var server = new WebSocket('wss://ws-kamscode.herokuapp.com');
                    server.onopen = function() {
                        resolve(server);
                    };
                    server.onerror = function(err) {
                        reject(err);
                    };
                });
            }
            async function renderTable(loading= true){
                var emptyData = '<tr id="none">' +
                    '<td colspan="4" style="text-align: center;">' +
                        '<p>Tidak Ada Data</p>' +
                    '</td>' +
                '</tr>';
                $("#table-order tbody").empty();

                if(loading)
                    showLoading();
                $.get(path + 'ws/order', function(data){
                    if(Object.keys(data.data).length > 0){
                        var rows = '';
                        var no = 1;
                        data = data.data;
                        Object.keys(data).forEach(e => {
                            var rowdata = data[e];
                            var display = rowdata.status == 'PROSES' ? 'none' : 'block';
                            rows += '<tr>' +
                                '<td>' + no + '</td>'+
                                '<td>' + e + '</td>'+
                                '<td>' + rowdata.atasnama + '</td>' +
                                '<td>' + rowdata.meja + '</td>' +
                                '<td class="row">' +
                                 '<button type="button" data-meja ="'+ rowdata.meja +'" data-token="' + e +'" class="bayar btn btn-primary btn-sm ml-2">Bayar</button>' +
                                 '<button style="display:'+ display +'" type="button" data-meja ="'+ rowdata.meja +'" data-token="' + e +'" class="proses btn btn-warning btn-sm ml-2">Proses</button>' +
                                 '<button type="button" data-token="' + e +'" class="detail btn btn-info btn-sm ml-2">Detail</button>' +
                                '</td>' + 
                                '</tr>';                            
                        });
                        $("#table-order tbody").append(rows);
                        $(".bayar, .detail").off('click');
                       
                        $(".detail").click(function(){
                            $("#modal-order").off('shown.bs.modal');
				            $("#modal-order").on('shown.bs.modal', {bayar: $(this).hasClass('bayar'), token: $(this).data('token')}, onShown);
                            $("#modal-order").modal('show');
                        });
                        $(".proses").click(async function(){
                            var meja = $(this).data('meja'),
                                token = $(this).data('token');
                            var b = confirm("Yakin ingin memproses pesanan untuk meja " + meja + ' ?');
                            if(!b) return;
                            showLoading();
                            $.get(path + 'order/proses/' + token, function(data){
                                endLoading();
                                connect().then(function(socket){
                                    socket.send(JSON.stringify({type: 'pelanggan'}));
								    socket.close();
                                });                                
                            }).fail(function(){
                                endLoading();
                            });
                        });
                        $(".bayar").click(async function(){
                            var meja = $(this).data('meja'),
                                token = $(this).data('token');
                            var b = confirm("Yakin ingin melakukan pembayaran untuk meja " + meja);
                            if(!b) return;
                            showLoading();
                            $.get(path + 'order/pay/' + token + '/' + meja, function(data){
                                endLoading();
                                connect().then(function(socket){
                                    socket.send(JSON.stringify({type: 'pelanggan'}));
								    socket.close();
                                    alert("Berhasil melakukan pembayaran untuk meja " + meja);
                                });

                            }).fail(function(){
                                endLoading();
                            });
                        });
                        
                    }else{
                        $("#table-order tbody").append(emptyData);
                    }
                    if(loading)
                        endLoading();
                 });
            }
            $("#pay").click(function(){
                $("#modal-order").off('shown.bs.modal');
                $("#modal-order").on('shown.bs.modal', {bayar: true}, onShown);
                $("#modal-order").modal('show');
            });
            $("#modal-order").on('hidden.bs.modal', function(){
                if(html5QrcodeScanner){
                    html5QrcodeScanner.html5Qrcode.stop();
                    html5QrcodeScanner = null;
                }
            });
			setInterval(function(){renderTable(false)}, 5000);
		});
	
	</script>
</body>

</html>
