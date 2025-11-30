<?php
/**
 * Controlador de Autenticación
 * Alianza Inclusiva Tech
 */

class AuthController extends BaseController {

    public function loginForm() {
        if (isLoggedIn()) {
            $this->redirectToDashboard();
        }
        $this->view('auth/login');
    }

    public function login() {
        $this->validateCSRF();

        $email = $this->getPost('email');
        $password = $this->getPost('password');

        if (empty($email) || empty($password)) {
            setFlash('error', 'Por favor ingresa tu correo y contraseña');
            redirect('/login');
        }

        if (!$this->db) {
            setFlash('error', 'Error de conexión a la base de datos');
            redirect('/login');
        }

        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            setFlash('error', 'Credenciales incorrectas');
            $this->log('login_fallido', "Intento de login fallido: $email");
            redirect('/login');
        }

        // Obtener nombre según tipo
        $nombre = $email;
        if ($usuario['tipo'] === 'empresa') {
            $stmt = $this->db->prepare("SELECT nombre_comercial, razon_social FROM empresas WHERE usuario_id = ?");
            $stmt->execute([$usuario['id']]);
            $empresa = $stmt->fetch();
            $nombre = $empresa['nombre_comercial'] ?? $empresa['razon_social'];
        } elseif ($usuario['tipo'] === 'candidato') {
            $stmt = $this->db->prepare("SELECT nombre, apellido_paterno FROM candidatos WHERE usuario_id = ?");
            $stmt->execute([$usuario['id']]);
            $candidato = $stmt->fetch();
            $nombre = $candidato['nombre'] . ' ' . $candidato['apellido_paterno'];
        } elseif ($usuario['tipo'] === 'admin') {
            $nombre = 'Administrador';
        }

        // Crear sesión
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user_tipo'] = $usuario['tipo'];
        $_SESSION['user_nombre'] = $nombre;

