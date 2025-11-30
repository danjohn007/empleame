<?php
/**
 * Funciones auxiliares del sistema
 * Alianza Inclusiva Tech
 */

/**
 * Sanitizar entrada de datos
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validar formato RFC mexicano
 */
function validarRFC($rfc) {
    // RFC Persona Moral: 3 letras + 6 dígitos + 3 caracteres
    // RFC Persona Física: 4 letras + 6 dígitos + 3 caracteres
    $pattern = '/^([A-ZÑ&]{3,4})(\d{6})([A-Z0-9]{3})$/i';
    return preg_match($pattern, strtoupper($rfc));
}

/**
 * Validar email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Generar slug desde texto
 */
function generarSlug($texto) {
    $texto = strtolower(trim($texto));
    $texto = preg_replace('/[áàäâã]/u', 'a', $texto);
    $texto = preg_replace('/[éèëê]/u', 'e', $texto);
    $texto = preg_replace('/[íìïî]/u', 'i', $texto);
    $texto = preg_replace('/[óòöôõ]/u', 'o', $texto);
    $texto = preg_replace('/[úùüû]/u', 'u', $texto);
    $texto = preg_replace('/[ñ]/u', 'n', $texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    $texto = preg_replace('/-+/', '-', $texto);
    return trim($texto, '-');
}

/**
 * Formatear moneda MXN
 */
function formatoMoneda($cantidad) {
    return '$' . number_format($cantidad, 2, '.', ',') . ' MXN';
}

/**
 * Formatear fecha en español
 */
function formatoFecha($fecha, $conHora = false) {
    $timestamp = strtotime($fecha);
    if ($conHora) {
        return date('d/m/Y H:i', $timestamp);
    }
    return date('d/m/Y', $timestamp);
}

/**
 * Redireccionar
 */
function redirect($url) {
    if (strpos($url, 'http') !== 0) {
        $url = BASE_URL . '/' . ltrim($url, '/');
    }
    header("Location: $url");
    exit;
}

/**
 * Establecer mensaje flash
 */
function setFlash($tipo, $mensaje) {
    $_SESSION['flash'] = [
        'tipo' => $tipo,
        'mensaje' => $mensaje
    ];
}

/**
 * Obtener y limpiar mensaje flash
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Verificar si el usuario está autenticado
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Obtener datos del usuario actual
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'tipo' => $_SESSION['user_tipo'],
        'email' => $_SESSION['user_email'],
        'nombre' => $_SESSION['user_nombre']
    ];
}

/**
 * Requerir autenticación
 */
function requireAuth() {
    if (!isLoggedIn()) {
        setFlash('error', 'Debes iniciar sesión para acceder a esta sección');
        redirect('/login');
    }
}

/**
 * Requerir tipo de usuario específico
 */
function requireRole($roles) {
    requireAuth();
    $roles = (array) $roles;
    if (!in_array($_SESSION['user_tipo'], $roles)) {
        setFlash('error', 'No tienes permisos para acceder a esta sección');
        redirect('/');
    }
}

/**
 * Generar token CSRF
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Calcular score de accesibilidad
 */
function calcularScoreAccesibilidad($checklist) {
    $total = 0;
    $puntos = 0;
    
    $pesos = [
        'rampas' => 15,
        'banos_adaptados' => 15,
        'ascensor' => 10,
        'estacionamiento_accesible' => 10,
        'puertas_automaticas' => 5,
        'senalizacion_braille' => 10,
        'alarmas_visuales' => 10,
        'software_accesible' => 15,
        'mobiliario_adaptable' => 10
    ];
    
    foreach ($pesos as $item => $peso) {
        $total += $peso;
        if (!empty($checklist[$item])) {
            $puntos += $peso;
        }
    }
    
    return $total > 0 ? round(($puntos / $total) * 100) : 0;
}

/**
 * Calcular compatibilidad candidato-vacante
 */
function calcularCompatibilidad($necesidadesCandidato, $facilidadesVacante) {
    $coincidencias = 0;
    $totalNecesidades = count($necesidadesCandidato);
    
    if ($totalNecesidades == 0) {
        return 100;
    }
    
    foreach ($necesidadesCandidato as $necesidad) {
        if (in_array($necesidad, $facilidadesVacante)) {
            $coincidencias++;
        }
    }
    
    return round(($coincidencias / $totalNecesidades) * 100);
}

/**
 * Calcular deducción ISR (simplificado)
 */
function calcularDeduccionISR($salarioBruto, $porcentajeDiscapacidad = 30) {
    // Basado en Ley ISR México - Art. 186
    // Si discapacidad > 30%: Deducción = 100% del ISR Retenido
    
    // Tabla ISR 2024 (simplificada)
    $isrRetenido = 0;
    
    if ($salarioBruto <= 8952.49) {
        $isrRetenido = $salarioBruto * 0.0192;
    } elseif ($salarioBruto <= 75984.55) {
        $isrRetenido = 171.88 + ($salarioBruto - 8952.49) * 0.1088;
    } elseif ($salarioBruto <= 133536.07) {
        $isrRetenido = 7461.29 + ($salarioBruto - 75984.55) * 0.16;
    } elseif ($salarioBruto <= 155229.80) {
        $isrRetenido = 16669.53 + ($salarioBruto - 133536.07) * 0.1792;
    } else {
        $isrRetenido = 20549.88 + ($salarioBruto - 155229.80) * 0.2136;
    }
    
    // Si discapacidad >= 30%, deducción del 100% del ISR
    $deduccion = ($porcentajeDiscapacidad >= 30) ? $isrRetenido : $isrRetenido * 0.25;
    
    return [
        'salario_bruto' => $salarioBruto,
        'isr_retenido' => round($isrRetenido, 2),
        'deduccion' => round($deduccion, 2),
        'costo_real' => round($salarioBruto - $deduccion, 2)
    ];
}

/**
 * Subir archivo de forma segura
 */
function subirArchivo($archivo, $directorio, $tiposPermitidos = ['pdf', 'jpg', 'jpeg', 'png']) {
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return ['exito' => false, 'mensaje' => 'Error al subir el archivo'];
    }
    
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $tiposPermitidos)) {
        return ['exito' => false, 'mensaje' => 'Tipo de archivo no permitido'];
    }
    
    // Verificar tamaño (máx 5MB)
    if ($archivo['size'] > 5 * 1024 * 1024) {
        return ['exito' => false, 'mensaje' => 'El archivo excede el tamaño máximo (5MB)'];
    }
    
    // Generar nombre único
    $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
    $rutaCompleta = $directorio . '/' . $nombreArchivo;
    
    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }
    
    if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
        return ['exito' => true, 'archivo' => $nombreArchivo, 'ruta' => $rutaCompleta];
    }
    
    return ['exito' => false, 'mensaje' => 'Error al guardar el archivo'];
}
