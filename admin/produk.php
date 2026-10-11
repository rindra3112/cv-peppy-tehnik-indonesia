<?php

session_start();

include "../config/database.php";



// CEK LOGIN ADMIN

if(!isset($_SESSION['admin'])){

header("location:login.php");

exit;

}





// =========================
// TAMBAH PRODUK
// =========================


if(isset($_POST['simpan'])){



$brand=$_POST['brand'];
$nama=$_POST['nama'];
$tipe=$_POST['tipe'];
$btu=$_POST['btu'];
$watt=$_POST['watt'];
$pk=$_POST['pk'];
$harga=$_POST['harga'];



$foto=$_FILES['foto']['name'];

$tmp=$_FILES['foto']['tmp_name'];



$folder="../upload/produk/";



if(!is_dir($folder)){


mkdir($folder,0777,true);


}




$namaFoto=time()."_".$foto;




if(move_uploaded_file($tmp,$folder.$namaFoto)){



mysqli_query(
$conn,


"INSERT INTO produk

(
brand,
nama_produk,
tipe,
btu,
watt,
pk,
harga,
foto
)

VALUES

(
'$brand',
'$nama',
'$tipe',
'$btu',
'$watt',
'$pk',
'$harga',
'$namaFoto'
)"

);



echo "

<script>

alert('Produk berhasil ditambahkan');

location='produk.php';

</script>

";



}



}










// =========================
// HAPUS PRODUK
// =========================


if(isset($_GET['hapus'])){


$id=$_GET['hapus'];



$ambil=mysqli_query(
$conn,
"SELECT foto FROM produk WHERE id='$id'"
);



$f=mysqli_fetch_assoc($ambil);



if($f){


$file="../upload/produk/".$f['foto'];



if(file_exists($file)){


unlink($file);


}



}




mysqli_query(

$conn,

"DELETE FROM produk WHERE id='$id'"

);



echo "

<script>

location='produk.php';

</script>

";


}







$produk=mysqli_query(

$conn,

"SELECT * FROM produk ORDER BY id DESC"

);


?>








<!DOCTYPE html>

<html>


<head>



<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>



<title>

Kelola Produk

</title>





<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"

rel="stylesheet"

>




<link

rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"

>





<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<link
rel="preconnect"
href="https://fonts.googleapis.com"
>
<link
href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
rel="stylesheet"
>






<style>


:root{

--bg-1:#0f2740;

--bg-2:#123a5e;

--brand:#0061e0;

--brand-2:#00c6fb;

--ink:#14202b;

--muted:#66798c;

--surface:rgba(255,255,255,.92);

--border:rgba(20,40,60,.08);

}



*{

box-sizing:border-box;

}



