<?php
/**
 * Controlador de Vacantes
 * Alianza Inclusiva Tech
 */

class VacanteController extends BaseController {

    public function listar() {
        $filtros = [];
        $params = [];

        $busqueda = $this->getGet('q');
        $municipio = $this->getGet('municipio');
        $modalidad = $this->getGet('modalidad');
        $tipoDiscapacidad = $this->getGet('tipo_discapacidad');
        $salarioMin = $this->getGet('salario_min');

        $sql = "
            SELECT v.*, e.nombre_comercial, e.razon_social, e.logo
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.estado = 'publicada'
        ";

        if ($busqueda) {
            $sql .= " AND (v.titulo LIKE ? OR v.descripcion LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }

        if ($municipio) {
            $sql .= " AND v.municipio = ?";
            $params[] = $municipio;
        }

        if ($modalidad) {
            $sql .= " AND v.modalidad = ?";
            $params[] = $modalidad;
        }

        if ($tipoDiscapacidad) {
            $sql .= " AND JSON_CONTAINS(v.discapacidades_aceptadas, ?)";
            $params[] = '"' . $tipoDiscapacidad . '"';
        }

        if ($salarioMin) {
            $sql .= " AND (v.salario_minimo >= ? OR v.salario_maximo >= ?)";
            $params[] = $salarioMin;
            $params[] = $salarioMin;
        }

        $sql .= " ORDER BY v.score_accesibilidad DESC, v.fecha_publicacion DESC";

        if ($this->db) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $vacantes = $stmt->fetchAll();
        } else {
            $vacantes = [];
        }

        // Municipios de Querétaro para el filtro
        $municipios = [
            'Querétaro', 'El Marqués', 'Corregidora', 'Huimilpan',
            'San Juan del Río', 'Tequisquiapan', 'Pedro Escobedo',
            'Amealco', 'Cadereyta', 'Colón', 'Ezequiel Montes',
            'Jalpan', 'Arroyo Seco', 'Landa de Matamoros', 'Peñamiller',
            'Pinal de Amoles', 'San Joaquín', 'Tolimán'
        ];

        $this->view('vacantes/listar', [
            'vacantes' => $vacantes,
            'municipios' => $municipios,
            'filtros' => [
                'q' => $busqueda,
                'municipio' => $municipio,
                'modalidad' => $modalidad,
                'tipo_discapacidad' => $tipoDiscapacidad,
                'salario_min' => $salarioMin
            ]
        ]);
    }

    public function ver($slug) {
        if (!$this->db) {
            setFlash('error', 'Error de conexión');
            redirect('/vacantes');
        }

        $stmt = $this->db->prepare("
            SELECT v.*, e.nombre_comercial, e.razon_social, e.logo, e.descripcion as empresa_descripcion,
                e.sector_industrial, e.tamano_empresa, e.verificada
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.slug = ?
        ");
        $stmt->execute([$slug]);
        $vacante = $stmt->fetch();

        if (!$vacante) {
            setFlash('error', 'Vacante no encontrada');
            redirect('/vacantes');
        }

        // Incrementar visitas
        $stmt = $this->db->prepare("UPDATE vacantes SET visitas = visitas + 1 WHERE id = ?");
        $stmt->execute([$vacante['id']]);

        // Decodificar JSON
        $vacante['checklist_accesibilidad'] = json_decode($vacante['checklist_accesibilidad'], true) ?? [];
        $vacante['discapacidades_aceptadas'] = json_decode($vacante['discapacidades_aceptadas'], true) ?? [];

        // Verificar si el candidato ya postuló
        $yaPostulo = false;
        $compatibilidad = 0;
        if (isLoggedIn() && $_SESSION['user_tipo'] === 'candidato') {
            $stmt = $this->db->prepare("SELECT id FROM candidatos WHERE usuario_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $candidato = $stmt->fetch();

            if ($candidato) {
                $stmt = $this->db->prepare("SELECT id FROM postulaciones WHERE vacante_id = ? AND candidato_id = ?");
                $stmt->execute([$vacante['id'], $candidato['id']]);
                $yaPostulo = (bool) $stmt->fetch();

                // Calcular compatibilidad
                $stmt = $this->db->prepare("SELECT necesidades_accesibilidad FROM candidatos WHERE id = ?");
                $stmt->execute([$candidato['id']]);
                $datosC = $stmt->fetch();
                $necesidades = json_decode($datosC['necesidades_accesibilidad'], true) ?? [];
                $facilidades = array_keys(array_filter($vacante['checklist_accesibilidad']));
                $compatibilidad = calcularCompatibilidad($necesidades, $facilidades);
            }
        }

        // Vacantes similares
        $stmt = $this->db->prepare("
            SELECT v.*, e.nombre_comercial
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.estado = 'publicada' AND v.id != ? AND v.empresa_id = ?
            LIMIT 3
        ");
        $stmt->execute([$vacante['id'], $vacante['empresa_id']]);
        $vacantesSimilares = $stmt->fetchAll();

        $this->view('vacantes/ver', [
            'vacante' => $vacante,
            'yaPostulo' => $yaPostulo,
            'compatibilidad' => $compatibilidad,
            'vacantesSimilares' => $vacantesSimilares
        ]);
    }
}
