<?php
$pageTitle = 'Mi Empresa';
?>

<div class="py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Mi Empresa</h1>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="<?= BASE_URL ?>/empresa/perfil" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Razón Social</label>
                        <input type="text" value="<?= htmlspecialchars($empresa['razon_social']) ?>" disabled
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">No se puede modificar</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RFC</label>
                        <input type="text" value="<?= htmlspecialchars($empresa['rfc']) ?>" disabled
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Comercial</label>
                        <input type="text" name="nombre_comercial" value="<?= htmlspecialchars($empresa['nombre_comercial'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" name="telefono" value="<?= htmlspecialchars($empresa['telefono'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección Fiscal</label>
                        <input type="text" name="direccion_fiscal" value="<?= htmlspecialchars($empresa['direccion_fiscal']) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Colonia</label>
                        <input type="text" name="colonia" value="<?= htmlspecialchars($empresa['colonia'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                        <input type="text" name="municipio" value="<?= htmlspecialchars($empresa['municipio'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Código Postal</label>
                        <input type="text" name="codigo_postal" value="<?= htmlspecialchars($empresa['codigo_postal']) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sector Industrial</label>
                        <input type="text" name="sector_industrial" value="<?= htmlspecialchars($empresa['sector_industrial']) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tamaño</label>
                        <select name="tamano_empresa" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="micro" <?= $empresa['tamano_empresa'] === 'micro' ? 'selected' : '' ?>>Micro</option>
                            <option value="pequena" <?= $empresa['tamano_empresa'] === 'pequena' ? 'selected' : '' ?>>Pequeña</option>
                            <option value="mediana" <?= $empresa['tamano_empresa'] === 'mediana' ? 'selected' : '' ?>>Mediana</option>
                            <option value="grande" <?= $empresa['tamano_empresa'] === 'grande' ? 'selected' : '' ?>>Grande</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sitio Web</label>
                        <input type="url" name="sitio_web" value="<?= htmlspecialchars($empresa['sitio_web'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"><?= htmlspecialchars($empresa['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                        <input type="file" name="logo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                </button>
            </form>
        </div>
    </div>
</div>
