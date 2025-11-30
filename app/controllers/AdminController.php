<?php
/**
 * Controlador de Administración
 * Alianza Inclusiva Tech
 */

class AdminController extends BaseController {

    public function dashboard() {
        requireRole('admin');

        $estadisticas = [];

        if ($this->db) {
            // Estadísticas generales
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM candidatos");
            $estadisticas['candidatos'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM candidatos WHERE certificado_verificado = 0 AND certificado_discapacidad IS NOT NULL");
            $estadisticas['pendientes_verificar'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM empresas");
            $estadisticas['empresas'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM vacantes WHERE estado = 'publicada'");
            $estadisticas['vacantes_activas'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM vacantes WHERE estado = 'pendiente'");
            $estadisticas['vacantes_pendientes'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM contrataciones WHERE activo = 1");
            $estadisticas['contrataciones'] = $stmt->fetch()['total'];

            $stmt = $this->db->query("SELECT COUNT(*) as total FROM quejas_anonimas WHERE estado = 'pendiente'");
            $estadisticas['quejas_pendientes'] = $stmt->fetch()['total'];

            // Últimas actividades
            $stmt = $this->db->query("
                SELECT * FROM logs_actividad
                ORDER BY fecha_creacion DESC
                LIMIT 10
            ");
            $ultimasActividades = $stmt->fetchAll();

            // Candidatos recientes sin verificar
            $stmt = $this->db->query("
                SELECT c.*, u.email
                FROM candidatos c
                JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.certificado_verificado = 0 AND c.certificado_discapacidad IS NOT NULL
                ORDER BY c.fecha_creacion DESC
                LIMIT 5
            ");
            $candidatosPendientes = $stmt->fetchAll();
        } else {
            $estadisticas = [
                'candidatos' => 0,
                'pendientes_verificar' => 0,
                'empresas' => 0,
                'vacantes_activas' => 0,
                'vacantes_pendientes' => 0,
                'contrataciones' => 0,
                'quejas_pendientes' => 0
            ];
            $ultimasActividades = [];
            $candidatosPendientes = [];
        }

        $this->view('admin/dashboard', [
            'estadisticas' => $estadisticas,
            'ultimasActividades' => $ultimasActividades,
            'candidatosPendientes' => $candidatosPendientes
        ]);
    }

    public function candidatos() {
        requireRole('admin');

        $stmt = $this->db->query("
            SELECT c.*, u.email, u.activo as usuario_activo
            FROM candidatos c
            JOIN usuarios u ON c.usuario_id = u.id
            ORDER BY c.fecha_creacion DESC
        ");
        $candidatos = $stmt->fetchAll();

        $this->view('admin/candidatos', ['candidatos' => $candidatos]);
    }

    public function verCandidato($id) {
        requireRole('admin');

        $stmt = $this->db->prepare("
            SELECT c.*, u.email, u.activo as usuario_activo, u.fecha_creacion as fecha_registro
            FROM candidatos c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $candidato = $stmt->fetch();

        if (!$candidato) {
            setFlash('error', 'Candidato no encontrado');
            redirect('/admin/candidatos');
        }

        $candidato['dispositivos_apoyo'] = json_decode($candidato['dispositivos_apoyo'], true) ?? [];
        $candidato['necesidades_accesibilidad'] = json_decode($candidato['necesidades_accesibilidad'], true) ?? [];

        // Postulaciones del candidato
        $stmt = $this->db->prepare("
            SELECT p.*, v.titulo, e.nombre_comercial
            FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            JOIN empresas e ON v.empresa_id = e.id
            WHERE p.candidato_id = ?
            ORDER BY p.fecha_postulacion DESC
        ");
        $stmt->execute([$id]);
        $postulaciones = $stmt->fetchAll();

        $this->view('admin/candidato_detalle', [
            'candidato' => $candidato,
            'postulaciones' => $postulaciones
        ]);
    }

    public function verificarCertificado($candidatoId) {
        requireRole('admin');
        $this->validateCSRF();

        $accion = $this->getPost('accion');
        $notas = $this->getPost('notas');

        if ($accion === 'aprobar') {
            $stmt = $this->db->prepare("
                UPDATE candidatos SET certificado_verificado = 1, fecha_verificacion = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$candidatoId]);
            $this->log('verificar_certificado', "Certificado aprobado para candidato ID: $candidatoId");
            setFlash('success', 'Certificado verificado correctamente');
        } else {
            $stmt = $this->db->prepare("
                UPDATE candidatos SET certificado_verificado = 0, certificado_discapacidad = NULL
                WHERE id = ?
            ");
            $stmt->execute([$candidatoId]);
            $this->log('rechazar_certificado', "Certificado rechazado para candidato ID: $candidatoId. Notas: $notas");
            setFlash('warning', 'Certificado rechazado. Se ha notificado al candidato.');
        }

        redirect('/admin/candidato/' . $candidatoId);
    }

    public function empresas() {
        requireRole('admin');

        $stmt = $this->db->query("
            SELECT e.*, u.email, u.activo as usuario_activo,
                (SELECT COUNT(*) FROM vacantes WHERE empresa_id = e.id) as total_vacantes,
                (SELECT COUNT(*) FROM contrataciones WHERE empresa_id = e.id AND activo = 1) as total_contrataciones
            FROM empresas e
            JOIN usuarios u ON e.usuario_id = u.id
            ORDER BY e.fecha_creacion DESC
        ");
        $empresas = $stmt->fetchAll();

        $this->view('admin/empresas', ['empresas' => $empresas]);
    }

    public function verificarEmpresa($empresaId) {
        requireRole('admin');
        $this->validateCSRF();

        $verificada = $this->getPost('verificada') ? 1 : 0;

        $stmt = $this->db->prepare("UPDATE empresas SET verificada = ? WHERE id = ?");
        $stmt->execute([$verificada, $empresaId]);

        $this->log('verificar_empresa', "Empresa ID: $empresaId, verificada: $verificada");
        setFlash('success', 'Estado de verificación actualizado');
        redirect('/admin/empresas');
    }

    public function vacantes() {
        requireRole('admin');

        $estado = $this->getGet('estado');
        
        $sql = "
            SELECT v.*, e.nombre_comercial, e.razon_social
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
        ";
        $params = [];

        if ($estado) {
            $sql .= " WHERE v.estado = ?";
            $params[] = $estado;
        }

        $sql .= " ORDER BY v.fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $vacantes = $stmt->fetchAll();

        $this->view('admin/vacantes', ['vacantes' => $vacantes, 'estadoFiltro' => $estado]);
    }

    public function cambiarEstadoVacante($vacanteId) {
        requireRole('admin');
        $this->validateCSRF();

        $nuevoEstado = $this->getPost('estado');

        $stmt = $this->db->prepare("
            UPDATE vacantes SET estado = ?, fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$nuevoEstado, $vacanteId]);

        if ($nuevoEstado === 'publicada') {
            $stmt = $this->db->prepare("
                UPDATE vacantes SET fecha_publicacion = NOW() WHERE id = ? AND fecha_publicacion IS NULL
            ");
            $stmt->execute([$vacanteId]);
        }

        $this->log('cambiar_estado_vacante', "Vacante ID: $vacanteId, nuevo estado: $nuevoEstado");
        setFlash('success', 'Estado de vacante actualizado');
        redirect('/admin/vacantes');
    }

    public function quejas() {
        requireRole('admin');

        $stmt = $this->db->query("
            SELECT q.*, e.nombre_comercial, e.razon_social
            FROM quejas_anonimas q
            LEFT JOIN empresas e ON q.empresa_id = e.id
            ORDER BY q.fecha_creacion DESC
        ");
        $quejas = $stmt->fetchAll();

        $this->view('admin/quejas', ['quejas' => $quejas]);
    }

    public function verQueja($id) {
        requireRole('admin');

        $stmt = $this->db->prepare("
            SELECT q.*, e.nombre_comercial, e.razon_social, e.rfc
            FROM quejas_anonimas q
            LEFT JOIN empresas e ON q.empresa_id = e.id
            WHERE q.id = ?
        ");
        $stmt->execute([$id]);
        $queja = $stmt->fetch();

        if (!$queja) {
            setFlash('error', 'Queja no encontrada');
            redirect('/admin/quejas');
        }

        $this->view('admin/queja_detalle', ['queja' => $queja]);
    }

    public function cambiarEstadoQueja($quejaId) {
        requireRole('admin');
        $this->validateCSRF();

        $nuevoEstado = $this->getPost('estado');
        $notas = $this->getPost('notas_admin');

        $stmt = $this->db->prepare("
            UPDATE quejas_anonimas SET estado = ?, notas_admin = ?, fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$nuevoEstado, $notas, $quejaId]);

        $this->log('cambiar_estado_queja', "Queja ID: $quejaId, nuevo estado: $nuevoEstado");
        setFlash('success', 'Estado de queja actualizado');
        redirect('/admin/queja/' . $quejaId);
    }

    public function nuevaQueja() {
        // Esta ruta es pública para quejas anónimas
        if (!$this->db) {
            $empresas = [];
        } else {
            $stmt = $this->db->query("SELECT id, nombre_comercial, razon_social FROM empresas ORDER BY nombre_comercial");
            $empresas = $stmt->fetchAll();
        }

        $this->view('admin/queja_form', ['empresas' => $empresas]);
    }

    public function guardarQueja() {
        $this->validateCSRF();

        if (!$this->db) {
            setFlash('error', 'Error de conexión');
            redirect('/queja/nueva');
        }

        $stmt = $this->db->prepare("
            INSERT INTO quejas_anonimas (empresa_id, tipo_queja, descripcion)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $this->getPost('empresa_id') ?: null,
            $this->getPost('tipo_queja'),
            $this->getPost('descripcion')
        ]);

        setFlash('success', 'Tu queja ha sido registrada y será revisada por nuestro equipo. Gracias por ayudarnos a mejorar.');
        redirect('/');
    }

    public function reportes() {
        requireRole('admin');

        // Estadísticas por mes
        $stmt = $this->db->query("
            SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') as mes, COUNT(*) as total
            FROM candidatos
            WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(fecha_creacion, '%Y-%m')
            ORDER BY mes
        ");
        $candidatosPorMes = $stmt->fetchAll();

        $stmt = $this->db->query("
            SELECT DATE_FORMAT(fecha_contratacion, '%Y-%m') as mes, COUNT(*) as total,
                SUM(salario_bruto) as total_salarios, SUM(deduccion_isr_mensual) as total_deduccion
            FROM contrataciones
            WHERE fecha_contratacion >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(fecha_contratacion, '%Y-%m')
            ORDER BY mes
        ");
        $contratacionesPorMes = $stmt->fetchAll();

        // Por tipo de discapacidad
        $stmt = $this->db->query("
            SELECT tipo_discapacidad, COUNT(*) as total
            FROM candidatos
            GROUP BY tipo_discapacidad
        ");
        $porTipoDiscapacidad = $stmt->fetchAll();

        // Por municipio
        $stmt = $this->db->query("
            SELECT municipio, COUNT(*) as total
            FROM candidatos
            GROUP BY municipio
            ORDER BY total DESC
            LIMIT 10
        ");
        $porMunicipio = $stmt->fetchAll();

        $this->view('admin/reportes', [
            'candidatosPorMes' => $candidatosPorMes,
            'contratacionesPorMes' => $contratacionesPorMes,
            'porTipoDiscapacidad' => $porTipoDiscapacidad,
            'porMunicipio' => $porMunicipio
        ]);
    }
}
