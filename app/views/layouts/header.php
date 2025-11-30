<?php
/**
 * Header Layout
 * Alianza Inclusiva Tech
 */

// Obtener configuraciones del sistema
$sitioNombre = SITE_NAME;
$colorPrimario = '#2563eb';
$colorSecundario = '#1e40af';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?><?= htmlspecialchars($sitioNombre) ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?= $colorPrimario ?>',
                        secondary: '<?= $colorSecundario ?>'
                    }
                }
            }
        }
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, <?= $colorPrimario ?> 0%, <?= $colorSecundario ?> 100%);
        }
        .btn-primary {
            background-color: <?= $colorPrimario ?>;
        }
        .btn-primary:hover {
            background-color: <?= $colorSecundario ?>;
        }
        .text-primary {
            color: <?= $colorPrimario ?>;
        }
        .border-primary {
            border-color: <?= $colorPrimario ?>;
        }
        /* Accessibility focus styles */
        *:focus {
            outline: 3px solid <?= $colorPrimario ?>;
            outline-offset: 2px;
        }
        /* Skip link for screen readers */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: <?= $colorPrimario ?>;
            color: white;
            padding: 8px;
            z-index: 100;
        }
        .skip-link:focus {
            top: 0;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Skip link for accessibility -->
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    
    <!-- Navigation -->
    <nav class="bg-white shadow-lg" role="navigation" aria-label="Navegación principal">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= BASE_URL ?>/" class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <i class="fas fa-handshake text-white text-xl"></i>
                        </div>
                        <span class="font-bold text-xl text-gray-800"><?= htmlspecialchars($sitioNombre) ?></span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-4">
                    <a href="<?= BASE_URL ?>/vacantes" class="text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-briefcase mr-1"></i> Vacantes
                    </a>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php $user = getCurrentUser(); ?>
                        
                        <?php if ($user['tipo'] === 'empresa'): ?>
                            <a href="<?= BASE_URL ?>/empresa/dashboard" class="text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                            <a href="<?= BASE_URL ?>/fiscal/calculadora" class="text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-calculator mr-1"></i> Calculadora Fiscal
                            </a>
                        <?php elseif ($user['tipo'] === 'candidato'): ?>
                            <a href="<?= BASE_URL ?>/candidato/dashboard" class="text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                            <a href="<?= BASE_URL ?>/candidato/vacantes-compatibles" class="text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-star mr-1"></i> Vacantes Compatibles
                            </a>
                        <?php elseif ($user['tipo'] === 'admin'): ?>
                            <a href="<?= BASE_URL ?>/admin/dashboard" class="text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-cog mr-1"></i> Administración
                            </a>
                        <?php endif; ?>
                        
                        <div class="relative group">
                            <button class="flex items-center text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-user-circle mr-1"></i>
                                <?= htmlspecialchars($user['nombre']) ?>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block z-50">
                                <?php if ($user['tipo'] === 'empresa'): ?>
                                    <a href="<?= BASE_URL ?>/empresa/perfil" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-building mr-2"></i> Mi Empresa
                                    </a>
                                <?php elseif ($user['tipo'] === 'candidato'): ?>
                                    <a href="<?= BASE_URL ?>/candidato/perfil" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Mi Perfil
                                    </a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="text-gray-600 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                            <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesión
                        </a>
                        <a href="<?= BASE_URL ?>/registro" class="btn-primary text-white px-4 py-2 rounded-md text-sm font-medium transition">
                            <i class="fas fa-user-plus mr-1"></i> Registrarse
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-primary focus:outline-none" aria-label="Abrir menú">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="<?= BASE_URL ?>/vacantes" class="block text-gray-600 hover:text-primary px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-briefcase mr-2"></i> Vacantes
                </a>
                <?php if (isLoggedIn()): ?>
                    <?php if ($user['tipo'] === 'empresa'): ?>
                        <a href="<?= BASE_URL ?>/empresa/dashboard" class="block text-gray-600 hover:text-primary px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    <?php elseif ($user['tipo'] === 'candidato'): ?>
                        <a href="<?= BASE_URL ?>/candidato/dashboard" class="block text-gray-600 hover:text-primary px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    <?php elseif ($user['tipo'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="block text-gray-600 hover:text-primary px-3 py-2 rounded-md text-base font-medium">
                            <i class="fas fa-cog mr-2"></i> Administración
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/logout" class="block text-red-600 px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="block text-gray-600 hover:text-primary px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                    </a>
                    <a href="<?= BASE_URL ?>/registro" class="block text-primary px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-user-plus mr-2"></i> Registrarse
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php $flash = getFlash(); if ($flash): ?>
    <div class="max-w-7xl mx-auto px-4 mt-4" role="alert">
        <div class="p-4 rounded-lg <?= $flash['tipo'] === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : ($flash['tipo'] === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-red-100 text-red-800 border border-red-200') ?>">
            <div class="flex items-center">
                <i class="fas <?= $flash['tipo'] === 'success' ? 'fa-check-circle' : ($flash['tipo'] === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle') ?> mr-2"></i>
                <span><?= $flash['mensaje'] ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main id="main-content" class="flex-grow">
