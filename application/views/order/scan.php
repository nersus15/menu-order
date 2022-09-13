<!DOCTYPE html>
<html lang="en">

<!-- head -->
<?php $this->load->view("components/auth/_head"); ?>
<!-- ./head -->

<body>
	<div id="app">
		<section class="section">
			<div class="container mt-5">
				<div class="row">
					<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
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
								<h4>Scan Untuk Memesan</h4>
							</div>

							<div class="card-body">
                                <div style="width: 100%" id="reader"></div>
							</div>
						</div>
						<?php $this->load->view("components/auth/_footer"); ?>
					</div>
				</div>
			</div>
		</section>
	</div>

	<?php $this->load->view("components/auth/_scripts"); ?>

    <script>
        $(document).ready(function(){
            $("#reader__dashboard_section_swaplink").text("");
            $("#reader__dashboard_section_swaplink").click(function(e){
                e.preventDefault();
            });
            $("#reader__camera_permission_button").text("Izinkan untuk Mengakses Kamera")
        })
        var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
                
        function onScanSuccess(decodedText, decodedResult) {
            html5QrcodeScanner.clear();
            $.post(path + 'order/add_token').then(function(data){
                window.location.href = decodedText + '/' + data['token'];
            });
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>

</html>
