<?php

session_start();

include "config/database.php";

// ======================
// SEARCH PRODUK
// ======================

if (isset($_GET['search'])) {

    $keyword = $_GET['search'];

    $produk = mysqli_query($conn,
        "SELECT * FROM produk
        WHERE nama_produk LIKE '%$keyword%'
        OR brand LIKE '%$keyword%'
        OR tipe LIKE '%$keyword%'
        OR pk LIKE '%$keyword%'
        ORDER BY id DESC"
    );

} else {

    $produk = mysqli_query(
        $conn,
        "SELECT * FROM produk ORDER BY id DESC"
    );

}

// JUMLAH KERANJANG
$jumlahKeranjang = 0;

if (isset($_SESSION['keranjang'])) {
    $jumlahKeranjang = count($_SESSION['keranjang']);
}

// halaman aktif
$page = basename($_SERVER['PHP_SELF']);

?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CV Peppy Tehnik Indonesia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="css/style.css">

<style>

/* ==========================================================
   DESIGN TOKENS — "Cold Air / Frost" system
   Metaphor: condensation on a cool surface, airflow diffusion
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
}

.container{ max-width:1200px; }

h1,h2,h3,h4,h5,h6{ font-family:var(--font-display); }

::selection{ background:var(--ice-cyan); color:var(--ice-navy); }

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
    transition:transform .35s ease, opacity .35s ease, top .35s ease;
}

.logo{
    font-family:var(--font-display);
    font-size:18px;
    font-weight:800;
    color:var(--ice-navy);
    white-space:nowrap;
    display:flex;
    align-items:center;
    gap:10px;
    letter-spacing:-.2px;
}
.logo .logo-badge{
    width:36px;
    height:36px;
    border-radius:11px;
    background:linear-gradient(145deg,var(--frost-blue),var(--ice-cyan));
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:16px;
    box-shadow:0 6px 14px rgba(29,111,224,.35);
    flex-shrink:0;
}

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

/* wrapper netral di desktop, dipakai ulang di mobile */
.nav-top-row{
    display:contents;
}
.menu-toggle{
    display:none;
}
.nav-collapse{
    display:contents;
}

