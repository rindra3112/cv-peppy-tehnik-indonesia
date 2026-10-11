<?php

session_start();

include "config/database.php";

$produk = mysqli_query(
    $conn,
    "SELECT * FROM produk WHERE brand='Midea' ORDER BY id DESC"
);

$jumlahKeranjang = 0;

if (isset($_SESSION['keranjang'])) {
    $jumlahKeranjang = count($_SESSION['keranjang']);
}

$page = basename($_SERVER['PHP_SELF']);

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Midea AC - CV Peppy Tehnik Indonesia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

/* ==========================================================
   DESIGN TOKENS — "Cold Air / Frost" system (sama seperti index.php)
========================================================== */
:root{
    --ice-navy:#0B2C55;
    --frost-blue:#1D6FE0;
    --frost-blue-soft:#4C8FEE;
    --ice-cyan:#56D6E8;
    --mist:#EEF5FB;
    --surface:#FFFFFF;
    --slate:#5E7288;
    --slate-soft:#93A5B8;
    --leaf:#22C55E;
    --amber:#FFB020;
    --danger:#E5484D;

    --radius-lg:26px;
    --radius-md:18px;
    --radius-sm:12px;

    --shadow-soft:0 10px 30px rgba(11,44,85,.08);
    --shadow-lift:0 20px 45px rgba(11,44,85,.14);

    --font-display:'Sora',sans-serif;
    --font-body:'Inter',sans-serif;
    --font-mono:'JetBrains Mono',monospace;
}

*{ box-sizing:border-box; }

body{
    background:
        radial-gradient(circle at 15% 0%, rgba(86,214,232,.10), transparent 40%),
        radial-gradient(circle at 90% 10%, rgba(29,111,224,.08), transparent 45%),
        var(--mist);
    font-family:var(--font-body);
    color:#22303f;
    padding-bottom:30px;
}

.container{ max-width:1200px; }
h1,h2,h3,h4,h5,h6{ font-family:var(--font-display); }
::selection{ background:var(--ice-cyan); color:var(--ice-navy); }

.page-container{ max-width:520px; margin:0 auto; }

/* ==================
   NAVBAR
================== */
.nav-box{
    background:rgba(255,255,255,.72);
    backdrop-filter:blur(18px) saturate(160%);
    -webkit-backdrop-filter:blur(18px) saturate(160%);
    border:1px solid rgba(255,255,255,.6);
    border-radius:20px;
    padding:12px 20px;
    box-shadow:var(--shadow-soft);
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    position:sticky;
    top:12px;
    z-index:50;
    margin-top:16px;
    transition:transform .35s ease, opacity .35s ease;
}

.logo{
    font-family:var(--font-display);
    font-size:17px;
    font-weight:800;
    color:var(--ice-navy);
    white-space:nowrap;
    display:flex;
    align-items:center;
    gap:10px;
    letter-spacing:-.2px;
}
.logo .logo-badge{
    width:34px;
    height:34px;
    border-radius:11px;
    background:linear-gradient(145deg,var(--frost-blue),var(--ice-cyan));
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:15px;
    box-shadow:0 6px 14px rgba(29,111,224,.35);
    flex-shrink:0;
}

.nav-top-row{ display:contents; }
.menu-toggle{ display:none; }
.nav-collapse{ display:contents; }

.menu{
    display:flex;
    align-items:center;
    gap:4px;
    flex-wrap:nowrap;
    overflow-x:auto;
    scrollbar-width:none;
}
.menu::-webkit-scrollbar{ display:none; }

.menu a{
    text-decoration:none;
    color:var(--slate);
    font-weight:600;
    font-size:12.5px;
    padding:9px 14px;
    border-radius:30px;
    white-space:nowrap;
    transition:.25s;
    display:flex;
    align-items:center;
    gap:6px;
}
.menu a:hover{
    color:var(--frost-blue);
    background:rgba(29,111,224,.08);
}
.menu a.active{
    color:#fff;
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    box-shadow:0 8px 16px rgba(29,111,224,.28);
}
.menu a .badge{
    background:var(--ice-cyan);
    color:var(--ice-navy);
    font-size:10px;
    font-weight:700;
    padding:2px 6px;
}

