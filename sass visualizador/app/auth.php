<?php
require_once "db.php";

class Auth {

    // 🔑 LOGIN
    public static function login($email, $password){

        $db = DB::connect();

        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email=:e LIMIT 1");
        $stmt->execute(['e'=>$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return ["ok"=>false, "msg"=>"Usuario no encontrado"];
        }

        if(!password_verify($password, $user['password'])){
            return ["ok"=>false, "msg"=>"Contraseña incorrecta"];
        }

        // 🔥 SOLO 1 SESIÓN ACTIVA
        $db->prepare("UPDATE sesiones SET activa=false WHERE usuario_id=:u")
           ->execute(['u'=>$user['id']]);

        $token = bin2hex(random_bytes(32));

        $db->prepare("
            INSERT INTO sesiones(usuario_id,token,ip,user_agent)
            VALUES(:u,:t,:ip,:ua)
        ")->execute([
            'u'=>$user['id'],
            't'=>$token,
            'ip'=>$_SERVER['REMOTE_ADDR'],
            'ua'=>$_SERVER['HTTP_USER_AGENT']
        ]);

        // cookie segura
        setcookie("TOKEN", $token, time()+86400, "/", "", false, true);

        return ["ok"=>true];
    }

    // 📝 REGISTRO
    public static function register($nombre, $email, $password){

        $db = DB::connect();

        // validar si existe
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE email=:e");
        $stmt->execute(['e'=>$email]);

        if($stmt->rowCount() > 0){
            return ["ok"=>false, "msg"=>"El correo ya está registrado"];
        }

        // hash seguro
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $db->prepare("
            INSERT INTO usuarios(nombre,email,password)
            VALUES(:n,:e,:p)
        ")->execute([
            'n'=>$nombre,
            'e'=>$email,
            'p'=>$hash
        ]);

        return ["ok"=>true];
    }

    // 🔓 LOGOUT
    public static function logout(){

        if(!isset($_COOKIE['TOKEN'])) return;

        $db = DB::connect();

        $db->prepare("UPDATE sesiones SET activa=false WHERE token=:t")
           ->execute(['t'=>$_COOKIE['TOKEN']]);

        setcookie("TOKEN","",time()-3600,"/");
    }

    // 👤 OBTENER USUARIO ACTUAL
    public static function user(){

        if(!isset($_COOKIE['TOKEN'])) return null;

        $db = DB::connect();

        $stmt = $db->prepare("
            SELECT u.*
            FROM sesiones s
            JOIN usuarios u ON u.id = s.usuario_id
            WHERE s.token=:t AND s.activa=true
        ");

        $stmt->execute(['t'=>$_COOKIE['TOKEN']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔐 VERIFICAR LOGIN
    public static function check(){

        $user = self::user();

        if(!$user){
            header("Location: login.html");
            exit;
        }

        return $user;
    }

    // 💳 VERIFICAR SUSCRIPCIÓN
    public static function checkSubscription($user_id){

        $db = DB::connect();

        $stmt = $db->prepare("
            SELECT * FROM suscripciones
            WHERE usuario_id=:u 
            AND estado='activa'
            AND fecha_fin > NOW()
        ");

        $stmt->execute(['u'=>$user_id]);

        return $stmt->rowCount() > 0;
    }

}