<?php
require_once "../app/db.php";

$token = $_COOKIE['TOKEN'];

$db = DB::connect();

$db->prepare("UPDATE sesiones SET activa=false WHERE token=:t")
   ->execute(array('t'=>$token));

setcookie("TOKEN","",time()-3600,"/");

header("Location: ../public/index.php");