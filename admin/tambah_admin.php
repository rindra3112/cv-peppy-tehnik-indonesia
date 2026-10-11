<?php

session_start();

include "../config/database.php";



// ======================
// CEK LOGIN
// ======================

if(!isset($_SESSION['admin'])){


header("location:login.php");

exit;


}






// ======================
// HANYA VVIP
// ======================

if($_SESSION['admin']['role']!="VVIP"){


header("location:dashboard.php");

exit;


}






$status="";



// ======================
// SIMPAN ADMIN
// ======================

if(isset($_POST['simpan'])){



$username=mysqli_real_escape_string(
$conn,
$_POST['username']
);



$password=password_hash(

$_POST['password'],

PASSWORD_DEFAULT

);



$role=$_POST['role'];







$cek=mysqli_query(

$conn,

"SELECT * FROM admin WHERE username='$username'"

);







if(mysqli_num_rows($cek)>0){



$status="duplikat";



}else{








mysqli_query(

$conn,


"INSERT INTO admin

(

username,

password,

role

)

VALUES

(

'$username',

'$password',

'$role'

)"

);







$status="berhasil";







}



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

Tambah Admin

</title>







<!-- BOOTSTRAP -->

<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"

rel="stylesheet"

>








<!-- ICON -->

<link

href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"

rel="stylesheet"

>








<!-- ANIMATE -->

<link

rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"

>



<!-- FONT -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">



<!-- SWEET ALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>









<style>

/* =========================================
   TOKENS — matches dashboard.php / kelola_admin.php / login.php
   ========================================= */

:root{

--bg-0:#081722;
--bg-1:#0d2236;

--panel:#0f2439;
--panel-2:#0b1d2e;
--panel-border:rgba(148,197,224,.14);

--text-hi:#f2f8fc;
--text-mid:#aec4d7;
--text-low:#6f8ba0;

--amber:#ffb020;
--amber-deep:#ffcf7a;
--ice:#67e8f9;
--ice-deep:#22a6d9;
--danger:#ff5d6c;
--success:#33e08a;

--font-display:'Space Grotesk','Segoe UI',sans-serif;
--font-body:'Plus Jakarta Sans','Segoe UI',sans-serif;
--font-mono:'JetBrains Mono', monospace;

}

*{ box-sizing:border-box; }

body{

background:
radial-gradient(circle at 12% 8%, rgba(103,232,249,.08), transparent 42%),
radial-gradient(circle at 90% 4%, rgba(255,176,32,.08), transparent 40%),
repeating-linear-gradient(115deg, rgba(255,255,255,.015) 0 2px, transparent 2px 14px),
linear-gradient(165deg, var(--bg-0), var(--bg-1) 55%, #05101a);

min-height:100vh;

display:flex;
justify-content:center;
align-items:center;

font-family:var(--font-body);
color:var(--text-hi);

padding:20px;

}


/* ===== CARD ===== */

.card-admin{

width:100%;
max-width:440px;

background:linear-gradient(160deg, rgba(255,255,255,.035), rgba(255,255,255,0) 55%), var(--panel);
padding:38px 34px 32px;
border-radius:26px;

box-shadow:0 30px 70px -20px rgba(0,0,0,.6);
border:1px solid var(--panel-border);

position:relative;
overflow:hidden;

}

/* corner brackets — amber marks the VVIP zone, same signature as kelola_admin.php */
.card-admin::before,
.card-admin::after,
.corner-tr,
.corner-bl{
  content:"";
  position:absolute;
  width:20px;
  height:20px;
  border:2px solid var(--amber);
  opacity:.85;
  z-index:1;
}
.card-admin::before{ top:-1px; left:-1px; border-right:none; border-bottom:none; border-top-left-radius:8px; }
.card-admin::after{ bottom:-1px; right:-1px; border-left:none; border-top:none; border-bottom-right-radius:8px; }
.corner-tr{ top:-1px; right:-1px; border-left:none; border-bottom:none; border-top-right-radius:8px; }
.corner-bl{ bottom:-1px; left:-1px; border-right:none; border-top:none; border-bottom-left-radius:8px; }

.card-admin > *{ position:relative; z-index:1; }


.eyebrow{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-family:var(--font-mono);
  font-size:11px;
  font-weight:600;
  letter-spacing:.14em;
  text-transform:uppercase;
  color:var(--amber-deep);
  background:rgba(255,176,32,.1);
  border:1px solid rgba(255,176,32,.25);
  padding:6px 12px;
  border-radius:999px;
  margin-bottom:20px;
}

.eyebrow .dot{
  width:6px; height:6px; border-radius:50%;
  background:var(--amber);
  box-shadow:0 0 0 4px rgba(255,176,32,.18);
}


/* ===== BRAND ===== */

.brand-row{
    display:flex;
    align-items:center;
    gap:16px;
    margin-bottom:24px;
}

.logo{

width:60px;
height:60px;
flex-shrink:0;

padding:4px;
border-radius:50%;

background:repeating-conic-gradient(var(--amber) 0deg 4deg, transparent 4deg 18deg);

}

.logo-inner{

width:100%;
height:100%;
border-radius:50%;

background:var(--panel-2);
color:var(--amber);

display:flex;
justify-content:center;
align-items:center;
font-size:24px;

box-shadow:inset 0 2px 8px rgba(0,0,0,.5);

}

.brand-text h3{

font-family:var(--font-display);
font-size:20px;
font-weight:700;
color:#ffffff;
margin:0 0 2px;
line-height:1.2;
letter-spacing:-.2px;

}

.brand-text p{
    margin:0;
    font-size:12.5px;
    color:var(--text-mid);
    font-weight:600;
}


/* ===== FORM ===== */

label{
    font-size:12.5px;
    font-weight:700;
    letter-spacing:.02em;
    color:var(--text-mid);
    margin-bottom:7px;
    display:block;
}

.field-group{
    margin-bottom:18px;
}

.input-group{
    border:1.5px solid var(--panel-border);
    border-radius:14px;
    overflow:hidden;
    background:var(--panel-2);
    transition:.2s;
}

.input-group:focus-within{
    border-color:var(--ice);
    box-shadow:0 0 0 4px rgba(103,232,249,.12);
}

.input-group-text{
    background:transparent;
    border:none;
    color:var(--text-low);
    width:44px;
    justify-content:center;
    font-size:15px;
}

.form-control,
.form-select{

height:48px;
border:none;
background:transparent;
font-size:14.5px;
color:var(--text-hi);

box-shadow:none !important;

}

.form-control:focus{
    background:transparent;
    color:var(--text-hi);
}

.form-control::placeholder{
    color:var(--text-low);
}

.toggle-pass{
    background:transparent;
    border:none;
    color:var(--text-low);
    width:44px;
    cursor:pointer;
}
.toggle-pass:hover{ color:var(--ice); }


/* ===== ROLE SEGMENTED ===== */

.role-segment{
    display:flex;
    gap:8px;
    background:var(--panel-2);
    border:1px solid var(--panel-border);
    padding:5px;
    border-radius:14px;
}

.role-option{
    flex:1;
    position:relative;
}

.role-option input{
    position:absolute;
    opacity:0;
    inset:0;
    cursor:pointer;
    margin:0;
}

.role-option label{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    margin:0;
    padding:11px 8px;
    border-radius:10px;
    font-size:13px;
    font-weight:700;
    color:var(--text-low);
    cursor:pointer;
    transition:.2s;
}

.role-option:nth-child(1) input:checked + label{
    background:rgba(103,232,249,.12);
    color:#8fe9fb;
    box-shadow:inset 0 0 0 1px rgba(103,232,249,.3);
}

.role-option:nth-child(2) input:checked + label{
    background:rgba(255,176,32,.12);
    color:var(--amber-deep);
    box-shadow:inset 0 0 0 1px rgba(255,176,32,.35);
}

.role-hint{
    font-size:11.5px;
    color:var(--text-low);
    margin-top:9px;
    display:flex;
    align-items:center;
    gap:5px;
}


/* ===== BUTTONS ===== */

.btn{

height:50px;
border-radius:14px;
font-weight:700;
font-size:14.5px;
border:none;
transition:.2s;

}

.btn-save{

background:linear-gradient(135deg, var(--ice), var(--ice-deep));
color:#03222d;
box-shadow:0 14px 26px -8px rgba(34,166,217,.5);

display:flex;
align-items:center;
justify-content:center;
gap:8px;

margin-top:6px;

}

.btn-save:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 30px -8px rgba(34,166,217,.6);
    color:#03222d;
}

.btn-back{

background:transparent;
color:var(--text-mid);
border:1px solid var(--panel-border);
text-decoration:none;

display:flex;
align-items:center;
justify-content:center;
gap:6px;

margin-top:10px;

}

.btn-back:hover{
    background:rgba(255,255,255,.03);
    border-color:rgba(255,255,255,.3);
    color:#fff;
}

hr{
    border-color:var(--panel-border);
    margin:0 0 22px;
}


/* MOBILE */

@media(max-width:480px){

body{ padding:14px; align-items:flex-start; padding-top:34px; }

.card-admin{

padding:28px 22px 24px;
border-radius:22px;

}

.logo{ width:52px; height:52px; }

.logo-inner{ font-size:21px; }

.brand-text h3{ font-size:18px; }

.form-control,
.form-select{ height:46px; font-size:14px; }

.btn{ height:46px; }

}


/* =========================================
   MODERN ALERT ANIMATIONS (SweetAlert overrides — dark)
   ========================================= */

.swal-modern-popup{
    border-radius:26px !important;
    padding:34px 28px 28px !important;
    background:rgba(13,34,54,.9) !important;
    backdrop-filter:blur(20px) saturate(180%);
    -webkit-backdrop-filter:blur(20px) saturate(180%);
    box-shadow:0 40px 90px -20px rgba(0,0,0,.6) !important;
    border:1px solid rgba(255,255,255,.12);
}

.swal-modern-backdrop{
    background:rgba(5,15,28,.65) !important;
    backdrop-filter:blur(4px);
    -webkit-backdrop-filter:blur(4px);
}

.swal-modern-title{
    font-family:var(--font-display) !important;
    font-weight:700 !important;
    color:#ffffff !important;
    letter-spacing:-.2px;
}

.swal-modern-html{
    font-family:var(--font-body) !important;
    color:var(--text-mid) !important;
    line-height:1.6;
}

.swal-modern-confirm{
    border-radius:12px !important;
    padding:10px 26px !important;
    font-weight:700 !important;
    box-shadow:0 12px 24px -8px rgba(34,166,217,.5) !important;
}

/* success ring + check draw */
.success-orb-wrap{
    width:92px;
    height:92px;
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
    animation:pop-in .5s cubic-bezier(.34,1.56,.64,1) .3s both;
}

.success-orb-core svg{
    width:40px;
    height:40px;
}

.success-orb-core svg path{
    stroke:#fff;
    stroke-width:4;
    fill:none;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:48;
    stroke-dashoffset:48;
    animation:draw-check .45s ease-out .75s forwards;
}

@keyframes spin-ring{
    to{ transform:rotate(360deg); }
}

@keyframes pop-in{
    0%{ transform:scale(0); opacity:0; }
    100%{ transform:scale(1); opacity:1; }
}

@keyframes draw-check{
    to{ stroke-dashoffset:0; }
}

.swal-desc{
    font-family:var(--font-body);
    color:var(--text-mid);
    font-size:14.5px;
    margin:4px 0 0;
    line-height:1.6;
}

.swal-username{
    font-family:var(--font-mono);
    font-weight:600;
    color:#eaf9fd;
    background:rgba(255,255,255,.08);
    padding:3px 10px;
    border-radius:6px;
}

/* warning orb — pulsing amber, distinct from red danger error */
.warn-orb-wrap{
    width:86px;
    height:86px;
    margin:0 auto 16px;
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
}

.warn-orb-pulse{
    position:absolute;
    inset:0;
    border-radius:50%;
    background:rgba(255,176,32,.25);
    animation:warn-pulse 1.8s ease-out infinite;
}

.warn-orb-core{
    position:relative;
    width:70px;
    height:70px;
    border-radius:50%;
    background:linear-gradient(135deg,#ffcf7a,#ffb020);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 14px 26px -8px rgba(255,176,32,.5);
    animation:shake-pop .55s cubic-bezier(.36,.07,.19,.97);
}

.warn-orb-core i{
    font-size:30px;
    color:#3a2400;
}

@keyframes warn-pulse{
    0%{ transform:scale(.85); opacity:.7; }
    70%{ transform:scale(1.35); opacity:0; }
    100%{ transform:scale(1.35); opacity:0; }
}

@keyframes shake-pop{
    0%{ transform:scale(0) rotate(0deg); }
    60%{ transform:scale(1.08) rotate(6deg); }
    80%{ transform:scale(.97) rotate(-4deg); }
    100%{ transform:scale(1) rotate(0deg); }
}

</style>



</head>









<body>









<div class="card-admin animate__animated animate__fadeInUp">


<div class="corner-tr"></div>
<div class="corner-bl"></div>


<span class="eyebrow"><span class="dot"></span> Akses VVIP</span>


<div class="brand-row">

<div class="logo">
<div class="logo-inner">
<i class="bi bi-person-add"></i>
</div>
</div>

<div class="brand-text">
<h3>Tambah Admin</h3>
<p>CV Peppy Tehnik Indonesia</p>
</div>

</div>


<hr>


<form method="POST">


<div class="field-group">

<label>Username</label>

<div class="input-group">

<span class="input-group-text">
<i class="bi bi-person"></i>
</span>

<input
type="text"
name="username"
class="form-control"
placeholder="Masukkan username"
required
>

</div>

</div>


<div class="field-group">

<label>Password</label>

<div class="input-group">

<span class="input-group-text">
<i class="bi bi-lock"></i>
</span>

<input
type="password"
name="password"
id="passwordInput"
class="form-control"
placeholder="Masukkan password"
required
>

<button type="button" class="toggle-pass" onclick="togglePassword()">
<i class="bi bi-eye" id="toggleIcon"></i>
</button>

</div>

</div>


<div class="field-group">

<label>Role Admin</label>

<div class="role-segment">

<div class="role-option">
<input type="radio" name="role" id="roleAdmin" value="ADMIN" checked>
<label for="roleAdmin"><i class="bi bi-person-badge"></i> ADMIN</label>
</div>

<div class="role-option">
<input type="radio" name="role" id="roleVvip" value="VVIP">
<label for="roleVvip"><i class="bi bi-star-fill"></i> VVIP</label>
</div>

</div>

<div class="role-hint">
<i class="bi bi-info-circle"></i> VVIP memiliki akses penuh ke semua fitur admin
</div>

</div>


<button
name="simpan"
class="btn btn-save w-100"
>

<i class="bi bi-plus-circle"></i>
Tambah Admin

</button>


</form>


<a
href="kelola_admin.php"
class="btn btn-back w-100"
>

<i class="bi bi-arrow-left"></i>
Kembali

</a>


</div>











<!-- ===================
ALERT BERHASIL
=================== -->


<?php if($status=="berhasil"){ ?>


<script>



Swal.fire({



title:'Admin Dibuat',



html:`

<div class="success-orb-wrap">
  <div class="success-orb-ring"></div>
  <div class="success-orb-core">
    <svg viewBox="0 0 24 24">
      <path d="M4 12.5l5 5L20 6.5" />
    </svg>
  </div>
</div>

<p class="swal-desc">Akun administrator berhasil ditambahkan</p>

`,



showConfirmButton:false,



timer:1800,



timerProgressBar:true,



allowOutsideClick:false,



background:'transparent',



customClass:{
    popup:'swal-modern-popup',
    backdrop:'swal-modern-backdrop',
    title:'swal-modern-title',
    htmlContainer:'swal-modern-html'
},



showClass:{


popup:'animate__animated animate__fadeInUp animate__faster'


},



hideClass:{

popup:'animate__animated animate__fadeOutUp animate__faster'

}



});







setTimeout(()=>{


window.location.href='kelola_admin.php';


},1800);




</script>


<?php } ?>









<!-- ===================
ALERT DUPLIKAT
=================== -->


<?php if($status=="duplikat"){ ?>



<script>



Swal.fire({




title:'Username Digunakan',




html:`

<div class="warn-orb-wrap">
  <div class="warn-orb-pulse"></div>
  <div class="warn-orb-core">
    <i class="bi bi-exclamation-lg"></i>
  </div>
</div>

<p class="swal-desc">Silahkan gunakan username lain</p>

`,




showConfirmButton:true,




confirmButtonText:'Coba Lagi',




confirmButtonColor:'#22a6d9',




background:'transparent',




customClass:{
    popup:'swal-modern-popup',
    backdrop:'swal-modern-backdrop',
    title:'swal-modern-title',
    htmlContainer:'swal-modern-html',
    confirmButton:'swal-modern-confirm'
},




showClass:{



popup:'animate__animated animate__headShake animate__faster'



},



hideClass:{

popup:'animate__animated animate__fadeOut animate__faster'

}



});



</script>



<?php } ?>


<script>
function togglePassword(){
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('toggleIcon');
    if(input.type === 'password'){
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }else{
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>


</body>


</html>