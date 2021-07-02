<!-- General JS Scripts -->
<script src="<?= base_url("assets/modules/jquery.min.js"); ?>"></script>
<script src="<?= base_url("assets/modules/popper.js") ?>"></script>
<script src="<?= base_url("assets/modules/tooltip.js"); ?>"></script>
<script src="<?= base_url("assets/modules/bootstrap/js/bootstrap.min.js"); ?>"></script>
<script src="<?= base_url("assets/modules/nicescroll/jquery.nicescroll.min.js"); ?>"></script>
<script src="<?= base_url("assets/modules/moment.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/stisla.js"); ?>"></script>

<!-- JS Libraies -->
<script src="<?= base_url("assets/modules/simple-weather/jquery.simpleWeather.min.js") ?>"></script>
<script src="<?= base_url("assets/modules/chart.min.js") ?>"></script>
<script src="<?= base_url("assets/modules/jqvmap/dist/jquery.vmap.min.js") ?>"></script>
<script src="<?= base_url("assets/modules/jqvmap/dist/maps/jquery.vmap.world.js") ?>"></script>
<script src="<?= base_url("assets/modules/summernote/summernote-bs4.js") ?>"></script>
<script src="<?= base_url("assets/modules/chocolat/dist/js/jquery.chocolat.min.js") ?>"></script>
<!-- datatables -->
<script src="<?= base_url("assets/modules/datatables/datatables.min.js") ?>"></script>
<script src="<?= base_url("assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js") ?>"></script>
<script src="<?= base_url("assets/modules/jquery-ui/jquery-ui.min.js") ?>"></script>
<script src="<?= base_url("assets/js/page/modules-datatables.js") ?>"></script>
<!-- ./datatables -->

<!-- sweet alert -->
<script src="<?= base_url("assets/modules/sweetalert/sweetalert.min.js") ?>"></script>
<script src="<?= base_url("assets/js/page/modules-sweetalert.js") ?>"></script>
<!-- ./sweet alert -->

<script src="<?= base_url("assets/modules/select2/dist/js/select2.full.min.js") ?>"></script>
<script src="<?= base_url("assets/js/page/forms-advanced-forms.js") ?>"></script>

<!-- Page Specific JS File -->
<script src="<?= base_url("assets/js/page/index-0.js") ?>"></script>

<!-- Template JS File -->
<script src="<?= base_url("assets/js/scripts.js") ?>"></script>
<script src="<?= base_url("assets/js/custom.js") ?>"></script>
<script>
	var flashData = null;
	var type = undefined;

	if($('.flash-data').length > 0)
		flashData = $('.flash-data').data('flashdata');
	else{
		flashData = "<?php echo isset($message) ? $message : $this->session->flashdata('message') ?>";
		type = "<?php echo isset($type) ? $type : null ?>";
	}
	<?php 
		unset($_SESSION['message'])
	?>
	if (flashData) {
		swal({
			title: 'Berhasil',
			text: 'Data Berhasil ' + flashData,
			icon: type || 'success'
		});
	}

	// tombol hapus
	$('.btn-delete').on('click', function(e) {

		e.preventDefault();
		const href = $(this).attr('href');

		swal({
			title: "Kamu yakin?",
			text: "Data akan dihapus!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((result) => {
			if (result) {
				document.location.href = href;
			}
		})

	})
</script>
