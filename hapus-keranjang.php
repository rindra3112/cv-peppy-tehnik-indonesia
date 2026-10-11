<?php

session_start();



if(isset($_GET['id'])){


$id=$_GET['id'];



unset($_SESSION['keranjang'][$id]);


}



header("location:lihat-keranjang.php");


exit;



?>