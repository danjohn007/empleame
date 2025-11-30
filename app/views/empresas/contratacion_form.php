<?php $pageTitle = 'Nueva Contratación'; ?>
<div class="py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Registrar Contratación</h1>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="<?= BASE_URL ?>/empresa/contratacion/nueva">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Candidato *</label>
                    <select name="candidato_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Seleccionar...</option>
                        <?php foreach ($candidatos as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido_paterno']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Salario Bruto Mensual *</label>
                    <input type="number" name="salario_bruto" required min="0" step="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Contratación *</label>
                    <input type="date" name="fecha_contratacion" required value="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Contrato</label>
                    <select name="tipo_contrato" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="indefinido">Indefinido</option>
                        <option value="temporal">Temporal</option>
                        <option value="practicas">Prácticas</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i> Registrar Contratación
                </button>
            </form>
        </div>
    </div>
</div>