.logout-btn{
    border:none;
    background:rgba(229,72,77,.1);
    color:var(--danger);
    font-weight:700;
    font-size:12px;
    padding:9px 16px;
    border-radius:30px;
    transition:.25s;
    white-space:nowrap;
}
.logout-btn:hover{
    background:var(--danger);
    color:#fff;
    transform:translateY(-2px);
}

/* ==================
   BANNER
================== */
.header{
    position:relative;
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    border-radius:var(--radius-lg);
    padding:26px 22px 20px;
    box-shadow:var(--shadow-lift);
    margin-top:20px;
    margin-bottom:18px;
    overflow:hidden;
    isolation:isolate;
}
.header::before{
    content:'';
    position:absolute;
    inset:0;
    z-index:0;
    pointer-events:none;
    background:
        linear-gradient(115deg, transparent 40%, rgba(86,214,232,.28) 46%, transparent 52%),
        linear-gradient(115deg, transparent 55%, rgba(255,255,255,.18) 60%, transparent 66%);
    background-size:220% 220%;
    animation:airflow 9s linear infinite;
    mix-blend-mode:screen;
}
@keyframes airflow{
    0%{ background-position:0% 0%, 0% 0%; }
    100%{ background-position:-220% 0%, -220% 0%; }
}
@media (prefers-reduced-motion: reduce){
    .header::before{ animation:none; }
}
.header > *{ position:relative; z-index:1; }

.header h1{
    font-size:20px;
    font-weight:800;
    text-align:center;
    margin-bottom:4px;
}
.header p{
    font-size:12px;
    text-align:center;
    opacity:.9;
    margin-bottom:18px;
    font-family:var(--font-body);
}
.brand-row{
    display:flex;
    justify-content:space-around;
    align-items:flex-end;
    flex-wrap:wrap;
    gap:10px;
}
.brand-logo{
    background:#fff;
    border-radius:50%;
    width:60px;
    height:60px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 6px 14px rgba(0,0,0,.2);
    overflow:hidden;
    flex-shrink:0;
}
.brand-logo img{
    width:100%;
    height:100%;
    object-fit:cover;
    padding:4px;
    box-sizing:border-box;
}
.brand-label{
    text-align:center;
    font-size:10px;
    font-weight:700;
    margin-top:6px;
    font-family:var(--font-mono);
    letter-spacing:.04em;
}

