<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
	<title>Menu Order App | <?= $title; ?></title>
	
	<!-- General CSS Files -->
	<link rel="stylesheet" href="<?= base_url("assets/modules/bootstrap/css/bootstrap.min.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/modules/fontawesome/css/all.min.css") ?>">

	<!-- CSS Libraries -->
	<link rel="stylesheet" href="<?= base_url("assets/modules/jqvmap/dist/jqvmap.min.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/modules/weather-icon/css/weather-icons.min.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/modules/weather-icon/css/weather-icons-wind.min.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/modules/summernote/summernote-bs4.css") ?>">

	<!-- datatables -->
	<link rel="stylesheet" href="<?= base_url("assets/modules/datatables/datatables.min.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css") ?>">
	<!-- ./datatables -->

	<link rel="stylesheet" href="<?= base_url("assets/modules/select2/dist/css/select2.min.css") ?>">

	<!-- Template CSS -->
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/components.css") ?>">
	<script src="<?= base_url("assets/modules/jquery.min.js"); ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/modules/daterangepicker/moment.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/modules/daterangepicker/daterangepicker.js') ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/modules/daterangepicker/daterangepicker.css')?>" />

	<script src="<?= base_url("assets/js/kamscore/js/Kamscore.js"); ?>"></script>
	<script src="<?= base_url("assets/js/kamscore/js/uihelper.js"); ?>"></script>
	<script src="<?= base_url("assets/js/socket.io.js") ?>"></script>


	<?php
	if (isset($this->params))
		extract($this->params);
	if (isset($extra_js) && !empty($extra_js)) {
		foreach ($extra_js as $js) {
			if (!isset($js['attr']))
				$js['attr'] = null;

			if ($js['pos'] == 'head' && $js['type'] == 'file')
				echo '<script src="' . base_url('public/assets/' . $js['src']) . '"></script>';
			elseif ($js['pos'] == 'head' && $js['type'] == 'cache')
				echo '<script type="application/javascript" src="' . base_url('public/assets/' . $js['src']) . '"></script>';
			elseif ($js['pos'] == 'head' && $js['type'] == 'inline') {
				echo '<script>' . $js['script'] . '</script>';
			} elseif ($js['pos'] == 'head' && $js['type'] == 'cdn')
				echo '<script src="' . $js['src'] . '"' . $js['attr'] . '></script>';
		}
	}

	if (isset($extra_css) && !empty($extra_css)) {
		foreach ($extra_css as $css) {
			if (!isset($css['attr']))
				$css['attr'] = null;

			if ($css['pos'] == 'head' && $css['type'] == 'file')
				echo '<link rel="stylesheet" href="' . base_url('public/assets/' . $css['src']) . '"></link>';
			elseif ($css['pos'] == 'head' && $css['type'] == 'inline') {
				echo '<style>' . $css['style'] . '</style>';
			} elseif ($css['pos'] == 'head' && $css['type'] == 'cdn')
				echo '<link rel="stylesheet" href="' .  $css['src'] . '" ' . $css['attr'] . '></link>';
		}
	}
	?>
	<script>
		var path = "<?= base_url() ?>";
		var logininfo = <?= json_encode(sessiondata()) ?>;
	</script>
</head>