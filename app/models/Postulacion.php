<?php
/**
 * Modelo Postulacion
 * Alianza Inclusiva Tech
 */

class Postulacion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM postulaciones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByVacante($vacanteId) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre, c.apellido_paterno, c.tipo_discapacidad, u.email
            FROM postulaciones p
            JOIN candidatos c ON p.candidato_id = c.id
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE p.vacante_id = ?
            ORDER BY p.fecha_postulacion DESC
        ");
        $stmt->execute([$vacanteId]);
        return $stmt->fetchAll();
    }

    public function findByCandidato($candidatoId) {
        $stmt = $this->db->prepare("
            SELECT p.*, v.titulo, v.slug, e.nombre_comercial
            FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            JOIN empresas e ON v.empresa_id = e.id
            WHERE p.candidato_id = ?
            ORDER BY p.fecha_postulacion DESC
        ");
        $stmt->execute([$candidatoId]);
        return $stmt->fetchAll();
    }

    public function existe($vacanteId, $candidatoId) {
        $stmt = $this->db->prepare("
            SELECT id FROM postulaciones WHERE vacante_id = ? AND candidato_id = ?
        ");
        $stmt->execute([$vacanteId, $candidatoId]);
        return (bool) $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO postulaciones (vacante_id, candidato_id, carta_presentacion, score_compatibilidad)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['vacante_id'],
            $data['candidato_id'],
            $data['carta_presentacion'] ?? null,
            $data['score_compatibilidad'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    public function cambiarEstado($id, $estado, $notas = null) {
        $stmt = $this->db->prepare("
            UPDATE postulaciones SET estado = ?, notas_empresa = ?, fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$estado, $notas, $id]);
    }

    public function estadisticasPorEmpresa($empresaId) {
        $stmt = $this->db->prepare("
            SELECT p.estado, COUNT(*) as total
            FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            WHERE v.empresa_id = ?
            GROUP BY p.estado
        ");
        $stmt->execute([$empresaId]);
        return $stmt->fetchAll();
    }
}
