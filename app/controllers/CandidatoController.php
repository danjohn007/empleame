<?php
/**
 * Controlador de Candidatos
 * Alianza Inclusiva Tech
 */

class CandidatoController extends BaseController {

    public function dashboard() {
        requireRole('candidato');

        $candidato = $this->getCandidatoActual();
        if (!$candidato) {
            setFlash('error', 'Error al cargar los datos del candidato');
            redirect('/');
        }

        // Estadísticas
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM postulaciones WHERE candidato_id = ?");
        $stmt->execute([$candidato['id']]);
        $totalPostulaciones = $stmt->fetch()['total'];

        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM postulaciones 
            WHERE candidato_id = ? AND estado IN ('entrevista', 'seleccionado')
        ");
        $stmt->execute([$candidato['id']]);
        $entrevistas = $stmt->fetch()['total'];

        // Vacantes compatibles
        $necesidades = json_decode($candidato['necesidades_accesibilidad'], true) ?? [];
        $tipoDiscapacidad = $candidato['tipo_discapacidad'];

        $stmt = $this->db->prepare("
            SELECT v.*, e.nombre_comercial, e.razon_social
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.estado = 'publicada'
            AND JSON_CONTAINS(v.discapacidades_aceptadas, ?)
            ORDER BY v.score_accesibilidad DESC
            LIMIT 5
        ");
        $stmt->execute(['"' . $tipoDiscapacidad . '"']);
        $vacantesCompatibles = $stmt->fetchAll();

        // Calcular compatibilidad para cada vacante
        foreach ($vacantesCompatibles as &$vacante) {
            $checklist = json_decode($vacante['checklist_accesibilidad'], true) ?? [];
            $facilidades = array_keys(array_filter($checklist));
            $vacante['compatibilidad'] = calcularCompatibilidad($necesidades, $facilidades);
        }

        // Últimas postulaciones
        $stmt = $this->db->prepare("
            SELECT p.*, v.titulo, e.nombre_comercial
            FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            JOIN empresas e ON v.empresa_id = e.id
            WHERE p.candidato_id = ?
            ORDER BY p.fecha_postulacion DESC
            LIMIT 5
        ");
        $stmt->execute([$candidato['id']]);
        $ultimasPostulaciones = $stmt->fetchAll();

        $this->view('candidatos/dashboard', [
            'candidato' => $candidato,
            'totalPostulaciones' => $totalPostulaciones,
            'entrevistas' => $entrevistas,
            'vacantesCompatibles' => $vacantesCompatibles,
            'ultimasPostulaciones' => $ultimasPostulaciones
        ]);
    }

    public function perfil() {
        requireRole('candidato');

        $candidato = $this->getCandidatoActual();
        $candidato['dispositivos_apoyo'] = json_decode($candidato['dispositivos_apoyo'], true) ?? [];
        $candidato['necesidades_accesibilidad'] = json_decode($candidato['necesidades_accesibilidad'], true) ?? [];

        $this->view('candidatos/perfil', ['candidato' => $candidato]);
    }

    public function actualizarPerfil() {
        requireRole('candidato');
        $this->validateCSRF();

        $candidato = $this->getCandidatoActual();

        $dispositivosApoyo = $this->getPost('dispositivos_apoyo') ?? [];
        $necesidadesAccesibilidad = $this->getPost('necesidades_accesibilidad') ?? [];

        $stmt = $this->db->prepare("
            UPDATE candidatos SET
                nombre = ?,
                apellido_paterno = ?,
                apellido_materno = ?,
                telefono = ?,
                direccion = ?,
                colonia = ?,
                municipio = ?,
                codigo_postal = ?,
                tipo_discapacidad = ?,
                descripcion_discapacidad = ?,
                porcentaje_discapacidad = ?,
                dispositivos_apoyo = ?,
                necesidades_accesibilidad = ?,
                nivel_estudios = ?,
                experiencia_anos = ?,
                habilidades = ?,
                disponibilidad = ?,
                expectativa_salarial = ?,
                activo_busqueda = ?,
                fecha_actualizacion = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $this->getPost('nombre'),
            $this->getPost('apellido_paterno'),
            $this->getPost('apellido_materno'),
            $this->getPost('telefono'),
            $this->getPost('direccion'),
            $this->getPost('colonia'),
            $this->getPost('municipio'),
            $this->getPost('codigo_postal'),
            $this->getPost('tipo_discapacidad'),
            $this->getPost('descripcion_discapacidad'),
            intval($this->getPost('porcentaje_discapacidad')),
            json_encode($dispositivosApoyo),
            json_encode($necesidadesAccesibilidad),
            $this->getPost('nivel_estudios'),
            intval($this->getPost('experiencia_anos')),
            $this->getPost('habilidades'),
            $this->getPost('disponibilidad'),
            $this->getPost('expectativa_salarial') ?: null,
            $this->getPost('activo_busqueda') ? 1 : 0,
            $candidato['id']
        ]);

        // Procesar foto si se subió
        if (!empty($_FILES['foto']['name'])) {
            $resultado = subirArchivo($_FILES['foto'], UPLOADS_PATH . '/fotos', ['jpg', 'jpeg', 'png']);
            if ($resultado['exito']) {
                $stmt = $this->db->prepare("UPDATE candidatos SET foto = ? WHERE id = ?");
                $stmt->execute([$resultado['archivo'], $candidato['id']]);
            }
        }

        // Procesar certificado si se subió
        if (!empty($_FILES['certificado_discapacidad']['name'])) {
            $resultado = subirArchivo($_FILES['certificado_discapacidad'], UPLOADS_PATH . '/certificados');
            if ($resultado['exito']) {
                $stmt = $this->db->prepare("UPDATE candidatos SET certificado_discapacidad = ?, certificado_verificado = 0 WHERE id = ?");
                $stmt->execute([$resultado['archivo'], $candidato['id']]);
            }
        }

        // Procesar CV si se subió
        if (!empty($_FILES['curriculum_pdf']['name'])) {
            $resultado = subirArchivo($_FILES['curriculum_pdf'], UPLOADS_PATH . '/cv', ['pdf']);
            if ($resultado['exito']) {
                $stmt = $this->db->prepare("UPDATE candidatos SET curriculum_pdf = ? WHERE id = ?");
                $stmt->execute([$resultado['archivo'], $candidato['id']]);
            }
        }

        setFlash('success', 'Perfil actualizado correctamente');
        redirect('/candidato/perfil');
    }

    public function misPostulaciones() {
        requireRole('candidato');

        $candidato = $this->getCandidatoActual();

        $stmt = $this->db->prepare("
            SELECT p.*, v.titulo, v.slug, v.modalidad, v.jornada, v.salario_minimo, v.salario_maximo,
                e.nombre_comercial, e.razon_social, e.logo
            FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            JOIN empresas e ON v.empresa_id = e.id
            WHERE p.candidato_id = ?
            ORDER BY p.fecha_postulacion DESC
        ");
        $stmt->execute([$candidato['id']]);
        $postulaciones = $stmt->fetchAll();

        $this->view('candidatos/postulaciones', ['postulaciones' => $postulaciones]);
    }

    public function postular($vacanteId) {
        requireRole('candidato');
        $this->validateCSRF();

        $candidato = $this->getCandidatoActual();

        // Verificar que la vacante existe y está publicada
        $stmt = $this->db->prepare("SELECT * FROM vacantes WHERE id = ? AND estado = 'publicada'");
        $stmt->execute([$vacanteId]);
        $vacante = $stmt->fetch();

        if (!$vacante) {
            setFlash('error', 'Vacante no disponible');
            redirect('/vacantes');
        }

        // Verificar si ya postuló
        $stmt = $this->db->prepare("SELECT id FROM postulaciones WHERE vacante_id = ? AND candidato_id = ?");
        $stmt->execute([$vacanteId, $candidato['id']]);
        if ($stmt->fetch()) {
            setFlash('warning', 'Ya te has postulado a esta vacante');
            redirect('/vacante/' . $vacante['slug']);
        }

        // Calcular compatibilidad
        $necesidades = json_decode($candidato['necesidades_accesibilidad'], true) ?? [];
        $checklist = json_decode($vacante['checklist_accesibilidad'], true) ?? [];
        $facilidades = array_keys(array_filter($checklist));
        $compatibilidad = calcularCompatibilidad($necesidades, $facilidades);

        // Crear postulación
        $stmt = $this->db->prepare("
            INSERT INTO postulaciones (vacante_id, candidato_id, carta_presentacion, score_compatibilidad)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $vacanteId,
            $candidato['id'],
            $this->getPost('carta_presentacion'),
            $compatibilidad
        ]);

        $this->log('postulacion', "Postulación a vacante: {$vacante['titulo']}");
        setFlash('success', '¡Te has postulado exitosamente! La empresa revisará tu perfil.');
        redirect('/candidato/postulaciones');
    }

    public function vacantesCompatibles() {
        requireRole('candidato');

        $candidato = $this->getCandidatoActual();
        $necesidades = json_decode($candidato['necesidades_accesibilidad'], true) ?? [];
        $tipoDiscapacidad = $candidato['tipo_discapacidad'];

        $stmt = $this->db->prepare("
            SELECT v.*, e.nombre_comercial, e.razon_social, e.logo,
                (SELECT COUNT(*) FROM postulaciones WHERE vacante_id = v.id AND candidato_id = ?) as ya_postulado
            FROM vacantes v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE v.estado = 'publicada'
            AND JSON_CONTAINS(v.discapacidades_aceptadas, ?)
            ORDER BY v.score_accesibilidad DESC
        ");
        $stmt->execute([$candidato['id'], '"' . $tipoDiscapacidad . '"']);
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

        $this->view('candidatos/vacantes_compatibles', [
            'vacantes' => $vacantes,
            'candidato' => $candidato
        ]);
    }

    private function getCandidatoActual() {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("SELECT * FROM candidatos WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
}
