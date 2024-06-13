<?php 
include 'header.php';
$sortage = mysqli_query($conn, "SELECT * FROM produksi where cek = '1'");
$cek_sor = mysqli_num_rows($sortage);
?>

<div class="container">
	<h2 style="width: 100%; border-bottom: 4px solid gray"><b>Daftar Pesanan</b></h2>
	<br>
	<h5 class="bg-success" style="padding: 7px; width: 710px; font-weight: bold;"><marquee>Lakukan Reload Setiap Masuk Halaman ini, untuk menghindari terjadinya kesalahan data dan informasi</marquee></h5>
	<a href="produksi.php" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Reload</a>
	<br>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">No</th>
				<th scope="col">Invoice</th>
				<th scope="col">Kode Customer</th>
				<th scope="col">Status</th>
				<th scope="col">Tanggal</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$result = mysqli_query($conn, "SELECT DISTINCT invoice, kode_customer, status, kode_produk, qty, terima, tolak, cek, tanggal FROM produksi GROUP BY invoice");
			$no = 1;
			$nama_material = []; // Inisialisasi array untuk menyimpan nama material yang kurang

			while ($row = mysqli_fetch_assoc($result)) {
				$kodep = $row['kode_produk'];
				$inv = $row['invoice'];
			?>
				<tr>
					<td><?= $no; ?></td>
					<td><?= $row['invoice']; ?></td>
					<td><?= $row['kode_customer']; ?></td>

					<?php 
					if ($row['terima'] == 1) {
						echo '<td style="color: green;font-weight: bold;">Pesanan Diterima (Siap Kirim)</td>';
					} elseif ($row['tolak'] == 1) {
						echo '<td style="color: red;font-weight: bold;">Pesanan Ditolak</td>';
					} else {
						echo '<td style="color: orange;font-weight: bold;">' . $row['status'] . '</td>';
					}
					?>

					<td><?= $row['tanggal']; ?></td>
					<td>
						<?php if ($row['tolak'] == 0 && $row['cek'] == 1 && $row['terima'] == 0) { ?>
							<a href="inventory.php?cek=0" id="rq" class="btn btn-warning"><i class="glyphicon glyphicon-warning-sign"></i> Request Material Shortage</a> 
							<a href="proses/tolak.php?inv=<?= $row['invoice']; ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak ?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a> 
						<?php } elseif ($row['terima'] == 0 && $row['cek'] == 0) { ?>
							<a href="proses/terima.php?inv=<?= $row['invoice']; ?>&kdp=<?= $row['kode_produk']; ?>" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Terima</a> 
							<a href="proses/tolak.php?inv=<?= $row['invoice']; ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak ?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a> 
						<?php } ?>

						<a href="detailorder.php?inv=<?= $row['invoice']; ?>&cs=<?= $row['kode_customer']; ?>" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Detail Pesanan</a>
					</td>
				</tr>
			<?php
				$no++;
			}
			?>

		</tbody>
	</table>

	<?php if ($cek_sor > 0) { ?>
		<br>
		<br>
		<div class="row">
			<div class="col-md-4 bg-danger" style="padding:10px;">
				<h4>Kekurangan Material </h4>
				<h5 style="color: red;font-weight: bold;">Silahkan Tambah Stok Material dibawah ini : </h5>
				<table class="table table-striped">
					<tr>
						<th>No</th>
						<th>Material</th>
					</tr>
					<?php 
					$arr = array_values(array_unique($nama_material));
					for ($i = 0; $i < count($arr); $i++) { 
					?>
						<tr>
							<td><?= $i + 1 ?></td>
							<td><?= $arr[$i]; ?></td>
						</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	<?php } ?>

</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php 
include 'footer.php';
?>
