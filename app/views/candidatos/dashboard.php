<?php $pageTitle = 'Mi Dashboard'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Hola, <?= htmlspecialchars($candidato['nombre']) ?></h1>
            <p class="text-gray-600 mt-2">Encuentra tu empleo ideal con accesibilidad garantizada</p>
        </div>
        
        <!-- Stats -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Mis Postulaciones</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalPostulaciones ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-paper-plane text-primary text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">En Proceso</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $entrevistas ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-comments text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Certificado</p>
                        <p class="text-lg font-bold <?= $candidato['certificado_verificado'] ? 'text-green-600' : 'text-yellow-600' ?>">
                            <?= $candidato['certificado_verificado'] ? 'Verificado' : 'Pendiente' ?>
                        </p>
                    </div>
                    <div class="w-12 h-12 <?= $candidato['certificado_verificado'] ? 'bg-green-100' : 'bg-yellow-100' ?> rounded-full flex items-center justify-center">
                        <i class="fas <?= $candidato['certificado_verificado'] ? 'fa-check-circle text-green-600' : 'fa-clock text-yellow-600' ?> text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vacantes Compatibles -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Vacantes Compatibles</h2>
                <a href="<?= BASE_URL ?>/candidato/vacantes-compatibles" class="text-primary hover:underline text-sm">Ver todas</a>
            </div>
            
            <?php if (empty($vacantesCompatibles)): ?>
            <p class="text-gray-500">No hay vacantes compatibles en este momento.</p>
            <?php else: ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($vacantesCompatibles as $v): ?>
                <a href="<?= BASE_URL ?>/vacante/<?= htmlspecialchars($v['slug']) ?>" class="block p-4 border rounded-lg hover:border-primary transition">
                    <h3 class="font-medium text-gray-800"><?= htmlspecialchars($v['titulo']) ?></h3>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($v['nombre_comercial'] ?? $v['razon_social']) ?></p>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded"><?= $v['compatibilidad'] ?>% compatible</span>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Score: <?= $v['score_accesibilidad'] ?>%</span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Últimas Postulaciones -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Mis Postulaciones Recientes</h2>
            </div>
            <?php if (empty($ultimasPostulaciones)): ?>
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">No tienes postulaciones aún</p>
                <a href="<?= BASE_URL ?>/vacantes" class="text-primary hover:underline mt-2 inline-block">Explorar vacantes</a>
            </div>
            <?php else: ?>
            <div class="divide-y">
                <?php foreach ($ultimasPostulaciones as $p): ?>
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-medium text-gray-800"><?= htmlspecialchars($p['titulo']) ?></h4>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($p['nombre_comercial']) ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            <?= $p['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                               ($p['estado'] === 'entrevista' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') ?>">
                            <?= ucfirst($p['estado']) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
