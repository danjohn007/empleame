<?php $pageTitle = 'Reportar Queja'; ?>
<div class="py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Buzón de Quejas Anónimas</h1>
            <p class="text-gray-600 mt-2">Tu identidad está protegida. Reporta discriminación o incumplimiento.</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-8">
            <form method="POST" action="<?= BASE_URL ?>/queja/nueva">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Queja *</label>
                    <select name="tipo_queja" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="">Seleccionar...</option>
                        <option value="discriminacion">Discriminación</option>
                        <option value="falta_accesibilidad">Falta de Accesibilidad Prometida</option>
                        <option value="acoso">Acoso Laboral</option>
                        <option value="incumplimiento">Incumplimiento de Contrato</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Empresa (opcional)</label>
                    <select name="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="">No especificar o desconozco</option>
                        <?php foreach ($empresas as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre_comercial'] ?? $e['razon_social']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Si la empresa no aparece, puedes mencionarla en la descripción</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Describe tu Situación *</label>
                    <textarea name="descripcion" required rows="6" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                              placeholder="Cuéntanos qué sucedió. Incluye fechas, lugares y detalles relevantes. Tu reporte es completamente anónimo."></textarea>
                </div>
                
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-shield-alt mr-1"></i>
                        <strong>Tu privacidad está protegida.</strong> Este reporte es completamente anónimo. 
                        No recopilamos información que pueda identificarte.
                    </p>
                </div>
                
                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-paper-plane mr-2"></i>Enviar Reporte Anónimo
                </button>
            </form>
        </div>
    </div>
</div>
