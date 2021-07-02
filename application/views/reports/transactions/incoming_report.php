<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		h1 {
			text-align: center;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: bold;
		}

		table {
			width: 100%;
		}

		#transaksi {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			font-size: 13px;
		}

		#transaksi td,
		#transaksi th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		#transaksi tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#transaksi th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #4CAF50;
			color: white;
		}
	</style>
</head>

<body>
	<h1><?= $keterangan; ?></h1>
	<br>
	<hr>
	<br>
	<table id="transaksi">
		<thead>
			<tr>
				<th>No. </th>
				<th>Tanggal</th>
				<th>Kode Transasksi</th>
				<th>Nama Barang</th>
				<th>Jumlah Masuk</th>
				<th>Supplier</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1; ?>
			<?php foreach ($transaksi_masuk as $trx) : ?>
				<tr>
					<td><?= $i++; ?></td>
					<td><?= date('d F Y', strtotime($trx["incoming_item_date"])) ?></td>
					<td><?= $trx["incoming_item_code"] ?></td>
					<td><?= $trx["item_name"] ?></td>
					<td><?= $trx["incoming_item_qty"] ?></td>
					<td><?= $trx["supplier_name"] ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</body>

</html>
