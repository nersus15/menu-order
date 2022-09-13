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
						<a href="<?= base_url("user/create/") ?>" class="btn btn-primary btn-lg">Tambah Kasir</a>
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
													<th>Username</th>
													<th>Alamat</th>
													<th>No. Hp</th>
													<th>Tgl. Gabung</th>
													<th class="col-md-3 col-sm-12">Photo</th>

													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<?php $no = 1; ?>
												<?php foreach ($users as $user) : ?>
													<tr>
														<td><?= $no++; ?></td>
														<td><?= kapitalize($user['nama_lengkap']) ?></td>
														<td><?= $user['username'] ?></td>
                                                        <td><?= $user['alamat'] ?></td>
                                                        <td> <?= $user['hp'] ?>
														<td> <?= date('d, M Y', strtotime($user['ditambah'])) ?> </td>
                                                        </td>
                                                        <td>
                                                            <img class="col-sm-12 col-md-6" style="width: 100%;" src="<?= assets_url('img/avatar/' . $user['gambar']) ?>" alt="">
                                                        </td>
														<td>
															<!-- <a href="" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a> -->
															<!-- <a data-toggle="tooltip" data-placement="bottom" title="Update" href="<?= base_url("user/update/" . $user['id']) ?>"class="btn btn-icon btn-info"><i class="fas fa-pencil-alt"></i></a> -->
															<a data-toggle="tooltip" data-placement="bottom" title="Hapus" href="<?= base_url("user/delete/" . $user['id']) ?>" class="btn btn-icco btn-danger btn-delete"><i class="fas fa-trash"></i></a>
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
