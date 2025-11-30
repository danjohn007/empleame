<?php
/**
 * Home Page
 * Alianza Inclusiva Tech
 */
?>

<!-- Hero Section -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    Conectamos Talento con Oportunidades Inclusivas
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Plataforma de vinculación laboral para Personas con Discapacidad (PcD) en Querétaro, México. 
                    Encuentra tu empleo ideal con condiciones de accesibilidad garantizadas.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?= BASE_URL ?>/registro/candidato" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition text-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Soy Candidato
                    </a>
                    <a href="<?= BASE_URL ?>/registro/empresa" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition text-center">
                        <i class="fas fa-building mr-2"></i>
                        Soy Empresa
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/20 rounded-xl p-6 text-center">
                            <div class="text-4xl font-bold mb-2"><?= $estadisticas['vacantes'] ?></div>
                            <div class="text-sm text-blue-100">Vacantes Activas</div>
                        </div>
                        <div class="bg-white/20 rounded-xl p-6 text-center">
                            <div class="text-4xl font-bold mb-2"><?= $estadisticas['candidatos'] ?></div>
                            <div class="text-sm text-blue-100">Candidatos Registrados</div>
                        </div>
                        <div class="bg-white/20 rounded-xl p-6 text-center">
                            <div class="text-4xl font-bold mb-2"><?= $estadisticas['empresas'] ?></div>
                            <div class="text-sm text-blue-100">Empresas Verificadas</div>
                        </div>
                        <div class="bg-white/20 rounded-xl p-6 text-center">
                            <div class="text-4xl font-bold mb-2"><?= $estadisticas['contrataciones'] ?></div>
                            <div class="text-sm text-blue-100">Contrataciones</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">¿Por qué elegirnos?</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Somos la plataforma líder en vinculación laboral inclusiva en Querétaro
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-xl p-6 text-center hover:shadow-lg transition">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-universal-access text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Sello de Accesibilidad</h3>
                <p class="text-gray-600">
                    Todas las vacantes incluyen un score de accesibilidad verificado. Sabrás exactamente qué condiciones te esperan.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6 text-center hover:shadow-lg transition">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-percentage text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Match Inteligente</h3>
                <p class="text-gray-600">
                    Nuestro algoritmo cruza tus necesidades de accesibilidad con las facilidades de cada vacante.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6 text-center hover:shadow-lg transition">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Beneficios Fiscales</h3>
                <p class="text-gray-600">
                    Las empresas pueden calcular su ahorro fiscal por contratar PcD según la Ley ISR de México.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Jobs Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Vacantes Recientes</h2>
            <a href="<?= BASE_URL ?>/vacantes" class="text-primary hover:underline font-medium">
                Ver todas <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <?php if (empty($vacantes)): ?>
        <div class="bg-white rounded-xl p-8 text-center">
            <i class="fas fa-briefcase text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No hay vacantes publicadas aún.</p>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($vacantes as $vacante): ?>
            <article class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <?php if ($vacante['logo']): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/logos/<?= htmlspecialchars($vacante['logo']) ?>" 
                                     alt="<?= htmlspecialchars($vacante['nombre_comercial'] ?? $vacante['razon_social']) ?>"
                                     class="w-12 h-12 rounded-lg object-cover">
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
                            <?= $vacante['score_accesibilidad'] ?>%
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($vacante['slug']) ?>" class="hover:text-primary">
                            <?= htmlspecialchars($vacante['titulo']) ?>
                        </a>
                    </h3>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                            <?= ucfirst(str_replace('_', ' ', $vacante['modalidad'])) ?>
                        </span>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">
                            <?= ucfirst(str_replace('_', ' ', $vacante['jornada'])) ?>
                        </span>
                    </div>
                    
                    <?php if ($vacante['mostrar_salario'] && ($vacante['salario_minimo'] || $vacante['salario_maximo'])): ?>
                    <div class="text-gray-700 font-medium">
                        <i class="fas fa-money-bill-wave text-green-600 mr-1"></i>
                        <?php if ($vacante['salario_minimo'] && $vacante['salario_maximo']): ?>
                            $<?= number_format($vacante['salario_minimo']) ?> - $<?= number_format($vacante['salario_maximo']) ?>
                        <?php elseif ($vacante['salario_minimo']): ?>
                            Desde $<?= number_format($vacante['salario_minimo']) ?>
                        <?php else: ?>
                            Hasta $<?= number_format($vacante['salario_maximo']) ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="border-t px-6 py-4 bg-gray-50">
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
</section>

<!-- For Companies Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Para Empresas</span>
                <h2 class="text-3xl font-bold text-gray-800 mt-2 mb-6">
                    Contrata talento diverso y obtén beneficios fiscales
                </h2>
                <p class="text-gray-600 mb-6">
                    De acuerdo con el Artículo 186 de la Ley del ISR, las empresas que contratan personas con discapacidad 
                    pueden deducir hasta el 100% del ISR retenido.
                </p>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">Calculadora de deducción ISR integrada</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">Generación de reportes trimestrales para SAT</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">Candidatos con certificados de discapacidad verificados</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">Apoyo de la Cámara de Comercio de Querétaro</span>
                    </li>
                </ul>
                <a href="<?= BASE_URL ?>/registro/empresa" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold inline-block transition">
                    <i class="fas fa-building mr-2"></i>
                    Registrar mi empresa
                </a>
            </div>
            <div class="bg-gray-100 rounded-2xl p-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Ejemplo de Ahorro Fiscal</h3>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Salario mensual bruto</span>
                            <span class="font-semibold">$20,000 MXN</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">ISR Retenido (estimado)</span>
                            <span class="font-semibold">$2,372 MXN</span>
                        </div>
                    </div>
                    <div class="bg-green-100 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-green-800 font-medium">Deducción aplicable (100%)</span>
                            <span class="font-bold text-green-800">$2,372 MXN/mes</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-800 font-medium">Ahorro anual estimado</span>
                            <span class="font-bold text-blue-800">$28,464 MXN</span>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    * Cálculo estimado basado en tablas ISR 2024. Consulta con tu contador.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Anonymous Complaint CTA -->
<section class="py-12 bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-6 md:mb-0">
                <h3 class="text-2xl font-bold mb-2">¿Sufriste discriminación laboral?</h3>
                <p class="text-gray-300">Nuestro buzón de quejas anónimas te permite reportar de forma segura.</p>
            </div>
            <a href="<?= BASE_URL ?>/queja/nueva" class="bg-white text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-comment-alt mr-2"></i>
                Reportar Anónimamente
            </a>
        </div>
    </div>
</section>
