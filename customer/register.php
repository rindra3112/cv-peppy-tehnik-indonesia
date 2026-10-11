<?php

session_start();

include "../config/database.php";



// ======================
// PROSES DAFTAR CUSTOMER
// ======================

if(isset($_POST['daftar'])){


$nama = mysqli_real_escape_string(
$conn,
$_POST['nama']
);


$email = mysqli_real_escape_string(
$conn,
$_POST['email']
);



$password = password_hash(
$_POST['password'],
PASSWORD_DEFAULT
);




// CEK EMAIL

$cek=mysqli_query(
$conn,
"SELECT * FROM customer WHERE email='$email'"
);




if(mysqli_num_rows($cek)>0){


echo "

<script>

document.addEventListener('DOMContentLoaded',function(){

Swal.fire({

title:'Email Sudah Digunakan',

html:`
  <div style=\"
    width:84px;height:84px;
    background:linear-gradient(135deg,#fff6e0,#ffecb8);
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    margin:6px auto 18px;
    font-size:36px;color:#e2a80a;
  \">
    <i class=\"bi bi-exclamation-triangle\"></i>
  </div>
  <div style=\"font-size:14.5px;color:#6b7c88;line-height:1.6\">
    Email ini sudah terdaftar, silahkan gunakan email lain atau login
  </div>
`,

confirmButtonText:'Coba Lagi',

confirmButtonColor:'#0a6bb5',


showClass:{

popup:'animate__animated animate__shakeX'

}


});


});


</script>

";


}else{





// SIMPAN DATA


$simpan=mysqli_query(
$conn,

"INSERT INTO customer

(
nama,
email,
password
)

VALUES

(
'$nama',
'$email',
'$password'
)"

);





if($simpan){


echo "

<script>

document.addEventListener('DOMContentLoaded',function(){


Swal.fire({

title:'Daftar Berhasil',

html:`
  <div style=\"
    width:84px;height:84px;
    background:linear-gradient(135deg,#eaf6fd,#d6ecf9);
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    margin:6px auto 18px;
    font-size:36px;color:#0a6bb5;
  \">
    <i class=\"bi bi-check-circle\"></i>
  </div>
  <div style=\"font-size:14.5px;color:#6b7c88;line-height:1.6\">
    Akun berhasil dibuat, silahkan login untuk melanjutkan
  </div>
`,

timer:2200,

showConfirmButton:false,

allowOutsideClick:false,


showClass:{

popup:'animate__animated animate__zoomIn animate__faster'

},


hideClass:{

popup:'animate__animated animate__zoomOut animate__faster'

},

didOpen:()=>{ Swal.showLoading(); }


});



setTimeout(()=>{

window.location='login.php';

},2200);



});


</script>

";


}else{


echo "

<script>

document.addEventListener('DOMContentLoaded',function(){

Swal.fire({

title:'Gagal Daftar',

html:`
  <div style=\"
    width:84px;height:84px;
    background:linear-gradient(135deg,#fdecec,#fbd7d7);
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    margin:6px auto 18px;
    font-size:36px;color:#e6483a;
  \">
    <i class=\"bi bi-x-circle\"></i>
  </div>
  <div style=\"font-size:14.5px;color:#6b7c88;line-height:1.6\">
    Terjadi kesalahan database, silahkan coba lagi
  </div>
`,

confirmButtonColor:'#0a6bb5'

});

});

</script>

";


}




}




}


?>






<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Customer - CV Peppy Tehnik Indonesia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

*{box-sizing:border-box;}

body{
  margin:0;
  min-height:100vh;
  font-family:'Plus Jakarta Sans', sans-serif;
  color:var(--teks);
  background:linear-gradient(150deg,var(--biru-tua) 0%, var(--biru) 55%, var(--biru-terang) 100%);
  display:flex;
  align-items:center;
  justify-content:center;
  padding:24px;
  position:relative;
  overflow-x:hidden;
}

body::before{
  content:"";
  position:absolute;
  top:-120px; left:-120px;
  width:340px; height:340px;
  background:radial-gradient(circle, rgba(255,255,255,.14), transparent 70%);
  border-radius:50%;
}

