<?php
/**
 * Password Recovery Form
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Recuperar Contraseña';
?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Recuperar Contraseña</h1>
            <p class="text-gray-600 mt-2">Te enviaremos instrucciones a tu correo</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="<?= BASE_URL ?>/recuperar">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" required
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="tu@email.com">
                    </div>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar Instrucciones
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="<?= BASE_URL ?>/login" class="text-primary hover:underline font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Volver al login
                </a>
            </div>
        </div>
    </div>
</div>
