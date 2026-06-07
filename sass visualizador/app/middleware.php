<?php
require_once "db.php";

function verificarSesion(){
    if(!isset($_COOKIE['TOKEN'])) die("No autorizado");

    $db = DB::connect();

    $stmt = $db->prepare("
        SELECT u.*, s.usuario_id
        FROM sesiones s
        JOIN usuarios u ON u.id=s.usuario_id
        WHERE s.token=:t AND s.activa=true
    ");

    $stmt->execute(['t'=>$_COOKIE['TOKEN']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user) die("Sesión inválida");

    // validar suscripción
    $stmt = $db->prepare("
        SELECT * FROM suscripciones
        WHERE usuario_id=:u AND estado='activa' AND fecha_fin > NOW()
    ");
    $stmt->execute(['u'=>$user['id']]);

    if($stmt->rowCount()==0){
        header("Location: pagar.php");
        exit;
    }

    return $user;
}