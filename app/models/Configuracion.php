<?php
/**
 * Modelo Configuracion
 * Alianza Inclusiva Tech
 */

class Configuracion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function get($clave, $default = null) {
        $stmt = $this->db->prepare("SELECT valor FROM configuraciones WHERE clave = ?");
        $stmt->execute([$clave]);
        $result = $stmt->fetch();
        return $result ? $result['valor'] : $default;
    }

    public function set($clave, $valor, $tipo = 'texto') {
        $stmt = $this->db->prepare("
            INSERT INTO configuraciones (clave, valor, tipo) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE valor = VALUES(valor), fecha_actualizacion = NOW()
        ");
        return $stmt->execute([$clave, $valor, $tipo]);
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM configuraciones ORDER BY clave");
        $configs = [];
        while ($row = $stmt->fetch()) {
            $configs[$row['clave']] = $row;
        }
        return $configs;
    }

    public function delete($clave) {
        $stmt = $this->db->prepare("DELETE FROM configuraciones WHERE clave = ?");
        return $stmt->execute([$clave]);
    }

    public function getMultiple($claves) {
        $placeholders = str_repeat('?,', count($claves) - 1) . '?';
        $stmt = $this->db->prepare("SELECT clave, valor FROM configuraciones WHERE clave IN ($placeholders)");
        $stmt->execute($claves);
        
        $configs = [];
        while ($row = $stmt->fetch()) {
            $configs[$row['clave']] = $row['valor'];
        }
        return $configs;
    }
}
