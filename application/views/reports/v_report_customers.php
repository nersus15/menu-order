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
<h1>Rekap Data Customer</h1>
<hr>
	<table id="laporan">
		<tr>
			<th>Kode Customer</th>
			<th>Nama Customer</th>
			<th>Email Customer</th>
			<th>No HP Customer</th>
			<th>Alamat Customer</th>
		</tr>
		<?php foreach ($customers as $customer) : ?>
			<tr>
				<td><?= $customer["customer_code"] ?></td>
				<td><?= $customer["customer_name"] ?></td>
				<td><?= $customer["customer_email"] ?></td>
				<td><?= $customer["customer_phone"] ?></td>
				<td><?= $customer["customer_address"] ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</body></html>
