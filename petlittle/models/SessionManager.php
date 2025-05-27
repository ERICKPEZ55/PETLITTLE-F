<?php
class SessionManager {
    private $timeout;

    public function __construct($timeout = 1800000) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->timeout = $timeout;

        // Verifica si la sesión ya tiene actividad previa
        if (!isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
        }

        $this->checkSessionTimeout();
    }

    // Guarda datos del usuario al iniciar sesión
    public function login($userId, $userName) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $userName;
        $_SESSION['last_activity'] = time();
    }

    // Verifica si el usuario sigue logueado
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Obtiene el nombre del usuario actual
    public function getUserName() {
        return $_SESSION['user_name'] ?? null;
    }

    // Cierra sesión limpiando la sesión
    public function logout() {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }

        // Redirige al login (ajusta si lo deseas)
        header("Location: ../login/login.php");
        exit;
    }

    // Valida si la sesión está inactiva por más del tiempo permitido
    private function checkSessionTimeout() {
        if (isset($_SESSION['last_activity'])) {
            $inactividad = time() - $_SESSION['last_activity'];
            if ($inactividad > $this->timeout) {
                $this->logout();
            } else {
                $_SESSION['last_activity'] = time(); // Renueva actividad
            }
        }
    }
}
?>
