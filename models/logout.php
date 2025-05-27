<?php
require_once 'SessionManager.php';

$session = new SessionManager();
$session->logout();

header('Location:../views/Usuario/agendamiento.php');
exit;
