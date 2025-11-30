<?php
/**
 * Candidate Registration Form
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Registro de Candidato';
?>

<div class="py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Registro de Candidato</h1>
            <p class="text-gray-600 mt-2">Crea tu perfil y encuentra vacantes accesibles</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="<?= BASE_URL ?>/registro/candidato" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <!-- Sección: Datos de Acceso -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-key text-primary mr-2"></i>
                        Datos de Acceso
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo Electrónico *
                            </label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="tu@email.com">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña *
                            </label>
                            <input type="password" id="password" name="password" required minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Mínimo 8 caracteres">
                        </div>
                        <div>
                            <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar Contraseña *
                            </label>
                            <input type="password" id="password_confirm" name="password_confirm" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Repite la contraseña">
                        </div>
                    </div>
                </div>
                
                <!-- Sección: Datos Personales -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-primary mr-2"></i>
                        Datos Personales
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre(s) *
                            </label>
                            <input type="text" id="nombre" name="nombre" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Juan">
                        </div>
                        <div>
                            <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-1">
                                Apellido Paterno *
                            </label>
                            <input type="text" id="apellido_paterno" name="apellido_paterno" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Pérez">
                        </div>
                        <div>
                            <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-1">
                                Apellido Materno
                            </label>
                            <input type="text" id="apellido_materno" name="apellido_materno"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="García">
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha de Nacimiento *
                            </label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono
                            </label>
                            <input type="tel" id="telefono" name="telefono"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="442 123 4567">
                        </div>
                        <div>
                            <label for="municipio" class="block text-sm font-medium text-gray-700 mb-1">
                                Municipio
                            </label>
                            <select id="municipio" name="municipio"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="Querétaro">Querétaro</option>
                                <option value="El Marqués">El Marqués</option>
                                <option value="Corregidora">Corregidora</option>
                                <option value="Huimilpan">Huimilpan</option>
                                <option value="San Juan del Río">San Juan del Río</option>
                                <option value="Tequisquiapan">Tequisquiapan</option>
                                <option value="Pedro Escobedo">Pedro Escobedo</option>
                                <option value="Amealco">Amealco</option>
                                <option value="Cadereyta">Cadereyta</option>
                                <option value="Colón">Colón</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Sección: Información de Discapacidad -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-universal-access text-primary mr-2"></i>
                        Información de Discapacidad
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="tipo_discapacidad" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipo de Discapacidad *
                            </label>
                            <select id="tipo_discapacidad" name="tipo_discapacidad" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Seleccionar...</option>
                                <option value="motriz">Motriz</option>
                                <option value="visual">Visual</option>
                                <option value="auditiva">Auditiva</option>
                                <option value="neurodivergente">Neurodivergente</option>
                                <option value="multiple">Múltiple</option>
                                <option value="otra">Otra</option>
                            </select>
                        </div>
                        <div>
                            <label for="porcentaje_discapacidad" class="block text-sm font-medium text-gray-700 mb-1">
                                Porcentaje de Discapacidad
                            </label>
                            <div class="flex items-center">
                                <input type="range" id="porcentaje_discapacidad" name="porcentaje_discapacidad" 
                                       min="0" max="100" value="30"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                       oninput="document.getElementById('porcentaje_valor').textContent = this.value + '%'">
                                <span id="porcentaje_valor" class="ml-3 text-sm font-medium text-gray-700 w-12">30%</span>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label for="descripcion_discapacidad" class="block text-sm font-medium text-gray-700 mb-1">
                                Descripción de la Discapacidad
                            </label>
                            <textarea id="descripcion_discapacidad" name="descripcion_discapacidad" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Describe brevemente tu condición..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Dispositivos de Apoyo -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dispositivos de Apoyo Requeridos
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="dispositivos_apoyo[]" value="silla_de_ruedas" class="rounded text-primary">
                                <span class="ml-2 text-sm">Silla de ruedas</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="dispositivos_apoyo[]" value="lector_pantalla" class="rounded text-primary">
                                <span class="ml-2 text-sm">Lector de pantalla</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="dispositivos_apoyo[]" value="auxiliar_auditivo" class="rounded text-primary">
                                <span class="ml-2 text-sm">Auxiliar auditivo</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="dispositivos_apoyo[]" value="baston" class="rounded text-primary">
                                <span class="ml-2 text-sm">Bastón</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="dispositivos_apoyo[]" value="perro_guia" class="rounded text-primary">
                                <span class="ml-2 text-sm">Perro guía</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="dispositivos_apoyo[]" value="otro" class="rounded text-primary">
                                <span class="ml-2 text-sm">Otro</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Necesidades de Accesibilidad -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Necesidades de Accesibilidad en el Trabajo
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="rampas" class="rounded text-primary">
                                <span class="ml-2 text-sm">Rampas</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="banos_adaptados" class="rounded text-primary">
                                <span class="ml-2 text-sm">Baños adaptados</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="ascensor" class="rounded text-primary">
                                <span class="ml-2 text-sm">Ascensor</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="software_accesible" class="rounded text-primary">
                                <span class="ml-2 text-sm">Software accesible</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="senalizacion_braille" class="rounded text-primary">
                                <span class="ml-2 text-sm">Señalización Braille</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="alarmas_visuales" class="rounded text-primary">
                                <span class="ml-2 text-sm">Alarmas visuales</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="estacionamiento_accesible" class="rounded text-primary">
                                <span class="ml-2 text-sm">Estacionamiento accesible</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="mobiliario_adaptable" class="rounded text-primary">
                                <span class="ml-2 text-sm">Mobiliario adaptable</span>
                            </label>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="necesidades_accesibilidad[]" value="horario_flexible" class="rounded text-primary">
                                <span class="ml-2 text-sm">Horario flexible</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Sección: Documentación -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-primary mr-2"></i>
                        Documentación
                    </h2>
                    <div>
                        <label for="certificado_discapacidad" class="block text-sm font-medium text-gray-700 mb-1">
                            Certificado de Discapacidad (PDF o imagen)
                        </label>
                        <input type="file" id="certificado_discapacidad" name="certificado_discapacidad"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Máximo 5MB. Será verificado por nuestro equipo.</p>
                    </div>
                </div>
                
                <!-- Sección: Nivel de Estudios -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-graduation-cap text-primary mr-2"></i>
                        Formación Académica
                    </h2>
                    <div>
                        <label for="nivel_estudios" class="block text-sm font-medium text-gray-700 mb-1">
                            Nivel de Estudios
                        </label>
                        <select id="nivel_estudios" name="nivel_estudios"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            <option value="primaria">Primaria</option>
                            <option value="secundaria">Secundaria</option>
                            <option value="preparatoria">Preparatoria</option>
                            <option value="tecnico">Técnico</option>
                            <option value="licenciatura">Licenciatura</option>
                            <option value="posgrado">Posgrado</option>
                        </select>
                    </div>
                </div>
                
                <!-- Términos y Condiciones -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" required class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-600">
                            Acepto los <a href="#" class="text-primary hover:underline">Términos y Condiciones</a> 
                            y el <a href="#" class="text-primary hover:underline">Aviso de Privacidad</a>
                        </span>
                    </label>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear mi Cuenta
                </button>
            </form>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                ¿Ya tienes cuenta? 
                <a href="<?= BASE_URL ?>/login" class="text-primary hover:underline font-medium">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</div>