/* ==================
   TRUST BADGES + CTA
================== */
.trust-row{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:14px;
    background:rgba(255,255,255,.75);
    backdrop-filter:blur(14px);
    border:1px solid rgba(255,255,255,.6);
    border-radius:var(--radius-md);
    padding:16px 14px;
    box-shadow:var(--shadow-soft);
    margin-bottom:18px;
}
.trust-row .icons{
    display:flex;
    justify-content:center;
    gap:26px;
    flex-wrap:wrap;
    width:100%;
}
.trust-row .icons span{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:6px;
    font-size:11px;
    font-weight:700;
    color:var(--ice-navy);
    text-align:center;
    max-width:80px;
}
.trust-row .icons span i{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    width:38px;
    height:38px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:15px;
    box-shadow:0 6px 14px rgba(29,111,224,.3);
}
.btn-hubungi{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    background:linear-gradient(135deg,#25d366,var(--leaf));
    color:#fff;
    font-size:13px;
    font-weight:800;
    letter-spacing:.3px;
    border-radius:30px;
    padding:12px 18px;
    width:100%;
    text-align:center;
    text-decoration:none;
    box-shadow:0 8px 18px rgba(34,197,94,.32);
    transition:.3s;
}
.btn-hubungi:hover{
    transform:translateY(-3px);
    color:#fff;
    box-shadow:0 12px 22px rgba(34,197,94,.42);
}

/* ==================
   FILTER PILLS
================== */
.filter-row{
    display:flex;
    gap:8px;
    overflow-x:auto;
    padding-bottom:6px;
    margin-bottom:18px;
    justify-content:center;
    flex-wrap:wrap;
}
.filter-pill{
    flex:0 0 auto;
    border:1.5px solid rgba(29,111,224,.35);
    color:var(--frost-blue);
    background:var(--surface);
    border-radius:30px;
    padding:9px 20px;
    font-size:12.5px;
    font-weight:700;
    text-decoration:none;
    white-space:nowrap;
    transition:.25s;
}
.filter-pill:hover{
    border-color:var(--frost-blue);
    background:rgba(29,111,224,.06);
    transform:translateY(-2px);
    box-shadow:0 8px 16px rgba(29,111,224,.15);
}
.filter-pill.active{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    border-color:transparent;
    box-shadow:0 8px 18px rgba(29,111,224,.3);
}

/* ==================
   PRODUCT CARD
================== */
.product-card{
    position:relative;
    background:var(--surface);
    border-radius:var(--radius-md);
    padding:16px 14px 14px;
    box-shadow:var(--shadow-soft);
    border:1px solid rgba(11,44,85,.04);
    height:100%;
    overflow:hidden;
    transition:.35s cubic-bezier(.2,.7,.3,1);
}
.product-card::before{
    content:'';
    position:absolute;
    top:0; left:0; right:0;
    height:4px;
    background:linear-gradient(90deg,var(--frost-blue),var(--ice-cyan));
    opacity:0;
    transition:.3s;
}
.product-card:hover{
    transform:translateY(-8px);
    box-shadow:var(--shadow-lift);
}
.product-card:hover::before{ opacity:1; }

.product-img{
    width:100%;
    height:110px;
    object-fit:contain;
    margin-bottom:8px;
}
.brand{
    font-family:var(--font-mono);
    font-size:11px;
    font-weight:600;
    letter-spacing:.06em;
    text-transform:uppercase;
    color:var(--frost-blue);
    margin-bottom:2px;
}
.nama-produk{
    font-size:13px;
    font-weight:700;
    color:#1c2b3a;
    margin-bottom:4px;
    min-height:34px;
    line-height:1.35;
}
.harga{
    font-family:var(--font-mono);
    font-size:15px;
    font-weight:600;
    color:var(--ice-navy);
    margin-bottom:10px;
}
.btn-keranjang{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    border:none;
    border-radius:16px;
    font-size:11px;
    font-weight:700;
    padding:9px 0;
    width:100%;
    transition:.25s;
}
.btn-keranjang:hover{
    filter:brightness(1.08);
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(29,111,224,.3);
}
.btn-pesan{
    background:linear-gradient(135deg,#25d366,var(--leaf));
    color:#fff;
    border:none;
    border-radius:16px;
    font-size:11px;
    font-weight:700;
    padding:9px 0;
    width:100%;
    display:block;
    text-align:center;
    text-decoration:none;
    margin-top:6px;
    transition:.25s;
}
.btn-pesan:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(34,197,94,.3);
}
.rating{
    text-align:center;
    color:var(--amber);
    font-size:13px;
    margin-top:8px;
    letter-spacing:2px;
}

/* ==================
   DESKTOP LAYOUT
================== */
@media(min-width:769px){

    .page-container{ max-width:1100px; }

    .logo{ font-size:19px; }
    .nav-box{ padding:14px 26px; }
    .menu a{ font-size:13.5px; padding:10px 18px; }
    .logout-btn{ font-size:13px; padding:10px 18px; }

    .header{ padding:45px 60px; }
    .header h1{ font-size:38px; margin-bottom:8px; }
    .header p{ font-size:16px; margin-bottom:28px; }
    .brand-row{ gap:40px; }
    .brand-logo{ width:90px; height:90px; }
    .brand-label{ font-size:13px; margin-top:10px; }

    .trust-row{ padding:24px 30px; gap:18px; }
    .trust-row .icons{ gap:40px; }
    .trust-row .icons span{ font-size:13px; max-width:none; }
    .btn-hubungi{ width:auto; min-width:280px; padding:14px 30px; font-size:14px; margin:0 auto; }

    .filter-pill{ font-size:14px; padding:10px 26px; }

    .product-card{ padding:20px 18px 18px; }
    .product-img{ height:170px; margin-bottom:10px; }
    .brand{ font-size:12px; }
    .nama-produk{ font-size:14px; min-height:40px; }
    .harga{ font-size:19px; margin-bottom:12px; }
    .btn-keranjang, .btn-pesan{ font-size:13px; padding:11px 0; }
    .rating{ font-size:15px; }
}

/* ==================
   MOBILE (HAMBURGER NAV)
================== */
@media(max-width:768px){

    .nav-box{
        padding:10px 12px;
        border-radius:16px;
        flex-direction:column;
        align-items:stretch;
        top:6px;
        margin-top:10px;
    }
    .nav-box.nav-hidden{
        transform:translateY(-130%);
        opacity:0;
        pointer-events:none;
    }

    .nav-top-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        width:100%;
    }
    .menu-toggle{
        display:flex;
        align-items:center;
        justify-content:center;
        width:38px;
        height:38px;
        border-radius:12px;
        border:none;
        background:var(--mist);
        color:var(--ice-navy);
        font-size:15px;
        flex-shrink:0;
        transition:.25s;
    }
    .menu-toggle.active{
        background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
        color:#fff;
    }
    .nav-collapse{
        display:block;
        width:100%;
        max-height:0;
        overflow:hidden;
        opacity:0;
        transition:max-height .35s ease, opacity .25s ease, margin-top .35s ease;
        margin-top:0;
    }
    .nav-collapse.open{
        max-height:520px;
        opacity:1;
        margin-top:12px;
    }

    .logo{ font-size:14.5px; }
    .menu{
        display:flex;
        flex-direction:column;
        gap:8px;
        width:100%;
        overflow-x:visible;
    }
    .menu a{
        font-size:12.5px;
        padding:12px 16px;
        justify-content:flex-start;
        background:var(--mist);
        border:1px solid rgba(11,44,85,.06);
        margin:0;
        white-space:normal;
        width:100%;
    }
    .menu a.active{ border-color:transparent; }
    .menu a .badge{ margin-left:auto; }
    .logout-btn{
        font-size:12.5px;
        padding:12px 16px;
        width:100%;
        margin:0;
        border:1px solid rgba(229,72,77,.18);
        display:flex;
        align-items:center;
        justify-content:flex-start;
        gap:8px;
    }

    .header{ margin-top:12px; padding:22px 18px 16px; border-radius:18px; }
    .header h1{ font-size:18px; }
    .header p{ font-size:11px; margin-bottom:14px; }
    .brand-logo{ width:54px; height:54px; }
    .brand-label{ font-size:9px; margin-top:4px; }

    .trust-row{ gap:8px; padding:12px 10px; }
    .trust-row .icons{ gap:20px; }
    .trust-row .icons span{ font-size:10px; }

    .filter-pill{ padding:8px 16px; font-size:11px; }

    .row.g-3{ gap:8px 0; }

    .product-card{ padding:14px; border-radius:16px; }
    .product-img{ height:100px; }
    .brand{ font-size:10px; }
    .nama-produk{ font-size:11px; min-height:30px; }
    .harga{ font-size:13px; margin-bottom:8px; }
    .btn-keranjang, .btn-pesan{ font-size:10px; padding:9px 0; }
    .rating{ font-size:11px; }
}

