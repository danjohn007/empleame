<?php
/**
 * Vacancies List
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Vacantes';
?>

<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Vacantes Disponibles</h1>
            <p class="text-gray-600 mt-2">Encuentra oportunidades laborales con condiciones de accesibilidad garantizadas</p>
        </div>
        
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" action="<?= BASE_URL ?>/vacantes" class="grid md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="q" value="<?= htmlspecialchars($filtros['q'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Título o descripción...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                    <select name="municipio" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <?php foreach ($municipios as $m): ?>
                            <option value="<?= $m ?>" <?= ($filtros['municipio'] ?? '') === $m ? 'selected' : '' ?>>
                                <?= $m ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
                    <select name="modalidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todas</option>
                        <option value="presencial" <?= ($filtros['modalidad'] ?? '') === 'presencial' ? 'selected' : '' ?>>Presencial</option>
                        <option value="remoto" <?= ($filtros['modalidad'] ?? '') === 'remoto' ? 'selected' : '' ?>>Remoto</option>
                        <option value="hibrido" <?= ($filtros['modalidad'] ?? '') === 'hibrido' ? 'selected' : '' ?>>Híbrido</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Discapacidad</label>
                    <select name="tipo_discapacidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todas</option>
                        <option value="motriz" <?= ($filtros['tipo_discapacidad'] ?? '') === 'motriz' ? 'selected' : '' ?>>Motriz</option>
                        <option value="visual" <?= ($filtros['tipo_discapacidad'] ?? '') === 'visual' ? 'selected' : '' ?>>Visual</option>
                        <option value="auditiva" <?= ($filtros['tipo_discapacidad'] ?? '') === 'auditiva' ? 'selected' : '' ?>>Auditiva</option>
                        <option value="neurodivergente" <?= ($filtros['tipo_discapacidad'] ?? '') === 'neurodivergente' ? 'selected' : '' ?>>Neurodivergente</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full btn-primary text-white py-2 rounded-lg font-medium hover:opacity-90 transition">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Results -->
        <?php if (empty($vacantes)): ?>
        <div class="bg-white rounded-xl p-12 text-center">
            <i class="fas fa-briefcase text-gray-300 text-6xl mb-4"></i>
            <h2 class="text-xl font-semibold text-gray-600 mb-2">No se encontraron vacantes</h2>
            <p class="text-gray-500">Intenta con otros filtros de búsqueda</p>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($vacantes as $vacante): ?>
            <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <?php if (!empty($vacante['logo'])): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/logos/<?= htmlspecialchars($vacante['logo']) ?>" 
                                     alt="Logo" class="w-12 h-12 rounded-lg object-cover">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                            <?php endif; ?>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-800"><?= htmlspecialchars($vacante['nombre_comercial'] ?? $vacante['razon_social']) ?></h4>
                                <span class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($vacante['municipio']) ?></span>
                            </div>
                        </div>
                        <div class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded" title="Score de Accesibilidad">
                            <i class="fas fa-universal-access mr-1"></i><?= $vacante['score_accesibilidad'] ?>%
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($vacante['slug']) ?>" class="hover:text-primary">
                            <?= htmlspecialchars($vacante['titulo']) ?>
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        <?= htmlspecialchars(substr($vacante['descripcion'], 0, 120)) ?>...
                    </p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                            <i class="fas fa-<?= $vacante['modalidad'] === 'remoto' ? 'home' : ($vacante['modalidad'] === 'hibrido' ? 'random' : 'building') ?> mr-1"></i>
                            <?= ucfirst($vacante['modalidad']) ?>
                        </span>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">
                            <i class="fas fa-clock mr-1"></i>
                            <?= ucfirst(str_replace('_', ' ', $vacante['jornada'])) ?>
                        </span>
                    </div>
                    
                    <?php if ($vacante['mostrar_salario'] && ($vacante['salario_minimo'] || $vacante['salario_maximo'])): ?>
                    <div class="text-gray-700 font-medium mb-4">
                        <i class="fas fa-money-bill-wave text-green-600 mr-1"></i>
                        <?php if ($vacante['salario_minimo'] && $vacante['salario_maximo']): ?>
                            $<?= number_format($vacante['salario_minimo']) ?> - $<?= number_format($vacante['salario_maximo']) ?> MXN
                        <?php elseif ($vacante['salario_minimo']): ?>
                            Desde $<?= number_format($vacante['salario_minimo']) ?> MXN
                        <?php else: ?>
                            Hasta $<?= number_format($vacante['salario_maximo']) ?> MXN
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="border-t px-6 py-4 bg-gray-50 flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-eye mr-1"></i> <?= $vacante['visitas'] ?> visitas
                    </span>
                    <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($vacante['slug']) ?>" 
                       class="text-primary hover:underline font-medium text-sm">
                        Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
