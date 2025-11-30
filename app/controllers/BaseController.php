<?php
/**
 * Controlador Base
 * Alianza Inclusiva Tech
 */

class BaseController {
    protected $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (Exception $e) {
            // La base de datos puede no estar configurada
            $this->db = null;
        }
    }

    protected function view($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . '/layouts/header.php';
        require_once VIEWS_PATH . '/' . $view . '.php';
        require_once VIEWS_PATH . '/layouts/footer.php';
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
    }

    protected function getGet($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
    }

    protected function validateCSRF() {
        $token = $this->getPost('csrf_token');
        if (!$token || !verifyCSRFToken($token)) {
            setFlash('error', 'Token de seguridad inválido');
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    protected function log($accion, $descripcion = null) {
        if (!$this->db) return;
        
        $stmt = $this->db->prepare("
            INSERT INTO logs_actividad (usuario_id, accion, descripcion, ip, user_agent) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $accion,
            $descripcion,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
}
