<?php

include "config/database.php";

$data=mysqli_query(
$conn,
"SELECT * FROM galeri"
);


?>


<h2>
Hasil Pekerjaan Kami
</h2>



<?php while($d=mysqli_fetch_array($data)){ ?>


<img

src="upload/<?= $d['foto']?>"

width="250"


>


<h4>

<?= $d['judul'] ?>

</h4>



<?php } ?>