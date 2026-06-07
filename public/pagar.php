<?php
require_once __DIR__ . "/../app/middleware.php";
require_once __DIR__ . "/../app/db.php";

$user = verificarSesion();

// ✅ COMPATIBLE PHP 5
$plan_id = isset($_GET['plan']) ? (int)$_GET['plan'] : 1;

$db = DB::connect();

// 🔒 EVITAR SQL INJECTION
$stmt = $db->prepare("SELECT * FROM planes WHERE id = :id");
$stmt->execute(['id' => $plan_id]);
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

// ❌ SI NO EXISTE PLAN
if(!$plan){
    die("Plan no válido");
}

// 🔐 IDENTIFICADOR DE PAGO
$custom = $user['id']."|".$plan['id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pagar Plan</title>
</head>

<body>

<h2>Pagar acceso al dashboard</h2>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">

    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="lxsrobalino@gmail.com">

    <input type="hidden" name="item_name" value="Acceso Dashboard Plan <?php echo $plan['nombre']; ?>">

    <input type="hidden" name="amount" value="<?php echo $plan['precio']; ?>">
    <input type="hidden" name="currency_code" value="USD">

    <input type="hidden" name="notify_url" value="https://TU_DOMINIO/public/ipn.php">
    <input type="hidden" name="return" value="https://TU_DOMINIO/public/success.php">

    <input type="hidden" name="custom" value="<?php echo $custom; ?>">

    <button type="submit">💳 Pagar con PayPal</button>

</form>

</body>
</html>