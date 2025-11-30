<?php
/**
 * Test de Conexión y Configuración
 * Alianza Inclusiva Tech
 * 
 * Este archivo verifica que la instalación esté correcta
 */

// Definir ruta base
define('BASE_PATH', dirname(__FILE__));

// Cargar configuración
require_once BASE_PATH . '/app/config/config.php';

// Verificaciones
$checks = [];

// 1. Verificar PHP Version
$phpVersion = phpversion();
$checks['php'] = [
    'name' => 'Versión de PHP',
    'value' => $phpVersion,
    'status' => version_compare($phpVersion, '7.4', '>='),
    'message' => version_compare($phpVersion, '7.4', '>=') ? 'OK - PHP 7.4 o superior' : 'Se requiere PHP 7.4 o superior'
];

// 2. Verificar extensiones necesarias
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
foreach ($requiredExtensions as $ext) {
    $loaded = extension_loaded($ext);
    $checks['ext_' . $ext] = [
        'name' => 'Extensión: ' . strtoupper($ext),
        'value' => $loaded ? 'Cargada' : 'No encontrada',
        'status' => $loaded,
        'message' => $loaded ? 'OK' : 'Extensión requerida no encontrada'
    ];
}

// 3. Verificar URL Base
$checks['base_url'] = [
    'name' => 'URL Base',
    'value' => BASE_URL,
    'status' => true,
    'message' => 'Detectada automáticamente'
];

// 4. Verificar directorio de uploads
$uploadsWritable = is_writable(BASE_PATH . '/public/uploads');
$checks['uploads'] = [
    'name' => 'Directorio Uploads',
    'value' => BASE_PATH . '/public/uploads',
    'status' => $uploadsWritable,
    'message' => $uploadsWritable ? 'OK - Escritura permitida' : 'No se puede escribir. Ejecutar: chmod -R 755 public/uploads'
];

// 5. Verificar directorio de logs
$logsWritable = is_writable(BASE_PATH . '/logs');
$checks['logs'] = [
    'name' => 'Directorio Logs',
    'value' => BASE_PATH . '/logs',
    'status' => $logsWritable,
    'message' => $logsWritable ? 'OK - Escritura permitida' : 'No se puede escribir. Ejecutar: chmod -R 755 logs'
];

// 6. Verificar conexión a base de datos
$dbStatus = false;
$dbMessage = '';
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $dbStatus = true;
    $dbMessage = 'Conectado a: ' . DB_NAME . '@' . DB_HOST;
    
    // Verificar tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $requiredTables = ['usuarios', 'empresas', 'candidatos', 'vacantes', 'postulaciones', 'contrataciones', 'quejas_anonimas', 'configuraciones'];
    $missingTables = array_diff($requiredTables, $tables);
    
    if (!empty($missingTables)) {
        $dbMessage .= ' | Tablas faltantes: ' . implode(', ', $missingTables);
    } else {
        $dbMessage .= ' | Todas las tablas OK';
    }
} catch (PDOException $e) {
    $dbMessage = 'Error: ' . $e->getMessage();
}

$checks['database'] = [
    'name' => 'Base de Datos MySQL',
    'value' => DB_NAME,
    'status' => $dbStatus,
    'message' => $dbMessage
];

// 7. Verificar archivo .htaccess
$htaccessExists = file_exists(BASE_PATH . '/.htaccess');
$checks['htaccess'] = [
    'name' => 'Archivo .htaccess',
    'value' => BASE_PATH . '/.htaccess',
    'status' => $htaccessExists,
    'message' => $htaccessExists ? 'OK - Existe' : 'No encontrado (URLs amigables pueden no funcionar)'
];

// Resultado general
$allPassed = true;
foreach ($checks as $check) {
    if (!$check['status']) {
        $allPassed = false;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - <?= SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-handshake text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800"><?= SITE_NAME ?></h1>
            <p class="text-gray-600">Test de Conexión y Configuración</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-6 <?= $allPassed ? 'bg-green-500' : 'bg-yellow-500' ?> text-white">
                <div class="flex items-center">
                    <i class="fas <?= $allPassed ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> text-3xl mr-4"></i>
                    <div>
                        <h2 class="text-xl font-semibold">
                            <?= $allPassed ? '¡Sistema Listo!' : 'Verificación con Advertencias' ?>
                        </h2>
                        <p class="text-sm opacity-90">
                            <?= $allPassed ? 'Todas las verificaciones pasaron correctamente' : 'Algunas verificaciones requieren atención' ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="divide-y">
                <?php foreach ($checks as $key => $check): ?>
                <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-4 <?= $check['status'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                            <i class="fas <?= $check['status'] ? 'fa-check' : 'fa-times' ?>"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800"><?= $check['name'] ?></h3>
                            <p class="text-sm text-gray-500"><?= $check['value'] ?></p>
                        </div>
                    </div>
                    <span class="text-sm <?= $check['status'] ? 'text-green-600' : 'text-red-600' ?>">
                        <?= $check['message'] ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="font-semibold text-gray-800 mb-4">URLs del Sistema</h3>
            <div class="space-y-2 text-sm">
                <p><strong>Inicio:</strong> <a href="<?= BASE_URL ?>" class="text-blue-600 hover:underline"><?= BASE_URL ?></a></p>
                <p><strong>Login:</strong> <a href="<?= BASE_URL ?>/login" class="text-blue-600 hover:underline"><?= BASE_URL ?>/login</a></p>
                <p><strong>Vacantes:</strong> <a href="<?= BASE_URL ?>/vacantes" class="text-blue-600 hover:underline"><?= BASE_URL ?>/vacantes</a></p>
                <p><strong>Admin:</strong> <a href="<?= BASE_URL ?>/admin/dashboard" class="text-blue-600 hover:underline"><?= BASE_URL ?>/admin/dashboard</a></p>
            </div>
        </div>
        
        <div class="bg-blue-50 rounded-xl p-6 text-center">
            <p class="text-blue-800 text-sm">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Nota:</strong> Elimina este archivo (test.php) después de verificar la instalación.
            </p>
        </div>
    </div>
</body>
</html>
