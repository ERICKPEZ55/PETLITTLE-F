<?php
class SessionManager {
    private $timeout;

    public function __construct($timeout = 1800) { // tiempo en segundos (1800 = 30 minutos)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->timeout = $timeout;

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

    // Verifica si el usuario está logueado
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Obtiene el nombre del usuario actual
    public function getUserName() {
        return $_SESSION['user_name'] ?? null;
    }

    // Cierra sesión limpiando la sesión (no hace redirección)
    public function logout() {
        if (session_status() !== PHP_SESSION_NONE) {
            // Limpia variables de sesión y destruye la sesión
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }
    }

    // Valida si la sesión está inactiva por más del tiempo permitido
    private function checkSessionTimeout() {
        if (isset($_SESSION['last_activity'])) {
            $inactividad = time() - $_SESSION['last_activity'];
            if ($inactividad > $this->timeout) {
                $this->logout();
                // No redirige aquí, que lo haga el script que instancia SessionManager
            } else {
                $_SESSION['last_activity'] = time(); // Renueva actividad
            }
        }
    }
}
?>
