# Alianza Inclusiva Tech - EmpleaMe

Plataforma SaaS de vinculación laboral enfocada en la inclusión de Personas con Discapacidad (PcD) en Querétaro, México.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat&logo=tailwind-css&logoColor=white)

## 🎯 Características Principales

### Para Candidatos (PcD)
- ✅ Registro con perfil de accesibilidad
- ✅ Carga de certificado de discapacidad
- ✅ Match inteligente con vacantes compatibles
- ✅ Score de compatibilidad por necesidades

### Para Empresas
- ✅ Registro con datos fiscales (RFC validado)
- ✅ Publicación de vacantes con Sello de Accesibilidad
- ✅ Checklist de accesibilidad obligatorio
- ✅ Calculadora de deducción ISR (Art. 186)
- ✅ Generación de reportes SAT

### Para Administradores (CDHQ)
- ✅ Validación de certificados de discapacidad
- ✅ Aprobación de vacantes
- ✅ Buzón de quejas anónimas
- ✅ Reportes y estadísticas con gráficos

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado
- Extensiones PHP: pdo, pdo_mysql, json, mbstring

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/danjohn007/empleame.git
cd empleame
```

### 2. Configurar la base de datos

Edita el archivo `app/config/config.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'empleame_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_password');
```

### 3. Crear la base de datos

Importa el esquema SQL:

```bash
mysql -u tu_usuario -p < database/schema.sql
```

O desde phpMyAdmin, importa el archivo `database/schema.sql`.

### 4. Configurar permisos

```bash
chmod -R 755 public/uploads
chmod -R 755 logs
```

### 5. Configurar Apache

Asegúrate de que el directorio apunte a la raíz del proyecto. El archivo `.htaccess` incluido maneja las URLs amigables.

Para un VirtualHost:

```apache
<VirtualHost *:80>
    ServerName empleame.local
    DocumentRoot /ruta/a/empleame
    
    <Directory /ruta/a/empleame>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 6. Verificar instalación

Accede a `http://tu-dominio/test.php` para verificar que todo esté configurado correctamente.

**⚠️ Elimina `test.php` después de verificar la instalación.**

## 👤 Credenciales de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | admin@alianzainclusiva.mx | password |
| Empresa | rh@techqueretaro.mx | password |
| Candidato | maria.garcia@email.com | password |

## 📁 Estructura del Proyecto

```
empleame/
├── app/
│   ├── config/          # Configuración, router, helpers
│   ├── controllers/     # Controladores MVC
│   ├── models/          # Modelos de datos
│   └── views/           # Vistas PHP con Tailwind CSS
├── database/
│   └── schema.sql       # Esquema de base de datos
├── logs/                # Logs de errores
├── public/
│   ├── css/             # Estilos personalizados
│   ├── js/              # JavaScript
│   ├── images/          # Imágenes estáticas
│   └── uploads/         # Archivos subidos
├── .htaccess            # Configuración Apache
├── index.php            # Punto de entrada
├── test.php             # Test de configuración
└── README.md
```

## 🔧 Módulos Implementados

### Módulo 1: Onboarding y Perfiles
- [x] Registro de empresas con validación RFC
- [x] Registro de candidatos con datos de accesibilidad
- [x] Carga de certificados de discapacidad

### Módulo 2: Gestión de Vacantes
- [x] Publicación con checklist de accesibilidad
- [x] Score de accesibilidad (0-100%)
- [x] Match inteligente candidato-vacante

### Módulo 3: Motor de Cálculo Fiscal
- [x] Calculadora de deducción ISR
- [x] Gráficas con Chart.js
- [x] Generación de reporte SAT

### Módulo 4: Panel de Auditoría
- [x] Validación de certificados
- [x] Buzón de quejas anónimas
- [x] Reportes y estadísticas

### Módulo de Configuraciones
- [x] Nombre y logotipo del sitio
- [x] Correo y teléfonos de contacto
- [x] Colores del sistema
- [x] Integración PayPal
- [x] API para QR
- [x] Modo mantenimiento

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP puro (sin framework)
- **Base de Datos:** MySQL 5.7
- **Frontend:** HTML5, CSS3, JavaScript
- **Estilos:** Tailwind CSS (CDN)
- **Gráficas:** Chart.js
- **Iconos:** Font Awesome

## 📊 Cálculo ISR (Art. 186 Ley ISR)

El sistema calcula automáticamente la deducción fiscal basada en:

- Si discapacidad ≥ 30%: Deducción = 100% del ISR retenido
- Si discapacidad < 30%: Deducción = 25% del ISR retenido

## 📱 Accesibilidad

- Skip links para navegación por teclado
- Estructura semántica HTML5
- Contrastes de color WCAG 2.1
- Textos alternativos en imágenes
- Formularios con labels asociados

## 📄 Licencia

MIT License - Ver archivo LICENSE

## 👥 Contribuciones

Las contribuciones son bienvenidas. Por favor, abre un issue primero para discutir los cambios que te gustaría hacer.

---

Desarrollado con ❤️ para la comunidad de Querétaro, México
