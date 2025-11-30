<?php $pageTitle = 'Gestión de Candidatos'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Gestión de Candidatos</h1>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Candidato</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Discapacidad</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Certificado</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($candidatos as $c): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido_paterno']) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($c['email']) ?></p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm"><?= ucfirst($c['tipo_discapacidad']) ?></span>
                            <span class="text-xs text-gray-500">(<?= $c['porcentaje_discapacidad'] ?>%)</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($c['certificado_verificado']): ?>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Verificado</span>
                            <?php elseif ($c['certificado_discapacidad']): ?>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">Pendiente</span>
                            <?php else: ?>
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium">Sin subir</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($c['usuario_activo']): ?>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Activo</span>
                            <?php else: ?>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= BASE_URL ?>/admin/candidato/<?= $c['id'] ?>" class="text-primary hover:underline">Ver</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
