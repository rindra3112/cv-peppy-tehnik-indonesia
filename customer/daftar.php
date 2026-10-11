<?php

include "../config/database.php";


if(isset($_POST['daftar'])){


    $nama = $_POST['nama'];

    $email = $_POST['email'];


    $password = password_hash(

        $_POST['password'],

        PASSWORD_DEFAULT

    );



    mysqli_query($conn,

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



    echo "

    <script>

    alert('Akun berhasil dibuat');

    location='login.php';

    </script>

    ";


}


?>



<!DOCTYPE html>

<html>

<head>


<title>

Daftar Customer

</title>



<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"

rel="stylesheet">



<link

rel="stylesheet"

href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">





<style>


body{


height:100vh;


background:

linear-gradient(

135deg,

#005b8f,

#00b4d8

);


display:flex;

justify-content:center;

align-items:center;


font-family:'Segoe UI';


}





.register-box{


width:450px;


background:white;


padding:35px;


border-radius:25px;


box-shadow:

0 15px 40px rgba(0,0,0,.25);



animation:muncul .5s;


}





@keyframes muncul{


from{

opacity:0;

transform:translateY(40px);

}


to{

opacity:1;

transform:translateY(0);

}



}






.logo{


text-align:center;

margin-bottom:25px;


}




.logo h3{


font-weight:bold;


color:#005b8f;


}




.logo p{


color:#777;


}






.input-group{


height:50px;


}



.input-group-text{


background:#005b8f;


color:white;


border:none;


}





.form-control{


border-left:none;


}





.form-control:focus{


box-shadow:none;


}







.btn-daftar{


height:50px;


background:#005b8f;


border:none;


border-radius:12px;


font-weight:bold;


}




.btn-daftar:hover{


background:#003f63;


}






.login{


text-align:center;


margin-top:20px;


}





.login a{


color:#005b8f;


font-weight:bold;


text-decoration:none;


}




</style>


</head>






<body>





<div class="register-box">





<div class="logo">


<h3>

❄ CV Peppy Tehnik

</h3>


<p>

Buat Akun Customer

</p>


</div>







<form method="POST">






<label>

Nama Lengkap

</label>



<div class="input-group mb-3">



<span class="input-group-text">

<i class="bi bi-person"></i>

</span>



<input

type="text"

name="nama"

class="form-control"

placeholder="Masukkan nama lengkap"

required>


</div>









<label>

Email

</label>




<div class="input-group mb-3">


<span class="input-group-text">


<i class="bi bi-envelope"></i>


</span>





<input

type="email"

name="email"

class="form-control"

placeholder="Masukkan email"

required>



</div>








<label>

Password

</label>





<div class="input-group mb-4">



<span class="input-group-text">


<i class="bi bi-lock"></i>


</span>




<input

type="password"

name="password"

class="form-control"

placeholder="Buat password"

required>



</div>








<button

name="daftar"

class="btn btn-primary btn-daftar w-100"

>


Daftar Akun


</button>






</form>








<div class="login">


Sudah punya akun?


<a href="login.php">


Login Sekarang


</a>



</div>







</div>





</body>


</html>