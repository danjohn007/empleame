<?php
/**
 * Modelo Contratacion
 * Alianza Inclusiva Tech
 */

class Contratacion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM contrataciones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmpresa($empresaId) {
        $stmt = $this->db->prepare("
            SELECT c.*, ca.nombre, ca.apellido_paterno, ca.tipo_discapacidad, ca.rfc as candidato_rfc
            FROM contrataciones c
            JOIN candidatos ca ON c.candidato_id = ca.id
            WHERE c.empresa_id = ?
            ORDER BY c.fecha_contratacion DESC
        ");
        $stmt->execute([$empresaId]);
        return $stmt->fetchAll();
    }

    public function activas($empresaId = null) {
        $sql = "
            SELECT c.*, ca.nombre, ca.apellido_paterno, ca.tipo_discapacidad, ca.rfc as candidato_rfc,
                e.razon_social, e.rfc as empresa_rfc
            FROM contrataciones c
            JOIN candidatos ca ON c.candidato_id = ca.id
            JOIN empresas e ON c.empresa_id = e.id
            WHERE c.activo = 1
        ";
        $params = [];

        if ($empresaId) {
            $sql .= " AND c.empresa_id = ?";
            $params[] = $empresaId;
        }

        $sql .= " ORDER BY c.fecha_contratacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $calculo = calcularDeduccionISR($data['salario_bruto'], $data['porcentaje_discapacidad'] ?? 30);

        $stmt = $this->db->prepare("
            INSERT INTO contrataciones (empresa_id, candidato_id, vacante_id, fecha_contratacion,
            salario_bruto, tipo_contrato, porcentaje_discapacidad, deduccion_isr_mensual)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['empresa_id'],
            $data['candidato_id'],
            $data['vacante_id'] ?? null,
            $data['fecha_contratacion'],
            $data['salario_bruto'],
            $data['tipo_contrato'] ?? 'indefinido',
            $data['porcentaje_discapacidad'] ?? 30,
            $calculo['deduccion']
        ]);
        return $this->db->lastInsertId();
    }

    public function darDeBaja($id, $fechaBaja = null) {
        $stmt = $this->db->prepare("
            UPDATE contrataciones SET activo = 0, fecha_baja = ?, fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$fechaBaja ?? date('Y-m-d'), $id]);
    }

    public function totalesPorEmpresa($empresaId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_empleados,
                SUM(salario_bruto) as total_salarios,
                SUM(deduccion_isr_mensual) as total_deduccion
            FROM contrataciones
            WHERE empresa_id = ? AND activo = 1
        ");
        $stmt->execute([$empresaId]);
        return $stmt->fetch();
    }

    public function reporteTrimestral($empresaId, $trimestre, $ano) {
        $mesInicio = ($trimestre - 1) * 3 + 1;
        $mesFin = $trimestre * 3;
        
        $fechaInicio = "$ano-" . str_pad($mesInicio, 2, '0', STR_PAD_LEFT) . "-01";
        $fechaFin = "$ano-" . str_pad($mesFin, 2, '0', STR_PAD_LEFT) . "-" . date('t', strtotime($fechaFin . "-01"));

        $stmt = $this->db->prepare("
            SELECT c.*, ca.nombre, ca.apellido_paterno, ca.apellido_materno, ca.rfc as candidato_rfc,
                ca.tipo_discapacidad, ca.porcentaje_discapacidad
            FROM contrataciones c
            JOIN candidatos ca ON c.candidato_id = ca.id
            WHERE c.empresa_id = ? 
            AND c.activo = 1
            AND c.fecha_contratacion <= ?
            ORDER BY c.fecha_contratacion
        ");
        $stmt->execute([$empresaId, $fechaFin]);
        return $stmt->fetchAll();
    }
}
