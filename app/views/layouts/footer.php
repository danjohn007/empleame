    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <i class="fas fa-handshake text-white text-xl"></i>
                        </div>
                        <span class="font-bold text-xl"><?= SITE_NAME ?></span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Plataforma de vinculación laboral enfocada en la inclusión de Personas con Discapacidad (PcD) en Querétaro, México.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Para Candidatos</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="<?= BASE_URL ?>/vacantes" class="hover:text-white transition">Buscar Vacantes</a></li>
                        <li><a href="<?= BASE_URL ?>/registro/candidato" class="hover:text-white transition">Registrarse</a></li>
                        <li><a href="<?= BASE_URL ?>/queja/nueva" class="hover:text-white transition">Reportar Queja</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Para Empresas</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="<?= BASE_URL ?>/registro/empresa" class="hover:text-white transition">Registrar Empresa</a></li>
                        <li><a href="<?= BASE_URL ?>/fiscal/calculadora" class="hover:text-white transition">Calculadora Fiscal</a></li>
                        <li><a href="#" class="hover:text-white transition">Beneficios ISR</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-lg mb-4">Contacto</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <?= SITE_CONTACT_EMAIL ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            <?= SITE_CONTACT_PHONE ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Querétaro, México
                        </li>
                    </ul>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition" aria-label="Facebook">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition" aria-label="Twitter">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © <?= date('Y') ?> <?= SITE_NAME ?>. Todos los derechos reservados.
                    </p>
                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <span class="text-gray-400 text-sm flex items-center">
                            <i class="fas fa-universal-access mr-1"></i>
                            Sitio con accesibilidad WCAG 2.1
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>
