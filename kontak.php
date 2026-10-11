<?php

include "config/database.php";


if(isset($_POST['submit'])){


$nama=$_POST['nama'];

$hp=$_POST['hp'];

$alamat=$_POST['alamat'];

$layanan=$_POST['layanan'];

$tanggal=$_POST['tanggal'];



mysqli_query($conn,
"INSERT INTO booking VALUES(
NULL,
'$nama',
'$hp',
'$alamat',
'$layanan',
'$tanggal',
NOW()
)"
);


echo "Booking berhasil dikirim";

}

?>


<html>

<a

href="
https://wa.me/6281382581571?
text=Halo%20Peppy%20Teknik,%20Saya%20ingin%20service%20AC
"


class="wa">


Chat WhatsApp

</a>
<body>


<h1>
Booking Service AC
</h1>



<form method="POST">


<input 
type="text"
name="nama"
placeholder="Nama"
required>


<br>



<input
type="text"
name="hp"
placeholder="No Whatsapp"
required>


<br>



<textarea name="alamat"
placeholder="Alamat">
</textarea>


<br>


<select name="layanan">

<option>
Cuci AC
</option>

<option>
Service AC
</option>


<option>
Isi Freon
</option>


</select>


<br>



<input
type="date"
name="tanggal">


<br>


<button name="submit">
Booking
</button>



</form>



</body>


</html>