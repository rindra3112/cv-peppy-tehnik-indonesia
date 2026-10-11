<?php

session_start();


// CEK ID

if(!isset($_GET['id'])){


header("location:index.php");

exit;


}



$id=$_GET['id'];



// jika produk sudah ada tambah qty

if(isset($_SESSION['keranjang'][$id])){


$_SESSION['keranjang'][$id] += 1;


}else{


$_SESSION['keranjang'][$id] = 1;


}




// kembali ke home

if(isset($_GET['back'])){


header("location:index.php");

exit;


}



// kalau akses manual

header("location:lihat-keranjang.php");

exit;



?>