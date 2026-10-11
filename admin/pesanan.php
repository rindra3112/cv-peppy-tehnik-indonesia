<?php

session_start();

include "../config/database.php";


// ========================
// CEK LOGIN ADMIN
// ========================

if(!isset($_SESSION['admin'])){

header("location:login.php");

exit;

}



// ========================
// KONFIRMASI
// ========================

if(isset($_GET['konfirmasi'])){


$id=$_GET['konfirmasi'];


mysqli_query(

$conn,

"UPDATE pesanan SET 
status='Pembayaran Berhasil'
WHERE id='$id'"

);



echo "

<script>

sessionStorage.setItem(
'pesan',
'konfirmasi'
);

location='pesanan.php';

</script>

";


exit;


}






// ========================
// TOLAK
// ========================


if(isset($_GET['cancel'])){



$id=$_GET['cancel'];



mysqli_query(

$conn,

"UPDATE pesanan SET
status='Pembayaran Ditolak'
WHERE id='$id'"

);




echo "

<script>

sessionStorage.setItem(
'pesan',
'tolak'
);

location='pesanan.php';

</script>

";



exit;


}








// ========================
// AMBIL PESANAN
// ========================


$data=mysqli_query(

$conn,


"SELECT

pesanan.*,

produk.nama_produk,
produk.brand,
produk.tipe,
produk.foto


FROM pesanan


LEFT JOIN produk

ON produk.id=pesanan.id_produk


ORDER BY pesanan.id DESC"

);



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

Verifikasi Pembayaran

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





<!-- SWEET ALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<link
rel="preconnect"
href="https://fonts.googleapis.com"
>
<link
href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap"
rel="stylesheet"
>









<style>

/* =========================================
   TOKENS — matches dashboard.php / kelola_admin.php / login.php / tambah_admin.php
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
--amber:#ffb020;
--amber-deep:#ffcf7a;
--danger:#ff5d6c;
--success:#33e08a;

--font-display:'Space Grotesk','Segoe UI',sans-serif;
--font-body:'Plus Jakarta Sans','Segoe UI',sans-serif;
--font-mono:'JetBrains Mono', monospace;

}



*{

box-sizing:border-box;

}



body{


background:

radial-gradient(circle at 10% 6%, rgba(103,232,249,.09), transparent 42%),
radial-gradient(circle at 90% 2%, rgba(255,176,32,.06), transparent 40%),
repeating-linear-gradient(115deg, rgba(255,255,255,.015) 0 2px, transparent 2px 14px),
linear-gradient(165deg, var(--bg-0), var(--bg-1) 55%, #05101a);


background-attachment:fixed;


font-family:var(--font-body);


min-height:100vh;


color:var(--text-hi);


padding-bottom:40px;


}



.container-fluid{


max-width:1400px;


padding-top:48px;


}






.box{


position:relative;


background:linear-gradient(160deg, rgba(255,255,255,.035), rgba(255,255,255,0) 55%), var(--panel);


padding:34px;


border-radius:24px;


border:1px solid var(--panel-border);



box-shadow:

0 25px 55px -18px rgba(0,0,0,.6);


}



/* corner brackets — cyan, matches the "Pembayaran" accent on the dashboard */
.box::before,
.box::after,
.corner-tr,
.corner-bl{
  content:"";
  position:absolute;
  width:22px;
  height:22px;
  border:2px solid var(--ice);
  opacity:.6;
}
.box::before{ top:-1px; left:-1px; border-right:none; border-bottom:none; border-top-left-radius:8px; }
.box::after{ bottom:-1px; right:-1px; border-left:none; border-top:none; border-bottom-right-radius:8px; }
.corner-tr{ top:-1px; right:-1px; border-left:none; border-bottom:none; border-top-right-radius:8px; }
.corner-bl{ bottom:-1px; left:-1px; border-right:none; border-top:none; border-bottom-left-radius:8px; }








.header{


display:flex;


justify-content:space-between;


align-items:flex-start;


flex-wrap:wrap;


gap:15px;


margin-bottom:8px;


}



.eyebrow{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-family:var(--font-mono);
  font-size:11px;
  font-weight:600;
  letter-spacing:.14em;
  text-transform:uppercase;
  color:#8fe9fb;
  background:rgba(103,232,249,.1);
  border:1px solid rgba(103,232,249,.25);
  padding:6px 12px;
  border-radius:999px;
  margin-bottom:14px;
}

.eyebrow .dot{
  width:6px; height:6px; border-radius:50%;
  background:var(--ice);
  box-shadow:0 0 0 4px rgba(103,232,249,.18);
}







