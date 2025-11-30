<?php $pageTitle = 'Configuraciones del Sistema'; ?>
<div class="py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">
            <i class="fas fa-cog text-primary mr-2"></i>
            Configuraciones del Sistema
        </h1>
        
        <form method="POST" action="<?= BASE_URL ?>/admin/configuraciones" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            
            <!-- Información del Sitio -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-globe text-primary mr-2"></i>
                    Información del Sitio
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Sitio</label>
                        <input type="text" name="sitio_nombre" 
                               value="<?= htmlspecialchars($configuraciones['sitio_nombre']['valor'] ?? 'Alianza Inclusiva Tech') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logotipo</label>
                        <input type="file" name="sitio_logo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Contacto -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-phone text-primary mr-2"></i>
                    Información de Contacto
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo del Sistema</label>
                        <input type="email" name="correo_sistema" 
                               value="<?= htmlspecialchars($configuraciones['correo_sistema']['valor'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de Contacto</label>
                        <input type="text" name="telefono_contacto" 
                               value="<?= htmlspecialchars($configuraciones['telefono_contacto']['valor'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Horario de Atención</label>
                        <input type="text" name="horario_atencion" 
                               value="<?= htmlspecialchars($configuraciones['horario_atencion']['valor'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                               placeholder="Ej: Lunes a Viernes 9:00 - 18:00">
                    </div>
                </div>
            </div>
            
            <!-- Estilos -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-palette text-primary mr-2"></i>
                    Estilos y Colores
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color Primario</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color_primario" 
                                   value="<?= htmlspecialchars($configuraciones['color_primario']['valor'] ?? '#2563eb') ?>"
                                   class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text" 
                                   value="<?= htmlspecialchars($configuraciones['color_primario']['valor'] ?? '#2563eb') ?>"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color Secundario</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color_secundario" 
                                   value="<?= htmlspecialchars($configuraciones['color_secundario']['valor'] ?? '#1e40af') ?>"
                                   class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text" 
                                   value="<?= htmlspecialchars($configuraciones['color_secundario']['valor'] ?? '#1e40af') ?>"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Integraciones -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-plug text-primary mr-2"></i>
                    Integraciones
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Client ID</label>
                        <input type="text" name="paypal_client_id" 
                               value="<?= htmlspecialchars($configuraciones['paypal_client_id']['valor'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                               placeholder="sb-xxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Secret</label>
                        <input type="password" name="paypal_secret" 
                               value="<?= htmlspecialchars($configuraciones['paypal_secret']['valor'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">API para Generación de QR</label>
                        <input type="url" name="api_qr_url" 
                               value="<?= htmlspecialchars($configuraciones['api_qr_url']['valor'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                               placeholder="https://api.qrserver.com/v1/create-qr-code/">
                    </div>
                </div>
            </div>
            
            <!-- Sistema -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-tools text-primary mr-2"></i>
                    Configuración del Sistema
                </h2>
                <div class="flex items-center">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="mantenimiento" value="1"
                               <?= ($configuraciones['mantenimiento']['valor'] ?? '0') === '1' ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-primary focus:ring-primary mr-2">
                        <span class="text-sm font-medium text-gray-700">Modo Mantenimiento</span>
                    </label>
                    <span class="ml-4 text-xs text-gray-500">Cuando está activo, solo los administradores pueden acceder</span>
                </div>
            </div>
            
            <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i>Guardar Configuraciones
            </button>
        </form>
    </div>
</div>
