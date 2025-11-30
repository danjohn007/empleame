<?php
/**
 * Configuración principal del sistema
 * Alianza Inclusiva Tech - Plataforma de vinculación laboral PcD
 */

// Evitar acceso directo
if (!defined('BASE_PATH')) {
    die('Acceso directo no permitido');
}

// Configuración de Base de Datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'empleame_sistema');
define('DB_USER', 'empleame_sistema');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// Detectar URL Base automáticamente
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = $scriptPath === '/' ? '' : $scriptPath;
define('BASE_URL', $protocol . '://' . $host . $basePath);

// Rutas del sistema
define('APP_PATH', BASE_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');
define('UPLOADS_PATH', BASE_PATH . '/public/uploads');
define('LOGS_PATH', BASE_PATH . '/logs');

// Configuración del sitio
define('SITE_NAME', 'Alianza Inclusiva Tech');
define('SITE_DESCRIPTION', 'Plataforma de vinculación laboral para Personas con Discapacidad');
define('SITE_CONTACT_EMAIL', 'contacto@alianzainclusiva.mx');
define('SITE_CONTACT_PHONE', '+52 442 123 4567');

// Configuración de sesión
define('SESSION_LIFETIME', 3600); // 1 hora

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Modo de entorno (development | production)
// En producción, cambiar a 'production' para ocultar credenciales de demo y errores detallados
define('ENVIRONMENT', 'development');

// Configuración de errores según entorno
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
ini_set('log_errors', 1);
ini_set('error_log', LOGS_PATH . '/error.log');
