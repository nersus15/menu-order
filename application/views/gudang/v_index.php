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
						<a href="<?= base_url("gudang/create") ?>" class="btn btn-primary btn-lg">Tambah Gudang</a>
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
													<th>ID Gudang</th>
													<th>Nama Gudang</th>
													<th>Alamat Gudang</th>
													<th>Wilayah</th>
													<!-- <th>Admin</th> -->
													<th>Staff</th>

													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($items as $item) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= $item->id ?></td>
														<td><?= $item->nama ?></td>
														<td><?= $item->alamat ?></td>
														<td><?= ($item->level_wilayah == '1' ? 'Prov. ' :( $item->level_wilayah == '3' ?  'Kec. ' : '') ) . kapitalize($item->wilayah_gudang) ?></td>
                                                       
                                                        <td>
                                                            <ul>
                                                                <?php foreach($item->staff as $staff): ?>
                                                                    <li><?= $staff->user_name ?></li>
                                                                <?php endforeach ?>
                                                            </ul>
                                                        </td>
														<td>
															<!-- <a href="" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a> -->
															<a data-toggle="tooltip" data-placement="bottom" title="Lihat Gudang" href="<?= base_url("gudang/detail/" . $item->id) ?>" class="btn btn-icon btn-info"><i class="fas  fa-eye"></i></a>
															<a data-toggle="tooltip" data-placement="bottom" title="Update" href="<?= base_url("gudang/update/" . $item->id) ?>"class="btn btn-icon btn-warning"><i class="fas fa-pencil-alt"></i></a>
															<a data-toggle="tooltip" data-placement="bottom" title="Hapus" href="<?= base_url("gudang/delete/" . $item->id) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
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
