<?php
/**
 * Company Registration Form
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Registro de Empresa';
?>

<div class="py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-building text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Registro de Empresa</h1>
            <p class="text-gray-600 mt-2">Completa los datos fiscales de tu empresa</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="<?= BASE_URL ?>/registro/empresa" enctype="multipart/form-data">
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
                                   placeholder="rh@miempresa.com">
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
                
                <!-- Sección: Datos Fiscales -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-invoice text-primary mr-2"></i>
                        Datos Fiscales
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="razon_social" class="block text-sm font-medium text-gray-700 mb-1">
                                Razón Social *
                            </label>
                            <input type="text" id="razon_social" name="razon_social" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Mi Empresa S.A. de C.V.">
                        </div>
                        <div>
                            <label for="rfc" class="block text-sm font-medium text-gray-700 mb-1">
                                RFC *
                            </label>
                            <input type="text" id="rfc" name="rfc" required maxlength="13" pattern="^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent uppercase"
                                   placeholder="XAXX010101000">
                            <p class="text-xs text-gray-500 mt-1">Formato: 3 o 4 letras + 6 dígitos + 3 caracteres</p>
                        </div>
                        <div>
                            <label for="nombre_comercial" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre Comercial
                            </label>
                            <input type="text" id="nombre_comercial" name="nombre_comercial"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Mi Empresa">
                        </div>
                    </div>
                </div>
                
                <!-- Sección: Dirección Fiscal -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                        Dirección Fiscal
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="direccion_fiscal" class="block text-sm font-medium text-gray-700 mb-1">
                                Calle y Número *
                            </label>
                            <input type="text" id="direccion_fiscal" name="direccion_fiscal" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Av. Principal 123">
                        </div>
                        <div>
                            <label for="colonia" class="block text-sm font-medium text-gray-700 mb-1">
                                Colonia
                            </label>
                            <input type="text" id="colonia" name="colonia"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Centro">
                        </div>
                        <div>
                            <label for="municipio" class="block text-sm font-medium text-gray-700 mb-1">
                                Municipio *
                            </label>
                            <select id="municipio" name="municipio" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Seleccionar...</option>
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
                                <option value="Ezequiel Montes">Ezequiel Montes</option>
                                <option value="Jalpan">Jalpan</option>
                            </select>
                        </div>
                        <div>
                            <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-1">
                                Código Postal *
                            </label>
                            <input type="text" id="codigo_postal" name="codigo_postal" required maxlength="5" pattern="\d{5}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="76000">
                        </div>
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono
                            </label>
                            <input type="tel" id="telefono" name="telefono"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="442 123 4567">
                        </div>
                    </div>
                </div>
                
                <!-- Sección: Información de la Empresa -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-industry text-primary mr-2"></i>
                        Información de la Empresa
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="sector_industrial" class="block text-sm font-medium text-gray-700 mb-1">
                                Sector Industrial *
                            </label>
                            <select id="sector_industrial" name="sector_industrial" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Seleccionar...</option>
                                <option value="Tecnología">Tecnología</option>
                                <option value="Manufactura">Manufactura</option>
                                <option value="Servicios">Servicios</option>
                                <option value="Comercio">Comercio</option>
                                <option value="Salud">Salud</option>
                                <option value="Educación">Educación</option>
                                <option value="Construcción">Construcción</option>
                                <option value="Alimentos y Bebidas">Alimentos y Bebidas</option>
                                <option value="Automotriz">Automotriz</option>
                                <option value="Aeroespacial">Aeroespacial</option>
                                <option value="Turismo">Turismo</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div>
                            <label for="tamano_empresa" class="block text-sm font-medium text-gray-700 mb-1">
                                Tamaño de la Empresa
                            </label>
                            <select id="tamano_empresa" name="tamano_empresa"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="micro">Micro (1-10 empleados)</option>
                                <option value="pequena" selected>Pequeña (11-50 empleados)</option>
                                <option value="mediana">Mediana (51-250 empleados)</option>
                                <option value="grande">Grande (251+ empleados)</option>
                            </select>
                        </div>
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
                    <i class="fas fa-building mr-2"></i>
                    Registrar Empresa
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
