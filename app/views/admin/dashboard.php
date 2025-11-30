<?php $pageTitle = 'Panel de Administración'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Panel de Administración</h1>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Candidatos</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $estadisticas['candidatos'] ?></p>
                    </div>
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
                <a href="<?= BASE_URL ?>/admin/candidatos" class="text-xs text-primary hover:underline">Ver todos</a>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pendientes</p>
                        <p class="text-2xl font-bold text-yellow-600"><?= $estadisticas['pendientes_verificar'] ?></p>
                    </div>
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
                <span class="text-xs text-gray-500">Certificados por verificar</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Empresas</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $estadisticas['empresas'] ?></p>
                    </div>
                    <i class="fas fa-building text-purple-500 text-2xl"></i>
                </div>
                <a href="<?= BASE_URL ?>/admin/empresas" class="text-xs text-primary hover:underline">Ver todas</a>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Vacantes</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $estadisticas['vacantes_activas'] ?></p>
                    </div>
                    <i class="fas fa-briefcase text-green-500 text-2xl"></i>
                </div>
                <a href="<?= BASE_URL ?>/admin/vacantes" class="text-xs text-primary hover:underline">Ver todas</a>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <a href="<?= BASE_URL ?>/admin/candidatos" class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white hover:from-blue-700 hover:to-blue-800 transition">
                <i class="fas fa-certificate text-2xl mb-2"></i>
                <h3 class="font-semibold">Verificar Certificados</h3>
                <p class="text-sm text-blue-100"><?= $estadisticas['pendientes_verificar'] ?> pendientes</p>
            </a>
            <a href="<?= BASE_URL ?>/admin/vacantes?estado=pendiente" class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-6 text-white hover:from-purple-700 hover:to-purple-800 transition">
                <i class="fas fa-check-double text-2xl mb-2"></i>
                <h3 class="font-semibold">Aprobar Vacantes</h3>
                <p class="text-sm text-purple-100"><?= $estadisticas['vacantes_pendientes'] ?> pendientes</p>
            </a>
            <a href="<?= BASE_URL ?>/admin/quejas" class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl p-6 text-white hover:from-red-700 hover:to-red-800 transition">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <h3 class="font-semibold">Quejas</h3>
                <p class="text-sm text-red-100"><?= $estadisticas['quejas_pendientes'] ?> pendientes</p>
            </a>
            <a href="<?= BASE_URL ?>/admin/configuraciones" class="bg-gradient-to-r from-gray-600 to-gray-700 rounded-xl p-6 text-white hover:from-gray-700 hover:to-gray-800 transition">
                <i class="fas fa-cog text-2xl mb-2"></i>
                <h3 class="font-semibold">Configuraciones</h3>
                <p class="text-sm text-gray-100">Del sistema</p>
            </a>
        </div>
        
        <!-- Candidatos Pendientes -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Certificados Pendientes de Verificación</h2>
            </div>
            <?php if (empty($candidatosPendientes)): ?>
            <div class="p-8 text-center">
                <i class="fas fa-check-circle text-green-400 text-4xl mb-2"></i>
                <p class="text-gray-500">No hay certificados pendientes</p>
            </div>
            <?php else: ?>
            <div class="divide-y">
                <?php foreach ($candidatosPendientes as $c): ?>
                <div class="p-4 flex justify-between items-center hover:bg-gray-50">
                    <div>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido_paterno']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($c['email']) ?> - <?= ucfirst($c['tipo_discapacidad']) ?></p>
                    </div>
                    <a href="<?= BASE_URL ?>/admin/candidato/<?= $c['id'] ?>" class="text-primary hover:underline">
                        Revisar <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
