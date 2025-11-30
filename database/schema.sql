-- =============================================================================
-- ALIANZA INCLUSIVA TECH - Base de Datos
-- Plataforma de vinculación laboral para Personas con Discapacidad (PcD)
-- Querétaro, México
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- Base de datos
-- -----------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS empleame_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE empleame_db;

-- -----------------------------------------------------------------------------
-- Tabla: usuarios (autenticación central)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('empresa', 'candidato', 'admin') NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    email_verificado TINYINT(1) DEFAULT 0,
    token_recuperacion VARCHAR(255) NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: empresas
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS empresas;
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    razon_social VARCHAR(255) NOT NULL,
    rfc VARCHAR(13) NOT NULL,
    nombre_comercial VARCHAR(255) NULL,
    direccion_fiscal TEXT NOT NULL,
    colonia VARCHAR(150) NULL,
    municipio VARCHAR(100) NOT NULL,
    estado VARCHAR(100) DEFAULT 'Querétaro',
    codigo_postal VARCHAR(10) NOT NULL,
    sector_industrial VARCHAR(100) NOT NULL,
    tamano_empresa ENUM('micro', 'pequena', 'mediana', 'grande') DEFAULT 'pequena',
    telefono VARCHAR(20) NULL,
    sitio_web VARCHAR(255) NULL,
    logo VARCHAR(255) NULL,
    descripcion TEXT NULL,
    verificada TINYINT(1) DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_rfc (rfc),
    INDEX idx_municipio (municipio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: candidatos
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS candidatos;
CREATE TABLE candidatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NULL,
    fecha_nacimiento DATE NOT NULL,
    curp VARCHAR(18) NULL,
    rfc VARCHAR(13) NULL,
    telefono VARCHAR(20) NULL,
    direccion TEXT NULL,
    colonia VARCHAR(150) NULL,
    municipio VARCHAR(100) DEFAULT 'Querétaro',
    estado VARCHAR(100) DEFAULT 'Querétaro',
    codigo_postal VARCHAR(10) NULL,
    tipo_discapacidad ENUM('motriz', 'visual', 'auditiva', 'neurodivergente', 'multiple', 'otra') NOT NULL,
    descripcion_discapacidad TEXT NULL,
    porcentaje_discapacidad INT DEFAULT 0,
    certificado_discapacidad VARCHAR(255) NULL,
    certificado_verificado TINYINT(1) DEFAULT 0,
    fecha_verificacion DATETIME NULL,
    dispositivos_apoyo TEXT NULL COMMENT 'JSON array de dispositivos requeridos',
    necesidades_accesibilidad TEXT NULL COMMENT 'JSON array de necesidades',
    nivel_estudios ENUM('primaria', 'secundaria', 'preparatoria', 'tecnico', 'licenciatura', 'posgrado') NULL,
    experiencia_anos INT DEFAULT 0,
    habilidades TEXT NULL,
    curriculum_pdf VARCHAR(255) NULL,
    disponibilidad ENUM('inmediata', '15_dias', '1_mes', 'otro') DEFAULT 'inmediata',
    expectativa_salarial DECIMAL(10,2) NULL,
    foto VARCHAR(255) NULL,
    activo_busqueda TINYINT(1) DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_tipo_discapacidad (tipo_discapacidad),
    INDEX idx_municipio (municipio),
    INDEX idx_activo_busqueda (activo_busqueda)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: vacantes
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS vacantes;
CREATE TABLE vacantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    requisitos TEXT NULL,
    tipo_contrato ENUM('indefinido', 'temporal', 'practicas', 'freelance') DEFAULT 'indefinido',
    jornada ENUM('tiempo_completo', 'medio_tiempo', 'por_horas', 'flexible') DEFAULT 'tiempo_completo',
    modalidad ENUM('presencial', 'remoto', 'hibrido') DEFAULT 'presencial',
    salario_minimo DECIMAL(10,2) NULL,
    salario_maximo DECIMAL(10,2) NULL,
    mostrar_salario TINYINT(1) DEFAULT 1,
    ubicacion VARCHAR(255) NULL,
    municipio VARCHAR(100) DEFAULT 'Querétaro',
    discapacidades_aceptadas TEXT NULL COMMENT 'JSON array de tipos de discapacidad',
    checklist_accesibilidad TEXT NULL COMMENT 'JSON del checklist',
    score_accesibilidad INT DEFAULT 0,
    estado ENUM('borrador', 'pendiente', 'publicada', 'pausada', 'cerrada') DEFAULT 'borrador',
    fecha_publicacion DATETIME NULL,
    fecha_cierre DATE NULL,
    plazas_disponibles INT DEFAULT 1,
    visitas INT DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    INDEX idx_estado (estado),
    INDEX idx_municipio (municipio),
    INDEX idx_score (score_accesibilidad),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: postulaciones
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS postulaciones;
CREATE TABLE postulaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vacante_id INT NOT NULL,
    candidato_id INT NOT NULL,
    carta_presentacion TEXT NULL,
    estado ENUM('pendiente', 'revisada', 'entrevista', 'seleccionado', 'contratado', 'rechazado') DEFAULT 'pendiente',
    score_compatibilidad INT DEFAULT 0,
    notas_empresa TEXT NULL,
    fecha_postulacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vacante_id) REFERENCES vacantes(id) ON DELETE CASCADE,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_postulacion (vacante_id, candidato_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: contrataciones (para cálculo fiscal)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS contrataciones;
CREATE TABLE contrataciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    candidato_id INT NOT NULL,
    vacante_id INT NULL,
    fecha_contratacion DATE NOT NULL,
    fecha_baja DATE NULL,
    salario_bruto DECIMAL(10,2) NOT NULL,
    tipo_contrato ENUM('indefinido', 'temporal', 'practicas') DEFAULT 'indefinido',
    porcentaje_discapacidad INT DEFAULT 30,
    deduccion_isr_mensual DECIMAL(10,2) DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    notas TEXT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE,
    FOREIGN KEY (vacante_id) REFERENCES vacantes(id) ON DELETE SET NULL,
    INDEX idx_empresa (empresa_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: quejas_anonimas
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS quejas_anonimas;
CREATE TABLE quejas_anonimas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NULL,
    tipo_queja ENUM('discriminacion', 'falta_accesibilidad', 'acoso', 'incumplimiento', 'otro') NOT NULL,
    descripcion TEXT NOT NULL,
    evidencia VARCHAR(255) NULL,
    estado ENUM('pendiente', 'en_revision', 'resuelta', 'archivada') DEFAULT 'pendiente',
    notas_admin TEXT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE SET NULL,
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: configuraciones
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS configuraciones;
CREATE TABLE configuraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NULL,
    tipo ENUM('texto', 'numero', 'booleano', 'json', 'color') DEFAULT 'texto',
    descripcion VARCHAR(255) NULL,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabla: logs_actividad
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS logs_actividad;
CREATE TABLE logs_actividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    accion VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    ip VARCHAR(45) NULL,
    user_agent TEXT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_accion (accion),
    INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- DATOS DE EJEMPLO - Querétaro, México
-- =============================================================================

-- Configuraciones iniciales
INSERT INTO configuraciones (clave, valor, tipo, descripcion) VALUES
('sitio_nombre', 'Alianza Inclusiva Tech', 'texto', 'Nombre del sitio'),
('sitio_logo', NULL, 'texto', 'Ruta al logotipo'),
('correo_sistema', 'sistema@alianzainclusiva.mx', 'texto', 'Correo principal del sistema'),
('telefono_contacto', '+52 442 123 4567', 'texto', 'Teléfono de contacto'),
('horario_atencion', 'Lunes a Viernes 9:00 - 18:00', 'texto', 'Horario de atención'),
('color_primario', '#2563eb', 'color', 'Color primario del sistema'),
('color_secundario', '#1e40af', 'color', 'Color secundario del sistema'),
('paypal_client_id', NULL, 'texto', 'ID de cliente de PayPal'),
('paypal_secret', NULL, 'texto', 'Secret de PayPal'),
('api_qr_url', NULL, 'texto', 'URL de API para generar QR'),
('mantenimiento', '0', 'booleano', 'Modo mantenimiento');

-- =====================================================
-- DEMO DATA - FOR DEVELOPMENT/TESTING ONLY
-- IMPORTANT: Change these passwords in production!
-- Default password for all demo users: 'password'
-- =====================================================

-- Usuario admin
INSERT INTO usuarios (email, password, tipo, activo, email_verificado) VALUES
('admin@alianzainclusiva.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 1);

-- Empresas de ejemplo en Querétaro
INSERT INTO usuarios (email, password, tipo, activo, email_verificado) VALUES
('rh@techqueretaro.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 1, 1),
('contacto@industriasqro.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 1, 1),
('rh@serviciosintegralesqro.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empresa', 1, 1);

INSERT INTO empresas (usuario_id, razon_social, rfc, nombre_comercial, direccion_fiscal, colonia, municipio, codigo_postal, sector_industrial, tamano_empresa, telefono, descripcion, verificada) VALUES
(2, 'Tech Querétaro S.A. de C.V.', 'TQU180515ABC', 'Tech Querétaro', 'Av. Tecnológico 2000', 'Centro Sur', 'Querétaro', '76090', 'Tecnología', 'mediana', '442 123 4567', 'Empresa líder en desarrollo de software y servicios tecnológicos en Querétaro.', 1),
(3, 'Industrias Querétaro S.A. de C.V.', 'IQR150320DEF', 'Industrias QRO', 'Blvd. Bernardo Quintana 500', 'Lomas de Casa Blanca', 'Querétaro', '76080', 'Manufactura', 'grande', '442 234 5678', 'Empresa manufacturera con más de 30 años de experiencia.', 1),
(4, 'Servicios Integrales de Querétaro S.A. de C.V.', 'SIQ200101GHI', 'Servicios QRO', 'Calle 5 de Febrero 120', 'Centro Histórico', 'Querétaro', '76000', 'Servicios', 'pequena', '442 345 6789', 'Empresa de servicios empresariales diversos.', 0);

-- Candidatos de ejemplo
INSERT INTO usuarios (email, password, tipo, activo, email_verificado) VALUES
('maria.garcia@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidato', 1, 1),
('juan.lopez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidato', 1, 1),
('ana.martinez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidato', 1, 1),
('carlos.hernandez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidato', 1, 1);

INSERT INTO candidatos (usuario_id, nombre, apellido_paterno, apellido_materno, fecha_nacimiento, telefono, municipio, tipo_discapacidad, descripcion_discapacidad, porcentaje_discapacidad, dispositivos_apoyo, necesidades_accesibilidad, nivel_estudios, experiencia_anos, habilidades, certificado_verificado) VALUES
(5, 'María', 'García', 'Rodríguez', '1990-05-15', '442 111 2222', 'Querétaro', 'motriz', 'Uso de silla de ruedas por lesión medular', 60, '["silla_de_ruedas", "rampa_portatil"]', '["rampas", "banos_adaptados", "estacionamiento_accesible", "ascensor"]', 'licenciatura', 5, 'Diseño gráfico, Ilustración digital, UX/UI', 1),
(6, 'Juan', 'López', 'Sánchez', '1988-08-22', '442 333 4444', 'El Marqués', 'visual', 'Ceguera parcial (baja visión)', 45, '["lector_pantalla", "lupa_electronica"]', '["software_accesible", "senalizacion_braille", "iluminacion_adecuada"]', 'licenciatura', 7, 'Programación, Testing, QA', 1),
(7, 'Ana', 'Martínez', 'Flores', '1995-03-10', '442 555 6666', 'Corregidora', 'auditiva', 'Hipoacusia severa bilateral', 50, '["auxiliar_auditivo", "interprete_lsm"]', '["alarmas_visuales", "comunicacion_escrita", "capacitacion_lsm"]', 'preparatoria', 3, 'Administración, Atención al cliente, Ventas', 0),
(8, 'Carlos', 'Hernández', 'Jiménez', '1992-11-30', '442 777 8888', 'Querétaro', 'neurodivergente', 'Trastorno del Espectro Autista', 35, '["auriculares_cancelacion_ruido"]', '["espacio_tranquilo", "instrucciones_claras", "horario_flexible"]', 'tecnico', 4, 'Análisis de datos, Excel avanzado, Bases de datos', 1);

-- Vacantes de ejemplo
INSERT INTO vacantes (empresa_id, titulo, slug, descripcion, requisitos, tipo_contrato, jornada, modalidad, salario_minimo, salario_maximo, ubicacion, municipio, discapacidades_aceptadas, checklist_accesibilidad, score_accesibilidad, estado, fecha_publicacion, plazas_disponibles) VALUES
(1, 'Desarrollador Frontend Junior', 'desarrollador-frontend-junior', 'Buscamos desarrollador frontend para unirse a nuestro equipo de innovación. Trabajarás en proyectos de alto impacto utilizando tecnologías modernas.', 'HTML, CSS, JavaScript básico. Conocimientos de React son un plus. Disposición para aprender.', 'indefinido', 'tiempo_completo', 'hibrido', 15000.00, 22000.00, 'Av. Tecnológico 2000, Centro Sur', 'Querétaro', '["motriz", "visual", "auditiva", "neurodivergente"]', '{"rampas": true, "banos_adaptados": true, "ascensor": true, "estacionamiento_accesible": true, "puertas_automaticas": false, "senalizacion_braille": true, "alarmas_visuales": true, "software_accesible": true, "mobiliario_adaptable": true}', 90, 'publicada', NOW(), 2),
(1, 'Diseñador UX/UI', 'disenador-ux-ui', 'Únete a nuestro equipo de diseño. Crearás experiencias digitales inclusivas y accesibles.', 'Experiencia en Figma, Adobe XD. Conocimiento de principios de accesibilidad web (WCAG).', 'indefinido', 'tiempo_completo', 'remoto', 20000.00, 30000.00, 'Remoto', 'Querétaro', '["motriz", "visual", "neurodivergente"]', '{"rampas": true, "banos_adaptados": true, "ascensor": true, "estacionamiento_accesible": true, "puertas_automaticas": true, "senalizacion_braille": false, "alarmas_visuales": true, "software_accesible": true, "mobiliario_adaptable": true}', 85, 'publicada', NOW(), 1),
(2, 'Analista de Calidad', 'analista-de-calidad', 'Buscamos analista de calidad para nuestro departamento de producción.', 'Conocimientos en control de calidad, ISO 9001. Atención al detalle.', 'indefinido', 'tiempo_completo', 'presencial', 12000.00, 18000.00, 'Blvd. Bernardo Quintana 500', 'Querétaro', '["motriz", "auditiva"]', '{"rampas": true, "banos_adaptados": true, "ascensor": false, "estacionamiento_accesible": true, "puertas_automaticas": false, "senalizacion_braille": false, "alarmas_visuales": true, "software_accesible": false, "mobiliario_adaptable": true}', 55, 'publicada', NOW(), 1),
(3, 'Asistente Administrativo', 'asistente-administrativo', 'Apoyo en tareas administrativas generales de la empresa.', 'Manejo de Office, buena organización, atención al cliente.', 'temporal', 'medio_tiempo', 'presencial', 8000.00, 12000.00, 'Calle 5 de Febrero 120', 'Querétaro', '["motriz", "visual", "auditiva", "neurodivergente"]', '{"rampas": false, "banos_adaptados": false, "ascensor": false, "estacionamiento_accesible": false, "puertas_automaticas": false, "senalizacion_braille": false, "alarmas_visuales": false, "software_accesible": false, "mobiliario_adaptable": false}', 0, 'borrador', NULL, 1);

-- Postulaciones de ejemplo
INSERT INTO postulaciones (vacante_id, candidato_id, carta_presentacion, estado, score_compatibilidad) VALUES
(1, 2, 'Me interesa mucho esta posición ya que cuento con experiencia en programación y testing de software accesible.', 'revisada', 85),
(1, 4, 'Tengo conocimientos en análisis de datos que pueden aportar valor al equipo de desarrollo.', 'pendiente', 75),
(2, 1, 'Como diseñadora gráfica con experiencia en UX, estoy muy interesada en crear experiencias accesibles.', 'entrevista', 95),
(3, 3, 'Tengo experiencia en control de calidad y atención al detalle.', 'pendiente', 60);

-- Contrataciones de ejemplo
INSERT INTO contrataciones (empresa_id, candidato_id, vacante_id, fecha_contratacion, salario_bruto, tipo_contrato, porcentaje_discapacidad, deduccion_isr_mensual, activo) VALUES
(1, 1, 2, '2024-01-15', 25000.00, 'indefinido', 60, 2891.67, 1);

-- Quejas de ejemplo
INSERT INTO quejas_anonimas (empresa_id, tipo_queja, descripcion, estado) VALUES
(3, 'falta_accesibilidad', 'La empresa no cuenta con las rampas que prometieron en la vacante. Las instalaciones no son accesibles para personas en silla de ruedas.', 'en_revision');

SET FOREIGN_KEY_CHECKS = 1;
