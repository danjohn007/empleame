<?php
/**
 * Login Form
 * Alianza Inclusiva Tech
 */
$pageTitle = 'Iniciar Sesión';
?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sign-in-alt text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Iniciar Sesión</h1>
            <p class="text-gray-600 mt-2">Accede a tu cuenta para continuar</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="<?= BASE_URL ?>/login">
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
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="••••••••">
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                    <a href="<?= BASE_URL ?>/recuperar" class="text-sm text-primary hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    ¿No tienes cuenta? 
                    <a href="<?= BASE_URL ?>/registro" class="text-primary hover:underline font-medium">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>
        
        <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development'): ?>
        <!-- Demo credentials info - Only visible in development mode -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-blue-800 mb-2"><i class="fas fa-info-circle mr-1"></i> Credenciales de demostración</h4>
            <div class="text-sm text-blue-700 space-y-1">
                <p><strong>Admin:</strong> admin@alianzainclusiva.mx</p>
                <p><strong>Empresa:</strong> rh@techqueretaro.mx</p>
                <p><strong>Candidato:</strong> maria.garcia@email.com</p>
                <p><strong>Contraseña:</strong> password (para todos)</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