.search-box{
    display:flex;
    align-items:center;
    background:var(--mist);
    border:1px solid rgba(11,44,85,.06);
    border-radius:30px;
    padding:4px 4px 4px 16px;
    gap:6px;
    flex:1 1 auto;
    min-width:140px;
    max-width:270px;
    transition:.25s;
}
.search-box:focus-within{
    border-color:var(--frost-blue-soft);
    box-shadow:0 0 0 4px rgba(29,111,224,.10);
}
.search-box input{
    border:none;
    background:transparent;
    outline:none;
    font-size:12.5px;
    font-family:var(--font-body);
    width:100%;
    padding:7px 0;
}
.search-box button{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    border:none;
    width:34px;
    height:34px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

/* ==================
   HERO BANNER — kartu brand + fitur + CTA
================== */
.hero-banner{
    position:relative;
    margin-top:22px;
    border-radius:var(--radius-lg);
    overflow:hidden;
    box-shadow:var(--shadow-lift);
    isolation:isolate;
    background:
        radial-gradient(circle at 8% 15%, rgba(86,214,232,.35), transparent 45%),
        radial-gradient(circle at 95% 85%, rgba(29,111,224,.20), transparent 50%),
        linear-gradient(160deg, #EAF4FC 0%, #F7FBFE 55%, #FFFFFF 100%);
    padding:40px 30px 34px;
}
.hero-banner::after{
    content:'';
    position:absolute;
    inset:0;
    z-index:0;
    pointer-events:none;
    background-image:radial-gradient(rgba(11,44,85,.06) 1px, transparent 1px);
    background-size:22px 22px;
    -webkit-mask-image:radial-gradient(ellipse 70% 60% at 50% 30%, #000 40%, transparent 90%);
    mask-image:radial-gradient(ellipse 70% 60% at 50% 30%, #000 40%, transparent 90%);
}
.hero-blob{
    position:absolute;
    border-radius:50%;
    filter:blur(2px);
    pointer-events:none;
    z-index:0;
}
.hero-blob-1{
    width:220px; height:220px;
    top:-70px; right:-60px;
    background:radial-gradient(circle, rgba(86,214,232,.35), transparent 70%);
}
.hero-blob-2{
    width:260px; height:260px;
    bottom:-100px; left:-80px;
    background:radial-gradient(circle, rgba(29,111,224,.16), transparent 70%);
}

.hero-content{
    position:relative;
    z-index:1;
}

.hero-eyebrow{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:7px;
    width:fit-content;
    margin:0 auto 16px;
    background:rgba(255,255,255,.8);
    border:1px solid rgba(29,111,224,.18);
    color:var(--frost-blue);
    font-size:11.5px;
    font-weight:700;
    letter-spacing:.03em;
    padding:7px 16px;
    border-radius:30px;
    box-shadow:0 4px 12px rgba(29,111,224,.1);
}
.hero-eyebrow i{ color:var(--amber); }

.hero-content h1{
    text-align:center;
    font-size:32px;
    font-weight:800;
    margin-bottom:6px;
    letter-spacing:-.5px;
    background:linear-gradient(120deg, var(--ice-navy) 30%, var(--frost-blue) 65%, var(--ice-cyan) 100%);
    -webkit-background-clip:text;
    background-clip:text;
    -webkit-text-fill-color:transparent;
    color:var(--ice-navy);
}
.hero-content > p{
    text-align:center;
    font-size:14.5px;
    color:var(--slate);
    font-family:var(--font-body);
    margin-bottom:30px;
}

.hero-brand-grid{
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:16px;
    margin-bottom:28px;
}
.hero-brand-card{
    position:relative;
    background:#fff;
    border-radius:var(--radius-md);
    padding:16px 12px 14px;
    min-height:150px;
    box-shadow:var(--shadow-soft);
    border:1px solid rgba(11,44,85,.05);
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    overflow:hidden;
    transition:.3s cubic-bezier(.2,.7,.3,1);
}
.hero-brand-card::before{
    content:'';
    position:absolute;
    top:0; left:0; right:0;
    height:4px;
    background:linear-gradient(90deg,var(--frost-blue),var(--ice-cyan));
    opacity:0;
    transition:.3s;
}
.hero-brand-card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 34px rgba(29,111,224,.22);
    border-color:rgba(29,111,224,.18);
}
.hero-brand-card:hover::before{ opacity:1; }
.hero-brand-card:hover .hero-brand-logo{
    transform:scale(1.06);
}
.hero-ac-img{
    width:100%;
    height:80px;
    object-fit:contain;
    margin-bottom:12px;
}
.hero-brand-logo{
    height:64px;
    max-width:88%;
    object-fit:contain;
    transition:.3s;
}
.hero-brand-logo-wrap{
    display:contents;
}

.hero-divider{
    width:60px;
    height:3px;
    border-radius:10px;
    background:linear-gradient(90deg,var(--frost-blue),var(--ice-cyan));
    margin:0 auto 24px;
}

.hero-features{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:14px;
    flex-wrap:wrap;
}
.hero-feature{
    display:flex;
    align-items:center;
    gap:9px;
    font-size:12.5px;
    font-weight:700;
    color:var(--ice-navy);
    background:rgba(255,255,255,.8);
    border:1px solid rgba(11,44,85,.06);
    padding:8px 16px 8px 8px;
    border-radius:30px;
    white-space:nowrap;
    box-shadow:0 4px 12px rgba(11,44,85,.05);
}
.hero-feature i{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-cyan));
    color:#fff;
    width:26px;
    height:26px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    flex-shrink:0;
}
.hero-cta{
    display:flex;
    align-items:center;
    gap:10px;
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    font-size:13px;
    font-weight:700;
    padding:9px 20px 9px 9px;
    border-radius:30px;
    text-decoration:none;
    white-space:nowrap;
    box-shadow:0 10px 22px rgba(29,111,224,.32);
    transition:.25s;
}
.hero-cta .hero-cta-icon{
    background:rgba(255,255,255,.2);
    width:28px;
    height:28px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    flex-shrink:0;
}
.hero-cta i.fa-arrow-right{
    font-size:11px;
    opacity:.85;
    transition:.25s;
}
.hero-cta:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 14px 26px rgba(29,111,224,.42);
}
.hero-cta:hover i.fa-arrow-right{
    transform:translateX(3px);
}


/* ==================
   TRUST STRIP
================== */
.trust-strip{
    display:flex;
    justify-content:center;
    gap:12px;
    flex-wrap:wrap;
    background:rgba(255,255,255,.7);
    backdrop-filter:blur(14px);
    border:1px solid rgba(255,255,255,.6);
    border-radius:20px;
    padding:16px 14px;
    margin-top:18px;
    box-shadow:var(--shadow-soft);
}
.trust-strip span{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:12px;
    font-weight:700;
    color:var(--ice-navy);
    background:linear-gradient(135deg, rgba(29,111,224,.08), rgba(86,214,232,.10));
    padding:9px 15px;
    border-radius:30px;
}
.trust-strip span i{
    color:var(--frost-blue);
}

/* ==================
   FILTER
================== */
.filter{
    display:flex;
    gap:10px;
    overflow-x:auto;
    padding:26px 2px 8px;
    scrollbar-width:none;
    justify-content:center;
    flex-wrap:wrap;
}
.filter::-webkit-scrollbar{ display:none; }

.filter button, .filter a button{
    border:1.5px solid rgba(29,111,224,.35);
    background:var(--surface);
    color:var(--frost-blue);
    padding:10px 22px;
    border-radius:30px;
    font-weight:700;
    font-size:13px;
    font-family:var(--font-body);
    white-space:nowrap;
    transition:.25s;
}
.filter button:hover{
    border-color:var(--frost-blue);
    background:rgba(29,111,224,.06);
    transform:translateY(-2px);
    box-shadow:0 8px 16px rgba(29,111,224,.15);
}
.filter a:first-child button{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    border-color:transparent;
    box-shadow:0 8px 18px rgba(29,111,224,.3);
}

