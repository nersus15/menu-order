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
								<h4>Silahkan Login</h4>
							</div>

							<div class="card-body">
								<form method="POST" action="<?= base_url("auth"); ?>">
									<div class="form-group">
										<label for="username" class="control-label">Username</label>
										<input id="username" type="text" class="form-control <?= form_error('username') ? 'is-invalid' : ''; ?>" value="<?= set_value('username') ?>" name="username" autofocus>
										<?= form_error('username', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
									</div>

									<div class="form-group">
										<div class="d-block">
											<label for="user_password" class="control-label">Password</label>
										</div>
										<input id="user_password" type="password" class="form-control <?= form_error('user_password') ? 'is-invalid' : ''; ?>" name="user_password">
										<?= form_error('user_password', '<div class="invalid-feedback font-weight-bold pl-1">', '</div>') ?>
									</div>

									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
											Login
										</button>
									</div>
								</form>

							</div>
						</div>
						<?php $this->load->view("components/auth/_footer"); ?>
					</div>
				</div>
			</div>
		</section>
	</div>

	<?php $this->load->view("components/auth/_scripts"); ?>
</body>

</html>
