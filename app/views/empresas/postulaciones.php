<?php $pageTitle = 'Postulaciones - ' . $vacante['titulo']; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <a href="<?= BASE_URL ?>/empresa/vacantes" class="text-primary hover:underline text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver a vacantes
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mt-4 mb-8">
            Postulaciones: <?= htmlspecialchars($vacante['titulo']) ?>
        </h1>
        
        <?php if (empty($postulaciones)): ?>
        <div class="bg-white rounded-xl p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No hay postulaciones para esta vacante</p>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($postulaciones as $p): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido_paterno']) ?></h3>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($p['email']) ?></p>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-wheelchair mr-1"></i> <?= ucfirst($p['tipo_discapacidad']) ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-graduation-cap mr-1"></i> <?= ucfirst($p['nivel_estudios'] ?? 'No especificado') ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="mb-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                Compatibilidad: <?= $p['score_compatibilidad'] ?>%
                            </span>
                        </div>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium 
                            <?= $p['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                               ($p['estado'] === 'entrevista' ? 'bg-purple-100 text-purple-800' : 
                               ($p['estado'] === 'contratado' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) ?>">
                            <?= ucfirst($p['estado']) ?>
                        </span>
                    </div>
                </div>
                
                <?php if ($p['carta_presentacion']): ?>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($p['carta_presentacion'])) ?></p>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?= BASE_URL ?>/empresa/postulacion/<?= $p['id'] ?>/estado" class="mt-4 flex items-center gap-4">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <select name="estado" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="pendiente" <?= $p['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="revisada" <?= $p['estado'] === 'revisada' ? 'selected' : '' ?>>Revisada</option>
                        <option value="entrevista" <?= $p['estado'] === 'entrevista' ? 'selected' : '' ?>>Entrevista</option>
                        <option value="seleccionado" <?= $p['estado'] === 'seleccionado' ? 'selected' : '' ?>>Seleccionado</option>
                        <option value="rechazado" <?= $p['estado'] === 'rechazado' ? 'selected' : '' ?>>Rechazado</option>
                    </select>
                    <button type="submit" class="btn-primary text-white px-4 py-2 rounded-lg text-sm">
                        Actualizar
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