/* ==================
   PRODUK — "panel" card
================== */
.product-card{
    position:relative;
    background:var(--surface);
    border-radius:var(--radius-md);
    padding:20px 18px 18px;
    height:100%;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    box-shadow:var(--shadow-soft);
    border:1px solid rgba(11,44,85,.04);
    transition:.35s cubic-bezier(.2,.7,.3,1);
    overflow:hidden;
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
.product-card:hover::before{
    opacity:1;
}

.product-img{
    height:160px;
    width:100%;
    object-fit:contain;
    margin-bottom:10px;
}

.brand{
    font-family:var(--font-mono);
    font-size:11px;
    font-weight:600;
    letter-spacing:.06em;
    text-transform:uppercase;
    color:var(--frost-blue);
    margin-bottom:4px;
}
.product-card h5{
    font-family:var(--font-body);
    font-size:13px;
    font-weight:700;
    margin:2px 0;
    color:#1c2b3a;
    line-height:1.35;
    min-height:36px;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}
.product-card p{
    font-size:11px;
    color:var(--slate-soft);
    margin:2px 0 6px;
}
.harga{
    font-family:var(--font-mono);
    font-size:16px;
    font-weight:600;
    color:var(--ice-navy);
    margin:8px 0 12px;
}

.btn{
    border:none;
    border-radius:14px;
    font-weight:700;
    font-size:12px;
    padding:11px 12px;
    transition:.25s;
}
.btn-primary{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
}
.btn-primary:hover{
    filter:brightness(1.08);
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(29,111,224,.3);
}
.btn-pesan{
    background:linear-gradient(135deg,#25d366,var(--leaf));
    color:#fff;
}
.btn-pesan:hover{
    filter:brightness(0.97);
    transform:translateY(-2px);
    color:#fff;
    box-shadow:0 10px 20px rgba(34,197,94,.28);
}
.w-100{ width:100%; }
.mb-2{ margin-bottom:8px !important; }

.rating{
    font-size:13px;
    color:var(--amber);
    text-align:center;
    margin-top:8px;
    letter-spacing:2px;
}

/* ==================
   FOOTER
================== */
footer{
    position:relative;
    background:linear-gradient(160deg,var(--ice-navy) 0%,#061A34 100%);
    color:#fff;
    padding:56px 0 26px;
    margin-top:64px;
    border-radius:32px 32px 0 0;
    overflow:hidden;
}
footer::before{
    content:'';
    position:absolute;
    inset:0;
    background:
        radial-gradient(circle at 10% 20%, rgba(86,214,232,.14), transparent 40%),
        radial-gradient(circle at 90% 80%, rgba(29,111,224,.18), transparent 45%);
    pointer-events:none;
}
footer .container{ position:relative; z-index:1; }

.footer-brand{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:8px;
}
.footer-brand .logo-badge{
    width:38px;
    height:38px;
    border-radius:12px;
    background:linear-gradient(145deg,var(--frost-blue),var(--ice-cyan));
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:17px;
    box-shadow:0 8px 18px rgba(29,111,224,.35);
    flex-shrink:0;
}
.footer-brand span{
    font-family:var(--font-display);
    font-size:19px;
    font-weight:800;
    color:#fff;
}
.footer-tagline{
    font-size:13px;
    color:#9FB6CE;
    max-width:420px;
    line-height:1.7;
    margin-bottom:36px;
}

footer h5{
    font-size:13px;
    font-weight:700;
    margin-bottom:16px;
    color:#fff;
    display:flex;
    align-items:center;
    gap:9px;
    text-transform:uppercase;
    letter-spacing:.07em;
}
footer h5 i{
    color:var(--ice-cyan);
    background:rgba(86,214,232,.14);
    width:32px;
    height:32px;
    aspect-ratio:1/1;
    border-radius:50%;
    display:grid;
    place-items:center;
    font-size:13px;
    line-height:1;
    text-align:center;
    flex-shrink:0;
}
footer h5 i::before{
    display:block;
    width:1em;
    text-align:center;
}
footer p, footer a, footer{
    font-size:13px;
    line-height:1.85;
    color:#C7D9EC;
}
footer a{
    text-decoration:none;
    color:#C7D9EC;
    transition:.2s;
}
footer a:hover{
    color:var(--ice-cyan);
}

.footer-panel{
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.07);
    border-radius:18px;
    padding:22px 20px;
    height:100%;
    transition:.3s;
}
.footer-panel:hover{
    background:rgba(255,255,255,.06);
    border-color:rgba(86,214,232,.2);
}
footer .col-md-4{
    margin-bottom:18px;
}

.footer-chip{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.08);
    color:#DCEBFA !important;
    font-size:12.5px;
    font-weight:600;
    padding:8px 14px;
    border-radius:30px;
    margin:0 6px 8px 0;
    transition:.25s;
}
.footer-chip i{
    color:var(--ice-cyan);
    font-size:12px;
}
.footer-chip:hover{
    background:rgba(86,214,232,.14);
    border-color:rgba(86,214,232,.3);
    color:#fff !important;
    transform:translateY(-2px);
}

.footer-address-link{
    display:flex;
    align-items:flex-start;
    gap:10px;
    color:#DCEBFA;
}
.footer-address-link i{
    color:var(--ice-cyan);
    margin-top:3px;
    flex-shrink:0;
}
.footer-address-link:hover{
    color:#fff;
}

.footer-divider{
    border:none;
    border-top:1px solid rgba(255,255,255,.08);
    margin:8px 0 24px;
}

.footer-bottom{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    text-align:center;
    font-size:12px;
    color:#7691AE;
    margin-top:8px;
    padding-top:20px;
    border-top:1px solid rgba(255,255,255,.08);
}
.footer-bottom i{
    color:var(--danger);
    font-size:11px;
}

/* ==================
   MODERN SWEETALERT (LOGOUT)
================== */
.swal-modern-popup{
    border-radius:24px !important;
    padding:10px 6px 24px !important;
    box-shadow:0 25px 60px rgba(11,44,85,.25) !important;
    font-family:var(--font-body) !important;
}

.swal-modern-title{
    font-family:var(--font-display) !important;
    font-weight:800 !important;
    color:var(--ice-navy) !important;
}

.swal-modern-confirm{
    border-radius:14px !important;
    font-weight:700 !important;
    padding:10px 26px !important;
    box-shadow:0 8px 18px rgba(220,53,69,.3) !important;
    transition:.25s !important;
}
.swal-modern-confirm:hover{
    transform:translateY(-2px);
}

.swal-modern-cancel{
    border-radius:14px !important;
    font-weight:700 !important;
    padding:10px 26px !important;
    transition:.25s !important;
}
.swal-modern-cancel:hover{
    transform:translateY(-2px);
}

.logout-icon-ring{
    width:90px;
    height:90px;
    margin:6px auto 18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#fdeceb,#ffe1df);
    position:relative;
}
.logout-icon-ring i{
    font-size:34px;
    color:var(--danger);
}
.logout-icon-ring::after{
    content:'';
    position:absolute;
    inset:0;
    border-radius:50%;
    border:2px solid var(--danger);
    opacity:.4;
    animation:ringPulse 1.6s ease-out infinite;
}
@keyframes ringPulse{
    0%{ transform:scale(1); opacity:.5; }
    100%{ transform:scale(1.35); opacity:0; }
}

.logout-loader{
    width:56px;
    height:56px;
    margin:10px auto 16px;
    border-radius:50%;
    border:5px solid var(--mist);
    border-top-color:var(--frost-blue);
    animation:spin .8s linear infinite;
}
@keyframes spin{
    to{ transform:rotate(360deg); }
}

.logout-success-ring{
    width:90px;
    height:90px;
    margin:6px auto 18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#e4f9ec,#d3f5e0);
}
.logout-success-ring i{
    font-size:38px;
    color:var(--leaf);
}

/* ==================
   CART ADD ANIMATION
================== */
.cart-success-ring{
    width:92px;
    height:92px;
    margin:6px auto 18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    position:relative;
    box-shadow:0 14px 30px rgba(29,111,224,.35);
    animation:cartPop .5s cubic-bezier(.34,1.56,.64,1);
}
.cart-success-ring i.fa-cart-shopping{
    font-size:36px;
    color:#fff;
}
.cart-success-ring::after{
    content:'';
    position:absolute;
    inset:0;
    border-radius:50%;
    border:2px solid var(--ice-cyan);
    opacity:.5;
    animation:ringPulse 1.6s ease-out infinite;
}
.cart-check-badge{
    position:absolute;
    bottom:-2px;
    right:-2px;
    width:32px;
    height:32px;
    border-radius:50%;
    background:linear-gradient(135deg,#25d366,var(--leaf));
    border:3px solid #fff;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 6px 14px rgba(34,197,94,.4);
    animation:checkPop .4s cubic-bezier(.34,1.56,.64,1) .35s both;
}
.cart-check-badge i{
    font-size:13px;
    color:#fff;
}
@keyframes cartPop{
    0%{ transform:scale(.4); opacity:0; }
    100%{ transform:scale(1); opacity:1; }
}
@keyframes checkPop{
    0%{ transform:scale(0); opacity:0; }
    100%{ transform:scale(1); opacity:1; }
}
.cart-particles{
    position:absolute;
    inset:0;
    pointer-events:none;
}
.cart-particles span{
    position:absolute;
    top:50%; left:50%;
    width:6px; height:6px;
    border-radius:50%;
    background:var(--ice-cyan);
    animation:particleBurst .7s ease-out .1s both;
}
.cart-particles span:nth-child(1){ background:var(--frost-blue); --tx:-38px; --ty:-30px; }
.cart-particles span:nth-child(2){ background:var(--ice-cyan); --tx:40px; --ty:-28px; }
.cart-particles span:nth-child(3){ background:var(--leaf); --tx:-42px; --ty:22px; }
.cart-particles span:nth-child(4){ background:var(--amber); --tx:38px; --ty:30px; }
.cart-particles span:nth-child(5){ background:var(--frost-blue-soft); --tx:0px; --ty:-46px; }
.cart-particles span:nth-child(6){ background:var(--ice-cyan); --tx:0px; --ty:46px; }
@keyframes particleBurst{
    0%{ transform:translate(-50%,-50%) scale(1); opacity:1; }
    100%{ transform:translate(calc(-50% + var(--tx)), calc(-50% + var(--ty))) scale(0); opacity:0; }
}

.cart-subtitle{
    font-size:13px;
    color:var(--slate);
    font-family:var(--font-body);
    line-height:1.5;
}
.cart-subtitle b{
    color:var(--ice-navy);
}
.swal2-timer-progress-bar{
    background:linear-gradient(90deg,var(--frost-blue),var(--ice-cyan)) !important;
}

/* ==================
   LOGIN REQUIRED MODAL
================== */
.login-lock-ring{
    width:90px;
    height:90px;
    margin:6px auto 20px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg, rgba(29,111,224,.12), rgba(86,214,232,.16));
    position:relative;
}
.login-lock-ring i{
    font-size:32px;
    color:var(--frost-blue);
}
.login-lock-ring::after{
    content:'';
    position:absolute;
    inset:0;
    border-radius:50%;
    border:2px solid var(--frost-blue-soft);
    opacity:.45;
    animation:ringPulse 1.8s ease-out infinite;
}
.login-desc{
    font-size:13.5px;
    color:var(--slate);
    line-height:1.65;
    font-family:var(--font-body);
}
.btn-login-row{
    display:flex;
    gap:10px;
    margin-top:22px;
    justify-content:center;
    flex-wrap:wrap;
}
.btn-login-confirm{
    background:linear-gradient(135deg,var(--frost-blue),var(--ice-navy));
    color:#fff;
    border:none;
    padding:11px 26px;
    border-radius:14px;
    font-weight:700;
    font-size:13.5px;
    font-family:var(--font-body);
    box-shadow:0 10px 20px rgba(29,111,224,.3);
    transition:.25s;
}
.btn-login-confirm:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 14px 24px rgba(29,111,224,.4);
}
.btn-login-cancel{
    background:var(--mist);
    color:var(--slate);
    border:none;
    padding:11px 22px;
    border-radius:14px;
    font-weight:700;
    font-size:13.5px;
    font-family:var(--font-body);
    transition:.25s;
}
.btn-login-cancel:hover{
    background:#e2ecf5;
    color:var(--ice-navy);
}