</style>
</head>
<body>

<div class="container page-container">

    <!-- NAVBAR -->
    <div class="nav-box" id="navBox">

        <div class="nav-top-row">

            <div class="logo">
                <span class="logo-badge"><i class="fa fa-snowflake"></i></span>
                CV Peppy Tehnik Indonesia
            </div>

            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Buka menu">
                <i class="fa fa-bars"></i>
            </button>

        </div>

        <div class="nav-collapse" id="navCollapse">

            <div class="menu">

                <a href="index.php">
                    <i class="fa fa-house"></i> HOME
                </a>

                <a href="lihat-keranjang.php">
                    <i class="fa fa-cart-shopping"></i> KERANJANG
                    <span class="badge bg-primary"><?= $jumlahKeranjang ?></span>
                </a>

                <a href="riwayat.php">
                    <i class="fa fa-clock-rotate-left"></i> RIWAYAT PESANAN
                </a>

                <?php if (isset($_SESSION['customer'])) { ?>

                    <button onclick="logoutConfirm()" class="logout-btn">
                        <i class="fa fa-right-from-bracket"></i> LOGOUT
                    </button>

                <?php } else { ?>

                    <a href="customer/login.php">
                        <i class="fa fa-right-to-bracket"></i> LOGIN
                    </a>

                <?php } ?>

            </div>

        </div>

    </div>

    <!-- BANNER -->
    <div class="header animate__animated animate__fadeInDown">
        <h1>CV Peppy Tehnik Indonesia</h1>
        <p>Sedia Aneka Merek AC Terpercaya &amp; Berkualitas Original</p>

        <div class="brand-row">
            <div>
                <div class="brand-logo"><img src="assets/logo-daikin.png" alt="Daikin"></div>
                <div class="brand-label">DAIKIN</div>
            </div>
            <div>
                <div class="brand-logo"><img src="assets/logo-gree.png" alt="Gree"></div>
                <div class="brand-label">GREE</div>
            </div>
            <div>
                <div class="brand-logo"><img src="assets/logo-aqua.png" alt="Aqua"></div>
                <div class="brand-label">AQUA</div>
            </div>
            <div>
                <div class="brand-logo"><img src="assets/logo-midea.png" alt="Midea"></div>
                <div class="brand-label">Midea</div>
            </div>
        </div>
    </div>

    <!-- TRUST ROW + CTA -->
    <div class="trust-row">
        <div class="icons">
            <span><i class="fa fa-certificate"></i> Bergaransi Resmi</span>
            <span><i class="fa fa-shield-halved"></i> Terpercaya</span>
            <span><i class="fa fa-truck-fast"></i> Pengiriman Cepat</span>
        </div>
        <a href="https://wa.me/62xxxxxxxxxx" class="btn-hubungi">
            <i class="fa-brands fa-whatsapp" style="font-size:16px;"></i> HUBUNGI KAMI SEKARANG
        </a>
    </div>

    <!-- FILTER PILLS -->
    <div class="filter-row">
        <a href="index.php" class="filter-pill">Semua Merk</a>
        <a href="daikin.php" class="filter-pill ">Daikin</a>
        <a href="gree.php" class="filter-pill ">Gree</a>
        <a href="aqua.php" class="filter-pill ">Aqua</a>
        <a href="midea.php" class="filter-pill active">Midea</a>
    </div>

    <!-- PRODUCT GRID -->
    <div class="row g-3">

        <?php while ($d = mysqli_fetch_array($produk)) { ?>

            <div class="col-6 col-md-3">
                <div class="product-card animate__animated animate__zoomIn">

                    <img src="upload/produk/<?= $d['foto']; ?>" class="product-img" alt="<?= $d['nama_produk']; ?>">

                    <div class="brand"><?= $d['brand']; ?></div>
                    <div class="nama-produk"><?= $d['nama_produk']; ?> <?= $d['tipe']; ?></div>
                    <?php
                        $hargaBersih = (float) preg_replace('/[^0-9]/', '', $d['harga']);
                    ?>
                    <div class="harga">Rp <?= number_format($hargaBersih, 0, ',', '.'); ?></div>

                    <button onclick="tambahKeranjang(<?= $d['id']; ?>)" class="btn-keranjang">
                        <i class="fa fa-cart-shopping"></i> Masukkan Keranjang
                    </button>

                    <a href="checkout.php?id=<?= $d['id']; ?>" class="btn-pesan">
                        Pesan Sekarang
                    </a>

                    <div class="rating">★★★★★</div>

                </div>
            </div>

        <?php } ?>

    </div>

