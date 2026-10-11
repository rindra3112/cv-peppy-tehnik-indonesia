<?php

// =======================
// KONFIGURASI SESSION
// PENTING: harus PERSIS sama dengan yang di login.php,
// supaya cookie session dikenali di kedua halaman.
// =======================
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    // set 'secure' => true HANYA jika situs sudah pakai HTTPS
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();


// CEK LOGIN ADMIN

if(!isset($_SESSION['admin'])){


header("location:login.php");

exit;


}



?>


<!DOCTYPE html>

<html lang="id">


<head>


<meta charset="UTF-8">
<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>



<title>

Dashboard Admin · CV Peppy Tehnik Indonesia

</title>




<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet"
>



<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
>


<!-- ICON -->
<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
rel="stylesheet"
>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<link
rel="preconnect"
href="https://fonts.googleapis.com"
>
<link
href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
rel="stylesheet"
>




<style>


/* =========================================
   TOKENS
   ========================================= */

:root{

--bg-0:#081722;
--bg-1:#0d2236;
--bg-2:#123048;

--panel:#0f2439;
--panel-2:#0b1d2e;
--panel-border:rgba(148,197,224,.14);

--text-hi:#f2f8fc;
--text-mid:#aec4d7;
--text-low:#6f8ba0;

--danger:#ff5d6c;
--success:#33e08a;

--font-display:'Space Grotesk','Segoe UI',sans-serif;
--font-body:'Plus Jakarta Sans','Segoe UI',sans-serif;

}



*{

box-sizing:border-box;

}