/* ==================
   RESPONSIVE
================== */
@media(max-width:768px){

    .nav-box{
        padding:10px 12px;
        border-radius:16px;
        flex-direction:column;
        align-items:stretch;
        top:6px;
    }

    /* auto-hide navbar on scroll down (mobile only) */
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

    .logo{
        font-size:15px;
        margin-bottom:0;
    }
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
    .menu a.active{
        border-color:transparent;
    }
    .menu a .badge{
        margin-left:auto;
    }
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
    .search-box{
        max-width:none;
        width:100%;
        margin-top:4px;
        padding:4px 4px 4px 18px;
    }
    .search-box input{
        padding:9px 0;
    }

    .hero-banner{
        margin-top:12px;
        border-radius:18px;
        padding:24px 16px 20px;
    }
    .hero-eyebrow{
        font-size:10px;
        padding:6px 12px;
        margin-bottom:12px;
    }
    .hero-content h1{
        font-size:19px;
        margin-bottom:4px;
    }
    .hero-content > p{
        font-size:11.5px;
        margin-bottom:18px;
        padding:0 6px;
    }
    .hero-brand-grid{
        grid-template-columns:repeat(2, 1fr);
        gap:10px;
        margin-bottom:20px;
    }
    .hero-brand-card{
        padding:16px 10px 14px;
        border-radius:16px;
        min-height:100px;
        box-shadow:0 6px 18px rgba(11,44,85,.08);
    }
    .hero-ac-img{
        height:56px;
        margin-bottom:8px;
    }
    .hero-brand-logo-wrap{
        width:64px;
        height:64px;
        border-radius:50%;
        background:linear-gradient(135deg, rgba(29,111,224,.08), rgba(86,214,232,.12));
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:2px;
    }
    .hero-brand-logo{
        height:30px;
        max-width:74%;
    }
    .hero-divider{
        margin-bottom:20px;
    }
    .hero-features{
        flex-direction:column;
        align-items:stretch;
        gap:9px;
    }
    .hero-feature{
        width:100%;
        font-size:12px;
        padding:10px 14px 10px 10px;
        justify-content:flex-start;
    }
    .hero-feature i{
        width:26px;
        height:26px;
        font-size:11px;
    }
    .hero-cta{
        width:100%;
        justify-content:center;
        font-size:12.5px;
        padding:10px 16px 10px 8px;
        margin-top:6px;
    }
    .hero-cta .hero-cta-icon{
        width:24px;
        height:24px;
        font-size:11px;
    }

    .trust-strip{ gap:8px; padding:12px 10px; }
    .trust-strip span{ font-size:10px; padding:7px 11px; }

    .filter button{ padding:8px 16px; font-size:11px; }

    .row.mt-4 .col-12.col-sm-6.col-md-3{
        flex:0 0 calc(50% - 4px) !important;
        max-width:calc(50% - 4px) !important;
    }
    .row.mt-4{ gap:8px 0; }

    .product-card{ padding:14px; border-radius:16px; }
    .product-img{ height:110px; }
    .brand{ font-size:10px; }
    .product-card h5{ font-size:11px; min-height:30px; }
    .product-card p{ display:none; }
    .harga{ font-size:13px; margin:6px 0 10px; }
    .btn{ font-size:10px; padding:9px 8px; }
    .rating{ font-size:11px; }

    footer{ padding:32px 18px 18px; border-radius:24px 24px 0 0; }
    .footer-brand{ justify-content:center; }
    .footer-brand span{ font-size:16px; }
    .footer-tagline{
        text-align:center;
        font-size:12px;
        margin:0 auto 26px;
    }
    footer h5{
        font-size:12.5px;
        justify-content:center;
    }
    footer p, footer a{ font-size:12px; }
    .footer-panel{
        text-align:center;
        padding:18px 16px;
    }
    .footer-address-link{
        justify-content:center;
        text-align:left;
    }
    .footer-chip{
        font-size:11.5px;
        padding:7px 12px;
    }
    footer .col-md-4{
        margin-bottom:14px;
    }
    .footer-bottom{ margin-top:4px; }
}

