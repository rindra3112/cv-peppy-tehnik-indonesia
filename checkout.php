<?php

session_start();

include "config/database.php";

// ==========================
// HELPER: HALAMAN TRANSISI MODERN
// ==========================

function tampilkanTransisi($judul, $pesan, $tujuan, $ikon = "bi-shield-lock"){

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CV Peppy Tehnik Indonesia</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
  margin:0;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(160deg,#083a5e,#0a6bb5 60%,#1e9ce0);
  font-family:'Plus Jakarta Sans', sans-serif;
}
</style>
</head>
<body>

<script>
Swal.fire({
  title: <?= json_encode($judul); ?>,
  html: `
    <div style="
      width:84px;height:84px;
      background:linear-gradient(135deg,#eaf6fd,#d6ecf9);
      border-radius:50%;
      display:flex;align-items:center;justify-content:center;
      margin:6px auto 18px;
      font-size:36px;color:#0a6bb5;
    ">
      <i class="bi <?= $ikon; ?>"></i>
    </div>
    <div style="font-size:14.5px;color:#6b7c88;line-height:1.6">
      <?= addslashes($pesan); ?>
    </div>
  `,
  showConfirmButton:false,
  allowOutsideClick:false,
  timer:2200,
  showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
  hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' },
  didOpen:()=>{ Swal.showLoading(); }
});

setTimeout(()=>{
  window.location.href = "<?= $tujuan; ?>";
}, 2200);
</script>

</body>
</html>
<?php
    exit;
}

// ==========================
// CEK LOGIN CUSTOMER
// ==========================

if (!isset($_SESSION['customer'])) {

    tampilkanTransisi(
        "Login Diperlukan",
        "Silahkan login terlebih dahulu untuk melanjutkan proses checkout AC kamu",
        "customer/login.php",
        "bi-shield-lock"
    );

}

$id_customer = is_array($_SESSION['customer'])
    ? $_SESSION['customer']['id']
    : $_SESSION['customer'];

// ==========================
// AMBIL CUSTOMER
// ==========================

$getCustomer = mysqli_query(
    $conn,
    "SELECT * FROM customer WHERE id='" . mysqli_real_escape_string($conn, $id_customer) . "'"
);

$customer = mysqli_fetch_assoc($getCustomer);

if (!$customer) {

    tampilkanTransisi(
        "Sesi Berakhir",
        "Sesi login kamu sudah habis, silahkan login ulang untuk melanjutkan",
        "customer/logout.php",
        "bi-clock-history"
    );

}

// ==========================
// TENTUKAN MODE: 1 PRODUK (?id=) ATAU SEMUA ISI KERANJANG
// ==========================

$items      = [];
$grandTotal = 0;
$modeKeranjang = false;

