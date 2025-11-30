<?php
/**
 * Controlador de Configuraciones
 * Alianza Inclusiva Tech
 */

class ConfigController extends BaseController {

    public function index() {
        requireRole('admin');

        if (!$this->db) {
            $configuraciones = [];
        } else {
            $stmt = $this->db->query("SELECT * FROM configuraciones ORDER BY clave");
            $configuraciones = [];
            while ($row = $stmt->fetch()) {
                $configuraciones[$row['clave']] = $row;
            }
        }

        $this->view('configuraciones/index', ['configuraciones' => $configuraciones]);
    }

    public function guardar() {
        requireRole('admin');
        $this->validateCSRF();

        if (!$this->db) {
            setFlash('error', 'Error de conexión a la base de datos');
            redirect('/admin/configuraciones');
        }

        $configuraciones = [
            'sitio_nombre' => $this->getPost('sitio_nombre'),
            'correo_sistema' => $this->getPost('correo_sistema'),
            'telefono_contacto' => $this->getPost('telefono_contacto'),
            'horario_atencion' => $this->getPost('horario_atencion'),
            'color_primario' => $this->getPost('color_primario'),
            'color_secundario' => $this->getPost('color_secundario'),
            'paypal_client_id' => $this->getPost('paypal_client_id'),
            'paypal_secret' => $this->getPost('paypal_secret'),
            'api_qr_url' => $this->getPost('api_qr_url'),
            'mantenimiento' => $this->getPost('mantenimiento') ? '1' : '0'
        ];

        foreach ($configuraciones as $clave => $valor) {
            $stmt = $this->db->prepare("
                INSERT INTO configuraciones (clave, valor) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE valor = VALUES(valor), fecha_actualizacion = NOW()
            ");
            $stmt->execute([$clave, $valor]);
        }

        // Procesar logo si se subió
        if (!empty($_FILES['sitio_logo']['name'])) {
            $resultado = subirArchivo($_FILES['sitio_logo'], UPLOADS_PATH . '/logos', ['jpg', 'jpeg', 'png']);
            if ($resultado['exito']) {
                $stmt = $this->db->prepare("
                    INSERT INTO configuraciones (clave, valor, tipo) VALUES ('sitio_logo', ?, 'texto')
                    ON DUPLICATE KEY UPDATE valor = VALUES(valor), fecha_actualizacion = NOW()
                ");
                $stmt->execute([$resultado['archivo']]);
            }
        }

        $this->log('actualizar_configuraciones', 'Configuraciones del sistema actualizadas');
        setFlash('success', 'Configuraciones guardadas correctamente');
        redirect('/admin/configuraciones');
    }
}
