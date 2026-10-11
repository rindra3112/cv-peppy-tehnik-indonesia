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
// CEK AKSES VVIP
// ======================


if($_SESSION['admin']['role']!="VVIP"){


echo "

<script>

sessionStorage.setItem(
'akses',
'tolak'
);

location='dashboard.php';

</script>

";


exit;


}







// ======================
// HAPUS ADMIN
// ======================


if(isset($_GET['hapus'])){



$id=$_GET['hapus'];



// CEGAH HAPUS AKUN SENDIRI

if($id != $_SESSION['admin']['id']){



mysqli_query(

$conn,

"DELETE FROM admin WHERE id='$id'"

);



}




header("location:kelola_admin.php");


exit;



}








// ======================
// DATA ADMIN
// ======================


$data=mysqli_query(

$conn,

"SELECT * FROM admin ORDER BY id DESC"

);



?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Admin — CV Peppy Tehnik Indonesia</title>

<!-- ANIMATE -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ICONS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- FONTS -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

<style>

/* =========================================
   TOKENS — matches dashboard.php control-panel theme
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

--amber:#ffb020;
--amber-deep:#ffcf7a;
--ice:#67e8f9;
--ice-deep:#22a6d9;
--danger:#ff5d6c;
--success:#33e08a;

--radius:18px;

--font-display:'Space Grotesk','Segoe UI',sans-serif;
--font-body:'Plus Jakarta Sans','Segoe UI',sans-serif;
--font-mono:'JetBrains Mono', monospace;

}

*{ box-sizing:border-box; }

body{
  margin:0;
  background:
    radial-gradient(circle at 10% 6%, rgba(103,232,249,.08), transparent 42%),
    radial-gradient(circle at 90% 2%, rgba(255,176,32,.07), transparent 40%),
    repeating-linear-gradient(115deg, rgba(255,255,255,.015) 0 2px, transparent 2px 14px),
    linear-gradient(165deg, var(--bg-0), var(--bg-1) 55%, #05101a);
  background-attachment:fixed;
  font-family:var(--font-body);
  color:var(--text-hi);
  min-height:100vh;
}

.wrap{
  max-width:1080px;
  margin:0 auto;
  padding:52px 20px 60px;
}

/* ===== PANEL ===== */
.panel{
  position:relative;
  background:linear-gradient(160deg, rgba(255,255,255,.035), rgba(255,255,255,0) 60%), var(--panel);
  border-radius:24px;
  padding:36px 38px;
  box-shadow:0 20px 45px -22px rgba(0,0,0,.6);
  border:1px solid var(--panel-border);
}

/* signature: technical corner brackets, amber = VVIP zone marker */
.panel::before,
.panel::after,
.corner-tr,
.corner-bl{
  content:"";
  position:absolute;
  width:22px;
  height:22px;
  border:2px solid var(--amber);
  opacity:.85;
}
.panel::before{ top:-1px; left:-1px; border-right:none; border-bottom:none; border-top-left-radius:8px; }
.panel::after{ bottom:-1px; right:-1px; border-left:none; border-top:none; border-bottom-right-radius:8px; }
.corner-tr{ top:-1px; right:-1px; border-left:none; border-bottom:none; border-top-right-radius:8px; }
.corner-bl{ bottom:-1px; left:-1px; border-right:none; border-top:none; border-bottom-left-radius:8px; }

/* ===== HEADER ===== */
.top{
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
  flex-wrap:wrap;
  gap:18px;
  padding-bottom:26px;
  border-bottom:1px solid var(--panel-border);
  margin-bottom:26px;
}

.eyebrow{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-family:var(--font-mono);
  font-size:11.5px;
  font-weight:600;
  letter-spacing:.14em;
  text-transform:uppercase;
  color:var(--amber-deep);
  background:rgba(255,176,32,.1);
  border:1px solid rgba(255,176,32,.25);
  padding:6px 12px;
  border-radius:999px;
  margin-bottom:14px;
}

