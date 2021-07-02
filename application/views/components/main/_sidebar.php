<div class="main-sidebar sidebar-style-2">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="<?= base_url(); ?>">Inventory App</a>
		</div>
		<div class="sidebar-brand sidebar-brand-sm">
			<a href="<?= base_url(); ?>">IA</a>
		</div>
		<ul class="sidebar-menu">
			<li class="menu-header">Dashboard</li>
			<li class="<?= ($this->uri->segment(1) == 'dashboard') ? 'active' : '' ?>">
				<a class="nav-link" href="<?= base_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
			</li>
			<li class="menu-header">Master Data</li>
			<li class="dropdown">
				<a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-box-open"></i> <span>Kelola Barang</span></a>
				<ul class="dropdown-menu">
					<li class="<?= ($this->uri->segment(1) == 'item') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("item") ?>">Item</a></li>
					<li class="<?= ($this->uri->segment(1) == 'category') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("category") ?>">Kategori</a></li>
					<li class="<?= ($this->uri->segment(1) == 'unit') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("unit") ?>">Satuan</a></li>
				</ul>
			</li>
			<li class="<?= ($this->uri->segment(1) == 'supplier') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("supplier") ?>"><i class="fas fa-truck-moving"></i> <span>Kelola Supplier</span></a></li>
			<li class="<?= ($this->uri->segment(1) == 'customer') ? 'active' : '' ?>"><a class="nav-link" href="<?= base_url("customer") ?>"><i class="fas fa-users"></i> <span>Kelola Customer</span></a></li>
			<li class="dropdown">
				<a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Kelola Transaksi</span></a>
				<ul class="dropdown-menu">
					<li><a class="nav-link" href="<?= base_url("incomingitem") ?>">Barang Masuk</a></li>
					<li><a class="nav-link" href="<?= base_url("outcomingitem") ?>">Barang Keluar</a></li>
				</ul>
			</li>
			<li class="menu-header">Laporan</li>
			<li><a class="nav-link" href="<?= base_url("report/reporttransactions") ?>"><i class="fas fa-file"></i> <span>Rekap Transaksi</span></a></li>
			<li><a class="nav-link" href="<?= base_url("report/stok") ?>"><i class="fas fa-file"></i> <span>Rekap Stok Barang</span></a></li>
			<li><a class="nav-link" href="<?= base_url("report/reportsuppliers") ?>"><i class="fas fa-file"></i> <span>Rekap Supplier</span></a></li>
			<li><a class="nav-link" href="<?= base_url("report/reportcustomers") ?>"><i class="fas fa-file"></i> <span>Rekap Customer</span></a></li>
			<li class="menu-header">Pengguna</li>

			<?php if ($this->session->userdata("user_role") == "admin") : ?>
				<li><a class="nav-link" href="<?= base_url("user") ?>"><i class="fas fa-users"></i> <span>Kelola Users</span></a></li>
			<?php endif; ?>

			<li><a class="nav-link" href="<?= base_url("profile") ?>"><i class="fas fa-user-circle"></i> <span>Profil Saya</span></a></li>
		</ul>

	</aside>
</div>
