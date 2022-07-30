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
						<a href="<?= base_url("user/create/" . sandi('staff')) ?>" class="btn btn-primary btn-lg">Tambah Staff</a>
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
										<table class="table table-striped" id="table-1">
											<thead>
												<tr>
                                                    <th class="text-center">
														No
													</th>
													<th>Nama</th>
													<th>Wilayah Kerja</th>
													<th>Alamat</th>
													<th>Gudang</th>
													<th>Wilayah Gudang</th>
													<th class="col-md-3 col-sm-12">Photo</th>

													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($items as $item) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $item['user_name'] ?></td>
														<td><?= ($item['level_wilayah'] == '1' ? 'Prov. ' :( $item['level_wilayah'] == '3' ?  'Kec. ' : '') ) . kapitalize($item['nama_wilayah']) ?></td>
                                                        <td><?= $item['user_address'] ?></td>
                                                        <td>
                                                            <ul>
                                                                <?php foreach($item['gudang'] as $gudang):?>
                                                                    <li><?= $gudang['nama'] ?></li>
                                                                <?php endforeach ?>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <ul>
                                                                <?php foreach($item['gudang'] as $gudang):?>
                                                                    <li><?= ($gudang['level_wil_gudang'] == '1' ? 'Prov. ' :( $gudang['level_wil_gudang'] == '3' ?  'Kec. ' : '') ) . kapitalize($gudang['wilayah_gudang']) ?></li>
                                                                <?php endforeach ?>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <img class="col-sm-12 col-md-6" style="width: 100%;" src="<?= assets_url('img/avatar/' . $item['user_avatar']) ?>" alt="">
                                                        </td>
														<td>
															<!-- <a href="" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a> -->
															<a data-toggle="tooltip" data-placement="bottom" title="Update" href="<?= base_url("staff/update/" . $item['id_user']) ?>"class="btn btn-icon btn-info"><i class="fas fa-pencil-alt"></i></a>
															<a data-toggle="tooltip" data-placement="bottom" title="Keluarkan dari gudang" href="<?= base_url("staff/unsign/" . $item['id_user']) ?>" class="btn btn-icco btn-warning btn-delete"><i class="fas fa-eraser"></i></a>
															<a data-toggle="tooltip" data-placement="bottom" title="Hapus" href="<?= base_url("staff/delete/" . $item['id_user']) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
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

</body>

</html>
