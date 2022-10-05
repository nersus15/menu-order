<style>

</style>
<?php if (is_login('Manager')) : ?>
	<div class="main-sidebar sidebar-style-2">
		<aside id="sidebar-wrapper">
			<div class="sidebar-brand">
				<a href="<?= base_url(); ?>">Menu Order App</a>
			</div>
			<div class="sidebar-brand sidebar-brand-sm">
				<a href="<?= base_url(); ?>">MO</a>
			</div>
			<ul class="sidebar-menu">
				<li class="menu-header">Dashboard</li>
				<li class="<?= ($this->uri->segment(1) == 'dashboard') ? 'active' : '' ?>">
					<a class="nav-link" href="<?= base_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
				</li>
				<li class="menu-header">Master Data</li>
				<li  class="<?= ($this->uri->segment(1) == 'meja') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("meja") ?>"><i class="fas fa-box-open"></i> <span>Daftar Meja</span></a></li>
				<li  class="<?= ($this->uri->segment(1) == 'menu') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("menu") ?>"><i class="fas fa-box-open"></i> <span>Daftar Menu</span></a></li>
				<li class="<?= ($this->uri->segment(1) == 'user') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("user") ?>"><i class="fas fa-users"></i> <span>Pegawai (Kasir)</span></a></li>

				<li class="menu-header">Laporan</li>
				<li class="<?= ($this->uri->segment(2) == 'list') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("order/list") ?>"><i class="fas fa-file"></i> <span>Rekap Penjualan</span></a></li>
				<li class="menu-header">Pengguna</li>
				<li><a class="nav-link" href="<?= base_url("profile") ?>"><i class="fas fa-user-circle"></i> <span>Profil Saya</span></a></li>
			</ul>

		</aside>
	</div>

<?php elseif (is_login('Kasir')) : ?>
	<div class="main-sidebar sidebar-style-2">
		<aside id="sidebar-wrapper">
			<div class="sidebar-brand">
				<a href="<?= base_url(); ?>">Menu Order App</a>
			</div>
			<div class="sidebar-brand sidebar-brand-sm">
				<a href="<?= base_url(); ?>">MO</a>
			</div>
			<ul class="sidebar-menu">
				<li class="menu-header">Master Data</li>
				<li  class="<?= ($this->uri->segment(1) == 'meja') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("meja") ?>"><i class="fas fa-box-open"></i> <span>Daftar Meja</span></a></li>
				<li  class="<?= ($this->uri->segment(1) == 'menu') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("menu") ?>"><i class="fas fa-box-open"></i> <span>Daftar Menu</span></a></li>
				<li class="dropdown <?= in_array($this->uri->segment(2), ['realtime', 'today'])? 'active' : '' ?>">
					<a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Kelola Pesanan</span></a>
					<ul class="dropdown-menu">
						<li class="<?= ($this->uri->segment(2) == 'realtime') ? 'active' : '' ?>"><a class="nav-link " href="<?= base_url("order/realtime") ?>">Pesanan Aktif</a></li>
						<li class="<?= ($this->uri->segment(2) == 'today') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("order/today") ?>">Pesanan Hari ini</a></li>
					</ul>
				</li>
				<li class="menu-header">Pengguna</li>
				<li><a class="nav-link" href="<?= base_url("profile") ?>"><i class="fas fa-user-circle"></i> <span>Profil Saya</span></a></li>
			</ul>

		</aside>
	</div>

<?php endif ?>