body{


background:

radial-gradient(circle at 12% 10%, #1c4a72 0%, transparent 45%),

radial-gradient(circle at 90% 0%, #0c3a5c 0%, transparent 40%),

linear-gradient(160deg, var(--bg-1), var(--bg-2) 55%, #0a1f33);


background-attachment:fixed;


font-family:'Plus Jakarta Sans','Segoe UI',sans-serif;


min-height:100vh;


padding-bottom:40px;


color:var(--ink);


}



.container{


max-width:1100px;


padding-top:44px;


}



.topbar{


display:flex;


align-items:center;


justify-content:space-between;


flex-wrap:wrap;


gap:14px;


margin-bottom:28px;


}




.title{


font-weight:800;


color:#ffffff;


font-size:32px;


letter-spacing:-.5px;


margin:0;


}



.back-btn{


display:inline-flex;


align-items:center;


gap:8px;


background:rgba(255,255,255,.1);


border:1px solid rgba(255,255,255,.2);


color:#eaf3fb;


backdrop-filter:blur(6px);


font-weight:600;


padding:10px 20px;


border-radius:14px;


transition:.25s ease;


text-decoration:none;


}



.back-btn:hover{


background:rgba(255,255,255,.18);


color:#fff;


transform:translateX(-2px);


}







.box{


background:var(--surface);


backdrop-filter:blur(10px);


padding:32px;


border-radius:26px;


border:1px solid rgba(255,255,255,.6);



box-shadow:

0 25px 50px -15px rgba(0,0,0,.35);


margin-bottom:28px;


}



.box h4{


font-weight:800;


color:var(--ink);


margin-bottom:22px;


font-size:20px;


display:flex;


align-items:center;


gap:10px;


}



.box h4::before{


content:"";


width:8px;


height:8px;


border-radius:50%;


background:linear-gradient(135deg,var(--brand-2),var(--brand));


display:inline-block;


}



label{


font-size:13.5px;


font-weight:700;


color:var(--muted);


text-transform:uppercase;


letter-spacing:.4px;


margin-bottom:6px;


display:block;


}



.field{


margin-bottom:18px;


}





.form-control,select.form-control{


border-radius:13px;


height:48px;


border:1.5px solid var(--border);


background:#f7f9fc;


font-size:15px;


padding:0 16px;


transition:.2s ease;


}



.form-control:focus,select.form-control:focus{


border-color:var(--brand);


background:#fff;


box-shadow:0 0 0 4px rgba(0,97,224,.12);


}



input[type="file"].form-control{


height:auto;


padding:10px 14px;


line-height:1.6;


}



.btn-primary{


background:linear-gradient(135deg,var(--brand-2),var(--brand));


border:none;


font-weight:700;


padding:13px 30px;


border-radius:14px;


box-shadow:0 12px 24px -8px rgba(0,97,224,.5);


transition:.25s ease;


}



.btn-primary:hover{


transform:translateY(-2px);


box-shadow:0 16px 30px -8px rgba(0,97,224,.6);


}



.btn{


border-radius:14px;


font-weight:700;


font-size:13.5px;


}



.btn-warning{


background:#ffb300;


border:none;


color:#3a2900;


}



.btn-warning:hover{


background:#ffa000;


color:#3a2900;


}



.btn-danger{


background:#ff4d5e;


border:none;


}



.btn-danger:hover{


background:#e63946;


}



.btn-sm{


padding:8px 16px;


}








table{


border-collapse:separate!important;


border-spacing:0;


width:100%;


}



th{


background:linear-gradient(135deg,#123a5e,#0061e0)!important;


color:white!important;


font-weight:700;


font-size:13px;


text-transform:uppercase;


letter-spacing:.5px;


border:none!important;


padding:14px 12px!important;


}



th:first-child{


border-top-left-radius:14px;


}



th:last-child{


border-top-right-radius:14px;


}



td{


border:none!important;


border-bottom:1px solid var(--border)!important;


padding:14px 12px!important;


vertical-align:middle;


font-size:14.5px;


color:var(--ink);


}



tr:last-child td{


border-bottom:none!important;


}



tr:hover td{


background:#f5f9ff;


}



.produk-img{


width:64px;


height:64px;


object-fit:contain;


border-radius:12px;


background:#f2f5f9;


padding:6px;


border:1px solid var(--border);


}



.brand-pill{


display:inline-block;


padding:4px 12px;


border-radius:20px;


background:#eaf1ff;


color:#0061e0;


font-weight:700;


font-size:12.5px;


}



.harga-tag{


font-weight:800;


color:#0a8f4e;


}



.empty-state{


text-align:center;


padding:50px 20px;


color:var(--muted);


}



.empty-state .icon{


font-size:48px;


margin-bottom:10px;


}



.action-cell{


display:flex;


gap:8px;


}









/* MOBILE */


@media(max-width:768px){




.container{


padding-top:26px;


}



.topbar{


flex-direction:column;


align-items:stretch;


text-align:center;


}



.title{


font-size:24px;


text-align:center;


}



.back-btn{


justify-content:center;


}




.box{


padding:20px;


border-radius:22px;


}




.btn-primary{


width:100%;


}



/* card-style table on mobile */

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



.table-responsive tr{


margin-bottom:16px;


border:1px solid var(--border);


border-radius:16px;


overflow:hidden;


box-shadow:0 6px 16px -8px rgba(0,0,0,.12);


padding:14px;


background:#fff;


}



.table-responsive td{


display:flex;


justify-content:space-between;


align-items:center;


border-bottom:1px dashed var(--border)!important;


padding:10px 4px!important;


text-align:right;


}



.table-responsive td:last-child{


border-bottom:none!important;


}



.table-responsive td::before{


content:attr(data-label);


font-weight:700;


color:var(--muted);


font-size:12.5px;


text-transform:uppercase;


letter-spacing:.4px;


text-align:left;


}



.table-responsive td.foto-cell{


justify-content:center;


}



.table-responsive td.foto-cell::before{


display:none;


}



.action-cell{


justify-content:flex-end;


width:100%;


}


}



@media(max-width:400px){


.title{


font-size:21px;


}


}



</style>



</head>










<body>





<div class="container">



<div class="topbar">


<h2 class="title">

Kelola Produk AC

</h2>


<a

href="dashboard.php"

class="back-btn"

>

← Dashboard

</a>


</div>











<div class="box animate__animated animate__fadeInUp">







<h4>


Tambah Produk


</h4>







<form

method="POST"

enctype="multipart/form-data"

>



<div class="row">



<div class="col-md-6 field">

<label>Merk AC</label>

<select

name="brand"

class="form-control"

required

>



<option>Daikin</option>

<option>Gree</option>

<option>Aqua</option>

<option>Midea</option>



</select>

</div>



<div class="col-md-6 field">

<label>Nama Produk</label>

<input

name="nama"

class="form-control"

required

>

</div>



<div class="col-md-6 field">

<label>Type</label>

<input

name="tipe"

class="form-control"

>

</div>



<div class="col-md-6 field">

<label>BTU</label>

<input

name="btu"

class="form-control"

>

</div>



<div class="col-md-6 field">

<label>Watt</label>

<input

name="watt"

class="form-control"

>

</div>



<div class="col-md-6 field">

<label>PK</label>

<input

name="pk"

class="form-control"

>

</div>



<div class="col-md-6 field">

<label>Harga</label>

<input

name="harga"

class="form-control"

required

>

</div>



<div class="col-md-6 field">

<label>Foto Produk</label>

<input

type="file"

name="foto"

class="form-control"

required

>

</div>



</div>






<button

name="simpan"

class="btn btn-primary mt-2"

>


Tambah Produk


</button>








</form>







</div>













<div class="box animate__animated animate__fadeInUp" style="animation-delay:.1s">








<h4>

Daftar Produk

</h4>









<div class="table-responsive">








<table class="table align-middle">








<thead>

<tr>


<th>Foto</th>

<th>Merk</th>

<th>Produk</th>

<th>Type</th>

<th>Harga</th>

<th>Aksi</th>


</tr>

</thead>



<tbody>



<?php

$adaProduk=false;

while($d=mysqli_fetch_assoc($produk)){

$adaProduk=true;

?>







<tr>






<td class="foto-cell" data-label="Foto">


<img

src="../upload/produk/<?= $d['foto']; ?>"

class="produk-img"

>


</td>








<td data-label="Merk">


<span class="brand-pill"><?= $d['brand']; ?></span>


</td>








<td data-label="Produk">


<?= $d['nama_produk']; ?>


</td>








<td data-label="Type">


<?= $d['tipe']; ?>


</td>








<td data-label="Harga">


<span class="harga-tag">Rp <?= number_format((float) preg_replace('/[^0-9.]/','',$d['harga']),0,',','.'); ?></span>


</td>









<td data-label="Aksi">



<div class="action-cell">



<a

href="edit_produk.php?id=<?= $d['id']; ?>"

class="btn btn-warning btn-sm"

>


Edit


</a>








<button

onclick="hapusProduk(<?= $d['id']; ?>)"

class="btn btn-danger btn-sm"

>


Hapus


</button>



</div>








</td>







</tr>








<?php }

if(!$adaProduk){

?>


<tr>

<td colspan="6" style="display:table-cell;text-align:center;">

<div class="empty-state">

<div class="icon">📭</div>

Belum ada produk yang ditambahkan

</div>

</td>

</tr>


<?php } ?>



</tbody>



</table>








</div>









</div>









</div>











<script>



function hapusProduk(id){





Swal.fire({




title:'Hapus Produk?',




text:'Produk AC akan dihapus permanen',




icon:'warning',




showCancelButton:true,




confirmButtonText:'Ya Hapus',




cancelButtonText:'Batal',




confirmButtonColor:'#d33',




showClass:{



popup:'animate__animated animate__zoomIn'



}






}).then((result)=>{






if(result.isConfirmed){





window.location.href=

"produk.php?hapus="+id;





}





});






}





</script>








</body>


</html>