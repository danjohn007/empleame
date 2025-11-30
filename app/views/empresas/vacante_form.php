<?php
/**
 * Create/Edit Vacancy Form
 * Alianza Inclusiva Tech
 */
$pageTitle = $vacante ? 'Editar Vacante' : 'Nueva Vacante';
$isEdit = !empty($vacante);
?>

<div class="py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="<?= BASE_URL ?>/empresa/vacantes" class="text-primary hover:underline text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver a mis vacantes
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-4"><?= $pageTitle ?></h1>
        </div>
        
        <form method="POST" action="<?= BASE_URL ?>/empresa/vacante/<?= $isEdit ? $vacante['id'] . '/editar' : 'nueva' ?>" class="space-y-8">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            
            <!-- Información Básica -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-primary mr-2"></i>
                    Información Básica
                </h2>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título del Puesto *</label>
                        <input type="text" id="titulo" name="titulo" required
                               value="<?= htmlspecialchars($vacante['titulo'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Ej: Desarrollador Frontend Junior">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                        <textarea id="descripcion" name="descripcion" required rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Describe las responsabilidades y el ambiente de trabajo..."><?= htmlspecialchars($vacante['descripcion'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="requisitos" class="block text-sm font-medium text-gray-700 mb-1">Requisitos</label>
                        <textarea id="requisitos" name="requisitos" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Experiencia requerida, habilidades técnicas, etc."><?= htmlspecialchars($vacante['requisitos'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Detalles del Puesto -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-briefcase text-primary mr-2"></i>
                    Detalles del Puesto
                </h2>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label for="tipo_contrato" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Contrato</label>
                        <select id="tipo_contrato" name="tipo_contrato"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="indefinido" <?= ($vacante['tipo_contrato'] ?? '') === 'indefinido' ? 'selected' : '' ?>>Indefinido</option>
                            <option value="temporal" <?= ($vacante['tipo_contrato'] ?? '') === 'temporal' ? 'selected' : '' ?>>Temporal</option>
                            <option value="practicas" <?= ($vacante['tipo_contrato'] ?? '') === 'practicas' ? 'selected' : '' ?>>Prácticas</option>
                            <option value="freelance" <?= ($vacante['tipo_contrato'] ?? '') === 'freelance' ? 'selected' : '' ?>>Freelance</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="jornada" class="block text-sm font-medium text-gray-700 mb-1">Jornada</label>
                        <select id="jornada" name="jornada"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="tiempo_completo" <?= ($vacante['jornada'] ?? '') === 'tiempo_completo' ? 'selected' : '' ?>>Tiempo Completo</option>
                            <option value="medio_tiempo" <?= ($vacante['jornada'] ?? '') === 'medio_tiempo' ? 'selected' : '' ?>>Medio Tiempo</option>
                            <option value="por_horas" <?= ($vacante['jornada'] ?? '') === 'por_horas' ? 'selected' : '' ?>>Por Horas</option>
                            <option value="flexible" <?= ($vacante['jornada'] ?? '') === 'flexible' ? 'selected' : '' ?>>Flexible</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="modalidad" class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
                        <select id="modalidad" name="modalidad"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="presencial" <?= ($vacante['modalidad'] ?? '') === 'presencial' ? 'selected' : '' ?>>Presencial</option>
                            <option value="remoto" <?= ($vacante['modalidad'] ?? '') === 'remoto' ? 'selected' : '' ?>>Remoto</option>
                            <option value="hibrido" <?= ($vacante['modalidad'] ?? '') === 'hibrido' ? 'selected' : '' ?>>Híbrido</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="salario_minimo" class="block text-sm font-medium text-gray-700 mb-1">Salario Mínimo (MXN)</label>
                        <input type="number" id="salario_minimo" name="salario_minimo" min="0" step="100"
                               value="<?= $vacante['salario_minimo'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Ej: 15000">
                    </div>
                    
                    <div>
                        <label for="salario_maximo" class="block text-sm font-medium text-gray-700 mb-1">Salario Máximo (MXN)</label>
                        <input type="number" id="salario_maximo" name="salario_maximo" min="0" step="100"
                               value="<?= $vacante['salario_maximo'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Ej: 25000">
                    </div>
                    
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" name="mostrar_salario" value="1" 
                                   <?= ($vacante['mostrar_salario'] ?? 1) ? 'checked' : '' ?>
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Mostrar salario en la vacante</span>
                        </label>
                    </div>
                    
                    <div>
                        <label for="ubicacion" class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                        <input type="text" id="ubicacion" name="ubicacion"
                               value="<?= htmlspecialchars($vacante['ubicacion'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Dirección o zona">
                    </div>
                    
                    <div>
                        <label for="municipio" class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                        <select id="municipio" name="municipio"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <?php
                            $municipios = ['Querétaro', 'El Marqués', 'Corregidora', 'Huimilpan', 'San Juan del Río', 'Tequisquiapan'];
                            foreach ($municipios as $m):
                            ?>
                            <option value="<?= $m ?>" <?= ($vacante['municipio'] ?? 'Querétaro') === $m ? 'selected' : '' ?>><?= $m ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="plazas_disponibles" class="block text-sm font-medium text-gray-700 mb-1">Plazas Disponibles</label>
                        <input type="number" id="plazas_disponibles" name="plazas_disponibles" min="1" max="100"
                               value="<?= $vacante['plazas_disponibles'] ?? 1 ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
            </div>
            
            <!-- Tipos de Discapacidad Aceptados -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-users text-primary mr-2"></i>
                    Tipos de Discapacidad Aceptados
                </h2>
                <p class="text-sm text-gray-600 mb-4">Selecciona los tipos de discapacidad que pueden aplicar a esta vacante</p>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <?php
                    $tipos = ['motriz' => 'Motriz', 'visual' => 'Visual', 'auditiva' => 'Auditiva', 'neurodivergente' => 'Neurodivergente', 'multiple' => 'Múltiple'];
                    $seleccionados = $vacante['discapacidades_aceptadas'] ?? [];
                    foreach ($tipos as $key => $label):
                    ?>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="discapacidades_aceptadas[]" value="<?= $key ?>"
                               <?= in_array($key, $seleccionados) ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm"><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Checklist de Accesibilidad -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">
                    <i class="fas fa-universal-access text-primary mr-2"></i>
                    Checklist de Accesibilidad
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    <strong>Importante:</strong> Este checklist determinará el Score de Accesibilidad de tu vacante. 
                    Un score menor a 50% dejará la vacante en borrador hasta mejorar las condiciones.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <?php
                    $checklist = $vacante['checklist_accesibilidad'] ?? [];
                    $items = [
                        'rampas' => ['icon' => 'wheelchair', 'label' => 'Rampas de acceso', 'peso' => 15],
                        'banos_adaptados' => ['icon' => 'restroom', 'label' => 'Baños adaptados', 'peso' => 15],
                        'ascensor' => ['icon' => 'building', 'label' => 'Ascensor', 'peso' => 10],
                        'estacionamiento_accesible' => ['icon' => 'parking', 'label' => 'Estacionamiento accesible', 'peso' => 10],
                        'puertas_automaticas' => ['icon' => 'door-open', 'label' => 'Puertas automáticas', 'peso' => 5],
                        'senalizacion_braille' => ['icon' => 'braille', 'label' => 'Señalización Braille', 'peso' => 10],
                        'alarmas_visuales' => ['icon' => 'bell', 'label' => 'Alarmas visuales', 'peso' => 10],
                        'software_accesible' => ['icon' => 'laptop', 'label' => 'Software accesible', 'peso' => 15],
                        'mobiliario_adaptable' => ['icon' => 'chair', 'label' => 'Mobiliario adaptable', 'peso' => 10]
                    ];
                    foreach ($items as $key => $item):
                    ?>
                    <label class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 cursor-pointer transition">
                        <input type="checkbox" name="<?= $key ?>" value="1"
                               <?= !empty($checklist[$key]) ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <div class="ml-3">
                            <span class="text-sm font-medium"><i class="fas fa-<?= $item['icon'] ?> mr-1"></i> <?= $item['label'] ?></span>
                            <span class="text-xs text-gray-500 block"><?= $item['peso'] ?> puntos</span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Submit -->
            <div class="flex justify-end space-x-4">
                <a href="<?= BASE_URL ?>/empresa/vacantes" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    <i class="fas fa-save mr-2"></i>
                    <?= $isEdit ? 'Guardar Cambios' : 'Publicar Vacante' ?>
                </button>
            </div>
        </form>
    </div>
</div>
