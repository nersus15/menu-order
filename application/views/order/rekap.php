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
                                <div class="card-header">
                                    <div class="col-sm-12 col-md-6 form-group">
                                        <label for="">Waktu</label>
                                        <div class="row ml-4">
                                            <div class="form-group">
                                                <input checked type="radio" name="jenis" id="jenis-semua" value="semua">
                                                <label for="jenis-semua">Semua</label>
                                            </div>
                                            <div class="form-group ml-4">
                                                <input type="radio" name="jenis" id="jenis-filter" value="filter">
                                                <label for="jenis-filter">Filter</label>
                                            </div>
                                        </div>
                                        <div style="display: none;" class="form-group ml-4 mt-0" id="filter">
                                            <input type="hidden" name="filter_val" id="filter-val">
                                            <label for="">Pilih Waktu</label>
                                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                <i class="fa fa-calendar"></i>&nbsp;
                                                <span></span> <i class="fa fa-caret-down"></i>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button style="text-align: center;" type="button" class="btn btn-primary" id="export">
                                                Export Data
                                            </button>
                                        </div>
                                    </div>
                                   
                                </div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table" id="table-order">
											<thead>
												<tr>
													<th class="text-center">
														No
													</th>
													<th>Tanggal</th>
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
            var dttableInstance = null;	
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
                            no = no+1;
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

            async function renderTable(loading= true, tanggal = 'semua'){
                if($.fn.DataTable.isDataTable('#table-order'))
                    $("#table-order").DataTable().destroy();

                var emptyData = '<tr id="none">' +
                    '<td colspan="4" style="text-align: center;">' +
                        '<p>Tidak Ada Data</p>' +
                    '</td>' +
                '</tr>';
                $("#table-order tbody").empty();

                if(loading)
                    showLoading();
                $.get(path + 'ws/order_list/' + tanggal, function(data){
                    if(Object.keys(data.data).length > 0){
                        var rows = '';
                        var no = 1;
                        data = data.data;
                        Object.keys(data).forEach(e => {
                            var rowdata = data[e];
                            rows += '<tr>' +
                                '<td>' + no + '</td>'+
                                '<td>' + rowdata.tanggal + '</td>'+
                                '<td>' + rowdata.atasnama + '</td>' +
                                '<td>' + rowdata.meja + '</td>' +
                                '<td>' +
                                 '<button type="button" data-token="' + e +'" class="detail btn btn-info btn-xs ml-2">Detail</button>' +
                                '</td>' + 
                                '</tr>';     
                            no++;                       
                        });
                        $("#table-order tbody").append(rows);
                        $(".bayar, .detail").off('click');
                       
                        $(".detail").click(function(){
                            $("#modal-order").off('shown.bs.modal');
				            $("#modal-order").on('shown.bs.modal', {bayar: $(this).hasClass('bayar'), token: $(this).data('token')}, onShown);
                            $("#modal-order").modal('show');
                        });                        
                    }else{
                        $("#table-order tbody").append(emptyData);
                    }
                    if(loading)
                        endLoading();
                 }).then(function(){
                    if($("#none").length > 0)
                        return;
                        
                    setTimeout(function(){
                        dttableInstance = $("#table-order").DataTable();
                    }, 1000);
                 });
            }

            $(function() {
                var start = moment().subtract(29, 'days');
                var end = moment();

                function cb(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $("#filter-val").val(start.format('YYYY-MM-DD') + '_' + end.format('YYYY-MM-DD'));

                    renderTable(true, start.format('YYYY-MM-DD') + '_' + end.format('YYYY-MM-DD'));                    
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
            $("input[name='jenis']").change(function() {
                var val = $(this).val();
                if (val == 'semua') {
                    $("#filter").hide();
                    renderTable();
                } else if (val == 'filter') {
                    $("#filter").show();
                    renderTable(true, $("#filter-val").val());
                }
            });
            $("#export").click(function() {
                var url = path + 'report/rtransaksi/';
                var waktu = $("#filter-val").val();
                
                if($("#jenis-filter").is(':checked'))
                    url += waktu;
                window.open(url, '_blank');
            });



              // Websocket
            var socket = null;
            function connectSocket(){
                var socket = new WebSocket("wss://ws-kamscode.herokuapp.com");
                
                socket.onopen = function(){
                    console.log("Connected to WebSocket ws-kamscode.herokuapp.com");
                };
                socket.onmessage = function(e){
                    console.log("New Message");
                    var data = JSON.parse(e.data);
                    if(data.type == 'pelanggan'){
                        renderTable();
                    }
                };
                socket.onerror = function(){
                    console.log("Error, can't connect to ws-kamscode.herokuapp.com");
                };
                socket.onclose = function(e){
                    console.log("Close connection");
                    setTimeout(function(){
                        socket = connectSocket();
                    }, 1000);
                    console.log("Reconnecting");
                }
                return socket;
            }
            socket = connectSocket();
		});
	</script>
</body>

</html>
