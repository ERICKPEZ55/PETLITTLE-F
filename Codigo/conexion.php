<?php
$conex =mysqli_connect ("localhost","rooot","1234567","petlittle","3306");
if ($conex) {
    echo "conexion a exitosa";
} else {
    echo"error en la conexion"
}
?>