body::after{
  content:"";
  position:absolute;
  bottom:-140px; right:-100px;
  width:380px; height:380px;
  background:radial-gradient(circle, rgba(255,255,255,.10), transparent 70%);
  border-radius:50%;
}

.wrapper{
  width:100%;
  max-width:880px;
  background:#fff;
  border-radius:26px;
  box-shadow:0 30px 70px -20px rgba(3,25,45,.45);
  overflow:hidden;
  display:flex;
  position:relative;
  z-index:1;
}

/* ==== PANEL KIRI (branding) ==== */

.panel-kiri{
  flex:1;
  background:linear-gradient(160deg,var(--biru-tua), var(--biru) 70%);
  color:#fff;
  padding:48px 40px;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  position:relative;
  overflow:hidden;
  min-width:280px;
}

.panel-kiri::before{
  content:"";
  position:absolute;
  right:-70px; top:40%;
  width:220px; height:220px;
  background:radial-gradient(circle, rgba(255,255,255,.12), transparent 70%);
  border-radius:50%;
}

.brand-mark{
  display:flex;
  align-items:center;
  gap:10px;
  font-weight:800;
  font-size:16px;
  position:relative;
  z-index:1;
}

.brand-mark .icon-snow{
  width:38px; height:38px;
  background:rgba(255,255,255,.15);
  border:1px solid rgba(255,255,255,.3);
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:18px;
}

.panel-kiri h2{
  font-size:26px;
  font-weight:800;
  line-height:1.35;
  margin:0;
  position:relative;
  z-index:1;
}

.panel-kiri p{
  color:rgba(255,255,255,.8);
  font-size:14px;
  margin-top:10px;
  position:relative;
  z-index:1;
}

.fitur-list{
  list-style:none;
  padding:0;
  margin:26px 0 0;
  display:flex;
  flex-direction:column;
  gap:14px;
  position:relative;
  z-index:1;
}

.fitur-list li{
  display:flex;
  align-items:center;
  gap:10px;
  font-size:13.5px;
  color:rgba(255,255,255,.92);
}

.fitur-list li i{
  width:30px; height:30px;
  background:rgba(255,255,255,.14);
  border-radius:8px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:14px;
}

.panel-kiri .footnote{
  font-size:11.5px;
  color:rgba(255,255,255,.55);
  position:relative;
  z-index:1;
}

/* ==== PANEL KANAN (form) ==== */

.panel-kanan{
  flex:1.15;
  padding:48px 44px;
  display:flex;
  flex-direction:column;
  justify-content:center;
  min-width:300px;
}

.judul-form h3{
  font-weight:800;
  font-size:25px;
  margin-bottom:4px;
  letter-spacing:-.3px;
}

.judul-form p{
  color:var(--abu);
  font-size:13.5px;
  margin-bottom:26px;
}

.field{
  margin-bottom:16px;
}

.field label{
  font-size:12.5px;
  font-weight:700;
  color:var(--teks);
  margin-bottom:6px;
  display:block;
}

.input-modern{
  position:relative;
  display:flex;
  align-items:center;
  background:var(--kabut);
  border:1.5px solid #e3ecf2;
  border-radius:13px;
  padding:0 14px;
  transition:.2s;
}

.input-modern:focus-within{
  border-color:var(--biru);
  background:#fff;
  box-shadow:0 0 0 4px rgba(10,107,181,.10);
}

.input-modern i{
  color:var(--abu);
  font-size:15px;
}

.input-modern input{
  border:none;
  background:transparent;
  outline:none;
  padding:13px 10px;
  font-size:14px;
  width:100%;
  color:var(--teks);
}

.toggle-eye{
  cursor:pointer;
  color:var(--abu);
  font-size:15px;
}

.toggle-eye:hover{ color:var(--biru); }

.hint{
  font-size:11.5px;
  color:var(--abu);
  margin-top:6px;
}