</style>
</head>

<body>

<div class="container mt-4">

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

                <a href="index.php" class="<?= $page == 'index.php' ? 'active' : '' ?>">
                    <i class="fa fa-house"></i> HOME
                </a>

                <a href="lihat-keranjang.php" class="<?= $page == 'lihat-keranjang.php' ? 'active' : '' ?>">
                    <i class="fa fa-cart-shopping"></i> KERANJANG
                    <span class="badge bg-primary"><?= $jumlahKeranjang ?></span>
                </a>

                <a href="riwayat.php" class="<?= $page == 'riwayat.php' ? 'active' : '' ?>">
                    <i class="fa fa-clock-rotate-left"></i> RIWAYAT PEMESANAN
                </a>

                <?php if (isset($_SESSION['customer'])) { ?>

                    <button onclick="logoutConfirm()" class="logout-btn">
                        <i class="fa fa-right-from-bracket"></i> LOGOUT
                    </button>

                <?php } else { ?>

                    <a href="customer/login.php" class="<?= $page == 'login.php' ? 'active' : '' ?>">
                        <i class="fa fa-right-to-bracket"></i> LOGIN
                    </a>

                <?php } ?>

            </div>

            <form method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari AC..." value="<?= $_GET['search'] ?? '' ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

        </div>

    </div>

    <!-- HERO BANNER -->
    <div class="hero-banner animate__animated animate__fadeIn">

        <div class="hero-blob hero-blob-1"></div>
        <div class="hero-blob hero-blob-2"></div>

        <div class="hero-content">

            <div class="hero-eyebrow"><i class="fa fa-star"></i> Distributor AC Resmi &amp; Terpercaya</div>

            <h1>CV Peppy Tehnik Indonesia</h1>
            <p>Solusi Udara Sejuk &amp; Sehat untuk Rumah &amp; Bisnis Anda</p>

            <div class="hero-brand-grid">

                <a href="daikin.php" class="hero-brand-card">
                    <img src="assets/ac-daikin.png" class="hero-ac-img" alt="AC Daikin" onerror="this.style.display='none'">
                    <div class="hero-brand-logo-wrap">
                        <img src="assets/logo-daikin.png" class="hero-brand-logo" alt="Daikin">
                    </div>
                </a>

                <a href="gree.php" class="hero-brand-card">
                    <img src="assets/ac-gree.png" class="hero-ac-img" alt="AC Gree" onerror="this.style.display='none'">
                    <div class="hero-brand-logo-wrap">
                        <img src="assets/logo-gree.png" class="hero-brand-logo" alt="Gree">
                    </div>
                </a>

                <a href="aqua.php" class="hero-brand-card">
                    <img src="assets/ac-aqua.png" class="hero-ac-img" alt="AC Aqua" onerror="this.style.display='none'">
                    <div class="hero-brand-logo-wrap">
                        <img src="assets/logo-aqua.png" class="hero-brand-logo" alt="Aqua">
                    </div>
                </a>

                <a href="midea.php" class="hero-brand-card">
                    <img src="assets/ac-midea.png" class="hero-ac-img" alt="AC Midea" onerror="this.style.display='none'">
                    <div class="hero-brand-logo-wrap">
                        <img src="assets/logo-midea.png" class="hero-brand-logo" alt="Midea">
                    </div>
                </a>

            </div>

            <div class="hero-divider"></div>

            <div class="hero-features">

                <div class="hero-feature"><i class="fa fa-screwdriver-wrench"></i> Instalasi Profesional</div>
                <div class="hero-feature"><i class="fa fa-arrows-rotate"></i> Perawatan Rutin</div>
                <div class="hero-feature"><i class="fa fa-shield-halved"></i> Garansi Resmi</div>

                <a href="https://wa.me/62xxxxxxxxxx" class="hero-cta">
                    <span class="hero-cta-icon"><i class="fa-brands fa-whatsapp"></i></span>
                    Hubungi Kami Sekarang
                    <i class="fa fa-arrow-right"></i>
                </a>

            </div>

        </div>

    </div>

    <!-- TRUST STRIP -->
    <div class="trust-strip">
        <span><i class="fa fa-certificate"></i> Bergaransi Resmi</span>
        <span><i class="fa fa-shield-halved"></i> Produk Original</span>
        <span><i class="fa fa-truck-fast"></i> Pengiriman Cepat</span>
        <span><i class="fa fa-headset"></i> Siap Dihubungi</span>
    </div>

    <!-- FILTER -->
    <div class="filter" id="produk-anchor">

        <a href="#produk"><button>Semua Merk</button></a>
        <a href="daikin.php"><button>Daikin</button></a>
        <a href="gree.php"><button>Gree</button></a>
        <a href="aqua.php"><button>Aqua</button></a>
        <a href="midea.php"><button>Midea</button></a>

    </div>

    <!-- PRODUK -->
    <div id="produk" class="row mt-4">

        <?php while ($d = mysqli_fetch_array($produk)) { ?>

            <?php
                $hargaBersih = (float) preg_replace('/[^0-9]/', '', $d['harga']);
            ?>

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="product-card animate__animated animate__fadeIn">

                    <img src="upload/produk/<?= $d['foto']; ?>" class="product-img">

                    <div>
                        <div class="brand"><?= $d['brand']; ?></div>
                        <h5><?= $d['nama_produk']; ?></h5>
                        <p><?= $d['tipe']; ?></p>
                        <div class="harga">Rp <?= number_format($hargaBersih, 0, ',', '.'); ?></div>
                    </div>

                    <div>

                        <button onclick="tambahKeranjang(<?= $d['id']; ?>)" class="btn btn-primary w-100 mb-2">
                            🛒 Masukkan Keranjang
                        </button>

                        <?php if (isset($_SESSION['customer'])) { ?>

                            <a href="checkout.php?id=<?= $d['id']; ?>" class="btn btn-pesan w-100">
                                Pesan Sekarang
                            </a>

                        <?php } else { ?>

                            <button onclick="loginAlert()" class="btn btn-pesan w-100">
                                Pesan Sekarang
                            </button>

                        <?php } ?>

                        <div class="rating">★★★★★</div>

                    </div>

                </div>
            </div>

        <?php } ?>

    </div>

