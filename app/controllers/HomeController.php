<?php
/**
 * Controlador Home
 * Alianza Inclusiva Tech
 */

class HomeController extends BaseController {

    public function index() {
        $vacantesRecientes = [];
        $estadisticas = [
            'vacantes' => 0,
            'candidatos' => 0,
            'empresas' => 0,
            'contrataciones' => 0
        ];

        if ($this->db) {
            // Obtener vacantes recientes
            $stmt = $this->db->query("
                SELECT v.*, e.nombre_comercial, e.razon_social, e.logo
                FROM vacantes v
                JOIN empresas e ON v.empresa_id = e.id
                WHERE v.estado = 'publicada'
                ORDER BY v.fecha_publicacion DESC
                LIMIT 6
            ");
            $vacantesRecientes = $stmt->fetchAll();

            // Estadísticas
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM vacantes WHERE estado = 'publicada'");
            $estadisticas['vacantes'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM candidatos WHERE activo_busqueda = 1");
            $estadisticas['candidatos'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM empresas WHERE verificada = 1");
            $estadisticas['empresas'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM contrataciones WHERE activo = 1");
            $estadisticas['contrataciones'] = $stmt->fetch()['total'];
        }

        $this->view('home/index', [
            'vacantes' => $vacantesRecientes,
            'estadisticas' => $estadisticas
        ]);
    }
}