        $this->log('login', "Inicio de sesión exitoso");
        setFlash('success', '¡Bienvenido/a, ' . $nombre . '!');
        $this->redirectToDashboard();
    }

    public function registroForm() {
        if (isLoggedIn()) {
            $this->redirectToDashboard();
        }
        $this->view('auth/registro');
    }

    public function registroEmpresaForm() {
        if (isLoggedIn()) {
            $this->redirectToDashboard();
        }
        $this->view('auth/registro_empresa');
    }

    public function registroEmpresa() {
        $this->validateCSRF();

        if (!$this->db) {
            setFlash('error', 'Error de conexión a la base de datos');
            redirect('/registro/empresa');
        }

        // Validar datos
        $email = $this->getPost('email');
        $password = $this->getPost('password');
        $passwordConfirm = $this->getPost('password_confirm');
        $razonSocial = $this->getPost('razon_social');
        $rfc = strtoupper($this->getPost('rfc'));
        $nombreComercial = $this->getPost('nombre_comercial');
        $direccionFiscal = $this->getPost('direccion_fiscal');
        $colonia = $this->getPost('colonia');
        $municipio = $this->getPost('municipio');
        $codigoPostal = $this->getPost('codigo_postal');
        $sectorIndustrial = $this->getPost('sector_industrial');
        $tamanoEmpresa = $this->getPost('tamano_empresa');
        $telefono = $this->getPost('telefono');

        // Validaciones
        $errores = [];

        if (!validarEmail($email)) {
            $errores[] = 'El correo electrónico no es válido';
        }

        if (strlen($password) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if ($password !== $passwordConfirm) {
            $errores[] = 'Las contraseñas no coinciden';
        }

        if (!validarRFC($rfc)) {
            $errores[] = 'El RFC no tiene un formato válido';
        }

        if (empty($razonSocial) || empty($direccionFiscal) || empty($municipio) || empty($codigoPostal)) {
            $errores[] = 'Por favor completa todos los campos obligatorios';
        }

        // Verificar si el email ya existe
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errores[] = 'Este correo electrónico ya está registrado';
        }

        // Verificar si el RFC ya existe
        $stmt = $this->db->prepare("SELECT id FROM empresas WHERE rfc = ?");
        $stmt->execute([$rfc]);
        if ($stmt->fetch()) {
            $errores[] = 'Este RFC ya está registrado';
        }

        if (!empty($errores)) {
            setFlash('error', implode('<br>', $errores));
            redirect('/registro/empresa');
        }

        try {
            $this->db->beginTransaction();

            // Crear usuario
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (email, password, tipo) VALUES (?, ?, 'empresa')
            ");
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
            $usuarioId = $this->db->lastInsertId();

            // Crear empresa
            $stmt = $this->db->prepare("
                INSERT INTO empresas (usuario_id, razon_social, rfc, nombre_comercial, direccion_fiscal, 
                colonia, municipio, codigo_postal, sector_industrial, tamano_empresa, telefono)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $usuarioId, $razonSocial, $rfc, $nombreComercial, $direccionFiscal,
                $colonia, $municipio, $codigoPostal, $sectorIndustrial, $tamanoEmpresa, $telefono
            ]);

            $this->db->commit();

            $this->log('registro_empresa', "Nueva empresa registrada: $razonSocial (RFC: $rfc)");
            setFlash('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
            redirect('/login');

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en registro empresa: " . $e->getMessage());
            setFlash('error', 'Error al procesar el registro. Por favor intenta de nuevo.');
            redirect('/registro/empresa');
        }
    }

    public function registroCandidatoForm() {
        if (isLoggedIn()) {
            $this->redirectToDashboard();
        }
        $this->view('auth/registro_candidato');
    }

    public function registroCandidato() {
        $this->validateCSRF();

        if (!$this->db) {
            setFlash('error', 'Error de conexión a la base de datos');
            redirect('/registro/candidato');
        }

        // Validar datos
        $email = $this->getPost('email');
        $password = $this->getPost('password');
        $passwordConfirm = $this->getPost('password_confirm');
        $nombre = $this->getPost('nombre');
        $apellidoPaterno = $this->getPost('apellido_paterno');
        $apellidoMaterno = $this->getPost('apellido_materno');
        $fechaNacimiento = $this->getPost('fecha_nacimiento');
        $telefono = $this->getPost('telefono');
        $municipio = $this->getPost('municipio');
        $tipoDiscapacidad = $this->getPost('tipo_discapacidad');
        $descripcionDiscapacidad = $this->getPost('descripcion_discapacidad');
        $porcentajeDiscapacidad = intval($this->getPost('porcentaje_discapacidad'));
        $dispositivosApoyo = $this->getPost('dispositivos_apoyo') ?? [];
        $necesidadesAccesibilidad = $this->getPost('necesidades_accesibilidad') ?? [];
        $nivelEstudios = $this->getPost('nivel_estudios');

        // Validaciones
        $errores = [];

        if (!validarEmail($email)) {
            $errores[] = 'El correo electrónico no es válido';
        }

        if (strlen($password) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if ($password !== $passwordConfirm) {
            $errores[] = 'Las contraseñas no coinciden';
        }

        if (empty($nombre) || empty($apellidoPaterno) || empty($fechaNacimiento) || empty($tipoDiscapacidad)) {
            $errores[] = 'Por favor completa todos los campos obligatorios';
        }

        // Verificar si el email ya existe
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errores[] = 'Este correo electrónico ya está registrado';
        }

        // Procesar certificado si se subió
        $certificadoArchivo = null;
        if (!empty($_FILES['certificado_discapacidad']['name'])) {
            $resultado = subirArchivo(
                $_FILES['certificado_discapacidad'],
                UPLOADS_PATH . '/certificados',
                ['pdf', 'jpg', 'jpeg', 'png']
            );
            if ($resultado['exito']) {
                $certificadoArchivo = $resultado['archivo'];
            } else {
                $errores[] = $resultado['mensaje'];
            }
        }

        if (!empty($errores)) {
            setFlash('error', implode('<br>', $errores));
            redirect('/registro/candidato');
        }

        try {
            $this->db->beginTransaction();

            // Crear usuario
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (email, password, tipo) VALUES (?, ?, 'candidato')
            ");
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
            $usuarioId = $this->db->lastInsertId();

            // Crear candidato
            $stmt = $this->db->prepare("
                INSERT INTO candidatos (usuario_id, nombre, apellido_paterno, apellido_materno, 
                fecha_nacimiento, telefono, municipio, tipo_discapacidad, descripcion_discapacidad,
                porcentaje_discapacidad, dispositivos_apoyo, necesidades_accesibilidad, 
                nivel_estudios, certificado_discapacidad)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $usuarioId, $nombre, $apellidoPaterno, $apellidoMaterno,
                $fechaNacimiento, $telefono, $municipio, $tipoDiscapacidad,
                $descripcionDiscapacidad, $porcentajeDiscapacidad,
                json_encode($dispositivosApoyo), json_encode($necesidadesAccesibilidad),
                $nivelEstudios, $certificadoArchivo
            ]);

            $this->db->commit();

            $this->log('registro_candidato', "Nuevo candidato registrado: $nombre $apellidoPaterno");
            setFlash('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
            redirect('/login');

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en registro candidato: " . $e->getMessage());
            setFlash('error', 'Error al procesar el registro. Por favor intenta de nuevo.');
            redirect('/registro/candidato');
        }
    }

    public function logout() {
        $this->log('logout', 'Cierre de sesión');
        session_destroy();
        setFlash('success', 'Has cerrado sesión correctamente');
        redirect('/');
    }

    public function recuperarForm() {
        $this->view('auth/recuperar');
    }

    public function recuperar() {
        $this->validateCSRF();
        
        $email = $this->getPost('email');
        
        if (!validarEmail($email)) {
            setFlash('error', 'Por favor ingresa un correo electrónico válido');
            redirect('/recuperar');
        }

        // Por seguridad, siempre mostramos el mismo mensaje
        setFlash('success', 'Si el correo existe, recibirás instrucciones para recuperar tu contraseña.');
        redirect('/login');
    }

    private function redirectToDashboard() {
        $tipo = $_SESSION['user_tipo'] ?? 'candidato';
        
        switch ($tipo) {
            case 'admin':
                redirect('/admin/dashboard');
                break;
            case 'empresa':
                redirect('/empresa/dashboard');
                break;
            default:
                redirect('/candidato/dashboard');
        }
    }
}