.eyebrow .dot{
  width:6px; height:6px; border-radius:50%;
  background:var(--amber);
  box-shadow:0 0 0 4px rgba(255,176,32,.18);
}

h1{
  font-family:var(--font-display);
  font-weight:700;
  font-size:30px;
  letter-spacing:-.4px;
  color:#ffffff;
  margin:0 0 6px;
}

.sub{
  margin:0;
  color:var(--text-mid);
  font-size:14.5px;
}

.btn{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-family:var(--font-body);
  font-weight:700;
  font-size:14px;
  border:none;
  border-radius:12px;
  padding:12px 22px;
  cursor:pointer;
  text-decoration:none;
  transition:transform .18s ease, box-shadow .18s ease, background .18s ease, color .18s ease, border-color .18s ease;
  white-space:nowrap;
}
.btn:active{ transform:translateY(1px); }

.btn-ghost{
  background:transparent;
  color:var(--text-mid);
  border:1px solid var(--panel-border);
}
.btn-ghost:hover{ border-color:rgba(255,255,255,.3); color:#fff; background:rgba(255,255,255,.03); }

.btn-primary{
  background:linear-gradient(135deg, var(--ice), var(--ice-deep));
  color:#03222d;
  box-shadow:0 12px 24px -10px rgba(34,166,217,.55);
}
.btn-primary:hover{ transform:translateY(-2px); box-shadow:0 16px 30px -10px rgba(34,166,217,.65); }

.btn-danger{
  background:transparent;
  color:#ffb3ba;
  border:1px solid rgba(255,93,108,.35);
  padding:9px 15px;
  font-size:13px;
}
.btn-danger:hover{ background:var(--danger); color:#fff; border-color:var(--danger); }

.actions-row{ margin-bottom:22px; }

/* ===== TABLE (desktop) ===== */
.table-shell{
  border:1px solid var(--panel-border);
  border-radius:16px;
  overflow:hidden;
}

table{
  width:100%;
  border-collapse:collapse;
}

thead th{
  background:var(--panel-2);
  color:var(--text-mid);
  font-family:var(--font-mono);
  font-size:11px;
  letter-spacing:.14em;
  text-transform:uppercase;
  font-weight:600;
  text-align:left;
  padding:14px 22px;
}

tbody tr{
  border-top:1px solid var(--panel-border);
  transition:background .18s ease;
}
tbody tr:hover{ background:rgba(255,255,255,.025); }

tbody td{
  padding:17px 22px;
  vertical-align:middle;
  font-size:14.5px;
  transition:opacity .45s cubic-bezier(.4,0,.2,1), transform .45s cubic-bezier(.4,0,.2,1), background .45s ease;
}

/* ===== HAPUS: animasi baris keluar ===== */
tbody tr.row-removing{ background:rgba(255,93,108,.08) !important; }
tbody tr.row-removing td{
  opacity:0;
  transform:translateX(24px) scale(.97);
}

.card-row{
  transition:opacity .4s cubic-bezier(.4,0,.2,1), transform .4s cubic-bezier(.4,0,.2,1),
             max-height .4s cubic-bezier(.4,0,.2,1), margin .4s ease, padding .4s ease, border-color .4s ease;
  max-height:220px;
  overflow:hidden;
}
.card-row.row-removing{
  opacity:0;
  transform:scale(.94) translateY(-4px);
  max-height:0;
  margin-bottom:0;
  padding-top:0;
  padding-bottom:0;
  border-color:transparent;
}

.username{
  font-family:var(--font-mono);
  font-weight:600;
  color:#eaf9fd;
  font-size:14px;
}

.badge{
  display:inline-flex;
  align-items:center;
  gap:6px;
  font-size:12.5px;
  font-weight:700;
  padding:6px 13px;
  border-radius:20px;
  letter-spacing:.02em;
}

.badge-vvip{
  background:rgba(255,176,32,.14);
  color:var(--amber-deep);
  border:1px solid rgba(255,176,32,.35);
}

.badge-admin{
  background:rgba(103,232,249,.12);
  color:#8fe9fb;
  border:1px solid rgba(103,232,249,.3);
}

.badge-self{
  background:rgba(51,224,138,.12);
  color:#7ef0b8;
  border:1px solid rgba(51,224,138,.3);
}

/* ===== MOBILE CARDS ===== */
.cards{ display:none; }

.card-row{
  border:1px solid var(--panel-border);
  border-radius:14px;
  padding:16px 18px;
  margin-bottom:12px;
  background:var(--panel-2);
}
.card-row + .card-row{ margin-top:12px; }

.card-top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:10px;
}

.card-label{
  font-family:var(--font-mono);
  font-size:10.5px;
  letter-spacing:.12em;
  text-transform:uppercase;
  color:var(--text-low);
  margin-bottom:2px;
}

.card-actions{ margin-top:12px; }
.card-actions .btn-danger{ width:100%; justify-content:center; padding:11px; }

.empty{
  text-align:center;
  padding:56px 20px;
  color:var(--text-mid);
}
.empty .glyph{ font-size:32px; margin-bottom:12px; color:var(--text-low); }

/* ===== SWEETALERT CUSTOM (dark, matches dashboard) ===== */
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
.swal-trash-wrap{
  display:flex;
  justify-content:center;
  margin-bottom:8px;
}
.swal-trash{
  display:inline-flex;
  width:72px;
  height:72px;
  border-radius:50%;
  align-items:center;
  justify-content:center;
  background:rgba(255,93,108,.14);
  border:1px solid rgba(255,93,108,.3);
  animation:trashWiggle 1.6s ease-in-out infinite;
}
.swal-trash i{
  font-size:28px;
  color:var(--danger);
}
@keyframes trashWiggle{
  0%,100%{ transform:rotate(0deg) translateY(0); }
  20%{ transform:rotate(-9deg) translateY(-1px); }
  40%{ transform:rotate(7deg); }
  60%{ transform:rotate(-4deg); }
  80%{ transform:rotate(2deg); }
}
.swal-desc{
  font-family:var(--font-body);
  color:var(--text-mid);
  font-size:14.5px;
  margin:6px 0 0;
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

@media(max-width:768px){

  .wrap{ padding:28px 14px 40px; }

  .panel{ padding:26px 20px; }

  .top{ flex-direction:column; }

  .btn-ghost{ width:100%; justify-content:center; }

  h1{ font-size:23px; }

  .table-shell{ display:none; }

  .cards{ display:block; }
}

</style>
</head>
<body>

<div class="wrap">

  <div class="panel animate__animated animate__fadeIn">
    <div class="corner-tr"></div>
    <div class="corner-bl"></div>

    <div class="top">
      <div>
        <span class="eyebrow"><span class="dot"></span> Akses VVIP</span>
        <h1>Kelola Admin</h1>
        <p class="sub">Manajemen akun administrator CV Peppy Tehnik Indonesia</p>
      </div>

      <a href="dashboard.php" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Dashboard</a>
    </div>

    <div class="actions-row">
      <a href="tambah_admin.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Admin</a>
    </div>

    <?php
    $rows = [];
    while($d = mysqli_fetch_assoc($data)){ $rows[] = $d; }
    ?>

    <?php if(count($rows) === 0){ ?>

      <div class="empty">
        <div class="glyph"><i class="bi bi-inboxes"></i></div>
        <p>Belum ada data admin.</p>
      </div>

    <?php }else{ ?>

    <!-- DESKTOP TABLE -->
    <div class="table-shell">
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($rows as $d){ ?>
          <tr data-row="<?= $d['id']; ?>">
            <td><span class="username"><?= $d['username']; ?></span></td>
            <td>
              <?php if($d['role']=="VVIP"){ ?>
                <span class="badge badge-vvip"><i class="bi bi-shield-lock-fill"></i> VVIP</span>
              <?php }else{ ?>
                <span class="badge badge-admin">Admin</span>
              <?php } ?>
            </td>
            <td>
              <?php if($d['id'] != $_SESSION['admin']['id']){ ?>
                <button onclick="hapusAdmin(<?= $d['id']; ?>, '<?= addslashes($d['username']); ?>')" class="btn btn-danger"><i class="bi bi-trash3"></i> Hapus</button>
              <?php }else{ ?>
                <span class="badge badge-self">Akun Anda</span>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- MOBILE CARDS -->
    <div class="cards">
      <?php foreach($rows as $d){ ?>
      <div class="card-row" data-row="<?= $d['id']; ?>">
        <div class="card-top">
          <div>
            <div class="card-label">Username</div>
            <span class="username"><?= $d['username']; ?></span>
          </div>
          <?php if($d['role']=="VVIP"){ ?>
            <span class="badge badge-vvip"><i class="bi bi-shield-lock-fill"></i> VVIP</span>
          <?php }else{ ?>
            <span class="badge badge-admin">Admin</span>
          <?php } ?>
        </div>

        <?php if($d['id'] != $_SESSION['admin']['id']){ ?>
          <div class="card-actions">
            <button onclick="hapusAdmin(<?= $d['id']; ?>, '<?= addslashes($d['username']); ?>')" class="btn btn-danger"><i class="bi bi-trash3"></i> Hapus Akun Ini</button>
          </div>
        <?php }else{ ?>
          <div class="card-actions">
            <span class="badge badge-self">Akun Anda</span>
          </div>
        <?php } ?>
      </div>
      <?php } ?>
    </div>

    <?php } ?>

  </div>
</div>

<script>

function hapusAdmin(id, username){

  Swal.fire({
    title:'Hapus Admin?',
    html:`
      <div class="swal-trash-wrap"><span class="swal-trash"><i class="bi bi-trash3"></i></span></div>
      <p class="swal-desc">
        Akun <span class="swal-username">${username}</span> akan dihapus permanen
        dan tidak bisa dikembalikan.
      </p>
    `,
    showCancelButton:true,
    reverseButtons:true,
    confirmButtonText:'Ya, Hapus',
    cancelButtonText:'Batal',
    confirmButtonColor:'#ff5d6c',
    cancelButtonColor:'rgba(255,255,255,.12)',
    background:'transparent',
    customClass:{ popup:'swal-dark-popup', backdrop:'swal-dark-backdrop', title:'swal-dark-title' },
    showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },
    hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' }
  }).then((result)=>{

    if(!result.isConfirmed) return;

    // 1) animasikan baris/kartu terkait memudar keluar dulu
    const targets = document.querySelectorAll('[data-row="'+id+'"]');
    targets.forEach(el => el.classList.add('row-removing'));

    // 2) setelah animasi baris selesai, tampilkan konfirmasi sukses
    setTimeout(()=>{

      Swal.fire({
        title:'Terhapus!',
        html:`<p class="swal-desc">Akun <span class="swal-username">${username}</span> berhasil dihapus.</p>`,
        icon:'success',
        showConfirmButton:false,
        timer:1000,
        background:'transparent',
        customClass:{ popup:'swal-dark-popup', backdrop:'swal-dark-backdrop', title:'swal-dark-title' },
        showClass:{ popup:'animate__animated animate__bounceIn' },
        hideClass:{ popup:'animate__animated animate__fadeOut animate__faster' }
      });

      // 3) redirect ke server untuk memproses penghapusan sesungguhnya
      setTimeout(()=>{
        window.location.href = "kelola_admin.php?hapus=" + id;
      }, 950);

    }, 420);

  });

}

</script>

</body>
</html>