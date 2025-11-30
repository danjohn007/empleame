<?php
/**
 * Company Vacancies List
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Mis Vacantes';

$estadosClases = [
    'borrador' => 'bg-gray-100 text-gray-800',
    'pendiente' => 'bg-yellow-100 text-yellow-800',
    'publicada' => 'bg-green-100 text-green-800',
    'pausada' => 'bg-orange-100 text-orange-800',
    'cerrada' => 'bg-red-100 text-red-800'
];
?>

<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Mis Vacantes</h1>
                <p class="text-gray-600 mt-2">Gestiona tus ofertas de empleo</p>
            </div>
            <a href="<?= BASE_URL ?>/empresa/vacante/nueva" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="fas fa-plus mr-2"></i>
                Nueva Vacante
            </a>
        </div>
        
        <?php if (empty($vacantes)): ?>
        <div class="bg-white rounded-xl p-12 text-center">
            <i class="fas fa-briefcase text-gray-300 text-6xl mb-4"></i>
            <h2 class="text-xl font-semibold text-gray-600 mb-2">No tienes vacantes</h2>
            <p class="text-gray-500 mb-6">Comienza publicando tu primera oferta de empleo</p>
            <a href="<?= BASE_URL ?>/empresa/vacante/nueva" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold inline-block">
                <i class="fas fa-plus mr-2"></i>
                Crear Primera Vacante
            </a>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Vacante</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Score</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Postulaciones</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Visitas</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($vacantes as $vacante): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($vacante['slug']) ?>" 
                                   class="font-medium text-gray-800 hover:text-primary" target="_blank">
                                    <?= htmlspecialchars($vacante['titulo']) ?>
                                </a>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($vacante['municipio']) ?>
                                    <span class="mx-2">•</span>
                                    <?= ucfirst($vacante['modalidad']) ?>
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium <?= $estadosClases[$vacante['estado']] ?>">
                                <?= ucfirst($vacante['estado']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium 
                                <?= $vacante['score_accesibilidad'] >= 75 ? 'bg-green-100 text-green-800' : 
                                   ($vacante['score_accesibilidad'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                <i class="fas fa-universal-access mr-1"></i>
                                <?= $vacante['score_accesibilidad'] ?>%
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= BASE_URL ?>/empresa/vacante/<?= $vacante['id'] ?>/postulaciones" 
                               class="text-primary hover:underline font-medium">
                                <?= $vacante['total_postulaciones'] ?>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600">
                            <?= $vacante['visitas'] ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="<?= BASE_URL ?>/empresa/vacante/<?= $vacante['id'] ?>/editar" 
                                   class="text-blue-600 hover:text-blue-800" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/empresa/vacante/<?= $vacante['id'] ?>/postulaciones" 
                                   class="text-green-600 hover:text-green-800" title="Ver postulaciones">
                                    <i class="fas fa-users"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
