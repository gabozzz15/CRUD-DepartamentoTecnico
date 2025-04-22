<?php

function connection(){

    $host = "localhost";
    $user = "root";
    $pass = "";

    $db = "sistema_dept_tecnico";

    $connect=mysqli_connect($host, $user, $pass);

    mysqli_select_db($connect, $db);

    // Verificar conexión
    if ($connect->connect_error) {
        die("Conexión fallida: " . $connect->connect_error);
    }   

    return $connect;

}
?>