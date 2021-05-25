<?php
include("conection.php");

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'GuardarP1':
            insertP1();
            break;
        }
}

function insertP1(){
    $conection = Connect();
    $nombre=$_POST['name1'];
    $puntuacion=$_POST['nameP1'];

        $table = "Usuario";
        $sql = "INSERT INTO $table(userName, puntuacion) VALUES('$nombre',$puntuacion)";
        $result = mysqli_query($conection, $sql);


     mysqli_close($conection);
}


?>