<?php $pageTitle = 'Contrataciones'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Contrataciones PcD</h1>
            <a href="<?= BASE_URL ?>/empresa/contratacion/nueva" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-plus mr-2"></i> Nueva Contratación
            </a>
        </div>
        
        <?php if (empty($contrataciones)): ?>
        <div class="bg-white rounded-xl p-12 text-center">
            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No hay contrataciones registradas</p>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Empleado</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Discapacidad</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Salario</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Deducción ISR</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($contrataciones as $c): ?>
                    <tr>
                        <td class="px-6 py-4"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido_paterno']) ?></td>
                        <td class="px-6 py-4"><?= ucfirst($c['tipo_discapacidad']) ?> (<?= $c['porcentaje_discapacidad'] ?>%)</td>
                        <td class="px-6 py-4"><?= formatoFecha($c['fecha_contratacion']) ?></td>
                        <td class="px-6 py-4 text-right"><?= formatoMoneda($c['salario_bruto']) ?></td>
                        <td class="px-6 py-4 text-right text-green-600 font-medium"><?= formatoMoneda($c['deduccion_isr_mensual']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
