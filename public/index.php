<?php
require_once "../app/auth.php";
$user = Auth::user();
if($user){
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard Institucional</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    margin:0;
    font-family: 'Segoe UI',sans-serif;
    background:#F1F5F9;
    color:#0F172A;
}

/* HEADER */
header{
    display:flex;
    justify-content:space-between;
    padding:20px 40px;
    background:#0F172A;
    color:white;
}
header a{
    color:white;
    margin-left:15px;
    text-decoration:none;
}

/* HERO */
.hero{
    text-align:center;
    padding:100px 20px;
    background:#1E3A8A;
    color:white;
}
.hero h1{
    font-size:42px;
}
.hero p{
    opacity:.9;
}

/* BOTON */
.cta{
    margin-top:25px;
    display:inline-block;
    padding:14px 30px;
    background:#D4AF37;
    color:#0F172A;
    border-radius:8px;
    font-weight:bold;
}

/* SECCIONES */
.section{
    padding:60px;
    text-align:center;
}

/* CARDS */
.cards{
    display:flex;
    justify-content:center;
    gap:20px;
}
.card{
    background:white;
    padding:25px;
    border-radius:10px;
    width:250px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* DEMO */
.demo{
    height:300px;
    background:#334155;
    border-radius:15px;
    margin:auto;
    width:80%;
}

/* PRICING */
.pricing{
    display:flex;
    justify-content:center;
    gap:20px;
}
.plan{
    background:white;
    padding:30px;
    border-radius:10px;
    width:260px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
.popular{
    border:2px solid #D4AF37;
}
.price{
    font-size:36px;
    margin:15px 0;
}

/* CTA FINAL */
.final{
    background:#0F172A;
    color:white;
}

/* FOOTER */
footer{
    text-align:center;
    padding:20px;
    font-size:14px;
}
</style>
</head>

<body>

<header>
    <h2>📊 Data Institucional</h2>
    <div>
        <a href="login.html">Login</a>
        <a href="register.html">Registro</a>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <h1>Plataforma de Análisis Estratégico</h1>
    <p>Accede a información confiable para la toma de decisiones</p>

    <a href="register.html" class="cta">Solicitar acceso</a>
</section>

<!-- VALOR -->
<section class="section">
    <h2>Beneficios del sistema</h2>

    <div class="cards">
        <div class="card">📊 Datos actualizados</div>
        <div class="card">🔐 Seguridad institucional</div>
        <div class="card">📈 Análisis estratégico</div>
    </div>
</section>

<!-- DEMO -->
<section class="section">
    <h2>Vista del Dashboard</h2>
    <div class="demo"></div>
</section>

<!-- PLANES -->
<section class="section">
    <h2>Planes de acceso</h2>

    <div class="pricing">

        <div class="plan">
            <h3>Básico</h3>
            <div class="price">$5</div>
            <p>30 días</p>
            <a href="pagar.php?plan=1" class="cta">Adquirir</a>
        </div>

        <div class="plan popular">
            <h3>Profesional</h3>
            <div class="price">$10</div>
            <p>60 días</p>
            <a href="pagar.php?plan=2" class="cta">Adquirir</a>
        </div>

        <div class="plan">
            <h3>Premium</h3>
            <div class="price">$20</div>
            <p>90 días</p>
            <a href="pagar.php?plan=3" class="cta">Adquirir</a>
        </div>

    </div>
</section>

<!-- CTA FINAL -->
<section class="section final">
    <h2>Acceso exclusivo para usuarios autorizados</h2>
    <p>Regístrate y habilita tu acceso al sistema</p>

    <a href="register.html" class="cta">Crear cuenta</a>
</section>

<footer>
© <?php echo date("Y"); ?> Plataforma Institucional
</footer>

</body>
</html>