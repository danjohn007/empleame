<?php
/**
 * Modelo Queja
 * Alianza Inclusiva Tech
 */

class Queja {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT q.*, e.nombre_comercial, e.razon_social
            FROM quejas_anonimas q
            LEFT JOIN empresas e ON q.empresa_id = e.id
            WHERE q.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function all() {
        $stmt = $this->db->query("
            SELECT q.*, e.nombre_comercial, e.razon_social
            FROM quejas_anonimas q
            LEFT JOIN empresas e ON q.empresa_id = e.id
            ORDER BY q.fecha_creacion DESC
        ");
        return $stmt->fetchAll();
    }

    public function pendientes() {
        $stmt = $this->db->query("
            SELECT q.*, e.nombre_comercial, e.razon_social
            FROM quejas_anonimas q
            LEFT JOIN empresas e ON q.empresa_id = e.id
            WHERE q.estado = 'pendiente'
            ORDER BY q.fecha_creacion DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO quejas_anonimas (empresa_id, tipo_queja, descripcion, evidencia)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['empresa_id'] ?? null,
            $data['tipo_queja'],
            $data['descripcion'],
            $data['evidencia'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function cambiarEstado($id, $estado, $notas = null) {
        $stmt = $this->db->prepare("
            UPDATE quejas_anonimas SET estado = ?, notas_admin = ?, fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$estado, $notas, $id]);
    }

    public function estadisticas() {
        $stmt = $this->db->query("
            SELECT estado, COUNT(*) as total
            FROM quejas_anonimas
            GROUP BY estado
        ");
        return $stmt->fetchAll();
    }

    public function porTipo() {
        $stmt = $this->db->query("
            SELECT tipo_queja, COUNT(*) as total
            FROM quejas_anonimas
            GROUP BY tipo_queja
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }
}
