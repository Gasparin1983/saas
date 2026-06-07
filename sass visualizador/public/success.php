<?php
require_once "../app/db.php";

$mensaje = "✅ Pago recibido correctamente";
$detalle = "Tu suscripción se está activando automáticamente. Esto puede tardar unos segundos.";

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago exitoso</title>
<link rel="stylesheet" href="css/style.css">
<meta http-equiv="refresh" content="5;url=dashboard.php">
</head>
<body class="center">

<div class="card">
    <h2><?php echo $mensaje; ?></h2>
    <p><?php echo $detalle; ?></p>

    <p>Serás redirigido al sistema...</p>

    <a href="dashboard.php" class="btn">Ir ahora</a>
</div>

</body>
</html>