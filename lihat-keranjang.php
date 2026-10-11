<?php

session_start();

include "config/database.php";

?>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang Belanja - CV Peppy Tehnik Indonesia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

:root{
  --biru-tua:#083a5e;
  --biru:#0a6bb5;
  --biru-terang:#1e9ce0;
  --es:#eaf6fd;
  --kabut:#f4f9fc;
  --oranye:#ff8a3d;
  --teks:#1c2b36;
  --abu:#6b7c88;
}

*{box-sizing:border-box;}

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

.topbar::after{
  content:"";
  position:absolute;
  left:10%; bottom:-90px;
  width:200px; height:200px;
  background:radial-gradient(circle, rgba(255,255,255,.10), transparent 70%);
  border-radius:50%;
}

.topbar h2{
  color:#fff;
  font-weight:800;
  font-size:30px;
  letter-spacing:-.5px;
  margin-bottom:4px;
}

.topbar p{
  color:rgba(255,255,255,.78);
  margin:0;
  font-size:14.5px;
}

.topbar .breadcrumb-pill{
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

/* ==== ITEM PRODUK ==== */

.item-produk{
  display:flex;
  align-items:center;
  gap:18px;
  padding:20px;
  border-radius:16px;
  transition:.25s;
  border:1px solid transparent;
}

.item-produk:hover{
  background:var(--kabut);
  border-color:rgba(10,107,181,.10);
}

.item-produk + .item-produk{
  border-top:1px solid #eef2f5;
}

.foto-wrap{
  width:92px;
  height:92px;
  min-width:92px;
  background:var(--es);
  border-radius:14px;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
}

.foto-wrap img{
  width:100%;
  height:100%;
  object-fit:contain;
  padding:8px;
}

.brand-chip{
  display:inline-block;
  font-size:11px;
  font-weight:700;
  letter-spacing:.5px;
  text-transform:uppercase;
  color:var(--biru);
  background:var(--es);
  padding:3px 10px;
  border-radius:20px;
  margin-bottom:6px;
}

.nama-produk{
  font-weight:700;
  font-size:15.5px;
  margin-bottom:2px;
  color:var(--teks);
}

.tipe-produk{
  font-size:13px;
  color:var(--abu);
}

.qty-badge{
  display:flex;
  align-items:center;
  justify-content:center;
  min-width:38px;
  height:38px;
  background:var(--es);
  color:var(--biru-tua);
  font-weight:800;
  border-radius:10px;
  font-size:15px;
}

.harga-satuan{
  font-size:12.5px;
  color:var(--abu);
}

.subtotal-produk{
  font-weight:800;
  color:var(--biru-tua);
  font-size:16px;
}

.btn-hapus{
  width:38px; height:38px;
  border-radius:10px;
  border:1px solid #fde3e3;
  background:#fff5f5;
  color:#e6483a;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:.2s;
}

.btn-hapus:hover{
  background:#e6483a;
  color:#fff;
  border-color:#e6483a;
}

/* ==== RINGKASAN ==== */

.ringkasan{
  position:sticky;
  top:24px;
  padding:26px;
}

.ringkasan h5{
  font-weight:800;
  margin-bottom:18px;
  font-size:17px;
}

.baris-ringkasan{
  display:flex;
  justify-content:space-between;
  font-size:14.5px;
  color:var(--abu);
  margin-bottom:12px;
}

.baris-ringkasan span:last-child{
  color:var(--teks);
  font-weight:600;
}

.garis-putus{
  border-top:1.5px dashed #dde6ec;
  margin:16px 0;
}

.total-akhir{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:22px;
}

.total-akhir .label{
  font-size:14px;
  color:var(--abu);
  font-weight:600;
}

.total-akhir .angka{
  font-size:24px;
  font-weight:800;
  color:var(--biru-tua);
  letter-spacing:-.5px;
}

.btn-checkout{
  width:100%;
  background:linear-gradient(120deg,var(--biru-tua), var(--biru));
  border:none;
  color:#fff;
  font-weight:700;
  padding:14px;
  border-radius:14px;
  font-size:15px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  transition:.2s;
  box-shadow:0 12px 24px -8px rgba(10,107,181,.55);
}

.btn-checkout:hover{
  transform:translateY(-2px);
  color:#fff;
  box-shadow:0 16px 28px -8px rgba(10,107,181,.65);
}

.link-lanjut{
  display:inline-flex;
  align-items:center;
  gap:6px;
  color:var(--biru);
  font-weight:600;
  font-size:14px;
  text-decoration:none;
  margin-top:16px;
}

.link-lanjut:hover{ color:var(--biru-tua); }

.badge-jaminan{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:12.5px;
  color:var(--abu);
  margin-top:14px;
}

.badge-jaminan i{ color:var(--biru); }

/* ==== EMPTY STATE ==== */

.empty-state{
  text-align:center;
  padding:70px 20px 60px;
}

.empty-state .icon-circle{
  width:120px; height:120px;
  border-radius:50%;
  background:var(--es);
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0 auto 26px;
  font-size:52px;
  color:var(--biru);
}

.empty-state h4{
  font-weight:800;
  margin-bottom:8px;
}

.empty-state p{
  color:var(--abu);
  margin-bottom:28px;
}

.btn-belanja{
  background:linear-gradient(120deg,var(--biru-tua), var(--biru));
  border:none;
  color:#fff;
  font-weight:700;
  padding:13px 34px;
  border-radius:14px;
  box-shadow:0 12px 24px -8px rgba(10,107,181,.5);
}

.btn-belanja:hover{ color:#fff; transform:translateY(-2px); }

/* ==== MOBILE (<=768px) ==== */
@media(max-width:768px){

  body{ background:linear-gradient(180deg,var(--es) 0%, var(--kabut) 220px, #ffffff 220px); }

  .container.wrap, .topbar .container{
    padding-left:14px;
    padding-right:14px;
  }

  .topbar{ padding:22px 0 56px; }

  .topbar .breadcrumb-pill{
    font-size:11.5px;
    padding:5px 12px;
  }

  .topbar h2{
    font-size:21px;
    margin-top:12px !important;
  }

  .topbar h2 i{ font-size:19px; }

  .topbar p{
    font-size:12.5px;
    line-height:1.5;
  }

  .wrap{ margin-top:-40px; padding-bottom:36px; }

  .card-modern{ border-radius:16px; }

  .row.g-4{ --bs-gutter-y:14px; }

  /* ITEM PRODUK jadi card ringkas */
  .item-produk{
    flex-wrap:wrap;
    align-items:flex-start;
    gap:12px;
    padding:14px 4px;
    position:relative;
  }

  .item-produk:hover{ background:transparent; border-color:transparent; }
  .item-produk:active{ background:var(--kabut); }

  .foto-wrap{
    width:64px;
    height:64px;
    min-width:64px;
    border-radius:12px;
  }

  .brand-chip{
    font-size:10px;
    padding:2px 9px;
    margin-bottom:4px;
  }

  .nama-produk{
    font-size:13.5px;
    line-height:1.3;
  }

  .tipe-produk{ font-size:11.5px; }

  .harga-satuan{ font-size:11px; }

  .kolom-kanan-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:100%;
    margin-top:2px;
    padding-left:76px;
  }

  .qty-badge{
    min-width:32px;
    height:32px;
    font-size:13px;
    border-radius:8px;
  }

  .subtotal-produk{ font-size:14px; }

  .btn-hapus{
    width:32px;
    height:32px;
    border-radius:8px;
  }

  .link-lanjut{ font-size:13px; margin-top:14px; }

  /* RINGKASAN full width, tidak sticky */
  .ringkasan{
    position:static;
    margin-top:0;
    padding:20px;
  }

  .ringkasan h5{ font-size:15.5px; margin-bottom:14px; }

  .baris-ringkasan{ font-size:13.5px; margin-bottom:10px; }

  .total-akhir .angka{ font-size:21px; }

  .btn-checkout{
    padding:13px;
    font-size:14px;
    border-radius:12px;
  }

  .badge-jaminan{
    font-size:11.5px;
    align-items:flex-start;
  }

  /* EMPTY STATE */
  .empty-state{ padding:50px 16px 40px; }

  .empty-state .icon-circle{
    width:92px; height:92px;
    font-size:40px;
    margin-bottom:20px;
  }

  .empty-state h4{ font-size:18px; }

  .empty-state p{ font-size:13px; margin-bottom:22px; }

  .btn-belanja{
    padding:12px 26px;
    font-size:14px;
    width:100%;
  }

}

/* ==== MOBILE KECIL (<=380px) ==== */
@media(max-width:380px){

  .topbar h2{ font-size:19px; }

  .foto-wrap{ width:56px; height:56px; min-width:56px; }

  .kolom-kanan-item{ padding-left:68px; }

  .nama-produk{ font-size:13px; padding-right:28px; }

  .subtotal-produk{ font-size:13px; }

  .total-akhir .angka{ font-size:19px; }

}

</style>

</head>

<body>

<div class="topbar">
  <div class="container">
    <span class="breadcrumb-pill"><i class="bi bi-shop"></i> CV Peppy Tehnik Indonesia</span>
    <h2 class="mt-3"><i class="bi bi-bag-check-fill"></i> Keranjang Belanja</h2>
    <p>Periksa kembali AC pilihanmu sebelum lanjut ke pembayaran</p>
  </div>
</div>

<div class="container wrap">

<?php

if( !isset($_SESSION['keranjang']) || count($_SESSION['keranjang'])==0 ){

?>

  <div class="card-modern animate__animated animate__fadeInUp">
    <div class="empty-state">
      <div class="icon-circle">
        <i class="bi bi-cart-x"></i>
      </div>
      <h4>Keranjang kamu masih kosong</h4>
      <p>Yuk cari AC yang pas buat rumah atau kantormu.<br>Daikin, Gree, Aqua, sampai Midea, semua ada di sini.</p>
      <a href="index.php" class="btn btn-belanja">
        <i class="bi bi-arrow-left-circle me-1"></i> Mulai Belanja
      </a>
    </div>
  </div>

<?php

}else{

  $grandTotal = 0;
  $totalItem  = 0;

?>

  <div class="row g-4">

    <div class="col-lg-8">
      <div class="card-modern p-3 p-md-4 animate__animated animate__fadeInUp">

        <?php foreach($_SESSION['keranjang'] as $id=>$jumlah){

          $q = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
          $d = mysqli_fetch_assoc($q);

          if(!$d){ continue; }

          $harga = (int) preg_replace("/[^0-9]/", "", $d['harga']);
          $total = $harga * $jumlah;

          $grandTotal += $total;
          $totalItem  += $jumlah;

        ?>

        <div class="item-produk">

          <div class="foto-wrap">
            <img src="upload/produk/<?= $d['foto']; ?>" alt="<?= $d['nama_produk']; ?>">
          </div>

          <div class="flex-grow-1">
            <span class="brand-chip"><?= $d['brand']; ?></span>
            <div class="nama-produk"><?= $d['nama_produk']; ?></div>
            <div class="tipe-produk"><?= $d['tipe']; ?></div>
            <div class="harga-satuan mt-1">Rp <?= number_format($harga,0,',','.'); ?> / unit</div>
          </div>

          <div class="kolom-kanan-item d-flex align-items-center gap-3">
            <span class="qty-badge">x<?= $jumlah; ?></span>
            <div class="text-end" style="min-width:120px">
              <div class="subtotal-produk">Rp <?= number_format($total,0,',','.'); ?></div>
            </div>
            <button onclick="hapusProduk(<?= $id; ?>)" class="btn-hapus" title="Hapus produk">
              <i class="bi bi-trash3"></i>
            </button>
          </div>

        </div>

        <?php } ?>

        <a href="index.php" class="link-lanjut">
          <i class="bi bi-arrow-left"></i> Lanjut Belanja
        </a>

      </div>
    </div>

    <div class="col-lg-4">
      <div class="card-modern ringkasan animate__animated animate__fadeInUp">

        <h5><i class="bi bi-receipt me-1"></i> Ringkasan Pesanan</h5>

        <div class="baris-ringkasan">
          <span>Jumlah Produk</span>
          <span><?= $totalItem; ?> unit</span>
        </div>

        <div class="baris-ringkasan">
          <span>Subtotal</span>
          <span>Rp <?= number_format($grandTotal,0,',','.'); ?></span>
        </div>

        <div class="garis-putus"></div>

        <div class="total-akhir">
          <span class="label">Total Bayar</span>
          <span class="angka">Rp <?= number_format($grandTotal,0,',','.'); ?></span>
        </div>

        <?php if(isset($_SESSION['customer'])){ ?>

          <a href="checkout.php" class="btn-checkout">
            Checkout Sekarang <i class="bi bi-arrow-right"></i>
          </a>

        <?php }else{ ?>

          <button type="button" onclick="cekLoginCheckout()" class="btn-checkout">
            Checkout Sekarang <i class="bi bi-arrow-right"></i>
          </button>

        <?php } ?>

        <div class="badge-jaminan">
          <i class="bi bi-shield-check"></i> Garansi resmi & instalasi profesional
        </div>
        <div class="badge-jaminan">
          <i class="bi bi-truck"></i> Pengiriman cepat ke seluruh area
        </div>

      </div>
    </div>

  </div>

<?php } ?>

</div>

<script>

function cekLoginCheckout(){

  Swal.fire({
    title:'Login Diperlukan',
    html:`
      <div style="
        width:84px;height:84px;
        background:linear-gradient(135deg,#eaf6fd,#d6ecf9);
        border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        margin:6px auto 18px;
        font-size:36px;color:#0a6bb5;
      ">
        <i class="bi bi-shield-lock"></i>
      </div>
      <div style="font-size:14.5px;color:#6b7c88;line-height:1.6">
        Silahkan login terlebih dahulu untuk<br>melanjutkan proses checkout AC kamu
      </div>
    `,
    showCancelButton:true,
    confirmButtonText:'Login Sekarang',
    cancelButtonText:'Nanti Saja',
    buttonsStyling:false,
    customClass:{
      popup:'animate__animated animate__zoomIn animate__faster',
      confirmButton:'btn-checkout',
      cancelButton:'link-lanjut'
    },
    showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
    hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' }
  }).then((result)=>{

    if(result.isConfirmed){

      Swal.fire({
        title:'Mengalihkan...',
        html:'Menuju halaman login',
        timer:1100,
        showConfirmButton:false,
        allowOutsideClick:false,
        didOpen:()=>{ Swal.showLoading(); }
      });

      setTimeout(()=>{
        window.location.href = "customer/login.php";
      }, 1100);

    }

  });

}

function hapusProduk(id){

  Swal.fire({
    title:'Hapus Produk?',
    html:`
      <div style="font-size:56px">🛒</div>
      Produk akan dikeluarkan dari keranjang
    `,
    icon:'warning',
    showCancelButton:true,
    confirmButtonText:'Ya Hapus',
    cancelButtonText:'Batal',
    confirmButtonColor:'#e6483a',
    cancelButtonColor:'#0a6bb5',
    showClass:{ popup:'animate__animated animate__zoomIn' },
    hideClass:{ popup:'animate__animated animate__zoomOut' }
  }).then((result)=>{

    if(result.isConfirmed){

      Swal.fire({
        title:'Menghapus...',
        timer:900,
        showConfirmButton:false,
        didOpen:()=>{ Swal.showLoading(); }
      });

      setTimeout(()=>{
        window.location.href = "hapus-keranjang.php?id=" + id;
      }, 900);

    }

  });

}

</script>

</body>
</html>