if (isset($_GET['id'])) {

    // ===== MODE: 1 PRODUK (dari tombol "Pesan Sekarang") =====

    $id_produk = mysqli_real_escape_string($conn, $_GET['id']);

    $getProduk = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'");
    $p = mysqli_fetch_assoc($getProduk);

    if (!$p) {

        tampilkanTransisi(
            "Produk Tidak Ditemukan",
            "Produk yang kamu cari sudah tidak tersedia, yuk lihat produk AC lainnya",
            "index.php",
            "bi-exclamation-circle"
        );

    }

    $harga = (int) preg_replace("/[^0-9]/", "", $p['harga']);

    $items[] = ['id' => $id_produk, 'data' => $p, 'jumlah' => 1, 'harga' => $harga, 'total' => $harga];
    $grandTotal = $harga;

} elseif (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {

    // ===== MODE: SEMUA ISI KERANJANG =====

    $modeKeranjang = true;

    foreach ($_SESSION['keranjang'] as $idProdukKeranjang => $jumlah) {

        $q = mysqli_query($conn, "SELECT * FROM produk WHERE id='" . mysqli_real_escape_string($conn, $idProdukKeranjang) . "'");
        $d = mysqli_fetch_assoc($q);

        if (!$d) {
            continue;
        }

        $harga = (int) preg_replace("/[^0-9]/", "", $d['harga']);
        $total = $harga * $jumlah;
        $grandTotal += $total;

        $items[] = ['id' => $idProdukKeranjang, 'data' => $d, 'jumlah' => $jumlah, 'harga' => $harga, 'total' => $total];
    }

    if (count($items) == 0) {
        header("location:index.php");
        exit;
    }

} else {

    // Tidak ada produk maupun isi keranjang
    header("location:index.php");
    exit;

}

// ==========================
// PROSES CHECKOUT
// ==========================

if (isset($_POST['bayar'])) {

    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $hp     = mysqli_real_escape_string($conn, $_POST['hp']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);

    // ===== UPLOAD BUKTI =====
    $folder = "upload/bukti/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $bukti      = $_FILES['bukti']['name'];
    $tmp        = $_FILES['bukti']['tmp_name'];
    $nama_bukti = time() . "_" . $bukti;

    if (!move_uploaded_file($tmp, $folder . $nama_bukti)) {
        die("Upload bukti gagal");
    }

    // ===== INSERT PESANAN (loop untuk setiap item) =====

    $sukses = true;

    foreach ($items as $it) {

        $simpan = mysqli_query(
            $conn,
            "INSERT INTO pesanan
            (id_produk, id_customer, nama_customer, alamat, hp, email, total, bukti_transfer, status)
            VALUES
            ('" . mysqli_real_escape_string($conn, $it['id']) . "',
            '" . mysqli_real_escape_string($conn, $id_customer) . "',
            '$nama', '$alamat', '$hp', '$email',
            '" . $it['total'] . "', '$nama_bukti', 'Menunggu Konfirmasi')"
        );

        if (!$simpan) {
            $sukses = false;
        }
    }

    if ($sukses) {

        if ($modeKeranjang) {
            unset($_SESSION['keranjang']);
        }

        echo "<script>location='berhasil.php';</script>";
        exit;

    } else {

        echo "<h3>Gagal menyimpan pesanan</h3>";
        echo mysqli_error($conn);
        exit;

    }

}

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - CV Peppy Tehnik Indonesia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>

:root{
  --biru-tua:#083a5e;
  --biru:#0a6bb5;
  --biru-terang:#1e9ce0;
  --es:#eaf6fd;
  --kabut:#f4f9fc;
  --teks:#1c2b36;
  --abu:#6b7c88;
}

*{ box-sizing:border-box; }

body{
  background:linear-gradient(180deg,var(--es) 0%, var(--kabut) 320px, #ffffff 320px);
  font-family:'Plus Jakarta Sans', sans-serif;
  color:var(--teks);
}

.topbar{
  background:linear-gradient(120deg,var(--biru-tua), var(--biru) 60%, var(--biru-terang));
  padding:36px 0 90px;
  position:relative;
  overflow:hidden;
}
.topbar::before{
  content:"";
  position:absolute;
  right:-60px; top:-60px;
  width:260px; height:260px;
  background:radial-gradient(circle, rgba(255,255,255,.16), transparent 70%);
  border-radius:50%;
}
.topbar h2{
  color:#fff;
  font-weight:800;
  font-size:28px;
  letter-spacing:-.5px;
  margin-bottom:4px;
}
.topbar p{
  color:rgba(255,255,255,.78);
  margin:0;
  font-size:14px;
}
.breadcrumb-pill{
  display:inline-flex;
  align-items:center;
  gap:6px;
  background:rgba(255,255,255,.14);
  border:1px solid rgba(255,255,255,.25);
  color:#fff;
  padding:6px 14px;
  border-radius:30px;
  font-size:13px;
  font-weight:600;
}

.wrap{
  margin-top:-60px;
  padding-bottom:60px;
}

.card-modern{
  background:#fff;
  border-radius:20px;
  box-shadow:0 20px 45px -20px rgba(8,58,94,.25);
  border:1px solid rgba(10,107,181,.06);
}

.form-label{
  font-size:13px;
  font-weight:700;
  color:var(--abu);
  margin-bottom:4px;
}
.form-control, textarea.form-control{
  border-radius:12px;
  border:1.5px solid #e3ebf2;
  font-size:14px;
  padding:11px 14px;
}
.form-control:focus{
  border-color:var(--biru);
  box-shadow:0 0 0 3px rgba(10,107,181,.12);
}

.bank-info{
  background:var(--es);
  border-radius:14px;
  padding:14px 18px;
  font-size:13px;
  color:var(--biru-tua);
  margin:16px 0;
}
.bank-info b{ font-size:14px; }

.btn-bayar{
  width:100%;
  background:linear-gradient(120deg,var(--biru-tua), var(--biru));
  border:none;
  color:#fff;
  font-weight:700;
  padding:14px;
  border-radius:14px;
  font-size:15px;
  box-shadow:0 12px 24px -8px rgba(10,107,181,.55);
  transition:.2s;
}
.btn-bayar:hover{
  color:#fff;
  transform:translateY(-2px);
  box-shadow:0 16px 28px -8px rgba(10,107,181,.65);
}

/* ===== RINGKASAN PRODUK ===== */
.produk-mini{
  display:flex;
  align-items:center;
  gap:14px;
  padding:14px 0;
}
.produk-mini + .produk-mini{
  border-top:1px solid #eef2f5;
}
.foto-mini{
  width:64px;
  height:64px;
  min-width:64px;
  background:var(--es);
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
}
.foto-mini img{
  width:100%;
  height:100%;
  object-fit:contain;
  padding:6px;
}
.brand-chip{
  display:inline-block;
  font-size:10.5px;
  font-weight:700;
  letter-spacing:.4px;
  text-transform:uppercase;
  color:var(--biru);
  background:var(--es);
  padding:2px 9px;
  border-radius:20px;
  margin-bottom:4px;
}
.nama-mini{
  font-weight:700;
  font-size:13.5px;
  color:var(--teks);
}
.qty-mini{
  font-size:11.5px;
  color:var(--abu);
}
.subtotal-mini{
  font-weight:800;
  color:var(--biru-tua);
  font-size:13.5px;
  white-space:nowrap;
}

.garis-putus{
  border-top:1.5px dashed #dde6ec;
  margin:16px 0;
}

.total-akhir{
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.total-akhir .label{
  font-size:14px;
  color:var(--abu);
  font-weight:600;
}
.total-akhir .angka{
  font-size:23px;
  font-weight:800;
  color:var(--biru-tua);
  letter-spacing:-.5px;
}

.badge-jaminan{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:12px;
  color:var(--abu);
  margin-top:14px;
}
.badge-jaminan i{ color:var(--biru); }

@media(max-width:768px){

  .topbar{ padding:22px 0 56px; }
  .topbar h2{ font-size:20px; }
  .topbar p{ font-size:12.5px; }
  .wrap{ margin-top:-40px; padding-bottom:36px; }
  .card-modern{ border-radius:16px; }
  .total-akhir .angka{ font-size:20px; }

}

</style>
</head>

<body>

<div class="topbar">
  <div class="container">
    <span class="breadcrumb-pill"><i class="bi bi-shop"></i> CV Peppy Tehnik Indonesia</span>
    <h2 class="mt-3"><i class="bi bi-credit-card-2-front-fill"></i> Checkout Pesanan</h2>
    <p>Lengkapi data pengiriman dan selesaikan pembayaran</p>
  </div>
</div>

<div class="container wrap">
  <div class="row g-4">

    <!-- FORM PENGIRIMAN -->
    <div class="col-lg-7">
      <div class="card-modern p-4 animate__animated animate__fadeInUp">

        <h5 class="fw-bold mb-3"><i class="bi bi-truck me-1"></i> Detail Pengiriman</h5>

        <form method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Nama Penerima</label>
            <input class="form-control" name="nama" value="<?= htmlspecialchars($customer['nama']); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat Lengkap" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input class="form-control" name="hp" placeholder="Nomor Whatsapp" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" value="<?= htmlspecialchars($customer['email']); ?>" required>
          </div>

          <div class="bank-info">
            <b>Transfer Bank BCA</b><br>
            47605517653<br>
            A/N Darminto
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Bukti Transfer</label>
            <input type="file" name="bukti" class="form-control" required>
          </div>

          <button name="bayar" class="btn-bayar">
            <i class="bi bi-lock-fill me-1"></i> Selesaikan Pembayaran
          </button>

        </form>

      </div>
    </div>

    <!-- RINGKASAN PESANAN -->
    <div class="col-lg-5">
      <div class="card-modern p-4 animate__animated animate__fadeInUp">

        <h5 class="fw-bold mb-2"><i class="bi bi-receipt me-1"></i> Ringkasan Pesanan</h5>

        <?php foreach ($items as $it) { $d = $it['data']; ?>

          <div class="produk-mini">

            <div class="foto-mini">
              <img src="upload/produk/<?= $d['foto']; ?>" alt="<?= $d['nama_produk']; ?>">
            </div>

            <div class="flex-grow-1">
              <span class="brand-chip"><?= $d['brand']; ?></span>
              <div class="nama-mini"><?= $d['nama_produk']; ?></div>
              <div class="qty-mini"><?= $d['tipe']; ?> &middot; x<?= $it['jumlah']; ?></div>
            </div>

            <div class="subtotal-mini">Rp <?= number_format($it['total'], 0, ',', '.'); ?></div>

          </div>

        <?php } ?>

        <div class="garis-putus"></div>

        <div class="total-akhir">
          <span class="label">Total Bayar</span>
          <span class="angka">Rp <?= number_format($grandTotal, 0, ',', '.'); ?></span>
        </div>

        <div class="badge-jaminan">
          <i class="bi bi-shield-check"></i> Garansi resmi & instalasi profesional
        </div>
        <div class="badge-jaminan">
          <i class="bi bi-truck"></i> Pengiriman cepat ke seluruh area
        </div>

      </div>
    </div>

  </div>
</div>

</body>
</html>