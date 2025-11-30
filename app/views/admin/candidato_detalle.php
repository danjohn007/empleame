<?php $pageTitle = 'Detalle Candidato'; ?>
<div class="py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <a href="<?= BASE_URL ?>/admin/candidatos" class="text-primary hover:underline text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
        
        <div class="bg-white rounded-xl shadow-sm p-6 mt-4">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($candidato['nombre'] . ' ' . $candidato['apellido_paterno']) ?></h1>
                    <p class="text-gray-500"><?= htmlspecialchars($candidato['email']) ?></p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    <?= $candidato['certificado_verificado'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                    <?= $candidato['certificado_verificado'] ? 'Verificado' : 'Pendiente' ?>
                </span>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Datos Personales</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="text-gray-500">Teléfono:</span> <?= htmlspecialchars($candidato['telefono'] ?? 'No registrado') ?></p>
                        <p><span class="text-gray-500">Municipio:</span> <?= htmlspecialchars($candidato['municipio']) ?></p>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Discapacidad</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="text-gray-500">Tipo:</span> <?= ucfirst($candidato['tipo_discapacidad']) ?></p>
                        <p><span class="text-gray-500">Porcentaje:</span> <?= $candidato['porcentaje_discapacidad'] ?>%</p>
                        <p><span class="text-gray-500">Descripción:</span> <?= htmlspecialchars($candidato['descripcion_discapacidad'] ?? 'No proporcionada') ?></p>
                    </div>
                </div>
            </div>
            
            <?php if ($candidato['certificado_discapacidad'] && !$candidato['certificado_verificado']): ?>
            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-700 mb-4">Verificación de Certificado</h3>
                <div class="mb-4">
                    <a href="<?= BASE_URL ?>/public/uploads/certificados/<?= htmlspecialchars($candidato['certificado_discapacidad']) ?>" 
                       target="_blank" class="text-primary hover:underline">
                        <i class="fas fa-file mr-1"></i>Ver Certificado
                    </a>
                </div>
                
                <form method="POST" action="<?= BASE_URL ?>/admin/candidato/<?= $candidato['id'] ?>/verificar" class="flex gap-4">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <button type="submit" name="accion" value="aprobar" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-1"></i>Aprobar
                    </button>
                    <button type="submit" name="accion" value="rechazar" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                        <i class="fas fa-times mr-1"></i>Rechazar
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
