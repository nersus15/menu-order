<?php
$userdata = sessiondata();
?>
<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
	<form class="form-inline mr-auto">
		<ul class="navbar-nav mr-3">
			<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
			<li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
		</ul>
		<!-- <div class="search-element">
			<input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="250">
			<button class="btn" type="submit"><i class="fas fa-search"></i></button>
		</div> -->
	</form>
	<ul class="navbar-nav navbar-right">
		<li id="notificationButton" class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
				<i class="fas fa-bell"></i>
				<span class="count"></span>
			</a>
			<div id="notifications" style="height: 300px; overflow-y: scroll;" class="dropdown-menu dropdown-menu-right">

			</div>
		</li>
		<li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
				<img style="height: 30px;" alt="image" src="<?= base_url("assets/img/avatar/" . $userdata['gambar']) ?>" class="rounded-circle mr-1">
				<div class="d-sm-none d-lg-inline-block">Hi, <?= $userdata['nama_lengkap']; ?></div>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a href="<?= base_url("profile") ?>" class="dropdown-item has-icon">
					<i class="far fa-user"></i> Profile
				</a>
				<div class="dropdown-divider"></div>
				<a href="<?= base_url("auth/logout") ?>" class="dropdown-item has-icon text-danger">
					<i class="fas fa-sign-out-alt"></i> Logout
				</a>
			</div>
		</li>
	</ul>
	<audio id="notif-audio">
		<source src="<?= base_url('assets/audio/notif.wav') ?>" type="audio/wav">
	</audio>

</nav>