<?php
// conexion local
$host = 'localhost';
$db   = 'prueba';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';


// conexion remota https://byet.host/
// $host = 'sql208.byethost7.com';
// $db   = 'b7_38984310_loginapp';
// $user = 'b7_38984310';
// $pass = 'Eileen123456789*';
// $charset = 'utf8mb4';


// conexion remota InfinityFree  washingtonweb.free.nf
// $host = 'sql105.infinityfree.com';
// $db   = 'if0_38984570_loginapp';
// $user = 'if0_38984570';
// $pass = 'Eileen123456789';
// $charset = 'utf8mb4';


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
} catch (\PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

 