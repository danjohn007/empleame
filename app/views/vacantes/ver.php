<?php
/**
 * Vacancy Detail
 * Alianza Inclusiva Tech
 */
$pageTitle = $vacante['titulo'];

$tiposDiscapacidad = [
    'motriz' => 'Motriz',
    'visual' => 'Visual',
    'auditiva' => 'Auditiva',
    'neurodivergente' => 'Neurodivergente',
    'multiple' => 'Múltiple'
];

$checklistLabels = [
    'rampas' => ['icon' => 'wheelchair', 'label' => 'Rampas de acceso'],
    'banos_adaptados' => ['icon' => 'restroom', 'label' => 'Baños adaptados'],
    'ascensor' => ['icon' => 'building', 'label' => 'Ascensor'],
    'estacionamiento_accesible' => ['icon' => 'parking', 'label' => 'Estacionamiento accesible'],
    'puertas_automaticas' => ['icon' => 'door-open', 'label' => 'Puertas automáticas'],
    'senalizacion_braille' => ['icon' => 'braille', 'label' => 'Señalización Braille'],
    'alarmas_visuales' => ['icon' => 'bell', 'label' => 'Alarmas visuales'],
    'software_accesible' => ['icon' => 'laptop', 'label' => 'Software accesible'],
    'mobiliario_adaptable' => ['icon' => 'chair', 'label' => 'Mobiliario adaptable']
];
?>

