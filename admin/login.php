<?php

session_start();

include "../config/database.php";



// =======================
// PROSES LOGIN ADMIN
// =======================

if(isset($_POST['login'])){


$username=mysqli_real_escape_string(
$conn,
$_POST['username']
);


$password=$_POST['password'];



// AMBIL DATA ADMIN

$query=mysqli_query(
$conn,
"SELECT * FROM admin WHERE username='$username'"
);




if(mysqli_num_rows($query)>0){


$data=mysqli_fetch_assoc($query);



// CEK PASSWORD

if(

password_verify($password,$data['password'])

||

$password==$data['password']

){



$_SESSION['admin']=[

'id'=>$data['id'],

'username'=>$data['username'],

'role'=>$data['role']

];



$login_success=true;



}else{


$login_error="Password salah";


}




}else{


$login_error="Username tidak ditemukan";


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

Login Admin - Peppy Tehnik Indonesia

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
   TOKENS — matches dashboard.php / kelola_admin.php
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

min-height:100vh;
margin:0;

background:
radial-gradient(circle at 12% 10%, rgba(103,232,249,.1), transparent 42%),
radial-gradient(circle at 88% 6%, rgba(255,176,32,.06), transparent 40%),
repeating-linear-gradient(115deg, rgba(255,255,255,.015) 0 2px, transparent 2px 14px),
linear-gradient(165deg, var(--bg-0), var(--bg-1) 55%, #05101a);

display:flex;
justify-content:center;
align-items:center;

font-family:var(--font-body);
color:var(--text-hi);

padding:20px;
position:relative;
overflow:hidden;

}

/* subtle floating snowflake accents, dark-theme tuned */
body::before,
body::after{
    content:'\f52c';
    font-family:'bootstrap-icons';
    position:absolute;
    color:rgba(103,232,249,.05);
    font-size:220px;
    line-height:1;
    z-index:0;
    pointer-events:none;
}
body::before{ top:-60px; left:-60px; transform:rotate(-12deg); }
body::after{ bottom:-70px; right:-50px; transform:rotate(18deg); font-size:260px; color:rgba(255,176,32,.05); }


.login-box{

width:100%;
max-width:400px;

background:linear-gradient(160deg, rgba(255,255,255,.035), rgba(255,255,255,0) 55%), var(--panel);
padding:40px 34px 30px;
border-radius:26px;

border:1px solid var(--panel-border);
box-shadow:0 30px 70px -20px rgba(0,0,0,.6);

position:relative;
z-index:1;

}

/* corner brackets — same signature as other admin pages */
.login-box::before,
.login-box::after,
.corner-tr,
.corner-bl{
  content:"";
  position:absolute;
  width:20px;
  height:20px;
  border:2px solid var(--ice);
  opacity:.6;
}
.login-box::before{ top:-1px; left:-1px; border-right:none; border-bottom:none; border-top-left-radius:8px; }
.login-box::after{ bottom:-1px; right:-1px; border-left:none; border-top:none; border-bottom-right-radius:8px; }
.corner-tr{ top:-1px; right:-1px; border-left:none; border-bottom:none; border-top-right-radius:8px; }
.corner-bl{ bottom:-1px; left:-1px; border-right:none; border-top:none; border-bottom-left-radius:8px; }


.status-tag{
    display:flex;
    justify-content:center;
    margin-bottom:22px;
}

.status-tag span{
    display:inline-flex;
    align-items:center;
    gap:7px;
    font-family:var(--font-mono);
    font-size:11px;
    font-weight:600;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:var(--text-low);
    border:1px solid var(--panel-border);
    padding:6px 12px;
    border-radius:999px;
}

.status-tag .dot{
    width:6px;
    height:6px;
    border-radius:50%;
    background:var(--success);
    box-shadow:0 0 0 4px rgba(51,224,138,.18);
    animation:pulse-dot 2.2s ease-in-out infinite;
}

@keyframes pulse-dot{
    0%,100%{ box-shadow:0 0 0 4px rgba(51,224,138,.18); }
    50%{ box-shadow:0 0 0 7px rgba(51,224,138,.08); }
}


.logo{

width:70px;
height:70px;

padding:5px;
border-radius:50%;

background:repeating-conic-gradient(var(--ice) 0deg 4deg, transparent 4deg 18deg);

margin:0 auto 18px;

}

.logo-inner{

width:100%;
height:100%;
border-radius:50%;

background:var(--panel-2);
color:var(--ice);

display:flex;
justify-content:center;
align-items:center;
font-size:28px;

box-shadow:inset 0 2px 8px rgba(0,0,0,.5);

}


h3{

font-family:var(--font-display);
font-weight:700;
font-size:24px;
color:#ffffff;
text-align:center;
margin-bottom:2px;
letter-spacing:-.3px;

}

.subtitle{
    text-align:center;
    color:var(--text-mid);
    font-size:13.5px;
    margin-bottom:26px;
}

label{
    font-size:12.5px;
    font-weight:700;
    letter-spacing:.02em;
    color:var(--text-mid);
    margin-bottom:7px;
    display:block;
}

.field-group{ margin-bottom:16px; }

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
}

.form-control{

height:48px;
border:none;
background:transparent;
font-size:14.5px;
color:var(--text-hi);
box-shadow:none !important;

}

.form-control::placeholder{ color:var(--text-low); }

.form-control:focus{ background:transparent; color:var(--text-hi); }

.toggle-pass{
    background:transparent;
    border:none;
    color:var(--text-low);
    width:44px;
    cursor:pointer;
}
.toggle-pass:hover{ color:var(--ice); }

.form-options{
    display:flex;
    justify-content:flex-end;
    margin-bottom:20px;
}
.form-options a{
    font-size:12.5px;
    color:var(--ice);
    text-decoration:none;
    font-weight:600;
}
.form-options a:hover{ text-decoration:underline; }


.btn-login{

height:50px;
border-radius:14px;
background:linear-gradient(135deg, var(--ice), var(--ice-deep));
color:#03222d;
font-weight:700;
font-size:14.5px;
border:none;

display:flex;
align-items:center;
justify-content:center;
gap:8px;

box-shadow:0 14px 26px -8px rgba(34,166,217,.5);

transition:.25s;

}


.btn-login:hover{

color:#03222d;
transform:translateY(-2px);
box-shadow:0 18px 30px -8px rgba(34,166,217,.6);

}


hr{
    border-color:var(--panel-border);
    margin:26px 0 16px;
}


.footer{

font-size:12px;
color:var(--text-low);
text-align:center;
line-height:1.6;

}


/* MOBILE */

@media(max-width:480px){


.login-box{

padding:30px 24px 24px;
border-radius:22px;

}


.logo{

width:60px;
height:60px;

}

.logo-inner{ font-size:23px; }


h3{ font-size:21px; }

.form-control{ height:45px; font-size:14px; }

.btn-login{ height:46px; }

body::before, body::after{ font-size:150px; }

}


/* =========================================
   MODERN LOGIN ANIMATION (SweetAlert overrides — dark)
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

.welcome-name{
    display:block;
    font-size:18px;
    font-weight:800;
    color:#ffffff;
    margin-top:2px;
}

.welcome-role{
    display:inline-block;
    margin-top:8px;
    padding:4px 14px;
    font-size:12px;
    font-weight:700;
    color:#8fe9fb;
    background:rgba(103,232,249,.12);
    border:1px solid rgba(103,232,249,.3);
    border-radius:999px;
}

/* error shake orb */
.error-orb-wrap{
    width:86px;
    height:86px;
    margin:0 auto 14px;
    border-radius:50%;
    background:linear-gradient(135deg,#ff6b6b,#e8341c);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 14px 26px -8px rgba(232,52,28,.5);
    animation:shake-pop .5s cubic-bezier(.36,.07,.19,.97);
}

.error-orb-wrap i{
    font-size:36px;
    color:#fff;
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









<div class="login-box animate__animated animate__zoomIn">


<div class="corner-tr"></div>
<div class="corner-bl"></div>


<div class="status-tag">
<span><span class="dot"></span> Sistem Aktif</span>
</div>


<div class="logo">
<div class="logo-inner">
<i class="bi bi-snow"></i>
</div>
</div>









<h3>


Admin Login


</h3>







<p class="subtitle">


CV Peppy Tehnik Indonesia


</p>










<form method="POST">









<div class="field-group">

<label>

Username

</label>








<div class="input-group">



<span class="input-group-text">


<i class="bi bi-person"></i>


</span>







<input

type="text"

name="username"

class="form-control"

placeholder="Masukkan Username"

required

>



</div>

</div>









<div class="field-group">

<label>

Password

</label>








<div class="input-group">



<span class="input-group-text">


<i class="bi bi-lock"></i>


</span>







<input

type="password"

name="password"

id="passwordInput"

class="form-control"

placeholder="Masukkan Password"

required

>

<button type="button" class="toggle-pass" onclick="togglePassword()">
<i class="bi bi-eye" id="toggleIcon"></i>
</button>



</div>

</div>


<div class="form-options">
<a href="#">Lupa password?</a>
</div>









<button

name="login"

class="btn-login w-100"

>



<i class="bi bi-box-arrow-in-right"></i>


Login



</button>








</form>








<hr>







<p class="footer">


© 2026 CV Peppy Tehnik Indonesia


<br>


Admin Management System


</p>








</div>










<!-- LOGIN BERHASIL -->


<?php if(isset($login_success)){ ?>


<script>


Swal.fire({

icon: undefined,

title: 'Login Berhasil',

html: `

<div class="success-orb-wrap">
  <div class="success-orb-ring"></div>
  <div class="success-orb-core">
    <svg viewBox="0 0 24 24">
      <path d="M4 12.5l5 5L20 6.5" />
    </svg>
  </div>
</div>

<span class="welcome-name">Selamat datang, <?= htmlspecialchars($_SESSION['admin']['username']) ?></span>
<span class="welcome-role"><?= htmlspecialchars($_SESSION['admin']['role']) ?></span>

<p style="margin-top:14px;font-size:13px;">Mengalihkan ke dashboard...</p>

`,

showConfirmButton: false,

timer: 2200,

timerProgressBar: true,

allowOutsideClick: false,

background: 'transparent',

customClass: {
    popup: 'swal-modern-popup',
    backdrop: 'swal-modern-backdrop',
    title: 'swal-modern-title',
    htmlContainer: 'swal-modern-html'
},

showClass: {
    popup: 'animate__animated animate__fadeInUp animate__faster'
},

hideClass: {
    popup: 'animate__animated animate__fadeOutUp animate__faster'
}

});


setTimeout(()=>{


window.location.href="dashboard.php";


},2200);




</script>


<?php } ?>









<!-- LOGIN GAGAL -->

<?php if(isset($login_error)){ ?>



<script>



Swal.fire({

title: 'Login Gagal',

html: `

<div class="error-orb-wrap">
  <i class="bi bi-x-lg"></i>
</div>

<p style="margin-top:4px;"><?= addslashes($login_error) ?></p>

`,

showConfirmButton: true,

confirmButtonText: 'Coba Lagi',

confirmButtonColor: '#22a6d9',

background: 'transparent',

customClass: {
    popup: 'swal-modern-popup',
    backdrop: 'swal-modern-backdrop',
    title: 'swal-modern-title',
    htmlContainer: 'swal-modern-html',
    confirmButton: 'swal-modern-confirm'
},

showClass: {
    popup: 'animate__animated animate__headShake animate__faster'
},

hideClass: {
    popup: 'animate__animated animate__fadeOut animate__faster'
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