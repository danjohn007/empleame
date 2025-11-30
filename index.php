<?php
/**
 * Punto de entrada principal
 * Alianza Inclusiva Tech - Plataforma de vinculación laboral PcD
 */

// Definir ruta base
define('BASE_PATH', dirname(__FILE__));

// Iniciar sesión
session_start();

// Cargar configuración y helpers
require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/config/helpers.php';
require_once BASE_PATH . '/app/config/router.php';

// Cargar controladores
require_once BASE_PATH . '/app/controllers/BaseController.php';
require_once BASE_PATH . '/app/controllers/HomeController.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/controllers/EmpresaController.php';
require_once BASE_PATH . '/app/controllers/CandidatoController.php';
require_once BASE_PATH . '/app/controllers/VacanteController.php';
require_once BASE_PATH . '/app/controllers/AdminController.php';
require_once BASE_PATH . '/app/controllers/FiscalController.php';
require_once BASE_PATH . '/app/controllers/ConfigController.php';

// Cargar modelos
require_once BASE_PATH . '/app/models/Usuario.php';
require_once BASE_PATH . '/app/models/Empresa.php';
require_once BASE_PATH . '/app/models/Candidato.php';
require_once BASE_PATH . '/app/models/Vacante.php';
require_once BASE_PATH . '/app/models/Postulacion.php';
require_once BASE_PATH . '/app/models/Contratacion.php';
require_once BASE_PATH . '/app/models/Queja.php';
require_once BASE_PATH . '/app/models/Configuracion.php';

// Crear router
$router = new Router();

// Rutas públicas
$router->get('/', ['HomeController', 'index']);
$router->get('/vacantes', ['VacanteController', 'listar']);
$router->get('/vacante/{slug}', ['VacanteController', 'ver']);

// Rutas de autenticación
$router->get('/login', ['AuthController', 'loginForm']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/registro', ['AuthController', 'registroForm']);
$router->get('/registro/empresa', ['AuthController', 'registroEmpresaForm']);
$router->post('/registro/empresa', ['AuthController', 'registroEmpresa']);
$router->get('/registro/candidato', ['AuthController', 'registroCandidatoForm']);
$router->post('/registro/candidato', ['AuthController', 'registroCandidato']);
$router->get('/logout', ['AuthController', 'logout']);
$router->get('/recuperar', ['AuthController', 'recuperarForm']);
$router->post('/recuperar', ['AuthController', 'recuperar']);

// Rutas de empresa
$router->get('/empresa/dashboard', ['EmpresaController', 'dashboard']);
$router->get('/empresa/perfil', ['EmpresaController', 'perfil']);
$router->post('/empresa/perfil', ['EmpresaController', 'actualizarPerfil']);
$router->get('/empresa/vacantes', ['EmpresaController', 'misVacantes']);
$router->get('/empresa/vacante/nueva', ['EmpresaController', 'nuevaVacante']);
$router->post('/empresa/vacante/nueva', ['EmpresaController', 'guardarVacante']);
$router->get('/empresa/vacante/{id}/editar', ['EmpresaController', 'editarVacante']);
$router->post('/empresa/vacante/{id}/editar', ['EmpresaController', 'actualizarVacante']);
$router->get('/empresa/vacante/{id}/postulaciones', ['EmpresaController', 'verPostulaciones']);
$router->post('/empresa/postulacion/{id}/estado', ['EmpresaController', 'cambiarEstadoPostulacion']);
$router->get('/empresa/contrataciones', ['EmpresaController', 'contrataciones']);
$router->get('/empresa/contratacion/nueva', ['EmpresaController', 'nuevaContratacion']);
$router->post('/empresa/contratacion/nueva', ['EmpresaController', 'guardarContratacion']);

// Rutas de candidato
$router->get('/candidato/dashboard', ['CandidatoController', 'dashboard']);
$router->get('/candidato/perfil', ['CandidatoController', 'perfil']);
$router->post('/candidato/perfil', ['CandidatoController', 'actualizarPerfil']);
$router->get('/candidato/postulaciones', ['CandidatoController', 'misPostulaciones']);
$router->post('/vacante/{id}/postular', ['CandidatoController', 'postular']);
$router->get('/candidato/vacantes-compatibles', ['CandidatoController', 'vacantesCompatibles']);

// Rutas fiscales (empresa)
$router->get('/fiscal/calculadora', ['FiscalController', 'calculadora']);
$router->post('/fiscal/calcular', ['FiscalController', 'calcular']);
$router->get('/fiscal/reporte', ['FiscalController', 'reporte']);
$router->get('/fiscal/reporte/pdf', ['FiscalController', 'generarPDF']);

// Rutas admin
$router->get('/admin/dashboard', ['AdminController', 'dashboard']);
$router->get('/admin/candidatos', ['AdminController', 'candidatos']);
$router->get('/admin/candidato/{id}', ['AdminController', 'verCandidato']);
$router->post('/admin/candidato/{id}/verificar', ['AdminController', 'verificarCertificado']);
$router->get('/admin/empresas', ['AdminController', 'empresas']);
$router->post('/admin/empresa/{id}/verificar', ['AdminController', 'verificarEmpresa']);
$router->get('/admin/vacantes', ['AdminController', 'vacantes']);
$router->post('/admin/vacante/{id}/estado', ['AdminController', 'cambiarEstadoVacante']);
$router->get('/admin/quejas', ['AdminController', 'quejas']);
$router->get('/admin/queja/{id}', ['AdminController', 'verQueja']);
$router->post('/admin/queja/{id}/estado', ['AdminController', 'cambiarEstadoQueja']);
$router->get('/admin/reportes', ['AdminController', 'reportes']);

// Rutas de quejas anónimas
$router->get('/queja/nueva', ['AdminController', 'nuevaQueja']);
$router->post('/queja/nueva', ['AdminController', 'guardarQueja']);

// Rutas de configuración
$router->get('/admin/configuraciones', ['ConfigController', 'index']);
$router->post('/admin/configuraciones', ['ConfigController', 'guardar']);

// 404 Not Found
$router->notFound(function() {
    require_once VIEWS_PATH . '/layouts/header.php';
    echo '<div class="container mx-auto px-4 py-16 text-center">';
    echo '<h1 class="text-6xl font-bold text-gray-300 mb-4">404</h1>';
    echo '<p class="text-xl text-gray-600 mb-8">Página no encontrada</p>';
    echo '<a href="' . BASE_URL . '" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">Volver al inicio</a>';
    echo '</div>';
    require_once VIEWS_PATH . '/layouts/footer.php';
});

// Ejecutar router
$router->dispatch();
