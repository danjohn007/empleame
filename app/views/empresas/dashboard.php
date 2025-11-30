<?php
/**
 * Company Dashboard
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Dashboard Empresa';
?>

<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-tachometer-alt text-primary mr-2"></i>
                Dashboard
            </h1>
            <p class="text-gray-600 mt-2">Bienvenido, <?= htmlspecialchars($empresa['nombre_comercial'] ?? $empresa['razon_social']) ?></p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Vacantes Publicadas</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalVacantes ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-briefcase text-primary text-xl"></i>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/empresa/vacantes" class="text-sm text-primary hover:underline mt-4 inline-block">
                    Ver vacantes <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Postulaciones Recibidas</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalPostulaciones ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Empleados PcD Activos</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalContrataciones ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-check text-purple-600 text-xl"></i>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/empresa/contrataciones" class="text-sm text-primary hover:underline mt-4 inline-block">
                    Ver contrataciones <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ahorro Fiscal Mensual</p>
                        <p class="text-3xl font-bold text-green-600"><?= formatoMoneda($ahorroMensual) ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-piggy-bank text-green-600 text-xl"></i>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/fiscal/calculadora" class="text-sm text-primary hover:underline mt-4 inline-block">
                    Calculadora ISR <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <a href="<?= BASE_URL ?>/empresa/vacante/nueva" class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white hover:from-blue-700 hover:to-blue-800 transition">
                <i class="fas fa-plus-circle text-3xl mb-3"></i>
                <h3 class="text-xl font-semibold mb-1">Nueva Vacante</h3>
                <p class="text-blue-100 text-sm">Publica una nueva oportunidad laboral</p>
            </a>
            
            <a href="<?= BASE_URL ?>/fiscal/reporte" class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white hover:from-green-700 hover:to-green-800 transition">
                <i class="fas fa-file-pdf text-3xl mb-3"></i>
                <h3 class="text-xl font-semibold mb-1">Reporte SAT</h3>
                <p class="text-green-100 text-sm">Genera tu reporte fiscal trimestral</p>
            </a>
            
            <a href="<?= BASE_URL ?>/empresa/perfil" class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-6 text-white hover:from-purple-700 hover:to-purple-800 transition">
                <i class="fas fa-building text-3xl mb-3"></i>
                <h3 class="text-xl font-semibold mb-1">Mi Empresa</h3>
                <p class="text-purple-100 text-sm">Actualiza la información de tu empresa</p>
            </a>
        </div>
        
        <!-- Recent Applications -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Últimas Postulaciones</h2>
            </div>
            
            <?php if (empty($ultimasPostulaciones)): ?>
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">No hay postulaciones recientes</p>
            </div>
            <?php else: ?>
            <div class="divide-y">
                <?php foreach ($ultimasPostulaciones as $postulacion): ?>
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-800">
                                    <?= htmlspecialchars($postulacion['nombre'] . ' ' . $postulacion['apellido_paterno']) ?>
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Para: <?= htmlspecialchars($postulacion['vacante_titulo']) ?>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?= $postulacion['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($postulacion['estado'] === 'revisada' ? 'bg-blue-100 text-blue-800' : 
                                   ($postulacion['estado'] === 'entrevista' ? 'bg-purple-100 text-purple-800' : 
                                   ($postulacion['estado'] === 'contratado' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))) ?>">
                                <?= ucfirst($postulacion['estado']) ?>
                            </span>
                            <p class="text-xs text-gray-400 mt-1">
                                <?= formatoFecha($postulacion['fecha_postulacion'], true) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