</div>

<!-- FOOTER -->
<footer>
    <div class="container">

        <div class="footer-brand">
            <span class="logo-badge"><i class="fa fa-snowflake"></i></span>
            <span>CV Peppy Tehnik Indonesia</span>
        </div>
        <div class="footer-tagline">
            Distributor AC resmi &amp; terpercaya untuk kebutuhan rumah maupun bisnis Anda — Daikin, Gree, Aqua, dan Midea, lengkap dengan instalasi profesional dan garansi resmi.
        </div>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="footer-panel">
                    <h5><i class="fa fa-credit-card"></i> Payment Method</h5>
                    <div class="footer-chip"><i class="fa fa-building-columns"></i> Bank BCA</div><br>
                    <div style="margin-top:10px;">
                        47605517653<br>
                        A/N Darminto
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-panel">
                    <h5><i class="fa fa-phone"></i> Contact Info</h5>
                    <a href="tel:02129313600" class="footer-chip"><i class="fa fa-phone"></i> 021-29313600</a>
                    <a href="tel:02127658075" class="footer-chip"><i class="fa fa-phone"></i> 021-27658075</a>
                    <div style="margin-top:12px; margin-bottom:8px; font-size:11.5px; text-transform:uppercase; letter-spacing:.05em; color:#7691AE;">WhatsApp</div>
                    <a target="_blank" href="https://wa.me/6281382581571" class="footer-chip"><i class="fa-brands fa-whatsapp"></i> 081382581571</a>
                    <a target="_blank" href="https://wa.me/6282114811176" class="footer-chip"><i class="fa-brands fa-whatsapp"></i> 082114811176</a>
                    <a target="_blank" href="https://wa.me/6281210670883" class="footer-chip"><i class="fa-brands fa-whatsapp"></i> 081210670883</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-panel">
                    <h5><i class="fa fa-location-dot"></i> Alamat</h5>
                    <a class="footer-address-link" href="https://www.google.com/maps/search/?api=1&query=Jl+Ciledug+Raya+No+24+Petukangan+Utara+Pesanggrahan+Jakarta+Selatan" target="_blank">
                        <i class="fa fa-map-pin"></i>
                        <span>
                            Jl. Ciledug Raya No.24<br>
                            Petukangan Utara<br>
                            Pesanggrahan<br>
                            Jakarta Selatan
                        </span>
                    </a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            Copyright &copy; CV Peppy Tehnik Indonesia
        </div>
    </div>