.title{


font-family:var(--font-display);


font-weight:700;


color:#ffffff;


font-size:27px;


letter-spacing:-.4px;


margin:0;


}



.sub{

margin:4px 0 0;

color:var(--text-mid);

font-size:14px;

}



.back-btn{


display:inline-flex;


align-items:center;


gap:8px;


background:transparent;


border:1px solid var(--panel-border);


color:var(--text-mid);


font-weight:700;


font-size:14px;


padding:11px 22px;


border-radius:14px;


transition:.25s ease;


text-decoration:none;


}



.back-btn:hover{


background:rgba(255,255,255,.03);


border-color:rgba(255,255,255,.3);


color:#fff;


}



hr{


border-color:var(--panel-border);


margin:22px 0 24px;


}









table{


border-collapse:separate!important;


border-spacing:0;


width:100%;


}



th{


background:var(--panel-2)!important;


color:var(--text-mid)!important;


font-family:var(--font-mono);


font-weight:600;


font-size:11px;


text-transform:uppercase;


letter-spacing:.12em;


border:none!important;


border-bottom:1px solid var(--panel-border)!important;


padding:14px 14px!important;


white-space:nowrap;


}



th:first-child{


border-top-left-radius:14px;


}



th:last-child{


border-top-right-radius:14px;


}



td{


border:none!important;


border-bottom:1px solid var(--panel-border)!important;


padding:17px 14px!important;


vertical-align:middle;


font-size:14.5px;


color:var(--text-hi);


}



.table-shell{

border:1px solid var(--panel-border);

border-radius:16px;

overflow:hidden;

}



tr:last-child td{


border-bottom:none!important;


}



tr:hover td{


background:rgba(255,255,255,.02);


}








.foto-produk{


width:68px;


height:68px;


object-fit:contain;


border-radius:12px;


background:var(--panel-2);


padding:6px;


border:1px solid var(--panel-border);


}








.bukti{


width:72px;


height:72px;


object-fit:cover;


border-radius:14px;


box-shadow:0 6px 16px -6px rgba(0,0,0,.4);


border:1px solid var(--panel-border);


display:block;


}









.badge{


display:inline-flex;

align-items:center;

gap:6px;


padding:7px 14px;


border-radius:20px;


font-weight:700;


font-size:12px;


letter-spacing:.2px;


}



.badge-success{


background:rgba(51,224,138,.14);

color:#7ef0b8;

border:1px solid rgba(51,224,138,.3);


}



.badge-danger{


background:rgba(255,93,108,.12);

color:#ffb3ba;

border:1px solid rgba(255,93,108,.3);


}



.badge-warning{


background:rgba(255,176,32,.14);

color:var(--amber-deep);

border:1px solid rgba(255,176,32,.35);


}









.btn{


border-radius:12px;


font-weight:700;


font-size:13px;


border:none;


transition:.2s ease;


}



