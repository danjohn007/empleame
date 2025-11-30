<?php
/**
 * Registration Type Selection
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Registro';
?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-3xl w-full">
        <div class="text-center mb-8">
            <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Crear Cuenta</h1>
            <p class="text-gray-600 mt-2">Selecciona el tipo de cuenta que deseas crear</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Candidato -->
            <a href="<?= BASE_URL ?>/registro/candidato" class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition group">
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition">
                        <i class="fas fa-user text-green-600 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Soy Candidato</h2>
                    <p class="text-gray-600 mb-4">
                        Busco empleo y quiero encontrar vacantes con condiciones de accesibilidad garantizadas.
                    </p>
                    <ul class="text-sm text-gray-600 text-left space-y-2 mb-6">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Vacantes con Score de Accesibilidad
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Match inteligente según tus necesidades
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Postulación fácil y seguimiento
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Empresas verificadas
                        </li>
                    </ul>
                    <span class="inline-flex items-center text-green-600 font-semibold group-hover:underline">
                        Registrarme como Candidato <i class="fas fa-arrow-right ml-2"></i>
                    </span>
                </div>
            </a>
            
            <!-- Empresa -->
            <a href="<?= BASE_URL ?>/registro/empresa" class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition group">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition">
                        <i class="fas fa-building text-blue-600 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Soy Empresa</h2>
                    <p class="text-gray-600 mb-4">
                        Busco talento diverso y quiero aprovechar los beneficios fiscales por contratación inclusiva.
                    </p>
                    <ul class="text-sm text-gray-600 text-left space-y-2 mb-6">
                        <li class="flex items-center">
                            <i class="fas fa-check text-blue-500 mr-2"></i>
                            Publicar vacantes con sello de accesibilidad
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-blue-500 mr-2"></i>
                            Calculadora de deducción ISR
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-blue-500 mr-2"></i>
                            Generación de reportes SAT
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-blue-500 mr-2"></i>
                            Candidatos con certificados verificados
                        </li>
                    </ul>
                    <span class="inline-flex items-center text-blue-600 font-semibold group-hover:underline">
                        Registrar mi Empresa <i class="fas fa-arrow-right ml-2"></i>
                    </span>
                </div>
            </a>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                ¿Ya tienes cuenta? 
                <a href="<?= BASE_URL ?>/login" class="text-primary hover:underline font-medium">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</div>
