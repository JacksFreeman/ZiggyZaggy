<?php 

    function Connect(){

        $user     = "Aj67mjPa3a";
        $password = "r0YHKw5g7K";
        $server   = "remotemysql.com";
        $database = "Aj67mjPa3a";

        $conection = mysqli_connect($server,$user,$password);
        mysqli_select_db($conection,$database);

        return $conection;
    }

 ?>