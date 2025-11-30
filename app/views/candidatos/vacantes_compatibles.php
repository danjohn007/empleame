<?php $pageTitle = 'Vacantes Compatibles'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Vacantes Compatibles</h1>
        <p class="text-gray-600 mb-8">Ordenadas por compatibilidad con tus necesidades de accesibilidad</p>
        
        <?php if (empty($vacantes)): ?>
        <div class="bg-white rounded-xl p-12 text-center">
            <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No hay vacantes compatibles con tu perfil en este momento.</p>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($vacantes as $v): ?>
            <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-semibold text-gray-800">
                            <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($v['slug']) ?>" class="hover:text-primary">
                                <?= htmlspecialchars($v['titulo']) ?>
                            </a>
                        </h3>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">
                            <?= $v['compatibilidad'] ?>%
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($v['nombre_comercial'] ?? $v['razon_social']) ?></p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded"><?= ucfirst($v['modalidad']) ?></span>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded"><?= $v['municipio'] ?></span>
                    </div>
                    
                    <?php if ($v['ya_postulado']): ?>
                    <span class="text-green-600 text-sm"><i class="fas fa-check mr-1"></i>Ya postulado</span>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($v['slug']) ?>" class="text-primary hover:underline text-sm">
                        Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
