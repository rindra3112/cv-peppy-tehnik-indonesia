<?php

session_start();

include "config/database.php";

// ==========================
// CEK LOGIN CUSTOMER
// ==========================

if (!isset($_SESSION['customer'])) {

    header("location:customer/login.php");
    exit;

}

// ==========================
// AMBIL ID CUSTOMER
// ==========================

if (is_array($_SESSION['customer'])) {

    $id_customer = $_SESSION['customer']['id'];

} else {

    $id_customer = $_SESSION['customer'];

}

// ==========================
// AMBIL DATA CUSTOMER
// ==========================

$getCustomer = mysqli_query(
    $conn,
    "SELECT * FROM customer WHERE id='$id_customer'"
);

$customer = mysqli_fetch_assoc($getCustomer);

if (!$customer) {

    header("location:customer/logout.php");
    exit;

}

// ==========================
// AMBIL RIWAYAT
// ==========================

$data = mysqli_query(
    $conn,

    "SELECT

    pesanan.*,

    produk.nama_produk,
    produk.brand,
    produk.tipe,
    produk.foto

    FROM pesanan

    LEFT JOIN produk

    ON produk.id = pesanan.id_produk

    WHERE pesanan.id_customer='$id_customer'

    ORDER BY pesanan.id DESC"

);

$jumlahPesanan = mysqli_num_rows($data);

// Inisial nama untuk avatar
$namaCustomer = $customer['nama'] ?? '';
$inisial = strtoupper(substr($namaCustomer, 0, 1));
if ($inisial === '') { $inisial = '?'; }

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Pesanan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

:root{
    --primary:#0b63ce;
    --primary-dark:#003a63;
    --ink:#12202f;
    --muted:#7c8b9a;
    --line:#e7edf3;
    --bg:#eef3f8;
    --success:#1fae4b;
    --success-soft:#e7f8ed;
    --danger:#dc3545;
    --danger-soft:#fdeaec;
    --pending:#c9860a;
    --pending-soft:#fff3dc;
}

*{ box-sizing:border-box; }

body{
    background:var(--bg);
    font-family:'Plus Jakarta Sans','Segoe UI',sans-serif;
    color:var(--ink);
}

.container{
    max-width:900px;
    padding-left:16px;
    padding-right:16px;
}

/* ===== HEADER ===== */
.box-header{
    position:relative;
    overflow:hidden;
    background:linear-gradient(150deg,var(--primary) 0%,var(--primary-dark) 100%);
    color:#fff;
    margin-top:24px;
    padding:26px 26px 24px;
    border-radius:24px;
    box-shadow:0 16px 34px -12px rgba(11,99,206,.4);
    display:flex;
    flex-direction:column;
    gap:18px;
}

.box-header::before{
    content:'';
    position:absolute;
    top:-60px;
    right:-60px;
    width:180px;
    height:180px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
}
.box-header::after{
    content:'';
    position:absolute;
    bottom:-70px;
    right:60px;
    width:140px;
    height:140px;
    border-radius:50%;
    background:rgba(255,255,255,.06);
}

.header-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
    position:relative;
    z-index:1;
}

.customer-info{
    display:flex;
    align-items:center;
    gap:14px;
    min-width:0;
}

.avatar{
    width:52px;
    height:52px;
    border-radius:16px;
    background:rgba(255,255,255,.16);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:21px;
    font-weight:800;
    flex-shrink:0;
    border:1.5px solid rgba(255,255,255,.35);
}

.customer-info .title-group{ min-width:0; }

.customer-info .eyebrow{
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.6px;
    opacity:.75;
    margin:0 0 2px;
}

