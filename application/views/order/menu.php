<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/auth/_head"); ?>
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
<div style="position: fixed; z-index: 10; right: 0; border: 1px black solid; background: white; padding: 5px 10px">
	<p>Total Pesanan: <span id="total"><?= rupiah_format(0) ?></span></p>
</div>
	<div id="app">
		<section class="section">
			<div class="container mt-5 mb-5">
				
				<div class="row">
					<div class="col-12 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
						<div class="login-brand">
							<img src="<?= base_url("assets/img/logo.png") ?>" alt="logo" width="100" class="shadow-light rounded-circle">
						</div>

						<?php
							$flash = $this->session->flashdata('message');
							if(!empty($flash)){
								echo '<div class="alert alert-'. $flash['type'] .'">' . $flash['message'] . '</div>';
								unset($_SESSION['message']);
							}
						?>
						<div class="card card-primary">
							<div class="card-header">
								<h4>Pilih Menu</h4>
							</div>

							<div class="card-body">
							</div>
						</div>
					</div>
				</div>
				<div class="">
					<div class="form-group">
						<label>Atas Nama</label>
						<input class="form-control" name="atasnama" id="atasnama" />
						<p id="err" class="text-danger" style="display: none;">Harus diisi </p>
					</div>
				</div>
				<?php foreach($menus as $k => $m) :?>
					<h5><?= kapitalize($k) ?></h5>
					<div class="owl-carousel">
						<?php foreach($m as $v) :?>
							<div class="col-sm-4">
								<div class="card" style="width: 100%;">
									<img src="<?= base_url('assets/img/products/' . $v['gambar']) ?>" class="card-img-top" alt="...">
									<div class="card-body">
										<h4 class="card-title"><?= $v['nama'] ?></h4>
										<p class="card-text" style="font-size: 20px;"><?= rupiah_format($v['harga']) ?></p>
										<p class="card-text"><?= $v['keterangan'] ?></p>
									</div>
									<div class="card-footer">
										<label>Pesan</label>
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text min" data-id="<?= $v['id'] ?>"><i class="fas fa-minus"></i></span>
											</div>
											<input type="number" min="0" value="0" data-id="<?= $v['id'] ?>" class="form-control jumlah" aria-label="Jumlah">
											<div class="input-group-append">
												<span class="input-group-text add" data-id="<?= $v['id'] ?>"><i class="fas fa-plus"></i></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						<?php endforeach ?>
					</div>
				<?php endforeach ?>
				<div class="row" style="justify-content: center;">
					<p class="btn btn-primary" id="order" style="text-align: center;">Pesan</p>
				</div>
			</div>
			
		</section>
	</div>
	<!-- <?php $this->load->view("components/auth/_footer"); ?> -->

	<?php $this->load->view("components/auth/_scripts"); ?>

    <script>
		
        $(document).ready(function(){
			var history = <?= json_encode($pesanan) ?>;
			var pesanan = {};
			var menus = <?= json_encode($tmp) ?>;
			var meja = "<?= $meja ?>";
			var token = "<?= $token ?>";
			$(".add").click(function(){
				var input = $(this).parent().prev();
				var val = $(input).val();
				$(input).val(parseInt(val) + 1);

				$(input).trigger('change');
			});
			$(".min").click(function(){
				var input = $(this).parent().next();
				var val = $(input).val();
				if(val == 0) return;
				$(input).val(parseInt(val) - 1);
				$(input).trigger('change');

			});
			$(".jumlah").change(function(){
				var total = 0;
				var val = $(this).val();
				var id = $(this).data('id');
				if(val > 0){
					pesanan[id] = val;
				}else{
					delete pesanan[id];
				}
				Object.keys(pesanan).forEach(key => {
					var harga = menus.filter(e => e.id == key)[0]['harga'];
					total += parseInt(harga) * parseInt(pesanan[key]);
				});
				$("#total").text("Rp. "+ total.toString().rupiahFormat())
			});

			$(".owl-carousel").owlCarousel({
				items: 1,
				autpWidth: true,
				loop: true
			});
			$("#order").click(function(){
				var atasnama = $("#atasnama");
				if(!atasnama.val()) {
					$("#err").show();
					atasnama.focus();
					return;
				}else{
					$("#err").hide();
				}
				if(Object.keys(pesanan).length == 0){
					alert("Pilih menu");
					return;
				}

				showLoading();
				$.ajax({
					type: "POST",
					url: path + 'order/create',
					data:{
						atasnama: atasnama.val(),
						pesanan: pesanan,
						meja: meja,
						token: token
					},
					success: function(res){
						endLoading();
						if(res.type == 'error'){
							alert(res.message);
						}else{
							alert(res.message);
							setTimeout(function(){
								location.href = path + 'order/summary/' + token
							}, 1500);
						}
					},
				}).fail(function(){
					endLoading();
				});
				
			});
			if(history.length > 0){
				$("#atasnama").val(history[0].atasnama);
				$("#atasnama").prop('readonly', true);
			}
		});
		
    </script>
</body>

</html>
