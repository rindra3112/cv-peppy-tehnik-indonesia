<?php

include "config/database.php";


if(isset($_POST['kirim'])){


mysqli_query(
$conn,

"INSERT INTO testimoni VALUES
(
NULL,
'$_POST[nama]',
'$_POST[pesan]'
)"

);

}



$data=mysqli_query(
$conn,
"SELECT * FROM testimoni"
);


?>


<h2>
Testimoni Pelanggan
</h2>


<form method="POST">


<input name="nama"
placeholder="Nama">


<textarea name="pesan"
placeholder="Pesan">
</textarea>


<button name="kirim">

Kirim

</button>


</form>



<hr>



<?php while($d=mysqli_fetch_array($data)){ ?>


<h4>

<?= $d['nama'] ?>

</h4>


<p>

<?= $d['pesan'] ?>

</p>


<?php } ?>