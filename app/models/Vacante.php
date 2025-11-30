<?php
/**
 * Modelo Vacante
 * Alianza Inclusiva Tech
 */

class Vacante {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM vacantes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM vacantes WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function findByEmpresa($empresaId) {
        $stmt = $this->db->prepare("SELECT * FROM vacantes WHERE empresa_id = ? ORDER BY fecha_creacion DESC");
        $stmt->execute([$empresaId]);
        return $stmt->fetchAll();
    }

    public function publicadas() {
        $stmt = $this->db->query("
            SELECT v.*, e.nombre_comercial, e.razon_social, e.logo
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.estado = 'publicada'
            ORDER BY v.fecha_publicacion DESC
        ");
        return $stmt->fetchAll();
    }

    public function pendientes() {
        $stmt = $this->db->query("
            SELECT v.*, e.nombre_comercial, e.razon_social
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.estado = 'pendiente'
            ORDER BY v.fecha_creacion DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $slug = $this->generarSlug($data['titulo']);
        
        $stmt = $this->db->prepare("
            INSERT INTO vacantes (empresa_id, titulo, slug, descripcion, requisitos, tipo_contrato,
            jornada, modalidad, salario_minimo, salario_maximo, mostrar_salario, ubicacion, municipio,
            discapacidades_aceptadas, checklist_accesibilidad, score_accesibilidad, estado, 
            fecha_publicacion, plazas_disponibles)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['empresa_id'],
            $data['titulo'],
            $slug,
            $data['descripcion'],
            $data['requisitos'] ?? null,
            $data['tipo_contrato'] ?? 'indefinido',
            $data['jornada'] ?? 'tiempo_completo',
            $data['modalidad'] ?? 'presencial',
            $data['salario_minimo'] ?? null,
            $data['salario_maximo'] ?? null,
            $data['mostrar_salario'] ?? 1,
            $data['ubicacion'] ?? null,
            $data['municipio'] ?? 'Querétaro',
            json_encode($data['discapacidades_aceptadas'] ?? []),
            json_encode($data['checklist_accesibilidad'] ?? []),
            $data['score_accesibilidad'] ?? 0,
            $data['estado'] ?? 'borrador',
            $data['estado'] === 'publicada' ? date('Y-m-d H:i:s') : null,
            $data['plazas_disponibles'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['discapacidades_aceptadas', 'checklist_accesibilidad'])) {
                $value = json_encode($value);
            }
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $stmt = $this->db->prepare("
            UPDATE vacantes SET " . implode(', ', $fields) . ", fecha_actualizacion = NOW() WHERE id = ?
        ");
        return $stmt->execute($values);
    }

    public function cambiarEstado($id, $estado) {
        $stmt = $this->db->prepare("
            UPDATE vacantes SET estado = ?, fecha_actualizacion = NOW() WHERE id = ?
        ");
        $stmt->execute([$estado, $id]);

        if ($estado === 'publicada') {
            $stmt = $this->db->prepare("
                UPDATE vacantes SET fecha_publicacion = NOW() WHERE id = ? AND fecha_publicacion IS NULL
            ");
            $stmt->execute([$id]);
        }

        return true;
    }

    public function incrementarVisitas($id) {
        $stmt = $this->db->prepare("UPDATE vacantes SET visitas = visitas + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function generarSlug($titulo) {
        $slug = generarSlug($titulo);
        
        // Verificar unicidad
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM vacantes WHERE slug = ?");
        $stmt->execute([$slug]);
        
        if ($stmt->fetchColumn() > 0) {
            $slug .= '-' . uniqid();
        }
        
        return $slug;
    }

    public function buscarCompatibles($tipoDiscapacidad, $necesidades = []) {
        $stmt = $this->db->prepare("
            SELECT v.*, e.nombre_comercial, e.razon_social, e.logo
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.estado = 'publicada'
            AND JSON_CONTAINS(v.discapacidades_aceptadas, ?)
            ORDER BY v.score_accesibilidad DESC
        ");
        $stmt->execute(['"' . $tipoDiscapacidad . '"']);
        $vacantes = $stmt->fetchAll();

        // Calcular compatibilidad
        foreach ($vacantes as &$vacante) {
            $checklist = json_decode($vacante['checklist_accesibilidad'], true) ?? [];
            $facilidades = array_keys(array_filter($checklist));
            $vacante['compatibilidad'] = calcularCompatibilidad($necesidades, $facilidades);
        }

        // Ordenar por compatibilidad
        usort($vacantes, function($a, $b) {
            return $b['compatibilidad'] - $a['compatibilidad'];
        });

        return $vacantes;
    }
}
