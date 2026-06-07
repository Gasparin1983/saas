<?php
require_once __DIR__ . "/../app/db.php";

$raw = file_get_contents("php://input");
$req = 'cmd=_notify-validate&'.$raw;

// 🔐 VALIDAR CON PAYPAL
$ch = curl_init("https://ipnpb.paypal.com/cgi-bin/webscr");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($ch);
curl_close($ch);

if($res == "VERIFIED"){

    $txn     = $_POST['txn_id'];
    $status  = $_POST['payment_status'];
    $custom  = $_POST['custom'];
    $monto   = $_POST['mc_gross'];
    $moneda  = $_POST['mc_currency'];
    $correo  = $_POST['receiver_email'];

    // ❌ SOLO PAGOS COMPLETADOS
    if($status != "Completed") exit;

    // ❌ VALIDAR TU CUENTA PAYPAL
    if($correo != "lxsrobalino@gmail.com") exit;

    // ❌ VALIDAR MONEDA
    if($moneda != "USD") exit;

    list($user,$plan) = explode("|", $custom);

    $db = DB::connect();

    // ❌ EVITAR DUPLICADOS
    $ex = $db->prepare("SELECT id FROM pagos WHERE txn_id=:t");
    $ex->execute(['t'=>$txn]);
    if($ex->rowCount() > 0) exit;

    // 🔒 OBTENER PLAN
    $stmt = $db->prepare("SELECT * FROM planes WHERE id=:id");
    $stmt->execute(['id'=>$plan]);
    $planData = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$planData) exit;

    // ❌ VALIDAR MONTO
    if($monto != $planData['precio']) exit;

    // 💾 GUARDAR PAGO
    $db->prepare("INSERT INTO pagos(usuario_id,plan_id,txn_id,monto,estado)
                  VALUES(:u,:p,:t,:m,'ok')")
       ->execute([
        'u'=>$user,
        'p'=>$plan,
        't'=>$txn,
        'm'=>$monto
       ]);

    // 🚀 ACTIVAR SUSCRIPCIÓN
    $db->prepare("
        INSERT INTO suscripciones(usuario_id,estado,fecha_fin)
        VALUES(:u,'activa', NOW() + INTERVAL '".$planData['dias']." days')
    ")->execute(['u'=>$user]);
}