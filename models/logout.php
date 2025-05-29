<?php
require_once __DIR__ . '/SessionManager.php';

$session = new SessionManager();
$session->logout();

// Redirige después de cerrar sesión
header('Location: login.php');
exit;
