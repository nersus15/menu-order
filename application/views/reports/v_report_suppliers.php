<!DOCTYPE html>
<html><head>
	<style>
		h1 {
			text-align: center;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: bold;
		}

		#laporan {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
			font-size: 13px;
		}

		#laporan td,
		#laporan th {
			border: 1px solid #ddd;
			padding: 5px;
		}

		#laporan tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#laporan th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #4CAF50;
			color: white;
		}
	</style>
</head><body>
	<h1>Rekap Data Supplier</h1>
	<hr>
	<table id="laporan">
		<tr>
			<th>Kode Supplier</th>
			<th>Nama Supplier</th>
			<th>Email Supplier</th>
			<th>No HP Supplier</th>
			<th>Alamat Supplier</th>
		</tr>
		<?php foreach ($suppliers as $supplier) : ?>
			<tr>
				<td><?= $supplier["supplier_code"] ?></td>
				<td><?= $supplier["supplier_name"] ?></td>
				<td><?= $supplier["supplier_email"] ?></td>
				<td><?= $supplier["supplier_phone"] ?></td>
				<td><?= $supplier["supplier_address"] ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</body></html>