<div class="py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="<?= BASE_URL ?>" class="text-gray-500 hover:text-primary">Inicio</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="<?= BASE_URL ?>/vacantes" class="text-gray-500 hover:text-primary">Vacantes</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-800 font-medium"><?= htmlspecialchars(substr($vacante['titulo'], 0, 30)) ?>...</li>
            </ol>
        </nav>
        
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <!-- Header -->
                    <div class="p-6 border-b">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <?php if (!empty($vacante['logo'])): ?>
                                    <img src="<?= BASE_URL ?>/public/uploads/logos/<?= htmlspecialchars($vacante['logo']) ?>" 
                                         alt="Logo" class="w-16 h-16 rounded-xl object-cover">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-building text-primary text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($vacante['nombre_comercial'] ?? $vacante['razon_social']) ?></h3>
                                    <?php if ($vacante['verificada']): ?>
                                        <span class="inline-flex items-center text-sm text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i> Empresa Verificada
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    <i class="fas fa-universal-access mr-1"></i>
                                    Score: <?= $vacante['score_accesibilidad'] ?>%
                                </div>
                            </div>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($vacante['titulo']) ?></h1>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <?= htmlspecialchars($vacante['ubicacion'] ?? $vacante['municipio']) ?>
                            </span>
                            <span class="bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded-full">
                                <i class="fas fa-clock mr-1"></i>
                                <?= ucfirst(str_replace('_', ' ', $vacante['jornada'])) ?>
                            </span>
                            <span class="bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded-full">
                                <i class="fas fa-<?= $vacante['modalidad'] === 'remoto' ? 'home' : ($vacante['modalidad'] === 'hibrido' ? 'random' : 'building') ?> mr-1"></i>
                                <?= ucfirst($vacante['modalidad']) ?>
                            </span>
                            <span class="bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">
                                <i class="fas fa-file-contract mr-1"></i>
                                <?= ucfirst($vacante['tipo_contrato']) ?>
                            </span>
                        </div>
                        
                        <?php if ($vacante['mostrar_salario'] && ($vacante['salario_minimo'] || $vacante['salario_maximo'])): ?>
                        <div class="text-xl font-bold text-green-600">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            <?php if ($vacante['salario_minimo'] && $vacante['salario_maximo']): ?>
                                $<?= number_format($vacante['salario_minimo']) ?> - $<?= number_format($vacante['salario_maximo']) ?> MXN/mes
                            <?php elseif ($vacante['salario_minimo']): ?>
                                Desde $<?= number_format($vacante['salario_minimo']) ?> MXN/mes
                            <?php else: ?>
                                Hasta $<?= number_format($vacante['salario_maximo']) ?> MXN/mes
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Description -->
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            Descripción
                        </h2>
                        <div class="prose text-gray-600">
                            <?= nl2br(htmlspecialchars($vacante['descripcion'])) ?>
                        </div>
                    </div>
                    
                    <!-- Requirements -->
                    <?php if ($vacante['requisitos']): ?>
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">
                            <i class="fas fa-clipboard-list text-primary mr-2"></i>
                            Requisitos
                        </h2>
                        <div class="prose text-gray-600">
                            <?= nl2br(htmlspecialchars($vacante['requisitos'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Accessibility Checklist -->
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-universal-access text-primary mr-2"></i>
                            Facilidades de Accesibilidad
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <?php foreach ($checklistLabels as $key => $item): ?>
                            <div class="flex items-center p-3 rounded-lg <?= !empty($vacante['checklist_accesibilidad'][$key]) ? 'bg-green-50 text-green-800' : 'bg-gray-50 text-gray-400' ?>">
                                <i class="fas fa-<?= $item['icon'] ?> mr-2"></i>
                                <span class="text-sm"><?= $item['label'] ?></span>
                                <?php if (!empty($vacante['checklist_accesibilidad'][$key])): ?>
                                    <i class="fas fa-check ml-auto text-green-600"></i>
                                <?php else: ?>
                                    <i class="fas fa-times ml-auto text-gray-400"></i>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Accepted Disabilities -->
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">
                            <i class="fas fa-users text-primary mr-2"></i>
                            Tipos de Discapacidad Aceptados
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($vacante['discapacidades_aceptadas'] as $tipo): ?>
                            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                <?= $tiposDiscapacidad[$tipo] ?? ucfirst($tipo) ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Apply Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 sticky top-4">
                    <?php if (isLoggedIn() && $_SESSION['user_tipo'] === 'candidato'): ?>
                        <?php if ($compatibilidad > 0): ?>
                        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                            <div class="text-sm text-blue-800 font-medium mb-1">Tu compatibilidad</div>
                            <div class="flex items-center">
                                <div class="flex-1 bg-blue-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full" style="width: <?= $compatibilidad ?>%"></div>
                                </div>
                                <span class="ml-3 font-bold text-blue-800"><?= $compatibilidad ?>%</span>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">
                                Esta vacante cumple con <?= $compatibilidad ?>% de tus necesidades de accesibilidad
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($yaPostulo): ?>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-3xl mb-2"></i>
                                <p class="text-green-800 font-medium">Ya te postulaste a esta vacante</p>
                                <a href="<?= BASE_URL ?>/candidato/postulaciones" class="text-green-600 hover:underline text-sm">
                                    Ver mis postulaciones
                                </a>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="<?= BASE_URL ?>/vacante/<?= $vacante['id'] ?>/postular">
                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Carta de Presentación (opcional)
                                    </label>
                                    <textarea name="carta_presentacion" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                              placeholder="Escribe un mensaje para la empresa..."></textarea>
                                </div>
                                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Postularme Ahora
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php elseif (isLoggedIn() && $_SESSION['user_tipo'] === 'empresa'): ?>
                        <p class="text-center text-gray-600">
                            Las empresas no pueden postularse a vacantes.
                        </p>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Inicia sesión para postularte a esta vacante</p>
                            <a href="<?= BASE_URL ?>/login" class="block w-full btn-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Iniciar Sesión
                            </a>
                            <p class="mt-4 text-sm text-gray-500">
                                ¿No tienes cuenta? 
                                <a href="<?= BASE_URL ?>/registro/candidato" class="text-primary hover:underline">Regístrate</a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Company Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Sobre la Empresa</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        <?= htmlspecialchars(substr($vacante['empresa_descripcion'] ?? 'Información no disponible', 0, 200)) ?>
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-industry w-5 text-primary"></i>
                            <span class="ml-2"><?= htmlspecialchars($vacante['sector_industrial'] ?? 'No especificado') ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-users w-5 text-primary"></i>
                            <span class="ml-2"><?= ucfirst($vacante['tamano_empresa'] ?? 'No especificado') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Similar Vacancies -->
        <?php if (!empty($vacantesSimilares)): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Otras vacantes de esta empresa</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($vacantesSimilares as $similar): ?>
                <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($similar['slug']) ?>" 
                   class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition">
                    <h3 class="font-semibold text-gray-800 mb-2"><?= htmlspecialchars($similar['titulo']) ?></h3>
                    <div class="text-sm text-gray-500">
                        <span><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($similar['municipio']) ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
