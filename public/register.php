<?php
require_once "../app/db.php";

// 🔐 VALIDAR MÉTODO POST
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: register.html");
    exit;
}

// 🔍 CAPTURAR Y LIMPIAR DATOS
$nombre   = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// 🚨 VALIDACIONES BÁSICAS
if(empty($nombre) || empty($email) || empty($password)){
    header("Location: register.html?error=campos");
    exit;
}

// 📧 VALIDAR EMAIL FORMATO
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    header("Location: register.html?error=email_invalido");
    exit;
}

// 🔐 HASH SEGURO
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {

    $db = DB::connect();

    // 🔍 VERIFICAR SI YA EXISTE
    $check = $db->prepare("SELECT id FROM usuarios WHERE email = :email");
    $check->execute(['email' => $email]);

    if($check->rowCount() > 0){
        header("Location: register.html?error=email");
        exit;
    }

    // ✅ INSERTAR USUARIO
    $stmt = $db->prepare("
        INSERT INTO usuarios (nombre, email, password, estado)
        VALUES (:nombre, :email, :password, true)
    ");

    $stmt->execute([
        'nombre'   => $nombre,
        'email'    => $email,
        'password' => $passwordHash
    ]);

    // 🎉 REDIRIGIR A LOGIN CON MENSAJE
    header("Location: login.html?success=1");
    exit;

} catch (PDOException $e){

    // ⚠ ERROR CONTROLADO (NO EXPONER DETALLES EN PRODUCCIÓN)
    header("Location: register.html?error=server");
    exit;
}