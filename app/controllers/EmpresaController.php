<?php
/**
 * Controlador de Empresas
 * Alianza Inclusiva Tech
 */

class EmpresaController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function dashboard() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();
        if (!$empresa) {
            setFlash('error', 'Error al cargar los datos de la empresa');
            redirect('/');
        }

        // Estadísticas
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM vacantes WHERE empresa_id = ?");
        $stmt->execute([$empresa['id']]);
        $totalVacantes = $stmt->fetch()['total'];

        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            WHERE v.empresa_id = ?
        ");
        $stmt->execute([$empresa['id']]);
        $totalPostulaciones = $stmt->fetch()['total'];

        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM contrataciones WHERE empresa_id = ? AND activo = 1");
        $stmt->execute([$empresa['id']]);
        $totalContrataciones = $stmt->fetch()['total'];

        $stmt = $this->db->prepare("SELECT SUM(deduccion_isr_mensual) as total FROM contrataciones WHERE empresa_id = ? AND activo = 1");
        $stmt->execute([$empresa['id']]);
        $ahorroMensual = $stmt->fetch()['total'] ?? 0;

        // Últimas postulaciones
        $stmt = $this->db->prepare("
            SELECT p.*, v.titulo as vacante_titulo, c.nombre, c.apellido_paterno, c.tipo_discapacidad
            FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            JOIN candidatos c ON p.candidato_id = c.id
            WHERE v.empresa_id = ?
            ORDER BY p.fecha_postulacion DESC
            LIMIT 5
        ");
        $stmt->execute([$empresa['id']]);
        $ultimasPostulaciones = $stmt->fetchAll();

        $this->view('empresas/dashboard', [
            'empresa' => $empresa,
            'totalVacantes' => $totalVacantes,
            'totalPostulaciones' => $totalPostulaciones,
            'totalContrataciones' => $totalContrataciones,
            'ahorroMensual' => $ahorroMensual,
            'ultimasPostulaciones' => $ultimasPostulaciones
        ]);
    }

    public function perfil() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();
        $this->view('empresas/perfil', ['empresa' => $empresa]);
    }

    public function actualizarPerfil() {
        requireRole('empresa');
        $this->validateCSRF();

        $empresa = $this->getEmpresaActual();

        $stmt = $this->db->prepare("
            UPDATE empresas SET
                nombre_comercial = ?,
                direccion_fiscal = ?,
                colonia = ?,
                municipio = ?,
                codigo_postal = ?,
                sector_industrial = ?,
                tamano_empresa = ?,
                telefono = ?,
                sitio_web = ?,
                descripcion = ?,
                fecha_actualizacion = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $this->getPost('nombre_comercial'),
            $this->getPost('direccion_fiscal'),
            $this->getPost('colonia'),
            $this->getPost('municipio'),
            $this->getPost('codigo_postal'),
            $this->getPost('sector_industrial'),
            $this->getPost('tamano_empresa'),
            $this->getPost('telefono'),
            $this->getPost('sitio_web'),
            $this->getPost('descripcion'),
            $empresa['id']
        ]);

        // Procesar logo si se subió
        if (!empty($_FILES['logo']['name'])) {
            $resultado = subirArchivo($_FILES['logo'], UPLOADS_PATH . '/logos', ['jpg', 'jpeg', 'png']);
            if ($resultado['exito']) {
                $stmt = $this->db->prepare("UPDATE empresas SET logo = ? WHERE id = ?");
                $stmt->execute([$resultado['archivo'], $empresa['id']]);
            }
        }

        setFlash('success', 'Perfil actualizado correctamente');
        redirect('/empresa/perfil');
    }

    public function misVacantes() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();

        $stmt = $this->db->prepare("
            SELECT v.*, 
                (SELECT COUNT(*) FROM postulaciones WHERE vacante_id = v.id) as total_postulaciones
            FROM vacantes v
            WHERE v.empresa_id = ?
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute([$empresa['id']]);
        $vacantes = $stmt->fetchAll();

        $this->view('empresas/vacantes', ['vacantes' => $vacantes, 'empresa' => $empresa]);
    }

    public function nuevaVacante() {
        requireRole('empresa');
        $this->view('empresas/vacante_form', ['vacante' => null, 'empresa' => $this->getEmpresaActual()]);
    }

    public function guardarVacante() {
        requireRole('empresa');
        $this->validateCSRF();

        $empresa = $this->getEmpresaActual();

        $titulo = $this->getPost('titulo');
        $slug = generarSlug($titulo) . '-' . uniqid();

        // Checklist de accesibilidad
        $checklist = [
            'rampas' => $this->getPost('rampas') ? true : false,
            'banos_adaptados' => $this->getPost('banos_adaptados') ? true : false,
            'ascensor' => $this->getPost('ascensor') ? true : false,
            'estacionamiento_accesible' => $this->getPost('estacionamiento_accesible') ? true : false,
            'puertas_automaticas' => $this->getPost('puertas_automaticas') ? true : false,
            'senalizacion_braille' => $this->getPost('senalizacion_braille') ? true : false,
            'alarmas_visuales' => $this->getPost('alarmas_visuales') ? true : false,
            'software_accesible' => $this->getPost('software_accesible') ? true : false,
            'mobiliario_adaptable' => $this->getPost('mobiliario_adaptable') ? true : false
        ];

        $scoreAccesibilidad = calcularScoreAccesibilidad($checklist);
        $estado = $scoreAccesibilidad < 50 ? 'borrador' : 'pendiente';

        $discapacidadesAceptadas = $this->getPost('discapacidades_aceptadas') ?? [];

        $stmt = $this->db->prepare("
            INSERT INTO vacantes (empresa_id, titulo, slug, descripcion, requisitos, tipo_contrato,
            jornada, modalidad, salario_minimo, salario_maximo, mostrar_salario, ubicacion, municipio,
            discapacidades_aceptadas, checklist_accesibilidad, score_accesibilidad, estado, 
            fecha_publicacion, plazas_disponibles)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $empresa['id'],
            $titulo,
            $slug,
            $this->getPost('descripcion'),
            $this->getPost('requisitos'),
            $this->getPost('tipo_contrato'),
            $this->getPost('jornada'),
            $this->getPost('modalidad'),
            $this->getPost('salario_minimo') ?: null,
            $this->getPost('salario_maximo') ?: null,
            $this->getPost('mostrar_salario') ? 1 : 0,
            $this->getPost('ubicacion'),
            $this->getPost('municipio') ?: 'Querétaro',
            json_encode($discapacidadesAceptadas),
            json_encode($checklist),
            $scoreAccesibilidad,
            $estado,
            $estado === 'pendiente' ? date('Y-m-d H:i:s') : null,
            intval($this->getPost('plazas_disponibles')) ?: 1
        ]);

        if ($scoreAccesibilidad < 50) {
            setFlash('warning', 'Tu vacante tiene un Score de Accesibilidad bajo (' . $scoreAccesibilidad . '%). Por favor mejora las condiciones de accesibilidad para publicarla.');
        } else {
            setFlash('success', 'Vacante creada exitosamente. Está pendiente de aprobación.');
        }

        redirect('/empresa/vacantes');
    }

    public function editarVacante($id) {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();
        
        $stmt = $this->db->prepare("SELECT * FROM vacantes WHERE id = ? AND empresa_id = ?");
        $stmt->execute([$id, $empresa['id']]);
        $vacante = $stmt->fetch();

        if (!$vacante) {
            setFlash('error', 'Vacante no encontrada');
            redirect('/empresa/vacantes');
        }

        $vacante['checklist_accesibilidad'] = json_decode($vacante['checklist_accesibilidad'], true) ?? [];
        $vacante['discapacidades_aceptadas'] = json_decode($vacante['discapacidades_aceptadas'], true) ?? [];

        $this->view('empresas/vacante_form', ['vacante' => $vacante, 'empresa' => $empresa]);
    }

    public function actualizarVacante($id) {
        requireRole('empresa');
        $this->validateCSRF();

        $empresa = $this->getEmpresaActual();

        // Verificar que la vacante pertenece a la empresa
        $stmt = $this->db->prepare("SELECT id FROM vacantes WHERE id = ? AND empresa_id = ?");
        $stmt->execute([$id, $empresa['id']]);
        if (!$stmt->fetch()) {
            setFlash('error', 'Vacante no encontrada');
            redirect('/empresa/vacantes');
        }

        $titulo = $this->getPost('titulo');

        // Checklist de accesibilidad
        $checklist = [
            'rampas' => $this->getPost('rampas') ? true : false,
            'banos_adaptados' => $this->getPost('banos_adaptados') ? true : false,
            'ascensor' => $this->getPost('ascensor') ? true : false,
            'estacionamiento_accesible' => $this->getPost('estacionamiento_accesible') ? true : false,
            'puertas_automaticas' => $this->getPost('puertas_automaticas') ? true : false,
            'senalizacion_braille' => $this->getPost('senalizacion_braille') ? true : false,
            'alarmas_visuales' => $this->getPost('alarmas_visuales') ? true : false,
            'software_accesible' => $this->getPost('software_accesible') ? true : false,
            'mobiliario_adaptable' => $this->getPost('mobiliario_adaptable') ? true : false
        ];

        $scoreAccesibilidad = calcularScoreAccesibilidad($checklist);
        $discapacidadesAceptadas = $this->getPost('discapacidades_aceptadas') ?? [];

        $stmt = $this->db->prepare("
            UPDATE vacantes SET
                titulo = ?,
                descripcion = ?,
                requisitos = ?,
                tipo_contrato = ?,
                jornada = ?,
                modalidad = ?,
                salario_minimo = ?,
                salario_maximo = ?,
                mostrar_salario = ?,
                ubicacion = ?,
                municipio = ?,
                discapacidades_aceptadas = ?,
                checklist_accesibilidad = ?,
                score_accesibilidad = ?,
                plazas_disponibles = ?,
                fecha_actualizacion = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $titulo,
            $this->getPost('descripcion'),
            $this->getPost('requisitos'),
            $this->getPost('tipo_contrato'),
            $this->getPost('jornada'),
            $this->getPost('modalidad'),
            $this->getPost('salario_minimo') ?: null,
            $this->getPost('salario_maximo') ?: null,
            $this->getPost('mostrar_salario') ? 1 : 0,
            $this->getPost('ubicacion'),
            $this->getPost('municipio') ?: 'Querétaro',
            json_encode($discapacidadesAceptadas),
            json_encode($checklist),
            $scoreAccesibilidad,
            intval($this->getPost('plazas_disponibles')) ?: 1,
            $id
        ]);

        setFlash('success', 'Vacante actualizada correctamente');
        redirect('/empresa/vacantes');
    }

    public function verPostulaciones($vacanteId) {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();

        $stmt = $this->db->prepare("SELECT * FROM vacantes WHERE id = ? AND empresa_id = ?");
        $stmt->execute([$vacanteId, $empresa['id']]);
        $vacante = $stmt->fetch();

        if (!$vacante) {
            setFlash('error', 'Vacante no encontrada');
            redirect('/empresa/vacantes');
        }

        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre, c.apellido_paterno, c.apellido_materno, c.tipo_discapacidad,
                c.porcentaje_discapacidad, c.nivel_estudios, c.experiencia_anos, c.telefono,
                c.certificado_verificado, u.email
            FROM postulaciones p
            JOIN candidatos c ON p.candidato_id = c.id
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE p.vacante_id = ?
            ORDER BY p.fecha_postulacion DESC
        ");
        $stmt->execute([$vacanteId]);
        $postulaciones = $stmt->fetchAll();

        $this->view('empresas/postulaciones', [
            'vacante' => $vacante,
            'postulaciones' => $postulaciones
        ]);
    }

    public function cambiarEstadoPostulacion($postulacionId) {
        requireRole('empresa');
        $this->validateCSRF();

        $empresa = $this->getEmpresaActual();
        $nuevoEstado = $this->getPost('estado');
        $notas = $this->getPost('notas');

        // Verificar que la postulación pertenece a una vacante de la empresa
        $stmt = $this->db->prepare("
            SELECT p.vacante_id FROM postulaciones p
            JOIN vacantes v ON p.vacante_id = v.id
            WHERE p.id = ? AND v.empresa_id = ?
        ");
        $stmt->execute([$postulacionId, $empresa['id']]);
        $result = $stmt->fetch();

        if (!$result) {
            setFlash('error', 'Postulación no encontrada');
            redirect('/empresa/vacantes');
        }

        $stmt = $this->db->prepare("
            UPDATE postulaciones SET estado = ?, notas_empresa = ?, fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$nuevoEstado, $notas, $postulacionId]);

        setFlash('success', 'Estado de postulación actualizado');
        redirect('/empresa/vacante/' . $result['vacante_id'] . '/postulaciones');
    }

    public function contrataciones() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();

        $stmt = $this->db->prepare("
            SELECT c.*, ca.nombre, ca.apellido_paterno, ca.tipo_discapacidad, 
                ca.porcentaje_discapacidad, v.titulo as vacante_titulo
            FROM contrataciones c
            JOIN candidatos ca ON c.candidato_id = ca.id
            LEFT JOIN vacantes v ON c.vacante_id = v.id
            WHERE c.empresa_id = ?
            ORDER BY c.fecha_contratacion DESC
        ");
        $stmt->execute([$empresa['id']]);
        $contrataciones = $stmt->fetchAll();

        $this->view('empresas/contrataciones', [
            'contrataciones' => $contrataciones,
            'empresa' => $empresa
        ]);
    }

    public function nuevaContratacion() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();

        // Obtener candidatos con postulaciones en estado "seleccionado"
        $stmt = $this->db->prepare("
            SELECT DISTINCT c.*, u.email
            FROM candidatos c
            JOIN usuarios u ON c.usuario_id = u.id
            JOIN postulaciones p ON p.candidato_id = c.id
            JOIN vacantes v ON p.vacante_id = v.id
            WHERE v.empresa_id = ? AND p.estado = 'seleccionado'
        ");
        $stmt->execute([$empresa['id']]);
        $candidatos = $stmt->fetchAll();

        $this->view('empresas/contratacion_form', [
            'candidatos' => $candidatos,
            'empresa' => $empresa
        ]);
    }

    public function guardarContratacion() {
        requireRole('empresa');
        $this->validateCSRF();

        $empresa = $this->getEmpresaActual();

        $candidatoId = intval($this->getPost('candidato_id'));
        $salarioBruto = floatval($this->getPost('salario_bruto'));
        $fechaContratacion = $this->getPost('fecha_contratacion');
        $tipoContrato = $this->getPost('tipo_contrato');

        // Obtener porcentaje de discapacidad del candidato
        $stmt = $this->db->prepare("SELECT porcentaje_discapacidad FROM candidatos WHERE id = ?");
        $stmt->execute([$candidatoId]);
        $candidato = $stmt->fetch();

        if (!$candidato) {
            setFlash('error', 'Candidato no encontrado');
            redirect('/empresa/contratacion/nueva');
        }

        $porcentaje = $candidato['porcentaje_discapacidad'];
        $calculo = calcularDeduccionISR($salarioBruto, $porcentaje);

        $stmt = $this->db->prepare("
            INSERT INTO contrataciones (empresa_id, candidato_id, fecha_contratacion, salario_bruto,
            tipo_contrato, porcentaje_discapacidad, deduccion_isr_mensual)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $empresa['id'],
            $candidatoId,
            $fechaContratacion,
            $salarioBruto,
            $tipoContrato,
            $porcentaje,
            $calculo['deduccion']
        ]);

        // Actualizar postulación a "contratado"
        $stmt = $this->db->prepare("
            UPDATE postulaciones SET estado = 'contratado', fecha_actualizacion = NOW()
            WHERE candidato_id = ? AND vacante_id IN (SELECT id FROM vacantes WHERE empresa_id = ?)
        ");
        $stmt->execute([$candidatoId, $empresa['id']]);

        setFlash('success', 'Contratación registrada exitosamente');
        redirect('/empresa/contrataciones');
    }

    private function getEmpresaActual() {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
}