</div>

<script>

function tambahKeranjang(id){

    Swal.fire({
        title:'Berhasil Ditambahkan',
        html:`<div style="font-size:60px">🛒</div>Produk masuk ke keranjang`,
        icon:'success',
        timer:1500,
        showConfirmButton:false,
        showClass:{ popup:'animate__animated animate__bounceIn' }
    });

    setTimeout(()=>{
        window.location.href = "keranjang.php?id="+id+"&back=home";
    },1500);
}

function logoutConfirm(){

    Swal.fire({
        title:'Keluar dari Akun?',
        text:'Kamu akan keluar dari sesi akun ini. Yakin ingin melanjutkan?',
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Ya, Logout',
        cancelButtonText:'Tetap Login',
        confirmButtonColor:'#E5484D',
        cancelButtonColor:'#6c757d',
        showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
        hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' }
    }).then((result)=>{

        if(result.isConfirmed){

            Swal.fire({
                title:'Logout Berhasil',
                text:'Sampai jumpa kembali 👋',
                icon:'success',
                timer:1300,
                showConfirmButton:false
            });

            setTimeout(()=>{
                window.location.href='customer/logout.php';
            },1300);

        }

    });

}

/* ===== TOGGLE MENU MOBILE ===== */
(function(){
    const menuToggle  = document.getElementById('menuToggle');
    const navCollapse = document.getElementById('navCollapse');
    if (!menuToggle || !navCollapse) return;

    menuToggle.addEventListener('click', function(){
        navCollapse.classList.toggle('open');
        menuToggle.classList.toggle('active');
    });

    navCollapse.querySelectorAll('a, button').forEach(function(el){
        el.addEventListener('click', function(){
            navCollapse.classList.remove('open');
            menuToggle.classList.remove('active');
        });
    });
})();

