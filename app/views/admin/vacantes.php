<?php $pageTitle = 'Gestión de Vacantes'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Gestión de Vacantes</h1>
            <div class="flex gap-2">
                <a href="<?= BASE_URL ?>/admin/vacantes" class="px-4 py-2 rounded-lg <?= !$estadoFiltro ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' ?>">Todas</a>
                <a href="<?= BASE_URL ?>/admin/vacantes?estado=pendiente" class="px-4 py-2 rounded-lg <?= $estadoFiltro === 'pendiente' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700' ?>">Pendientes</a>
                <a href="<?= BASE_URL ?>/admin/vacantes?estado=publicada" class="px-4 py-2 rounded-lg <?= $estadoFiltro === 'publicada' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700' ?>">Publicadas</a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Vacante</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Score</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($vacantes as $v): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($v['slug']) ?>" target="_blank" class="font-medium text-gray-800 hover:text-primary">
                                <?= htmlspecialchars($v['titulo']) ?>
                            </a>
                            <p class="text-sm text-gray-500"><?= $v['municipio'] ?> - <?= ucfirst($v['modalidad']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm"><?= htmlspecialchars($v['nombre_comercial'] ?? $v['razon_social']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium 
                                <?= $v['score_accesibilidad'] >= 75 ? 'bg-green-100 text-green-800' : 
                                   ($v['score_accesibilidad'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                <?= $v['score_accesibilidad'] ?>%
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="<?= BASE_URL ?>/admin/vacante/<?= $v['id'] ?>/estado" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                <select name="estado" onchange="this.form.submit()" class="text-sm border rounded px-2 py-1">
                                    <option value="borrador" <?= $v['estado'] === 'borrador' ? 'selected' : '' ?>>Borrador</option>
                                    <option value="pendiente" <?= $v['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="publicada" <?= $v['estado'] === 'publicada' ? 'selected' : '' ?>>Publicada</option>
                                    <option value="pausada" <?= $v['estado'] === 'pausada' ? 'selected' : '' ?>>Pausada</option>
                                    <option value="cerrada" <?= $v['estado'] === 'cerrada' ? 'selected' : '' ?>>Cerrada</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($v['slug']) ?>" target="_blank" class="text-primary hover:underline text-sm">Ver</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