.btn-register-modern{
  width:100%;
  border:none;
  background:linear-gradient(120deg,var(--biru-tua), var(--biru));
  color:#fff;
  font-weight:700;
  font-size:14.5px;
  padding:14px;
  border-radius:13px;
  margin-top:6px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  transition:.2s;
  box-shadow:0 14px 26px -10px rgba(10,107,181,.55);
}

.btn-register-modern:hover{
  transform:translateY(-2px);
  box-shadow:0 18px 30px -10px rgba(10,107,181,.65);
}

.login-link{
  text-align:center;
  font-size:13.5px;
  color:var(--abu);
  margin-top:22px;
}

.login-link a{
  color:var(--biru);
  font-weight:700;
  text-decoration:none;
}

.login-link a:hover{ color:var(--biru-tua); }

.balik-link{
  display:inline-flex;
  align-items:center;
  gap:6px;
  font-size:12.5px;
  color:var(--abu);
  text-decoration:none;
  margin-bottom:22px;
}

.balik-link:hover{ color:var(--biru); }

/* MOBILE */
@media(max-width:768px){

  .wrapper{
    flex-direction:column;
    border-radius:22px;
  }

  .panel-kiri{
    padding:32px 26px;
  }

  .panel-kiri h2{ font-size:21px; }

  .fitur-list{ margin-top:18px; gap:10px; }

  .panel-kanan{
    padding:32px 26px 36px;
  }

  .judul-form h3{ font-size:21px; }
}

</style>

</head>

<body>

<div class="wrapper animate__animated animate__fadeInUp">

  <!-- PANEL KIRI -->
  <div class="panel-kiri">

    <div>
      <div class="brand-mark">
        <span class="icon-snow"><i class="bi bi-snow2"></i></span>
        CV Peppy Tehnik Indonesia
      </div>

      <h2 class="mt-4">Gabung dan nikmati belanja AC yang lebih mudah</h2>
      <p>Buat akun sekali, checkout jadi lebih cepat di setiap pemesanan berikutnya.</p>

      <ul class="fitur-list">
        <li><i class="bi bi-clock-history"></i> Pantau status pesanan kapan saja</li>
        <li><i class="bi bi-lightning-charge"></i> Checkout lebih cepat, data tersimpan</li>
        <li><i class="bi bi-shield-check"></i> Data akun kamu aman &amp; terenkripsi</li>
      </ul>
    </div>

    <div class="footnote">© CV Peppy Tehnik Indonesia</div>

  </div>

  <!-- PANEL KANAN -->
  <div class="panel-kanan">

    <a href="../index.php" class="balik-link">
      <i class="bi bi-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="judul-form">
      <h3>Buat Akun Baru</h3>
      <p>Daftar untuk melihat riwayat pesanan Anda</p>
    </div>

    <form method="POST">

      <div class="field">
        <label>Nama Lengkap</label>
        <div class="input-modern">
          <i class="bi bi-person"></i>
          <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
        </div>
      </div>

      <div class="field">
        <label>Email</label>
        <div class="input-modern">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" placeholder="nama@email.com" required>
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="input-modern">
          <i class="bi bi-lock"></i>
          <input type="password" name="password" id="passwordInput" placeholder="Masukkan password" required>
          <i class="bi bi-eye toggle-eye" id="toggleEye" onclick="togglePassword()"></i>
        </div>
        <div class="hint">Gunakan minimal 6 karakter agar lebih aman</div>
      </div>

      <button type="submit" name="daftar" class="btn-register-modern">
        Daftar Sekarang <i class="bi bi-arrow-right"></i>
      </button>

    </form>

    <div class="login-link">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>

  </div>

</div>

<script>

function togglePassword(){

  const input = document.getElementById('passwordInput');
  const eye   = document.getElementById('toggleEye');

  if(input.type === 'password'){
    input.type = 'text';
    eye.classList.remove('bi-eye');
    eye.classList.add('bi-eye-slash');
  }else{
    input.type = 'password';
    eye.classList.remove('bi-eye-slash');
    eye.classList.add('bi-eye');
  }

}

</script>

</body>
</html>