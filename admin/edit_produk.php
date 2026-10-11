<?php

session_start();

include "../config/database.php";



// =======================
// CEK LOGIN ADMIN
// =======================

if(!isset($_SESSION['admin'])){


header("location:login.php");


exit;


}




// =======================
// CEK ID PRODUK
// =======================


if(!isset($_GET['id'])){


header("location:produk.php");


exit;


}



$id=$_GET['id'];





$data=mysqli_query(

$conn,

"SELECT * FROM produk WHERE id='$id'"

);



$d=mysqli_fetch_assoc($data);




if(!$d){


header("location:produk.php");


exit;


}





$status="";



// =======================
// UPDATE PRODUK
// =======================


if(isset($_POST['update'])){



$brand=$_POST['brand'];

$nama=$_POST['nama'];

$tipe=$_POST['tipe'];

$btu=$_POST['btu'];

$watt=$_POST['watt'];

$pk=$_POST['pk'];

$harga=$_POST['harga'];





// =======================
// UPDATE DENGAN FOTO
// =======================


if($_FILES['foto']['name']!=""){



$foto=$_FILES['foto']['name'];


$tmp=$_FILES['foto']['tmp_name'];



$namaFoto=time()."_".$foto;




move_uploaded_file(

$tmp,

"../upload/produk/".$namaFoto

);




// hapus foto lama


if(file_exists("../upload/produk/".$d['foto'])){


unlink("../upload/produk/".$d['foto']);


}







mysqli_query(

$conn,

"UPDATE produk SET

brand='$brand',

nama_produk='$nama',

tipe='$tipe',

btu='$btu',

watt='$watt',

pk='$pk',

harga='$harga',

foto='$namaFoto'


WHERE id='$id'"

);



}



// =======================
// UPDATE TANPA FOTO
// =======================

else{


mysqli_query(

$conn,


"UPDATE produk SET

brand='$brand',

nama_produk='$nama',

tipe='$tipe',

btu='$btu',

watt='$watt',

pk='$pk',

harga='$harga'


WHERE id='$id'"

);



}



$status="berhasil";



}



?>








<!DOCTYPE html>

<html>


<head>



<meta

name="viewport"

content="width=device-width, initial-scale=1.0"

>



<title>

Edit Produk

</title>







<!-- BOOTSTRAP -->


<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"

rel="stylesheet"

>







<!-- ANIMATE -->

<link

rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"

>







<!-- SWEET ALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>








<style>


body{


background:#eef5f9;


font-family:'Segoe UI';


min-height:100vh;


padding:20px;


}








.card-edit{


max-width:650px;


margin:30px auto;


background:white;


padding:40px;


border-radius:30px;



box-shadow:

0 20px 45px rgba(0,0,0,.15);


}









.title{


font-weight:800;


color:#005b8f;


text-align:center;


}








.form-control,

.form-select{


height:48px;


border-radius:12px;


}








.btn{


border-radius:18px;


font-weight:600;


height:45px;


}









.foto{


width:180px;


height:160px;


object-fit:contain;


background:#f8f9fa;


border-radius:20px;


padding:10px;


}









/* MOBILE */


@media(max-width:768px){





.card-edit{


padding:25px;


margin-top:10px;


}






.title{


font-size:24px;


}






.foto{


width:130px;


height:120px;


}







.btn{


width:100%;


margin-bottom:8px;


}





}





</style>



</head>









<body>








<div class="card-edit animate__animated animate__fadeInUp">









<h2 class="title">


❄️ Edit Produk AC


</h2>







<p class="text-center text-muted">


CV Peppy Tehnik Indonesia


</p>








<hr>









<form

method="POST"

enctype="multipart/form-data"

>










<label>

Merk AC

</label>







<select

name="brand"

class="form-select mb-3"

>



<option <?=($d['brand']=="Daikin")?'selected':''?>>

Daikin

</option>



<option <?=($d['brand']=="Gree")?'selected':''?>>

Gree

</option>



<option <?=($d['brand']=="Aqua")?'selected':''?>>

Aqua

</option>



<option <?=($d['brand']=="Midea")?'selected':''?>>

Midea

</option>






</select>











<label>Nama Produk</label>


<input

name="nama"

class="form-control mb-3"

value="<?= $d['nama_produk']; ?>"

>









<label>Type</label>


<input

name="tipe"

class="form-control mb-3"

value="<?= $d['tipe']; ?>"

>








<label>BTU</label>


<input

name="btu"

class="form-control mb-3"

value="<?= $d['btu']; ?>"

>








<label>Watt</label>


<input

name="watt"

class="form-control mb-3"

value="<?= $d['watt']; ?>"

>









<label>PK</label>


<input

name="pk"

class="form-control mb-3"

value="<?= $d['pk']; ?>"

>









<label>Harga</label>


<input

name="harga"

class="form-control mb-3"

value="<?= $d['harga']; ?>"

>










<center>


<p>


Foto Sekarang


</p>




<img

src="../upload/produk/<?= $d['foto']; ?>"

class="foto"


>


</center>









<br>









<label>


Ganti Foto


</label>








<input

type="file"

name="foto"

class="form-control mb-4"

>










<button

name="update"

class="btn btn-primary"

>


Update Produk


</button>








<a

href="produk.php"

class="btn btn-secondary"

>


Batal


</a>









</form>








</div>











<?php if($status=="berhasil"){ ?>



<script>



Swal.fire({



title:'Berhasil 🎉',



text:'Produk AC berhasil diperbarui',



icon:'success',



timer:1800,



showConfirmButton:false,



showClass:{



popup:'animate__animated animate__zoomIn'



}




});






setTimeout(()=>{



window.location.href='produk.php';




},1800);




</script>




<?php } ?>









</body>


</html>