<!-- General JS Scripts -->
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

	});
    var notifBtn = $("#notificationButton");
	async function renderNotifikasi(){
		var notifItem = "";
        var notif = await $.get(path + 'ws/get_notif').then(res => toArray(res));
		console.log(notif);
		notif.forEach((n, i) => {
			var link = n.link && n.link != '#' ? path + n.link : '#';
			if(i == 15){
				notifItem += '<div style="width: 100%; background: whitesmoke; justify-content: center" class="d-flex flex-row mt-3">' +
							'<div style="cursor: pointer" id="read-all" class="pl-3 pr-2">' +
								'<span style="" class="">Baca Semua</span>'
							'</div>' +
						'</div>';
			}else if(i < 15){
				if(!n.dibaca){
					notifItem += '<div class="d-flex flex-row mb-3 pb-3 border-bottom">' +
							'<div class="pl-3 pr-2">' +
								'<b><a class="nitem" data-id="'+n.id+'" href="'+ link +'">' +
									'<p class="font-weight-medium mb-1">'+ n.pesan +'</p>' +
									'<p class="text-muted mb-0 text-small">'+ n.dibuat +'</p>' +
								'</a></b>' +
							'</div>' +
						'</div>';
						
				}else{
					notifItem += '<div class="d-flex flex-row mb-3 pb-3 border-bottom">' +
							'<div class="pl-3 pr-2">' +
								'<a style="color: black" class="nitem" data-id="'+ n.id +'" href="'+ link +'">' +
									'<p class="font-weight-medium mb-1">'+ n.pesan +'</p>' +
									'<p class="text-muted mb-0 text-small">'+ n.dibuat +'</p>' +
								'</a>' +
							'</div>' +
						'</div>';
				}
			}
			
		});
		
		var unreadNotif = notif.filter(n =>!n.dibaca);
		$(notifBtn).find('span.count').text(unreadNotif.length);
		$("#notifications").empty();
		$("#notifications").append(notifItem);
		$("#notifications").find('#read-all').click(function(e){
			if(unreadNotif.length == 0) return;
			var ids = unreadNotif.map(n => n.id);
			$.post(path + 'ws/baca_notif/all', {ids: ids}).then(res => {
					notif.forEach((n, i) => {
						if(ids.includes(n.id));
							notif[i].dibaca = res.dibaca;
					});
				});
		});
		$("#notifications").find('a.nitem').click(function(e){
			e.preventDefault();
			var link = $(this).attr('href');
			var nid = $(this).data('id');
			var notifItem = notif.filter(n => n.id == nid);
			if(notifItem.length > 0)
				notifItem = notifItem[0];
				
			if(!notifItem.dibaca){
				$.post(path + 'ws/baca_notif/' + nid).then(res => {
					notif.forEach((n, i) => {
						if(n.id == nid)
							notif[i].dibaca = res.dibaca;
					});
					renderNotifikasi();
				});
			}
			if(link != '#')
				notifItem.pesan += '<div class="modal-footer"><a class="btn btn-md btn-info" href="' + link + '"> Go </a></div>';
			notifikasi(notifItem.pesan, {
				saatTutup: function(){
				renderNotifikasi();
			}});
			

		});
	};

	$(document).ready(function(){
		if(notifBtn.length > 0){
            renderNotifikasi();
            setInterval(renderNotifikasi, 10000);
        }
	});
</script>
<?php
	if(isset($this->params))
		extract($this->params);
    if(isset($extra_js) && !empty($extra_js)){
        foreach($extra_js as $js){
            if(!isset($js['attr']))
                $js['attr'] = null;

            if($js['pos'] == 'body:end' && $js['type'] == 'file')
                echo '<script src="' . base_url('public/assets/' . $js['src']) . '"></script>';
            elseif($js['pos'] == 'body:end' && $js['type'] == 'cache')
                echo '<script type="application/javascript" src="' . base_url('public/assets/' . $js['src']) . '"></script>';
            elseif($js['pos'] == 'body:end' && $js['type'] == 'inline'){
                echo '<script async type="application/javascript">' . $js['script'] . '</script>';
            }
            elseif($js['pos'] == 'body:end' && $js['type'] == 'cdn')
                echo '<script src="' . $js['src'] . '"'. $js['attr'] .'></script>';
        }
    }

    if(isset($extra_css) && !empty($extra_css)){
        foreach($extra_css as $css){
            if(!isset($css['attr']))
                $css['attr'] = null;
                
            if($css['pos'] == 'body:end' && $css['type'] == 'file')
                echo '<link rel="stylesheet" href="' . base_url('public/assets/' . $css['src']) . '"></link>';
            elseif($css['pos'] == 'body:end' && $css['type'] == 'inline'){
                echo '<style>' . $css['style'] . '</style>';
            }
            elseif($css['pos'] == 'body:end' && $css['type'] == 'cdn')
                echo '<link rel="stylesheet" href="' . $css['src'] . '"'. $css['attr'] .'></link>';
        }
    }
?>