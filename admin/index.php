<?php
require_once "../app/db.php";
$db = DB::connect();

$users = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$ventas = $db->query("SELECT SUM(monto) FROM pagos")->fetchColumn();
?>

<h1>Admin</h1>
Usuarios: <?php echo $users; ?><br>
Ingresos: $<?php echo $ventas; ?>