</footer>

<script>

function logoutConfirm(){

    Swal.fire({
        title:'Keluar dari Akun?',
        html:`
            <div class="logout-icon-ring">
                <i class="fa fa-right-from-bracket"></i>
            </div>
            <div style="font-size:14px;color:#6c757d;">
                Kamu akan keluar dari sesi akun ini.<br>Yakin ingin melanjutkan?
            </div>
        `,
        showCancelButton:true,
        confirmButtonText:'Ya, Logout',
        cancelButtonText:'Tetap Login',
        confirmButtonColor:'#dc3545',
        cancelButtonColor:'#f1f3f5',
        buttonsStyling:true,
        reverseButtons:true,
        backdrop:`rgba(11,29,50,.55)`,
        customClass:{
            popup:'swal-modern-popup',
            title:'swal-modern-title',
            confirmButton:'swal-modern-confirm',
            cancelButton:'swal-modern-cancel text-dark'
        },
        showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
        hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' }
    }).then((result)=>{

        if(result.isConfirmed){

            Swal.fire({
                title:'Sedang Logout...',
                html:`
                    <div class="logout-loader"></div>
                    <div style="font-size:13px;color:#8a97a3;">Mengakhiri sesi akun kamu</div>
                `,
                showConfirmButton:false,
                allowOutsideClick:false,
                backdrop:`rgba(11,29,50,.55)`,
                customClass:{ popup:'swal-modern-popup' },
                showClass:{ popup:'animate__animated animate__fadeIn animate__faster' }
            });

            setTimeout(()=>{

                Swal.fire({
                    title:'Logout Berhasil',
                    html:`
                        <div class="logout-success-ring">
                            <i class="fa fa-check"></i>
                        </div>
                        <div style="font-size:13px;color:#8a97a3;">Sampai jumpa kembali 👋</div>
                    `,
                    showConfirmButton:false,
                    timer:1400,
                    backdrop:`rgba(11,29,50,.55)`,
                    customClass:{ popup:'swal-modern-popup', title:'swal-modern-title' },
                    showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
                    hideClass:{ popup:'animate__animated animate__fadeOut animate__faster' }
                });

                setTimeout(()=>{
                    window.location.href='customer/logout.php';
                },1400);

            },1400);

        }

    });

}

