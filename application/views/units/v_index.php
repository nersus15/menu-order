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
						<a href="javascript:void(0)" class="btn btn-primary btn-lg showCreateModal" onclick="addUnit()">Tambah Satuan</a>
					</div>
					<!-- alert flashdata -->
					<div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"></div>
					<!-- end alert flashdata -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped" id="tableUnits" width="100%">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nama Satuan</th>
													<th>Deskripsi</th>
													<th>Tanggal Dibuat</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<!-- generated from ajax -->
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

	<!-- modal -->
	<div class="modal fade" id="modal_form" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="#" id="form">
					<input type="hidden" value="" name="id_unit">
					<div class="modal-header">
						<h5 class="modal-title" id="modalLabel"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="unit_name">Nama Kategori</label>
							<input type="text" class="form-control" id="unit_name" name="unit_name">
							<div class="invalid-feedback"></div>
						</div>
						<div class="form-group">
							<label for="unit_description">Deskripsi Kategori <sup>Opsional</sup></label>
							<textarea name="unit_description" id="unit_description" rows="4" class="form-control"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
						<button type="button" class="btn btn-primary" id="btnSave" onclick="save()">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- ./modal -->

	<!-- scripts -->
	<?php $this->load->view("components/main/_scripts", $this->session->flashdata('message')); ?>
	<!-- ./scripts -->

	<!-- ajax request -->
	<script>
		let saveMethod;
		let table;
		let baseUrl = '<?= base_url() ?>';

		$(document).ready(function() {
			//datatables
			table = $('#tableUnits').DataTable({

				"processing": true, //Feature control the processing indicator.
				"serverSide": true, //Feature control DataTables' server-side processing mode.
				"order": [], //Initial no order.

				// Load data for the table's content from an Ajax source
				"ajax": {
					"url": "<?= base_url('unit/ajaxList') ?>",
					"type": "POST"
				},

				//Set column definition initialisation properties.
				"columnDefs": [{
						"targets": [-1], //last column
						"orderable": false, //set not orderable
					},
					{
						"targets": [-2], //2 last column (photo)
						"orderable": false, //set not orderable
					},
				],

			});

			//set input/textarea/select event when change value, remove class error and remove text help block 
			$("input").change(function() {
				$(this).removeClass('is-invalid');
				$(this).next().empty();
			});

		});

		function addUnit() {
			saveMethod = 'add';
			$('#form')[0].reset(); // reset form on modals
			$('.form-control').removeClass('is-invalid'); // clear error class
			$('.invalid-feedback').empty(); // clear error string
			$('#modal_form').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Kategori'); // Set Title to Bootstrap modal title

		}

		function editUnit(id_unit) {
			saveMethod = 'update';
			$('#form')[0].reset(); // reset form on modals
			$('.form-control').removeClass('is-invalid'); // clear error class
			$('.invalid-feedback').empty(); // clear error string

			// load data from ajax
			$.ajax({
				url: "<?= base_url('unit/ajaxedit') ?>/" + id_unit,
				type: "GET",
				dataType: "JSON",
				success: function(data) {
					$('[name="id_unit"]').val(data.id_unit);
					$('[name="unit_name"]').val(data.unit_name);
					$('[name="unit_description"]').val(data.unit_description);
					$('#modal_form').modal('show'); // show bootstrap modal
					$('.modal-title').text('Edit Kategori'); // Set Title to Bootstrap modal title

				},
				error: function(jqXHR, textStatus, errorThrown) {
					swal({
						title: 'Error',
						text: 'Gagal mendapatkan data',
						icon: 'error'
					});
				}
			});
		}

		function reloadTable() {
			table.ajax.reload(null, false); //reload datatable ajax 
		}

		function save() {
			$('#btnSave').text('Memproses...'); //change button text
			$('#btnSave').attr('disabled', true); //set button disable 
			let url;

			if (saveMethod == 'add') {
				url = "<?= base_url('unit/ajaxadd') ?>";
			} else {
				url = "<?= base_url('unit/ajaxupdate') ?>";
			}

			// ajax adding data to database

			var formData = new FormData($('#form')[0]);
			$.ajax({
				url: url,
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function(data) {

					if (data.status) //if success close modal and reload ajax table
					{

						if (saveMethod == 'add') {
							$('#modal_form').modal('hide');
							swal({
								title: 'Berhasil',
								text: 'Satuan berhasil ditambahkan',
								icon: 'success'
							});
						} else {
							$('#modal_form').modal('hide');
							swal({
								title: 'Berhasil',
								text: 'Satuan berhasil diubah',
								icon: 'success'
							});
						}

						reloadTable();
					} else {
						for (let i = 0; i < data.input_error.length; i++) {
							$('[name="' + data.input_error[i] + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
							$('[name="' + data.input_error[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
						}
					}
					$('#btnSave').text('Simpan'); //change button text
					$('#btnSave').attr('disabled', false); //set button enable 


				},
				error: function(jqXHR, textStatus, errorThrown) {
					swal({
						title: 'Gagal',
						text: 'Kategori gagal ditambahkan',
						icon: 'error'
					});
					$('#btnSave').text('Simpan'); //change button text
					$('#btnSave').attr('disabled', false); //set button enable 

				}
			});
		}

		function deleteUnit(id_unit) {
			swal({
				title: "Anda yakin?",
				text: "Data Satuan akan dihapus!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((result) => {
				if (result) {
					$.ajax({
						url: "<?= base_url('unit/ajaxdelete') ?>/" + id_unit,
						type: "POST",
						dataType: "JSON",
						success: function(data) {
							//if success reload ajax table
							$('#modal_form').modal('hide');
							reloadTable();
						},
						error: function(jqXHR, textStatus, errorThrown) {
							swal(
								'Dihapus!',
								'Data kamu telah dihapus.',
								'error'
							)
						}
					});
					swal(
						'Dihapus!',
						'Data kamu telah dihapus.',
						'success'
					)
				}
			})
		}

		// modal reset
		$("#modal_form").on("hidden", function() {
			$(".modal-content").html("");
		});
	</script>
	<!-- ./ajax request -->
</body>

</html>