.customer-info h2{
    font-size:18px;
    font-weight:800;
    margin:0 0 3px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.customer-info p{
    margin:0;
    font-size:12.5px;
    opacity:.85;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.btn-logout-icon{
    flex-shrink:0;
    width:40px;
    height:40px;
    border-radius:12px;
    border:1.5px solid rgba(255,255,255,.35);
    background:rgba(255,255,255,.1);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:15px;
    transition:.2s;
}
.btn-logout-icon:hover{ background:rgba(220,53,69,.85); border-color:transparent; }

.header-stats{
    position:relative;
    z-index:1;
    display:flex;
    gap:10px;
}

.stat-chip{
    flex:1;
    background:rgba(255,255,255,.1);
    border:1px solid rgba(255,255,255,.18);
    border-radius:14px;
    padding:10px 14px;
}
.stat-chip .num{ font-size:19px; font-weight:800; line-height:1.1; }
.stat-chip .lbl{ font-size:11px; opacity:.8; margin-top:1px; }

.btn-home-full{
    position:relative;
    z-index:1;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    text-decoration:none;
    background:#fff;
    color:var(--primary-dark);
    font-weight:700;
    font-size:13.5px;
    padding:11px;
    border-radius:14px;
    transition:.2s;
}
.btn-home-full:hover{ background:#eef3f8; color:var(--primary-dark); transform:translateY(-1px); }

/* ===== ORDER LIST ===== */
.section-label{
    margin:26px 2px 12px;
    font-size:13px;
    font-weight:700;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.6px;
}

.order-wrap{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.order-card{
    background:#fff;
    border-radius:20px;
    padding:16px;
    box-shadow:0 6px 18px rgba(18,32,47,.05);
    border:1px solid var(--line);
}

.order-card-top{
    display:flex;
    align-items:center;
    gap:14px;
}

.order-foto, .order-foto-empty{
    width:72px;
    height:72px;
    border-radius:14px;
    flex-shrink:0;
    background:#f4f7fa;
}
.order-foto{ object-fit:contain; padding:6px; }
.order-foto-empty{
    display:flex;
    align-items:center;
    justify-content:center;
    color:#c2cdd8;
    font-size:22px;
}

.order-info{
    flex:1;
    min-width:0;
}
.order-info .brand{
    font-size:10.5px;
    font-weight:800;
    color:var(--primary);
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:2px;
}
.order-info h5{
    font-size:15px;
    font-weight:700;
    margin:0 0 2px;
    color:var(--ink);
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.order-info small{
    color:var(--muted);
    font-size:12px;
}

.order-divider{
    height:1px;
    background:var(--line);
    margin:14px 0 12px;
}

.order-card-bottom{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
}

.order-total .label{
    font-size:11px;
    color:var(--muted);
    margin-bottom:1px;
}
.order-total .harga{
    font-size:17px;
    font-weight:800;
    color:var(--primary-dark);
}

.status-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:7px 13px;
    border-radius:20px;
    font-size:11.5px;
    font-weight:700;
    white-space:nowrap;
}
.status-sukses{ background:var(--success-soft); color:var(--success); }
.status-ditolak{ background:var(--danger-soft); color:var(--danger); }
.status-pending{ background:var(--pending-soft); color:var(--pending); }

/* ===== EMPTY STATE ===== */
.empty-state{
    background:#fff;
    border-radius:22px;
    padding:56px 26px;
    text-align:center;
    box-shadow:0 8px 22px rgba(0,0,0,.05);
    margin-top:20px;
    border:1px solid var(--line);
}
.empty-state i{
    font-size:46px;
    color:#c9d6e3;
    margin-bottom:16px;
}
.empty-state h4{
    font-weight:800;
    color:var(--ink);
    margin-bottom:6px;
    font-size:17px;
}
.empty-state p{
    color:var(--muted);
    font-size:13.5px;
    margin-bottom:22px;
}
.empty-state a{
    display:inline-block;
    background:var(--primary);
    color:#fff;
    text-decoration:none;
    padding:12px 28px;
    border-radius:24px;
    font-weight:700;
    font-size:13px;
    transition:.25s;
}
.empty-state a:hover{
    background:var(--primary-dark);
    transform:translateY(-2px);
    color:#fff;
}

/* ===== DESKTOP (>=576px) — roomier layout ===== */
@media(min-width:576px){

    .box-header{
        margin-top:40px;
        padding:30px 32px;
        border-radius:26px;
        flex-direction:row;
        align-items:center;
        justify-content:space-between;
    }
    .header-top{ flex:1; }
    .avatar{ width:58px; height:58px; font-size:24px; border-radius:50%; }
    .customer-info h2{ font-size:21px; }
    .customer-info p{ font-size:13px; }
    .btn-logout-icon{ display:none; }

    .header-stats{
        flex:0 0 auto;
        gap:8px;
    }
    .stat-chip{ min-width:88px; }

    .header-actions-desktop{
        display:flex;
        gap:10px;
    }
    .btn-home-full{
        width:auto;
        padding:11px 22px;
    }
    .btn-logout-full{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        border:none;
        text-decoration:none;
        background:rgba(255,255,255,.14);
        border:1.5px solid rgba(255,255,255,.35);
        color:#fff;
        font-weight:700;
        font-size:13.5px;
        padding:11px 22px;
        border-radius:14px;
        cursor:pointer;
        transition:.2s;
    }
    .btn-logout-full:hover{ background:var(--danger); border-color:transparent; }

    .order-card{
        padding:18px 20px;
        border-radius:22px;
    }
    .order-card-top{ gap:18px; }
    .order-foto, .order-foto-empty{ width:88px; height:88px; }
    .order-info h5{ font-size:16px; }

    .order-divider{ display:none; }
    .order-card-bottom{
        flex-direction:row;
        margin-left:auto;
        flex-shrink:0;
        gap:22px;
    }
    .order-card-top{
        display:flex;
        width:100%;
    }
    .order-card{
        display:flex;
        flex-wrap:wrap;
        align-items:center;
    }
    .order-card-top{ flex:1; min-width:0; }
    .order-total{ text-align:right; min-width:120px; }
}

.mobile-only{ display:block; }
.desktop-only{ display:none; }
@media(min-width:576px){
    .mobile-only{ display:none; }
    .desktop-only{ display:flex; }
}

/* ===================================================
   LOGOUT MODAL — custom SweetAlert2 styling
   =================================================== */

.swal2-container.logout-overlay{
    background:rgba(8,18,34,.55) !important;
    backdrop-filter:blur(6px);
    -webkit-backdrop-filter:blur(6px);
}

.swal2-popup.logout-popup{
    width:400px;
    max-width:92vw;
    border-radius:28px !important;
    padding:38px 32px 32px !important;
    font-family:'Plus Jakarta Sans','Segoe UI',sans-serif;
    box-shadow:0 30px 70px -20px rgba(8,18,34,.5) !important;
}

.logout-icon-ring{
    width:92px;
    height:92px;
    margin:0 auto 20px;
    border-radius:50%;
    background:linear-gradient(135deg,#fff1f1,#ffe0e0);
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
}
.logout-icon-ring::before{
    content:'';
    position:absolute;
    inset:-7px;
    border-radius:50%;
    border:2px solid rgba(220,53,69,.35);
    animation:ringPulse 1.7s ease-out infinite;
}
@keyframes ringPulse{
    0%{ transform:scale(.82); opacity:.85; }
    100%{ transform:scale(1.3); opacity:0; }
}
.logout-icon-ring svg{ width:40px; height:40px; overflow:visible; }
.logout-icon-ring path,
.logout-icon-ring polyline,
.logout-icon-ring line{
    fill:none;
    stroke:#dc3545;
    stroke-width:2.3;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:60;
    stroke-dashoffset:60;
    animation:drawDoor .6s ease .2s forwards;
}

@keyframes drawDoor{ to{ stroke-dashoffset:0; } }

.logout-title{
    font-weight:800 !important;
    font-size:21px !important;
    color:#1a2a3a !important;
    margin-bottom:6px !important;
}

.logout-html{
    color:#8a97a3 !important;
    font-size:14px !important;
    line-height:1.5;
    margin-top:2px !important;
}

.logout-actions{
    display:flex;
    gap:10px;
    margin-top:6px;
    width:100%;
}

.logout-btn-confirm, .logout-btn-cancel{
    flex:1;
    border:none;
    padding:12px 16px;
    border-radius:20px;
    font-weight:700;
    font-size:14px;
    cursor:pointer;
    transition:transform .2s ease, box-shadow .2s ease, background .2s ease;
}

.logout-btn-confirm{
    background:linear-gradient(135deg,#ff6b6b,#dc3545);
    color:#fff;
    box-shadow:0 8px 18px -6px rgba(220,53,69,.45);
}
.logout-btn-confirm:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px -6px rgba(220,53,69,.55);
}

.logout-btn-cancel{
    background:#eef3f8;
    color:var(--primary);
}
.logout-btn-cancel:hover{
    background:#dfe9f5;
    transform:translateY(-2px);
}

/* success (after logout) popup */
.success-icon-ring{
    width:88px;
    height:88px;
    margin:0 auto 18px;
    border-radius:50%;
    background:linear-gradient(155deg,#e9fbf1,#d6f7e4);
    display:flex;
    align-items:center;
    justify-content:center;
}
.success-icon-ring svg{ width:36px; height:36px; }
.success-icon-ring path{
    fill:none;
    stroke:#1fae4b;
    stroke-width:3;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:48;
    stroke-dashoffset:48;
    animation:drawDoor .5s ease .2s forwards;
}

/* modal enter / exit motion */
@keyframes logoutShow{
    from{ opacity:0; transform:scale(.85) translateY(14px); }
    to{ opacity:1; transform:scale(1) translateY(0); }
}
@keyframes logoutHide{
    from{ opacity:1; transform:scale(1) translateY(0); }
    to{ opacity:0; transform:scale(.92) translateY(8px); }
}
.swal2-popup.logout-anim-in{ animation:logoutShow .38s cubic-bezier(.22,.9,.3,1.12); }
.swal2-popup.logout-anim-out{ animation:logoutHide .22s ease forwards; }

@media(max-width:480px){
    .swal2-popup.logout-popup{ padding:30px 22px 26px !important; }
    .logout-icon-ring{ width:76px; height:76px; }
    .logout-icon-ring svg{ width:34px; height:34px; }
    .logout-title{ font-size:18px !important; }
}

</style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="box-header animate__animated animate__fadeInDown">

        <div class="header-top">

            <div class="customer-info">
                <div class="avatar"><?= htmlspecialchars($inisial); ?></div>
                <div class="title-group">
                    <div class="eyebrow">Riwayat Pesanan</div>
                    <h2><?= htmlspecialchars($customer['nama']); ?></h2>
                    <p><?= htmlspecialchars($customer['email']); ?></p>
                </div>
            </div>

            <!-- tombol logout ikon, hanya tampil di mobile -->
            <button onclick="logoutConfirm()" class="btn-logout-icon mobile-only" aria-label="Logout">
                <i class="fa fa-right-from-bracket"></i>
            </button>

        </div>

        <div class="header-stats">
            <div class="stat-chip">
                <div class="num"><?= $jumlahPesanan; ?></div>
                <div class="lbl">Total Pesanan</div>
            </div>
        </div>

        <!-- tombol lengkap, hanya tampil di desktop -->
        <div class="header-actions-desktop desktop-only">
            <a href="index.php" class="btn-home-full"><i class="fa fa-house"></i> Home</a>
            <button onclick="logoutConfirm()" class="btn-logout-full"><i class="fa fa-right-from-bracket"></i> Logout</button>
        </div>

        <!-- tombol home, hanya tampil di mobile -->
        <a href="index.php" class="btn-home-full mobile-only"><i class="fa fa-house"></i> Kembali ke Home</a>

    </div>

    <!-- LIST PESANAN -->

    <?php if ($jumlahPesanan == 0) { ?>

        <div class="empty-state animate__animated animate__fadeIn">
            <i class="fa fa-box-open"></i>
            <h4>Belum Ada Pesanan</h4>
            <p>Yuk mulai pesan AC pilihanmu sekarang juga.</p>
            <a href="index.php">Belanja Sekarang</a>
        </div>

    <?php } else { ?>

        <div class="section-label">Daftar Transaksi</div>

        <div class="order-wrap">

            <?php while ($d = mysqli_fetch_assoc($data)) {

                $total = (int) $d['total'];

                if ($d['status'] == "Pembayaran Berhasil") {
                    $statusClass = "status-sukses";
                    $statusIcon = "fa-circle-check";
                    $statusText = "Berhasil";
                } elseif ($d['status'] == "Pembayaran Ditolak") {
                    $statusClass = "status-ditolak";
                    $statusIcon = "fa-circle-xmark";
                    $statusText = "Ditolak";
                } else {
                    $statusClass = "status-pending";
                    $statusIcon = "fa-hourglass-half";
                    $statusText = "Menunggu Konfirmasi";
                }
            ?>

                <div class="order-card animate__animated animate__fadeInUp">

                    <div class="order-card-top">

                        <?php if ($d['foto']) { ?>
                            <img src="upload/produk/<?= $d['foto']; ?>" class="order-foto">
                        <?php } else { ?>
                            <div class="order-foto-empty"><i class="fa fa-image"></i></div>
                        <?php } ?>

                        <div class="order-info">
                            <div class="brand"><?= $d['brand']; ?></div>
                            <h5><?= $d['nama_produk']; ?></h5>
                            <small><?= $d['tipe']; ?></small>
                        </div>

                    </div>

                    <div class="order-divider"></div>

                    <div class="order-card-bottom">

                        <div class="order-total">
                            <div class="label">Total Pembayaran</div>
                            <div class="harga">Rp <?= number_format($total, 0, ',', '.'); ?></div>
                        </div>

                        <span class="status-badge <?= $statusClass ?>">
                            <i class="fa <?= $statusIcon ?>"></i> <?= $statusText ?>
                        </span>

                    </div>

                </div>

            <?php } ?>

        </div>

    <?php } ?>

</div>

<script>
function logoutConfirm(){

    Swal.fire({

        html: `
            <div class="logout-icon-ring">
                <svg viewBox="0 0 24 24">
                    <path d="M9 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <div class="logout-title">Keluar dari akun?</div>
            <div class="logout-html">Apakah kamu yakin ingin logout dari akun ini?</div>
        `,

        showCancelButton:true,
        confirmButtonText:'Ya Logout',
        cancelButtonText:'Tetap Login',

        buttonsStyling:false,
        reverseButtons:false,

        customClass:{
            container:'logout-overlay',
            popup:'logout-popup',
            actions:'logout-actions',
            confirmButton:'logout-btn-confirm',
            cancelButton:'logout-btn-cancel'
        },

        showClass:{ popup:'logout-anim-in' },
        hideClass:{ popup:'logout-anim-out' }

    }).then((result)=>{

        if(result.isConfirmed){

            Swal.fire({

                html: `
                    <div class="success-icon-ring">
                        <svg viewBox="0 0 24 24"><path d="M4 12.5l5 5 11-11"/></svg>
                    </div>
                    <div class="logout-title">Logout Berhasil</div>
                    <div class="logout-html">Sampai jumpa kembali 👋</div>
                `,

                customClass:{
                    container:'logout-overlay',
                    popup:'logout-popup'
                },

                showClass:{ popup:'logout-anim-in' },
                hideClass:{ popup:'logout-anim-out' },

                timer:1400,
                showConfirmButton:false

            });

            setTimeout(()=>{
                window.location.href='customer/logout.php';
            },1400);

        }

    });

}
</script>

</body>
</html>