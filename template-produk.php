<link href="
https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">


<div class="container mt-5">


<a href="index.php"
class="btn btn-secondary mb-3">

← Kembali

</a>


<div class="row">


<?php while($d=mysqli_fetch_array($data)){ ?>


<div class="col-md-3 mb-4">


<div class="card shadow p-3">


<img

src="upload/produk/<?= $d['foto']; ?>"

height="180"

style="object-fit:contain"

>


<h3>

<?= $d['brand']; ?>

</h3>


<h5>

<?= $d['nama_produk']; ?>

</h5>


<p>

<?= $d['tipe']; ?>

</p>



<h4>

Rp <?= $d['harga']; ?>

</h4>


<a

href="keranjang.php?id=<?= $d['id']; ?>"

class="btn btn-primary w-100"

>

🛒 Masukkan Keranjang

</a>


<br>


<a

href="checkout.php?id=<?= $d['id']; ?>"

class="btn btn-success w-100"

>

Pesan Sekarang

</a>


</div>


</div>


<?php } ?>


</div>


</div>