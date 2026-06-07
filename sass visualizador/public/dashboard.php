<?php
require_once "../app/middleware.php";
$user = verificarSesion();
?>

<h1>Bienvenido <?php echo $user['nombre']; ?></h1>
<a href="logout.php">Cerrar sesión</a>