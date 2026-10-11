<?php

session_start();


// HAPUS SESSION CUSTOMER

unset($_SESSION['customer']);

unset($_SESSION['nama_customer']);


// HANCURKAN SESSION JIKA KOSONG

session_destroy();


// KEMBALI KE HOME

header("location:../index.php");

exit;


?>