/* ===== NAVBAR AUTO-HIDE ON SCROLL (MOBILE ONLY) ===== */
(function(){
    const navBox = document.getElementById('navBox');
    const navCollapse = document.getElementById('navCollapse');
    const menuToggle  = document.getElementById('menuToggle');
    let lastScrollY = window.scrollY;
    let ticking = false;

    const MOBILE_BREAKPOINT = 768;
    const SCROLL_THRESHOLD = 8;
    const TOP_OFFSET = 60;

    function isMobile(){ return window.innerWidth <= MOBILE_BREAKPOINT; }

    function tutupMenu(){
        if (navCollapse) navCollapse.classList.remove('open');
        if (menuToggle) menuToggle.classList.remove('active');
    }

    function handleScroll(){
        const currentScrollY = window.scrollY;

        if (!isMobile()) {
            navBox.classList.remove('nav-hidden');
            lastScrollY = currentScrollY;
            ticking = false;
            return;
        }

        const diff = currentScrollY - lastScrollY;

        if (currentScrollY <= TOP_OFFSET) {
            navBox.classList.remove('nav-hidden');
        } else if (diff > SCROLL_THRESHOLD) {
            navBox.classList.add('nav-hidden');
            tutupMenu();
        } else if (diff < -SCROLL_THRESHOLD) {
            navBox.classList.remove('nav-hidden');
        }

        lastScrollY = currentScrollY;
        ticking = false;
    }

    window.addEventListener('scroll', function(){
        if (!ticking) {
            window.requestAnimationFrame(handleScroll);
            ticking = true;
        }
    }, { passive:true });

    window.addEventListener('resize', function(){
        if (!isMobile()) { navBox.classList.remove('nav-hidden'); }
    });
})();

</script>

</body>
</html>