function loginAlert(){

    Swal.fire({
        html:`
            <div class="login-lock-ring"><i class="fa fa-lock"></i></div>
            <div style="font-family:var(--font-display); font-weight:800; font-size:20px; color:var(--ice-navy); margin-bottom:10px;">
                Login Diperlukan
            </div>
            <div class="login-desc">
                Silahkan login akun terlebih dahulu sebelum<br>melakukan pemesanan AC
            </div>
            <div class="btn-login-row">
                <button id="btnLoginNow" class="btn-login-confirm">Login Sekarang</button>
                <button id="btnLoginLater" class="btn-login-cancel">Nanti Saja</button>
            </div>
        `,
        showConfirmButton:false,
        showCloseButton:false,
        backdrop:'rgba(11,44,85,.35)',
        customClass:{ popup:'swal-modern-popup' },
        showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
        hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' },
        didOpen:()=>{

            document.getElementById('btnLoginNow').addEventListener('click', ()=>{

                Swal.fire({
                    title:'Mengalihkan...',
                    text:'Menuju halaman login customer',
                    timer:1200,
                    showConfirmButton:false,
                    customClass:{ popup:'swal-modern-popup', title:'swal-modern-title' },
                    showClass:{ popup:'animate__animated animate__fadeIn animate__faster' },
                    didOpen:()=>{ Swal.showLoading(); }
                });

                setTimeout(function(){
                    window.location.href='customer/login.php';
                },1200);

            });

            document.getElementById('btnLoginLater').addEventListener('click', ()=>{
                Swal.close();
            });

        }
    });

}

function tambahKeranjang(id){

    Swal.fire({
        title:'Ditambahkan ke Keranjang!',
        html:`
            <div style="position:relative; width:92px; margin:6px auto 18px;">
                <div class="cart-particles">
                    <span></span><span></span><span></span>
                    <span></span><span></span><span></span>
                </div>
                <div class="cart-success-ring">
                    <i class="fa fa-cart-shopping"></i>
                    <div class="cart-check-badge"><i class="fa fa-check"></i></div>
                </div>
            </div>
            <div class="cart-subtitle"><b>Produk berhasil masuk keranjang.</b><br>Lanjutkan belanja atau menuju keranjang.</div>
        `,
        showConfirmButton:false,
        allowOutsideClick:false,
        timer:1800,
        timerProgressBar:true,
        backdrop:'rgba(11,44,85,.35)',
        customClass:{ popup:'swal-modern-popup', title:'swal-modern-title' },
        showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
        hideClass:{ popup:'animate__animated animate__fadeOut animate__faster' }
    });

    setTimeout(()=>{
        window.location.href="keranjang.php?id="+id;
    },1800);

}

/* ==================
   TOGGLE MENU MOBILE (HAMBURGER)
================== */
(function(){

    const menuToggle  = document.getElementById('menuToggle');
    const navCollapse = document.getElementById('navCollapse');

    if (!menuToggle || !navCollapse) return;

    menuToggle.addEventListener('click', function(){
        navCollapse.classList.toggle('open');
        menuToggle.classList.toggle('active');
    });

    // tutup menu otomatis kalau klik salah satu link di dalamnya
    navCollapse.querySelectorAll('a, button').forEach(function(el){
        el.addEventListener('click', function(){
            navCollapse.classList.remove('open');
            menuToggle.classList.remove('active');
        });
    });

})();

/* ==================
   NAVBAR AUTO-HIDE ON SCROLL (MOBILE ONLY)
================== */
(function(){

    const navBox = document.getElementById('navBox');
    const navCollapse = document.getElementById('navCollapse');
    const menuToggle  = document.getElementById('menuToggle');
    let lastScrollY = window.scrollY;
    let ticking = false;

    const MOBILE_BREAKPOINT = 768;
    const SCROLL_THRESHOLD = 8; // minimal jarak scroll agar tidak "gemetar"
    const TOP_OFFSET = 60; // navbar selalu tampil selama masih dekat atas halaman

    function isMobile(){
        return window.innerWidth <= MOBILE_BREAKPOINT;
    }

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
            // dekat paling atas -> selalu tampil
            navBox.classList.remove('nav-hidden');
        } else if (diff > SCROLL_THRESHOLD) {
            // scroll ke bawah -> sembunyikan
            navBox.classList.add('nav-hidden');
            tutupMenu();
        } else if (diff < -SCROLL_THRESHOLD) {
            // scroll ke atas -> tampilkan lagi
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
        if (!isMobile()) {
            navBox.classList.remove('nav-hidden');
        }
    });

})();

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>