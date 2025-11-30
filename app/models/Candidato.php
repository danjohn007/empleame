<?php
/**
 * Modelo Candidato
 * Alianza Inclusiva Tech
 */

class Candidato {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM candidatos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByUsuarioId($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM candidatos WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetch();
    }

    public function all() {
        $stmt = $this->db->query("
            SELECT c.*, u.email 
            FROM candidatos c 
            JOIN usuarios u ON c.usuario_id = u.id 
            ORDER BY c.nombre
        ");
        return $stmt->fetchAll();
    }

    public function activos() {
        $stmt = $this->db->query("
            SELECT c.*, u.email 
            FROM candidatos c 
            JOIN usuarios u ON c.usuario_id = u.id 
            WHERE c.activo_busqueda = 1
            ORDER BY c.nombre
        ");
        return $stmt->fetchAll();
    }

    public function pendientesVerificacion() {
        $stmt = $this->db->query("
            SELECT c.*, u.email 
            FROM candidatos c 
            JOIN usuarios u ON c.usuario_id = u.id 
            WHERE c.certificado_verificado = 0 AND c.certificado_discapacidad IS NOT NULL
            ORDER BY c.fecha_creacion DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO candidatos (usuario_id, nombre, apellido_paterno, apellido_materno,
            fecha_nacimiento, telefono, municipio, tipo_discapacidad, descripcion_discapacidad,
            porcentaje_discapacidad, dispositivos_apoyo, necesidades_accesibilidad, nivel_estudios,
            certificado_discapacidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['usuario_id'],
            $data['nombre'],
            $data['apellido_paterno'],
            $data['apellido_materno'] ?? null,
            $data['fecha_nacimiento'],
            $data['telefono'] ?? null,
            $data['municipio'] ?? 'Querétaro',
            $data['tipo_discapacidad'],
            $data['descripcion_discapacidad'] ?? null,
            $data['porcentaje_discapacidad'] ?? 0,
            json_encode($data['dispositivos_apoyo'] ?? []),
            json_encode($data['necesidades_accesibilidad'] ?? []),
            $data['nivel_estudios'] ?? null,
            $data['certificado_discapacidad'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['dispositivos_apoyo', 'necesidades_accesibilidad'])) {
                $value = json_encode($value);
            }
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $stmt = $this->db->prepare("
            UPDATE candidatos SET " . implode(', ', $fields) . ", fecha_actualizacion = NOW() WHERE id = ?
        ");
        return $stmt->execute($values);
    }

    public function verificarCertificado($id, $verificado = true) {
        $stmt = $this->db->prepare("
            UPDATE candidatos SET certificado_verificado = ?, fecha_verificacion = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$verificado ? 1 : 0, $id]);
    }

    public function buscarPorTipoDiscapacidad($tipo) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.email 
            FROM candidatos c 
            JOIN usuarios u ON c.usuario_id = u.id 
            WHERE c.tipo_discapacidad = ? AND c.activo_busqueda = 1
            ORDER BY c.nombre
        ");
        $stmt->execute([$tipo]);
        return $stmt->fetchAll();
    }
}