.btn-success{


background:linear-gradient(135deg,#33e08a,#0a8f4e);

color:#04240f;


}



.btn-success:hover{


filter:brightness(1.08);

transform:translateY(-1px);

color:#04240f;


}



.btn-danger{


background:transparent;

color:#ffb3ba;

border:1px solid rgba(255,93,108,.35);


}



.btn-danger:hover{


background:var(--danger);

color:#fff;

border-color:var(--danger);


}



.btn-secondary{


background:var(--panel-2);


border:1px solid var(--panel-border);


color:var(--text-low);


}



.btn-sm{


padding:9px 16px;


}



.action-cell{


display:flex;


gap:8px;


flex-wrap:wrap;


}



.produk-name{


font-weight:800;


color:#ffffff;


}



.produk-sub{


color:var(--text-mid);


font-size:13px;


}



.total-tag{


font-weight:800;


color:#7ef0b8;


font-size:15.5px;


font-family:var(--font-mono);


}



.no-bukti{


color:var(--text-low);


font-size:13px;


}



.empty-state{


text-align:center;


padding:56px 20px;


color:var(--text-mid);


}



.empty-state .icon{


font-size:34px;


margin-bottom:12px;


color:var(--text-low);


}









.customer-info{


min-width:200px;


line-height:24px;


}



.customer-info b{


font-size:15px;

color:#ffffff;


}



.customer-info .row-line{


color:var(--text-mid);


font-size:13.5px;


display:flex;


align-items:center;


gap:7px;

margin-top:2px;


}


.customer-info .row-line i{

color:var(--text-low);

font-size:12.5px;

width:14px;

}









/* MOBILE — CARD LAYOUT */


@media(max-width:768px){




.container-fluid{


padding:24px 12px!important;


}






.box{


padding:22px;


border-radius:22px;


margin-top:0;


}






.title{


font-size:21px;


}



.header{


flex-direction:column;


}



.back-btn{


justify-content:center;


width:100%;


}



.table-responsive table,

.table-responsive thead,

.table-responsive tbody,

.table-responsive th,

.table-responsive td,

.table-responsive tr{


display:block;


width:100%;


}



.table-responsive thead{


display:none;


}



.table-shell{

border:none;

}



.table-responsive tr{


margin-bottom:16px;


border:1px solid var(--panel-border);


border-radius:18px;


overflow:hidden;


box-shadow:0 10px 24px -14px rgba(0,0,0,.5);


padding:16px;


background:var(--panel-2);


}



.table-responsive td{


border-bottom:1px dashed var(--panel-border)!important;


padding:12px 4px!important;


}



.table-responsive tr:last-child td:last-child{


border-bottom:none!important;


}



.table-responsive td::before{


content:attr(data-label);


font-family:var(--font-mono);


font-weight:600;


color:var(--text-low);


font-size:10.5px;


text-transform:uppercase;


letter-spacing:.12em;


display:block;


margin-bottom:7px;


}



.action-cell{


justify-content:center;


}



.action-cell .btn{


flex:1;


}



.bukti,.foto-produk{


width:66px;


height:66px;


}





}



@media(max-width:400px){


.title{


font-size:19px;


}


}



/* =========================================
   MODERN SWEETALERT (dark, matches other admin pages)
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
    color:var(--text-mid) !important;
    line-height:1.6;
}
.swal-confirm-success{
    border-radius:12px !important;
    padding:10px 26px !important;
    font-weight:700 !important;
    box-shadow:0 12px 24px -8px rgba(51,224,138,.4) !important;
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

.swal-desc{
    font-family:var(--font-body);
    color:var(--text-mid);
    font-size:14.5px;
    margin:6px 0 0;
    line-height:1.6;
}

/* ask orb (question confirm) */
.ask-orb-wrap{
    width:84px; height:84px; margin:0 auto 16px; position:relative;
    display:flex; align-items:center; justify-content:center;
}
.ask-orb-pulse{
    position:absolute; inset:0; border-radius:50%;
    background:rgba(51,224,138,.22);
    animation:ask-pulse 1.8s ease-out infinite;
}
.ask-orb-pulse.danger-pulse{ background:rgba(255,93,108,.22); }
.ask-orb-core{
    position:relative; width:68px; height:68px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    animation:pop-in .45s cubic-bezier(.34,1.56,.64,1) both;
}
.ask-orb-core.success-tone{
    background:linear-gradient(135deg,#33e08a,#0a8f4e);
    box-shadow:0 14px 26px -8px rgba(10,143,78,.5);
}
.ask-orb-core.danger-tone{
    background:linear-gradient(135deg,#ff6b6b,#e0303a);
    box-shadow:0 14px 26px -8px rgba(224,48,58,.5);
}
.ask-orb-core i{ font-size:30px; color:#fff; }
@keyframes ask-pulse{
    0%{ transform:scale(.85); opacity:.7; }
    70%{ transform:scale(1.35); opacity:0; }
    100%{ transform:scale(1.35); opacity:0; }
}
@keyframes pop-in{
    0%{ transform:scale(0); opacity:0; }
    100%{ transform:scale(1); opacity:1; }
}

/* success ring + check draw */
.success-orb-wrap{
    width:92px; height:92px; margin:0 auto 18px; position:relative;
    display:flex; align-items:center; justify-content:center;
}
.success-orb-ring{
    position:absolute; inset:0; border-radius:50%;
    background:conic-gradient(from 0deg, #33e08a, #22a6d9, #33e08a);
    animation:spin-ring 1.4s linear infinite;
    -webkit-mask:radial-gradient(farthest-side, transparent calc(100% - 5px), #000 calc(100% - 5px));
    mask:radial-gradient(farthest-side, transparent calc(100% - 5px), #000 calc(100% - 5px));
}
.success-orb-core{
    position:absolute; inset:8px; border-radius:50%;
    background:linear-gradient(135deg,#33e08a,#00b96b);
    box-shadow:0 12px 24px -6px rgba(51,224,138,.5);
    display:flex; align-items:center; justify-content:center;
    animation:pop-in .5s cubic-bezier(.34,1.56,.64,1) .3s both;
}
.success-orb-core svg{ width:40px; height:40px; }
.success-orb-core svg path{
    stroke:#fff; stroke-width:4; fill:none;
    stroke-linecap:round; stroke-linejoin:round;
    stroke-dasharray:48; stroke-dashoffset:48;
    animation:draw-check .45s ease-out .75s forwards;
}
@keyframes spin-ring{ to{ transform:rotate(360deg); } }
@keyframes draw-check{ to{ stroke-dashoffset:0; } }

/* error orb (x mark) */
.error-orb-wrap{
    width:86px; height:86px; margin:0 auto 16px; border-radius:50%;
    background:linear-gradient(135deg,#ff6b6b,#e8341c);
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 14px 26px -8px rgba(232,52,28,.5);
    animation:shake-pop .5s cubic-bezier(.36,.07,.19,.97);
}
.error-orb-wrap i{ font-size:34px; color:#fff; }
@keyframes shake-pop{
    0%{ transform:scale(0) rotate(0deg); }
    60%{ transform:scale(1.08) rotate(6deg); }
    80%{ transform:scale(.97) rotate(-4deg); }
    100%{ transform:scale(1) rotate(0deg); }
}

</style>



</head>








<body>







<div class="container-fluid px-5">







<div class="box animate__animated animate__fadeInUp">


<div class="corner-tr"></div>
<div class="corner-bl"></div>







<div class="header">



<div>

<span class="eyebrow"><span class="dot"></span> Verifikasi Pembayaran</span>

<h2 class="title">Pembayaran Customer</h2>

<p class="sub">Tinjau dan proses bukti transfer yang masuk</p>

</div>







<a

href="dashboard.php"

class="back-btn"

>


<i class="bi bi-arrow-left"></i> Dashboard


</a>







</div>








<hr>









<div class="table-responsive">


<div class="table-shell">







<table class="table align-middle">







<thead>

<tr>


<th>Customer</th>

<th>Produk</th>

<th>Foto</th>

<th>Total</th>

<th>Bukti</th>

<th>Status</th>

<th>Aksi</th>


</tr>

</thead>



<tbody>


<?php

$adaPesanan=false;

while($d=mysqli_fetch_assoc($data)){

$adaPesanan=true;

?>








<tr>







<td class="customer-info" data-label="Customer">





<b>

<?= $d['nama_customer']; ?>

</b>


<div class="row-line"><i class="bi bi-envelope"></i> <?= $d['email']; ?></div>


<div class="row-line"><i class="bi bi-telephone"></i> <?= $d['hp']; ?></div>


<div class="row-line"><i class="bi bi-geo-alt"></i> <?= $d['alamat']; ?></div>




</td>








<td data-label="Produk">


<div class="produk-name"><?= $d['brand']; ?></div>


<div class="produk-sub"><?= $d['nama_produk']; ?></div>


<div class="produk-sub"><?= $d['tipe']; ?></div>



</td>










<td data-label="Foto">



<?php if($d['foto']){ ?>



<img

src="../upload/produk/<?= $d['foto']; ?>"

class="foto-produk"

>



<?php }else{ ?>


-


<?php } ?>



</td>










<td data-label="Total">



<span class="total-tag">


Rp <?= number_format(

(float) preg_replace('/[^0-9.]/','',$d['total']),

0,

',',

'.'

); ?>


</span>



</td>











<td data-label="Bukti">



<?php if($d['bukti_transfer']){ ?>



<a

href="../upload/bukti/<?= $d['bukti_transfer']; ?>"

target="_blank"

>



<img

src="../upload/bukti/<?= $d['bukti_transfer']; ?>"

class="bukti"

>



</a>



<?php }else{ ?>


<span class="badge badge-warning">

Belum Upload

</span>



<?php } ?>




</td>










<td data-label="Status">





<?php


if($d['status']=="Pembayaran Berhasil"){


echo "

<span class='badge badge-success'>

<i class='bi bi-check-lg'></i> Berhasil

</span>

";


}


elseif($d['status']=="Pembayaran Ditolak"){


echo "

<span class='badge badge-danger'>

<i class='bi bi-x-lg'></i> Ditolak

</span>

";


}


else{


echo "

<span class='badge badge-warning'>

<i class='bi bi-hourglass-split'></i> Menunggu

</span>

";


}



?>





</td>










<td data-label="Aksi">



<div class="action-cell">



<?php if($d['status']=="Menunggu Konfirmasi"){ ?>





<button

onclick="konfirmasi(<?= $d['id']; ?>)"

class="btn btn-success btn-sm"

>


<i class="bi bi-check-lg"></i> Konfirmasi


</button>






<button

onclick="tolak(<?= $d['id']; ?>)"

class="btn btn-danger btn-sm"

>


<i class="bi bi-x-lg"></i> Tolak


</button>






<?php }else{ ?>




<button

class="btn btn-secondary btn-sm"

disabled

>


Selesai


</button>




<?php } ?>



</div>






</td>








</tr>









<?php }

if(!$adaPesanan){

?>


<tr>

<td colspan="7" style="display:table-cell;text-align:center;">

<div class="empty-state">

<div class="icon"><i class="bi bi-receipt-cutoff"></i></div>

Belum ada pesanan masuk

</div>

</td>

</tr>


<?php } ?>



</tbody>









</table>



</div>









</div>









</div>








</div>













<script>



// ALERT SESSION


let pesan=sessionStorage.getItem('pesan');



if(pesan=='konfirmasi'){


Swal.fire({

title:'Pembayaran Dikonfirmasi',

html:`
<div class="success-orb-wrap">
  <div class="success-orb-ring"></div>
  <div class="success-orb-core">
    <svg viewBox="0 0 24 24">
      <path d="M4 12.5l5 5L20 6.5" />
    </svg>
  </div>
</div>
`,

timer:1600,

timerProgressBar:true,

showConfirmButton:false,

background:'transparent',

customClass:{ popup:'swal-dark-popup', backdrop:'swal-dark-backdrop', title:'swal-dark-title' },

showClass:{ popup:'animate__animated animate__fadeInUp animate__faster' },

hideClass:{ popup:'animate__animated animate__fadeOutUp animate__faster' }

});


sessionStorage.removeItem('pesan');


}




if(pesan=='tolak'){


Swal.fire({

title:'Pembayaran Ditolak',

html:`
<div class="error-orb-wrap"><i class="bi bi-x-lg"></i></div>
`,

timer:1600,

timerProgressBar:true,

showConfirmButton:false,

background:'transparent',

customClass:{ popup:'swal-dark-popup', backdrop:'swal-dark-backdrop', title:'swal-dark-title' },

showClass:{ popup:'animate__animated animate__fadeInUp animate__faster' },

hideClass:{ popup:'animate__animated animate__fadeOutUp animate__faster' }

});


sessionStorage.removeItem('pesan');


}










function konfirmasi(id){



Swal.fire({


title:'Konfirmasi pembayaran?',


html:`
<div class="ask-orb-wrap">
  <div class="ask-orb-pulse"></div>
  <div class="ask-orb-core success-tone">
    <i class="bi bi-check-lg"></i>
  </div>
</div>
<p class="swal-desc">Pesanan ini akan ditandai sebagai pembayaran berhasil</p>
`,


showCancelButton:true,


confirmButtonText:'Konfirmasi',


cancelButtonText:'Batal',


confirmButtonColor:'#0a8f4e',


background:'transparent',


customClass:{ popup:'swal-dark-popup', backdrop:'swal-dark-backdrop', title:'swal-dark-title', htmlContainer:'swal-dark-html', confirmButton:'swal-confirm-success', cancelButton:'swal-cancel-ghost' },


showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },


hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' }


}).then((result)=>{


if(result.isConfirmed){



location=

'pesanan.php?konfirmasi='+id;



}


});



}









function tolak(id){



Swal.fire({


title:'Tolak pembayaran?',


html:`
<div class="ask-orb-wrap">
  <div class="ask-orb-pulse danger-pulse"></div>
  <div class="ask-orb-core danger-tone">
    <i class="bi bi-x-lg"></i>
  </div>
</div>
<p class="swal-desc">Pesanan ini akan ditandai sebagai pembayaran ditolak</p>
`,


showCancelButton:true,


confirmButtonText:'Tolak',


cancelButtonText:'Batal',


confirmButtonColor:'#e0303a',


background:'transparent',


customClass:{ popup:'swal-dark-popup', backdrop:'swal-dark-backdrop', title:'swal-dark-title', htmlContainer:'swal-dark-html', confirmButton:'swal-confirm-danger', cancelButton:'swal-cancel-ghost' },


showClass:{ popup:'animate__animated animate__zoomIn animate__faster' },


hideClass:{ popup:'animate__animated animate__zoomOut animate__faster' }


}).then((result)=>{


if(result.isConfirmed){



location=

'pesanan.php?cancel='+id;



}


});



}





</script>








</body>


</html>