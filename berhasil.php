<?php

session_start();

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesanan Berhasil</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

<style>

:root{
  --ink:#0B1220;
  --panel:#101c33;
  --amber:#F5A524;
  --emerald:#16A34A;
  --slate:#5B6472;
  --heading:#101828;
  --line:#e6e8ec;
}

*{box-sizing:border-box;}

body{
  margin:0;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:40px 20px;
  font-family:'Inter',sans-serif;
  background:
    linear-gradient(var(--ink),var(--ink)) ,
    repeating-linear-gradient(0deg, rgba(255,255,255,.05) 0 1px, transparent 1px 42px),
    repeating-linear-gradient(90deg, rgba(255,255,255,.05) 0 1px, transparent 1px 42px);
  background-blend-mode:normal;
}

.wrap{
  position:relative;
  width:100%;
  max-width:460px;
}

/* stamp badge */
.stamp{
  position:absolute;
  top:-34px;
  right:8px;
  width:86px;
  height:86px;
  border:2px dashed var(--amber);
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  transform:rotate(9deg);
  background:var(--ink);
  z-index:3;
  font-family:'JetBrains Mono',monospace;
  font-size:10px;
  letter-spacing:.08em;
  color:var(--amber);
  text-align:center;
  line-height:1.2;
  animation:stampIn .55s cubic-bezier(.2,1.4,.4,1) .5s both;
}

@keyframes stampIn{
  from{opacity:0; transform:rotate(9deg) scale(1.6);}
  to{opacity:1; transform:rotate(9deg) scale(1);}
}

.slip{
  background:#fff;
  border-radius:18px;
  box-shadow:0 30px 60px -20px rgba(0,0,0,.55);
  padding:44px 40px 0 40px;
  animation:riseIn .6s cubic-bezier(.2,.8,.2,1);
  overflow:hidden;
}

@keyframes riseIn{
  from{opacity:0; transform:translateY(28px);}
  to{opacity:1; transform:translateY(0);}
}

.check-badge{
  width:78px;
  height:78px;
  margin:0 auto 22px;
  border-radius:50%;
  background:linear-gradient(155deg,#1fae63,var(--emerald));
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 10px 24px -6px rgba(22,163,74,.55);
}

.check-badge svg{width:38px;height:38px;}

.check-badge path{
  fill:none;
  stroke:#fff;
  stroke-width:5;
  stroke-linecap:round;
  stroke-linejoin:round;
  stroke-dasharray:48;
  stroke-dashoffset:48;
  animation:draw .5s ease .35s forwards;
}

@keyframes draw{ to{stroke-dashoffset:0;} }

h2{
  font-family:'Space Grotesk',sans-serif;
  font-weight:700;
  font-size:1.55rem;
  color:var(--heading);
  text-align:center;
  margin:0 0 10px;
}

p.lead{
  text-align:center;
  color:var(--slate);
  font-size:.95rem;
  line-height:1.55;
  margin:0;
}

.company{
  display:block;
  margin-top:2px;
  font-family:'Space Grotesk',sans-serif;
  font-weight:600;
  color:var(--heading);
}

.status{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  margin:20px 0 0;
  padding:10px 16px;
  background:#FFF6E7;
  border:1px solid #FBE3B3;
  border-radius:999px;
  font-family:'JetBrains Mono',monospace;
  font-size:.75rem;
  letter-spacing:.04em;
  color:#92650C;
}

.status .dot{
  width:7px;height:7px;border-radius:50%;
  background:var(--amber);
  box-shadow:0 0 0 4px rgba(245,165,36,.25);
  animation:pulse 1.4s infinite;
}

@keyframes pulse{
  0%,100%{opacity:1;}
  50%{opacity:.35;}
}

.perforation{
  height:16px;
  margin:32px -40px 0;
  background:
    radial-gradient(circle at 8px 8px, var(--ink) 8px, transparent 8.5px) 0 -8px/16px 16px repeat-x;
}

.actions{
  display:flex;
  gap:12px;
  padding:24px 0 30px;
}

.actions .btn{
  flex:1;
  border-radius:999px;
  padding:12px 18px;
  font-weight:600;
  font-size:.92rem;
  border:none;
  transition:transform .15s ease, box-shadow .15s ease;
}

.actions .btn:hover{transform:translateY(-2px);}

.btn-home{
  background:var(--ink);
  color:#fff;
}
.btn-home:hover{color:#fff; box-shadow:0 10px 20px -8px rgba(11,18,32,.5);}

.btn-riwayat{
  background:linear-gradient(155deg,#1fae63,var(--emerald));
  color:#fff;
}
.btn-riwayat:hover{color:#fff; box-shadow:0 10px 20px -8px rgba(22,163,74,.5);}

@media (prefers-reduced-motion: reduce){
  .slip, .stamp, .check-badge path, .status .dot{animation:none !important;}
}

@media (max-width:480px){

  body{padding:24px 14px;}

  .wrap{max-width:100%;}

  .stamp{
    width:64px;
    height:64px;
    top:-24px;
    right:0;
    font-size:8.5px;
  }

  .slip{
    padding:34px 22px 0 22px;
    border-radius:16px;
  }

  .check-badge{width:66px; height:66px; margin-bottom:18px;}
  .check-badge svg{width:32px; height:32px;}

  h2{font-size:1.28rem;}

  p.lead{font-size:.88rem;}

  .status{
    font-size:.68rem;
    padding:9px 12px;
    text-align:center;
  }

  .perforation{margin:26px -22px 0;}

  .actions{
    flex-direction:column;
    gap:10px;
    padding:20px 0 26px;
  }

  .actions .btn{
    padding:13px 16px;
    font-size:.88rem;
  }

}

@media (max-width:340px){
  .stamp{display:none;}
}

</style>
</head>

<body>

<div class="wrap">

  <div class="stamp">SELESAI<br>DIPESAN</div>

  <div class="slip">

    <div class="check-badge">
      <svg viewBox="0 0 24 24"><path d="M4 12.5l5 5 11-11"/></svg>
    </div>

    <h2>Pesanan Berhasil</h2>

    <p class="lead">
      Terima kasih sudah melakukan pemesanan di
      <span class="company">CV Peppy Tehnik Indonesia</span>
    </p>

    <div class="status">
      <span class="dot"></span>
      MENUNGGU KONFIRMASI ADMIN
    </div>

    <div class="perforation"></div>

    <div class="actions">
      <a href="index.php" class="btn btn-home">🏠 Kembali Home</a>
      <a href="riwayat.php" class="btn btn-riwayat">📦 Lihat Riwayat</a>
    </div>

  </div>

</div>

</body>
</html>