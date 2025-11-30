<?php $pageTitle = 'Detalle de Queja'; ?>
<div class="py-8 px-4">
    <div class="max-w-3xl mx-auto">
        <a href="<?= BASE_URL ?>/admin/quejas" class="text-primary hover:underline text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver al buzón
        </a>
        
        <div class="bg-white rounded-xl shadow-sm p-6 mt-4">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                        <?= ucfirst(str_replace('_', ' ', $queja['tipo_queja'])) ?>
                    </span>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    <?= $queja['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                       ($queja['estado'] === 'en_revision' ? 'bg-blue-100 text-blue-800' : 
                       ($queja['estado'] === 'resuelta' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) ?>">
                    <?= ucfirst(str_replace('_', ' ', $queja['estado'])) ?>
                </span>
            </div>
            
            <?php if ($queja['empresa_id']): ?>
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">Empresa Reportada</p>
                <p class="font-medium text-gray-800"><?= htmlspecialchars($queja['nombre_comercial'] ?? $queja['razon_social']) ?></p>
                <p class="text-sm text-gray-500">RFC: <?= htmlspecialchars($queja['rfc']) ?></p>
            </div>
            <?php endif; ?>
            
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Descripción de la Queja</h3>
                <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($queja['descripcion']) ?></p>
            </div>
            
            <p class="text-xs text-gray-400 mb-6">Recibida: <?= formatoFecha($queja['fecha_creacion'], true) ?></p>
            
            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-700 mb-4">Actualizar Estado</h3>
                <form method="POST" action="<?= BASE_URL ?>/admin/queja/<?= $queja['id'] ?>/estado">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="pendiente" <?= $queja['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="en_revision" <?= $queja['estado'] === 'en_revision' ? 'selected' : '' ?>>En Revisión</option>
                            <option value="resuelta" <?= $queja['estado'] === 'resuelta' ? 'selected' : '' ?>>Resuelta</option>
                            <option value="archivada" <?= $queja['estado'] === 'archivada' ? 'selected' : '' ?>>Archivada</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas de Seguimiento</label>
                        <textarea name="notas_admin" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?= htmlspecialchars($queja['notas_admin'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
