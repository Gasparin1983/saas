<?php
require_once "../app/db.php";

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: login.html");
    exit;
}

$db = DB::connect();

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$pass  = isset($_POST['password']) ? $_POST['password'] : '';

// 🚨 VALIDAR CAMPOS
if(empty($email) || empty($pass)){
    header("Location: login.html?error=campos");
    exit;
}

// 🔎 BUSCAR USUARIO (SIN FILTRAR ESTADO)
$stmt = $db->prepare("SELECT * FROM usuarios WHERE email=:e");
$stmt->execute(['e'=>$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ❌ NO EXISTE
if(!$user){
    header("Location: login.html?error=usuario");
    exit;
}

// 🚫 USUARIO INACTIVO
if(!$user['estado']){
    header("Location: login.html?error=inactivo");
    exit;
}

// 🔐 PASSWORD
if(!password_verify($pass,$user['password'])){
    header("Location: login.html?error=pass");
    exit;
}

// 🔥 CERRAR SESIONES
$db->prepare("UPDATE sesiones SET activa=false WHERE usuario_id=:u")
   ->execute(['u'=>$user['id']]);

// 🔐 TOKEN (PHP 5.6 compatible)
if(function_exists('random_bytes')){
    $token = bin2hex(random_bytes(32));
}else{
    $token = bin2hex(openssl_random_pseudo_bytes(32));
}

// 🔥 NUEVA SESIÓN
$db->prepare("INSERT INTO sesiones(usuario_id,token,ip,user_agent,activa)
              VALUES(:u,:t,:ip,:ua,true)")
   ->execute([
    'u'=>$user['id'],
    't'=>$token,
    'ip'=>$_SERVER['REMOTE_ADDR'],
    'ua'=>$_SERVER['HTTP_USER_AGENT']
   ]);

// 🍪 COOKIE
setcookie("TOKEN",$token,time()+86400,"/");

// 🚀 OK
header("Location: dashboard.php");
exit;
