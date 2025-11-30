<?php $pageTitle = 'Mis Postulaciones'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Mis Postulaciones</h1>
        
        <?php if (empty($postulaciones)): ?>
        <div class="bg-white rounded-xl p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500 mb-4">No has aplicado a ninguna vacante</p>
            <a href="<?= BASE_URL ?>/vacantes" class="btn-primary text-white px-6 py-3 rounded-lg inline-block">Explorar Vacantes</a>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($postulaciones as $p): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <?php if (!empty($p['logo'])): ?>
                        <img src="<?= BASE_URL ?>/public/uploads/logos/<?= htmlspecialchars($p['logo']) ?>" class="w-12 h-12 rounded-lg object-cover">
                        <?php else: ?>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-primary"></i>
                        </div>
                        <?php endif; ?>
                        <div class="ml-4">
                            <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($p['slug']) ?>" class="font-semibold text-gray-800 hover:text-primary">
                                <?= htmlspecialchars($p['titulo']) ?>
                            </a>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($p['nombre_comercial'] ?? $p['razon_social']) ?></p>
                            <p class="text-xs text-gray-400 mt-1">
                                Postulado el <?= formatoFecha($p['fecha_postulacion']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium 
                            <?= $p['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                               ($p['estado'] === 'revisada' ? 'bg-blue-100 text-blue-800' : 
                               ($p['estado'] === 'entrevista' ? 'bg-purple-100 text-purple-800' : 
                               ($p['estado'] === 'contratado' ? 'bg-green-100 text-green-800' : 
                               ($p['estado'] === 'rechazado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) ?>">
                            <?= ucfirst($p['estado']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
