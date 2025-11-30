<?php $pageTitle = 'Buzón de Quejas'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Buzón de Quejas Anónimas</h1>
        
        <?php if (empty($quejas)): ?>
        <div class="bg-white rounded-xl p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No hay quejas registradas</p>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($quejas as $q): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                <?= ucfirst(str_replace('_', ' ', $q['tipo_queja'])) ?>
                            </span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                <?= $q['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($q['estado'] === 'en_revision' ? 'bg-blue-100 text-blue-800' : 
                                   ($q['estado'] === 'resuelta' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) ?>">
                                <?= ucfirst(str_replace('_', ' ', $q['estado'])) ?>
                            </span>
                        </div>
                        <?php if ($q['empresa_id']): ?>
                        <p class="text-sm text-gray-500 mb-2">
                            <i class="fas fa-building mr-1"></i>Empresa: <?= htmlspecialchars($q['nombre_comercial'] ?? $q['razon_social']) ?>
                        </p>
                        <?php endif; ?>
                        <p class="text-gray-700"><?= htmlspecialchars(substr($q['descripcion'], 0, 200)) ?>...</p>
                        <p class="text-xs text-gray-400 mt-2">Recibida: <?= formatoFecha($q['fecha_creacion'], true) ?></p>
                    </div>
                    <a href="<?= BASE_URL ?>/admin/queja/<?= $q['id'] ?>" class="text-primary hover:underline">
                        Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