body{


background:

radial-gradient(circle at 10% 6%, rgba(103,232,249,.09), transparent 42%),
radial-gradient(circle at 90% 2%, rgba(255,176,32,.07), transparent 40%),
repeating-linear-gradient(115deg, rgba(255,255,255,.015) 0 2px, transparent 2px 14px),
linear-gradient(165deg, var(--bg-0), var(--bg-1) 55%, #05101a);


background-attachment:fixed;


font-family:var(--font-body);


min-height:100vh;


color:var(--text-hi);


}




.wrapper{


max-width:1180px;


margin:0 auto;


padding:52px 24px 40px;


}



/* =========================================
   HEADER PLATE
   ========================================= */

.header-plate{

border:1px solid var(--panel-border);

background:linear-gradient(160deg, rgba(255,255,255,.035), rgba(255,255,255,0) 60%), var(--panel);

border-radius:24px;

padding:26px 30px;

margin-bottom:30px;

display:flex;

align-items:center;

justify-content:space-between;

gap:20px;

flex-wrap:wrap;

box-shadow:0 20px 45px -22px rgba(0,0,0,.6);

}



.eyebrow{

display:inline-flex;

align-items:center;

gap:8px;

font-family:var(--font-display);

font-size:11.5px;

font-weight:600;

letter-spacing:2.2px;

text-transform:uppercase;

color:var(--text-low);

border:1px solid var(--panel-border);

padding:6px 12px;

border-radius:999px;

margin-bottom:14px;

}



.eyebrow .dot{


width:7px;

height:7px;

border-radius:50%;

background:var(--success);

box-shadow:0 0 0 4px rgba(51,224,138,.18);

animation:pulse-dot 2.2s ease-in-out infinite;


}



@keyframes pulse-dot{

0%,100%{ box-shadow:0 0 0 4px rgba(51,224,138,.18); }

50%{ box-shadow:0 0 0 7px rgba(51,224,138,.08); }

}



.title{


font-family:var(--font-display);

font-size:32px;

font-weight:700;

color:#ffffff;

letter-spacing:-.5px;

margin-bottom:6px;


}



.subtitle{


font-size:15px;

color:var(--text-mid);


}



.subtitle b{


color:#fff;

font-weight:700;


}



.role-chip{


display:inline-block;

margin-left:8px;

padding:3px 11px;

border-radius:999px;

font-size:11.5px;

font-weight:700;

letter-spacing:.4px;

text-transform:uppercase;

background:rgba(233,199,138,.14);

color:#e9c78a;

border:1px solid rgba(233,199,138,.3);

vertical-align:middle;


}



.logout{


border:1px solid rgba(255,90,95,.35);


background:rgba(255,60,60,.08);


color:#ff9a9a;


font-weight:700;


font-size:14.5px;


padding:12px 26px;


border-radius:14px;


transition:.25s ease;


display:inline-flex;

align-items:center;

gap:8px;


}



.logout:hover{


background:#e0303a;


color:#fff;


border-color:#e0303a;


}





/* =========================================
   DEVICE PANELS (CONTROL-PANEL CARDS)
   ========================================= */


.panel-grid{

display:grid;

grid-template-columns:repeat(3, 1fr);

gap:22px;

}



.device-panel{


position:relative;


min-height:280px;


border-radius:24px;


padding:0;


color:var(--text-hi);


display:flex;


flex-direction:column;


justify-content:space-between;


overflow:hidden;


border:1px solid var(--panel-border);


background:linear-gradient(165deg, rgba(255,255,255,.03), rgba(255,255,255,0) 45%), var(--panel);


box-shadow:0 20px 40px -18px rgba(0,0,0,.55);


transition:transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s ease, border-color .35s ease;


}



.device-panel:hover{


transform:translateY(-6px);


border-color:rgba(255,255,255,.28);


box-shadow:0 30px 55px -18px rgba(0,0,0,.65);


}



.panel-topline{


height:3px;

width:100%;

background:linear-gradient(90deg, var(--accent), var(--accent-2));


}



.panel-status{


position:absolute;

top:20px;

right:22px;

display:flex;

align-items:center;

gap:6px;

font-size:11px;

font-weight:700;

letter-spacing:.6px;

text-transform:uppercase;

color:var(--text-low);


}



.panel-status .led{


width:6px;

height:6px;

border-radius:50%;

background:var(--accent);

box-shadow:0 0 8px var(--accent);


}



.panel-content{


padding:28px 28px 24px;


}



/* --- rotary knob icon badge --- */


.knob{


width:66px;

height:66px;

border-radius:50%;

padding:5px;

margin-bottom:18px;

background:repeating-conic-gradient(var(--accent) 0deg 4deg, transparent 4deg 18deg);

position:relative;


}



.knob-inner{


width:100%;

height:100%;

border-radius:50%;

background:var(--panel-2);

display:flex;

align-items:center;

justify-content:center;

font-size:24px;

color:var(--accent);

box-shadow:inset 0 2px 8px rgba(0,0,0,.5);

position:relative;


}



.knob-pointer{


position:absolute;

top:2px;

left:50%;

width:2px;

height:12px;

background:var(--accent);

transform-origin:50% 33px;

border-radius:2px;


}



.device-panel h3{


font-family:var(--font-display);

font-weight:700;


font-size:21px;


margin-bottom:8px;


letter-spacing:-.2px;


}



.device-panel p{


font-size:14px;


color:var(--text-mid);


line-height:1.55;


margin-bottom:0;


}



.panel-action{


margin:0 28px 26px;


background:rgba(255,255,255,.96);


color:#0f1b26;


border:none;


font-weight:700;


font-size:14px;


padding:13px;


border-radius:14px;


transition:.25s ease;


display:flex;


align-items:center;


justify-content:center;


gap:8px;


}



.panel-action:hover{


transform:translateY(-2px);


background:#fff;


box-shadow:0 10px 24px -8px rgba(0,0,0,.4);


color:#111;


}


.panel-action:after{


content:"\2192";


transition:transform .25s ease;


}


.panel-action:hover:after{


transform:translateX(4px);


}



/* accent tokens per panel */

.device-panel--produk{ --accent:#ffb020; --accent-2:#ff7a3d; }

.device-panel--bayar{ --accent:#67e8f9; --accent-2:#22a6d9; }

.device-panel--vvip{ --accent:#e9c78a; --accent-2:#b5893c; }



/* =========================================
   FOOTER PLATE
   ========================================= */


.footer-plate{


margin-top:34px;

text-align:center;

font-size:12.5px;

color:var(--text-low);

letter-spacing:.3px;


}




/* ===================
    RESPONSIVE MOBILE
====================*/


@media(max-width:900px){


.panel-grid{

grid-template-columns:1fr;

}


}



@media(max-width:768px){




.wrapper{


padding:28px 16px 30px;


}



.header-plate{


flex-direction:column;


align-items:flex-start;


padding:22px 20px;


}




.title{


font-size:24px;


}





.subtitle{


font-size:13.5px;


}



.logout{


width:100%;


justify-content:center;


}






.device-panel{


min-height:auto;


}



.panel-content{

padding:24px 22px 20px;

}



.panel-action{

margin:0 22px 22px;

}



.knob{


width:56px;

height:56px;


}



.knob-inner{


font-size:20px;


}



.device-panel h3{


font-size:19px;


}



.device-panel p{


font-size:13.5px;


}







}




@media(max-width:400px){


.title{


font-size:21px;


}


}



/* =========================================
   MODERN LOGOUT ANIMATION (SweetAlert overrides)
   ========================================= */

.swal-dark-popup{
    border-radius:26px !important;
    padding:34px 28px 28px !important;
    background:rgba(13,34,54,.9) !important;
    backdrop-filter:blur(20px) saturate(180%);
    -webkit-backdrop-filter:blur(20px) saturate(180%);
    box-shadow:0 40px 90px -20px rgba(0,0,0,.6) !important;
    border:1px solid rgba(255,255,255,.12);
}

.swal-dark-backdrop{
    background:rgba(5,15,28,.65) !important;
    backdrop-filter:blur(4px);
    -webkit-backdrop-filter:blur(4px);
}

.swal-dark-title{
    font-family:var(--font-display) !important;
    font-weight:700 !important;
    color:#ffffff !important;
    letter-spacing:-.2px;
}

.swal-dark-html{
    font-family:var(--font-body) !important;
    color:#b9cbdc !important;
    line-height:1.6;
}

.swal-confirm-danger{
    border-radius:12px !important;
    padding:10px 26px !important;
    font-weight:700 !important;
    box-shadow:0 12px 24px -8px rgba(224,48,58,.5) !important;
}

.swal-cancel-ghost{
    border-radius:12px !important;
    padding:10px 26px !important;
    font-weight:700 !important;
    background:rgba(255,255,255,.08) !important;
    box-shadow:none !important;
}

/* question orb (confirm dialog) */
.ask-orb-wrap{
    width:84px;
    height:84px;
    margin:0 auto 18px;
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
}

.ask-orb-pulse{
    position:absolute;
    inset:0;
    border-radius:50%;
    background:rgba(224,48,58,.25);
    animation:ask-pulse 1.8s ease-out infinite;
}

.ask-orb-core{
    position:relative;
    width:68px;
    height:68px;
    border-radius:50%;
    background:linear-gradient(135deg,#ff6b6b,#e0303a);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 14px 26px -8px rgba(224,48,58,.55);
    animation:pop-in .45s cubic-bezier(.34,1.56,.64,1) both;
}

.ask-orb-core i{
    font-size:30px;
    color:#fff;
}

@keyframes ask-pulse{
    0%{ transform:scale(.85); opacity:.7; }
    70%{ transform:scale(1.35); opacity:0; }
    100%{ transform:scale(1.35); opacity:0; }
}

@keyframes pop-in{
    0%{ transform:scale(0); opacity:0; }
    100%{ transform:scale(1); opacity:1; }
}

/* success orb */
.success-orb-wrap{
    width:88px;
    height:88px;
    margin:0 auto 18px;
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
}

.success-orb-ring{
    position:absolute;
    inset:0;
    border-radius:50%;
    background:conic-gradient(from 0deg, #33e08a, #22a6d9, #33e08a);
    animation:spin-ring 1.4s linear infinite;
    -webkit-mask:radial-gradient(farthest-side, transparent calc(100% - 5px), #000 calc(100% - 5px));
    mask:radial-gradient(farthest-side, transparent calc(100% - 5px), #000 calc(100% - 5px));
}

.success-orb-core{
    position:absolute;
    inset:8px;
    border-radius:50%;
    background:linear-gradient(135deg,#33e08a,#00b96b);
    box-shadow:0 12px 24px -6px rgba(51,224,138,.5);
    display:flex;
    align-items:center;
    justify-content:center;
    animation:pop-in .5s cubic-bezier(.34,1.56,.64,1) .25s both;
}

.success-orb-core svg{
    width:38px;
    height:38px;
}

.success-orb-core svg path{
    stroke:#fff;
    stroke-width:4;
    fill:none;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:48;
    stroke-dashoffset:48;
    animation:draw-check .45s ease-out .65s forwards;
}

@keyframes spin-ring{
    to{ transform:rotate(360deg); }
}

@keyframes draw-check{
    to{ stroke-dashoffset:0; }
}

.goodbye-msg{
    display:block;
    font-size:14px;
    color:#b9cbdc;
    margin-top:2px;
}


</style>


</head>






<body>





<div class="wrapper">



<!-- HEADER -->

<div class="header-plate animate__animated animate__fadeIn">


<div>

<div class="eyebrow"><span class="dot"></span> Panel Kontrol &middot; CV Peppy Tehnik Indonesia</div>

<h1 class="title">Dashboard Admin</h1>

<p class="subtitle">

Selamat datang,

<b>

<?= $_SESSION['admin']['username']; ?>

</b>

<span class="role-chip"><?= $_SESSION['admin']['role']; ?></span>

</p>

</div>


<button

onclick="logoutAdmin()"

class="logout"

>

<i class="bi bi-box-arrow-right"></i> Logout

</button>


</div>



<!-- PANEL GRID -->

<div class="panel-grid">



<!-- PRODUK -->

<div class="device-panel device-panel--produk animate__animated animate__fadeInUp">

<div class="panel-topline"></div>

<div class="panel-status"><span class="led"></span> Aktif</div>

<div class="panel-content">

<div class="knob">
<div class="knob-inner">
<div class="knob-pointer" style="transform:translateX(-50%) rotate(-35deg);"></div>
<i class="bi bi-snow2"></i>
</div>
</div>

<h3>Produk AC</h3>

<p>Kelola produk Daikin, Gree, Aqua dan Midea</p>

</div>

<a href="produk.php" class="panel-action">Kelola Produk</a>

</div>



<!-- PEMBAYARAN -->

<div class="device-panel device-panel--bayar animate__animated animate__fadeInUp" style="animation-delay:.1s">

<div class="panel-topline"></div>

<div class="panel-status"><span class="led"></span> Aktif</div>

<div class="panel-content">

<div class="knob">
<div class="knob-inner">
<div class="knob-pointer" style="transform:translateX(-50%) rotate(15deg);"></div>
<i class="bi bi-credit-card-2-front"></i>
</div>
</div>

<h3>Pembayaran</h3>

<p>Konfirmasi bukti transfer customer</p>

</div>

<a href="pesanan.php" class="panel-action">Verifikasi Pembayaran</a>

</div>



<?php if($_SESSION['admin']['role']=="VVIP"){ ?>


<!-- ADMIN VVIP -->

<div class="device-panel device-panel--vvip animate__animated animate__fadeInUp" style="animation-delay:.2s">

<div class="panel-topline"></div>

<div class="panel-status"><span class="led"></span> Aktif</div>

<div class="panel-content">

<div class="knob">
<div class="knob-inner">
<div class="knob-pointer" style="transform:translateX(-50%) rotate(60deg);"></div>
<i class="bi bi-shield-lock-fill"></i>
</div>
</div>

<h3>Admin VVIP</h3>

<p>Tambah dan hapus akun administrator</p>

</div>

<a href="kelola_admin.php" class="panel-action">Kelola Admin</a>

</div>


<?php } ?>



</div>



<div class="footer-plate">

&copy; <?= date('Y'); ?> CV Peppy Tehnik Indonesia &middot; Sistem Admin Internal

</div>



</div>










<script>



function logoutAdmin(){





Swal.fire({



title: 'Keluar Dashboard?',



html: `

<div class="ask-orb-wrap">
  <div class="ask-orb-pulse"></div>
  <div class="ask-orb-core">
    <i class="bi bi-box-arrow-right"></i>
  </div>
</div>

<span class="goodbye-msg">Kamu akan keluar dari sesi admin ini</span>

`,



showCancelButton: true,



confirmButtonText: 'Ya, Logout',



cancelButtonText: 'Batal',



background: 'transparent',



customClass: {
    popup: 'swal-dark-popup',
    backdrop: 'swal-dark-backdrop',
    title: 'swal-dark-title',
    htmlContainer: 'swal-dark-html',
    confirmButton: 'swal-confirm-danger',
    cancelButton: 'swal-cancel-ghost'
},



showClass: {
    popup: 'animate__animated animate__zoomIn animate__faster'
},



hideClass: {
    popup: 'animate__animated animate__zoomOut animate__faster'
}



}).then((result)=>{



if(result.isConfirmed){



Swal.fire({



title: 'Logout Berhasil',



html: `

<div class="success-orb-wrap">
  <div class="success-orb-ring"></div>
  <div class="success-orb-core">
    <svg viewBox="0 0 24 24">
      <path d="M4 12.5l5 5L20 6.5" />
    </svg>
  </div>
</div>

<span class="goodbye-msg">Sampai jumpa lagi, admin 👋</span>

`,



showConfirmButton: false,



timer: 1600,



timerProgressBar: true,



allowOutsideClick: false,



background: 'transparent',



customClass: {
    popup: 'swal-dark-popup',
    backdrop: 'swal-dark-backdrop',
    title: 'swal-dark-title',
    htmlContainer: 'swal-dark-html'
},



showClass: {
    popup: 'animate__animated animate__fadeInUp animate__faster'
},



hideClass: {
    popup: 'animate__animated animate__fadeOutUp animate__faster'
}



});



setTimeout(()=>{



window.location.href='logout.php';



},1600);



}



});



}




</script>






</body>


</html>