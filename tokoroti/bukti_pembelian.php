<?php
include 'header.php';
include 'koneksi/koneksi.php';

// Memastikan parameter invoice ada di URL
if (!isset($_GET['invoice']) || empty($_GET['invoice'])) {
    die("Invoice tidak ditemukan.");
}

$invoice = mysqli_real_escape_string($conn, $_GET['invoice']);

// Mengambil data order berdasarkan invoice
$order_query = mysqli_query($conn, "SELECT * FROM produksi WHERE invoice='$invoice'") or die(mysqli_error($conn));

// Memeriksa apakah order ditemukan
if (mysqli_num_rows($order_query) == 0) {
    die("Order dengan invoice '$invoice' tidak ditemukan.");
}

$order = mysqli_fetch_assoc($order_query);

// Mengambil data customer berdasarkan kode_customer dari order
$customer_query = mysqli_query($conn, "SELECT * FROM customer WHERE kode_customer='{$order['kode_customer']}'") or die(mysqli_error($conn));

if (mysqli_num_rows($customer_query) == 0) {
    die("Customer dengan kode '{$order['kode_customer']}' tidak ditemukan.");
}

$customer = mysqli_fetch_assoc($customer_query);

// Mengambil detail pesanan berdasarkan invoice
$order_details_query = mysqli_query($conn, "SELECT * FROM produksi WHERE invoice='$invoice'") or die(mysqli_error($conn));
?>

<div class="container" style="padding-bottom: 200px">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Bukti Pembelian</b></h2>
    <h4>Informasi Pembeli</h4>
    <p>Nama: <?= $customer['nama']; ?></p>
    <p>Provinsi: <?= $order['provinsi']; ?></p>
    <p>Kota: <?= $order['kota']; ?></p>
    <p>Alamat: <?= $order['alamat']; ?></p>
    <p>Kode Pos: <?= $order['kode_pos']; ?></p>

    <h4>Detail Pesanan</h4>
    <table class="table table-stripped">
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Sub Total</th>
        </tr>
        <?php 
        $no = 1;
        $total = 0;
        while ($detail = mysqli_fetch_assoc($order_details_query)) {
        ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= $detail['nama_produk']; ?></td>
            <td>Rp.<?= number_format($detail['harga']); ?></td>
            <td><?= $detail['qty']; ?></td>
            <td>Rp.<?= number_format($detail['harga'] * $detail['qty']); ?></td>
        </tr>
        <?php 
            $total += $detail['harga'] * $detail['qty'];
            $no++;
        }
        ?>
        <tr>
            <td colspan="5" style="text-align: right; font-weight: bold;">Grand Total = Rp.<?= number_format($total); ?></td>
        </tr>
    </table>
</div>

<?php 
include 'footer.php';
?>
