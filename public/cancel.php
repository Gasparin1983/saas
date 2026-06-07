<?php
$mensaje = "❌ Pago cancelado";
$detalle = "No se realizó ningún cobro. Puedes intentarlo nuevamente.";

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago cancelado</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="center">

<div class="card">
    <h2><?php echo $mensaje; ?></h2>
    <p><?php echo $detalle; ?></p>

    <a href="pagar.php" class="btn">Intentar nuevamente</a>
    <br><br>
    <a href="dashboard.php">Volver al sistema</a>
</div>

</body>
</html>