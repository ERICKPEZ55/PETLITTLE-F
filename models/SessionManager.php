<?php
class SessionManager {
    private $timeout;

    public function __construct($timeout = 1000) { 
        // Inicia la sesión si aún no ha iniciado
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //  Cabeceras para evitar caché del navegador (seguridad)
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->timeout = $timeout;

        // Inicializa la marca de tiempo de actividad si no existe
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

    // Devuelve el nombre del usuario si existe
    public function getUserName() {
        return $_SESSION['user_name'] ?? null;
    }

    // Cierra sesión limpiando variables y cookies
    public function logout() {
        if (session_status() !== PHP_SESSION_NONE) {
            // Limpia todas las variables de sesión
            $_SESSION = [];

            // Elimina la cookie de sesión
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Destruye la sesión
            session_destroy();
        }
    }

    // Verifica si ha pasado el tiempo de inactividad permitido
    private function checkSessionTimeout() {
        if (isset($_SESSION['last_activity'])) {
            $inactividad = time() - $_SESSION['last_activity'];
            if ($inactividad > $this->timeout) {
                $this->logout();
                // ⚠️ Importante: no redirige automáticamente. Hazlo desde fuera si lo necesitas.
            } else {
                $_SESSION['last_activity'] = time(); // Renueva actividad
            }
        }
    }
}
?>
