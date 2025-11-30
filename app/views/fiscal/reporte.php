<?php $pageTitle = 'Reporte Fiscal'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Reporte Fiscal</h1>
                <p class="text-gray-600">Empleados con discapacidad - Deducción ISR Art. 186</p>
            </div>
            <a href="<?= BASE_URL ?>/fiscal/reporte/pdf" target="_blank" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-download mr-2"></i>Descargar PDF
            </a>
        </div>
        
        <!-- Resumen -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Total Empleados PcD</p>
                <p class="text-3xl font-bold text-gray-800"><?= count($contrataciones) ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Total Nómina Mensual</p>
                <p class="text-3xl font-bold text-gray-800"><?= formatoMoneda($totales['total_salarios']) ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Deducción ISR Mensual</p>
                <p class="text-3xl font-bold text-green-600"><?= formatoMoneda($totales['total_deduccion']) ?></p>
            </div>
        </div>
        
        <!-- Tabla -->
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
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">RFC</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">% Discap.</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Salario</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">ISR</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Deducción</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($contrataciones as $c): 
                        $calc = calcularDeduccionISR($c['salario_bruto'], $c['porcentaje_discapacidad']);
                    ?>
                    <tr>
                        <td class="px-6 py-4"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido_paterno']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($c['candidato_rfc'] ?? 'Pendiente') ?></td>
                        <td class="px-6 py-4 text-center"><?= $c['porcentaje_discapacidad'] ?>%</td>
                        <td class="px-6 py-4 text-right"><?= formatoMoneda($c['salario_bruto']) ?></td>
                        <td class="px-6 py-4 text-right"><?= formatoMoneda($calc['isr_retenido']) ?></td>
                        <td class="px-6 py-4 text-right text-green-600 font-medium"><?= formatoMoneda($calc['deduccion']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
