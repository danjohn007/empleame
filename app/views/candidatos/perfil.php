<?php $pageTitle = 'Mi Perfil'; ?>
<div class="py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Mi Perfil</h1>
        
        <form method="POST" action="<?= BASE_URL ?>/candidato/perfil" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            
            <!-- Datos Personales -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Datos Personales</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($candidato['nombre']) ?>" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" value="<?= htmlspecialchars($candidato['apellido_paterno']) ?>" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
                        <input type="text" name="apellido_materno" value="<?= htmlspecialchars($candidato['apellido_materno'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" name="telefono" value="<?= htmlspecialchars($candidato['telefono'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                        <input type="text" name="municipio" value="<?= htmlspecialchars($candidato['municipio'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Discapacidad -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Información de Discapacidad</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Discapacidad</label>
                        <select name="tipo_discapacidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="motriz" <?= $candidato['tipo_discapacidad'] === 'motriz' ? 'selected' : '' ?>>Motriz</option>
                            <option value="visual" <?= $candidato['tipo_discapacidad'] === 'visual' ? 'selected' : '' ?>>Visual</option>
                            <option value="auditiva" <?= $candidato['tipo_discapacidad'] === 'auditiva' ? 'selected' : '' ?>>Auditiva</option>
                            <option value="neurodivergente" <?= $candidato['tipo_discapacidad'] === 'neurodivergente' ? 'selected' : '' ?>>Neurodivergente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Porcentaje (%)</label>
                        <input type="number" name="porcentaje_discapacidad" min="0" max="100" 
                               value="<?= $candidato['porcentaje_discapacidad'] ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion_discapacidad" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?= htmlspecialchars($candidato['descripcion_discapacidad'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Formación -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Formación y Experiencia</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Estudios</label>
                        <select name="nivel_estudios" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Seleccionar...</option>
                            <option value="primaria" <?= ($candidato['nivel_estudios'] ?? '') === 'primaria' ? 'selected' : '' ?>>Primaria</option>
                            <option value="secundaria" <?= ($candidato['nivel_estudios'] ?? '') === 'secundaria' ? 'selected' : '' ?>>Secundaria</option>
                            <option value="preparatoria" <?= ($candidato['nivel_estudios'] ?? '') === 'preparatoria' ? 'selected' : '' ?>>Preparatoria</option>
                            <option value="tecnico" <?= ($candidato['nivel_estudios'] ?? '') === 'tecnico' ? 'selected' : '' ?>>Técnico</option>
                            <option value="licenciatura" <?= ($candidato['nivel_estudios'] ?? '') === 'licenciatura' ? 'selected' : '' ?>>Licenciatura</option>
                            <option value="posgrado" <?= ($candidato['nivel_estudios'] ?? '') === 'posgrado' ? 'selected' : '' ?>>Posgrado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Años de Experiencia</label>
                        <input type="number" name="experiencia_anos" min="0" value="<?= $candidato['experiencia_anos'] ?? 0 ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Habilidades</label>
                        <textarea name="habilidades" rows="3" placeholder="Ejemplo: Excel, Diseño gráfico, Atención al cliente..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?= htmlspecialchars($candidato['habilidades'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i> Guardar Cambios
            </button>
        </form>
    </div>
</div>
