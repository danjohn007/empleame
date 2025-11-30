<?php
/**
 * Controlador Fiscal
 * Motor de Cálculo Fiscal - Alianza Inclusiva Tech
 */

class FiscalController extends BaseController {

    public function calculadora() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();

        $this->view('fiscal/calculadora', ['empresa' => $empresa]);
    }

    public function calcular() {
        requireRole('empresa');

        $salarioBruto = floatval($this->getPost('salario_bruto'));
        $porcentajeDiscapacidad = intval($this->getPost('porcentaje_discapacidad')) ?: 30;
        $numEmpleados = intval($this->getPost('num_empleados')) ?: 1;

        $calculo = calcularDeduccionISR($salarioBruto, $porcentajeDiscapacidad);

        // Proyección anual
        $proyeccionAnual = [
            'salario_anual' => $salarioBruto * 12 * $numEmpleados,
            'isr_anual' => $calculo['isr_retenido'] * 12 * $numEmpleados,
            'deduccion_anual' => $calculo['deduccion'] * 12 * $numEmpleados,
            'ahorro_total' => $calculo['deduccion'] * 12 * $numEmpleados
        ];

        $this->json([
            'success' => true,
            'calculo_mensual' => $calculo,
            'proyeccion_anual' => $proyeccionAnual,
            'num_empleados' => $numEmpleados
        ]);
    }

    public function reporte() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();

        // Obtener contrataciones activas
        $stmt = $this->db->prepare("
            SELECT c.*, ca.nombre, ca.apellido_paterno, ca.apellido_materno, ca.rfc as candidato_rfc,
                ca.tipo_discapacidad, ca.porcentaje_discapacidad
            FROM contrataciones c
            JOIN candidatos ca ON c.candidato_id = ca.id
            WHERE c.empresa_id = ? AND c.activo = 1
            ORDER BY c.fecha_contratacion
        ");
        $stmt->execute([$empresa['id']]);
        $contrataciones = $stmt->fetchAll();

        // Calcular totales
        $totales = [
            'total_salarios' => 0,
            'total_isr' => 0,
            'total_deduccion' => 0
        ];

        foreach ($contrataciones as $contratacion) {
            $totales['total_salarios'] += $contratacion['salario_bruto'];
            $calculo = calcularDeduccionISR($contratacion['salario_bruto'], $contratacion['porcentaje_discapacidad']);
            $totales['total_isr'] += $calculo['isr_retenido'];
            $totales['total_deduccion'] += $calculo['deduccion'];
        }

        $this->view('fiscal/reporte', [
            'empresa' => $empresa,
            'contrataciones' => $contrataciones,
            'totales' => $totales
        ]);
    }

    public function generarPDF() {
        requireRole('empresa');

        $empresa = $this->getEmpresaActual();

        // Obtener contrataciones
        $stmt = $this->db->prepare("
            SELECT c.*, ca.nombre, ca.apellido_paterno, ca.apellido_materno, ca.rfc as candidato_rfc,
                ca.tipo_discapacidad, ca.porcentaje_discapacidad
            FROM contrataciones c
            JOIN candidatos ca ON c.candidato_id = ca.id
            WHERE c.empresa_id = ? AND c.activo = 1
            ORDER BY c.fecha_contratacion
        ");
        $stmt->execute([$empresa['id']]);
        $contrataciones = $stmt->fetchAll();

        // Generar HTML para el reporte
        $trimestre = ceil(date('n') / 3);
        $ano = date('Y');

        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Reporte Fiscal SAT - <?= $empresa['razon_social'] ?></title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .header h1 { margin: 0; font-size: 18px; }
                .header h2 { margin: 5px 0; font-size: 14px; color: #666; }
                .info { margin-bottom: 20px; }
                .info p { margin: 5px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f5f5f5; }
                .totales { margin-top: 30px; }
                .totales td { font-weight: bold; }
                .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>REPORTE DE DEDUCCIONES FISCALES - LEY ISR ART. 186</h1>
                <h2>Empleados con Discapacidad</h2>
                <p>Trimestre <?= $trimestre ?> - <?= $ano ?></p>
            </div>

            <div class="info">
                <p><strong>Empresa:</strong> <?= htmlspecialchars($empresa['razon_social']) ?></p>
                <p><strong>RFC:</strong> <?= htmlspecialchars($empresa['rfc']) ?></p>
                <p><strong>Dirección Fiscal:</strong> <?= htmlspecialchars($empresa['direccion_fiscal']) ?></p>
                <p><strong>Fecha de Generación:</strong> <?= date('d/m/Y H:i') ?></p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nombre del Empleado</th>
                        <th>RFC</th>
                        <th>% Discapacidad</th>
                        <th>Fecha Contratación</th>
                        <th>Salario Mensual</th>
                        <th>ISR Retenido</th>
                        <th>Deducción Aplicable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalSalarios = 0;
                    $totalISR = 0;
                    $totalDeduccion = 0;
                    $num = 1;
                    foreach ($contrataciones as $c): 
                        $calculo = calcularDeduccionISR($c['salario_bruto'], $c['porcentaje_discapacidad']);
                        $totalSalarios += $c['salario_bruto'];
                        $totalISR += $calculo['isr_retenido'];
                        $totalDeduccion += $calculo['deduccion'];
                    ?>
                    <tr>
                        <td><?= $num++ ?></td>
                        <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido_paterno'] . ' ' . $c['apellido_materno']) ?></td>
                        <td><?= htmlspecialchars($c['candidato_rfc'] ?? 'Pendiente') ?></td>
                        <td><?= $c['porcentaje_discapacidad'] ?>%</td>
                        <td><?= date('d/m/Y', strtotime($c['fecha_contratacion'])) ?></td>
                        <td>$<?= number_format($c['salario_bruto'], 2) ?></td>
                        <td>$<?= number_format($calculo['isr_retenido'], 2) ?></td>
                        <td>$<?= number_format($calculo['deduccion'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="totales">
                    <tr>
                        <td colspan="5" style="text-align: right;">TOTALES MENSUALES:</td>
                        <td>$<?= number_format($totalSalarios, 2) ?></td>
                        <td>$<?= number_format($totalISR, 2) ?></td>
                        <td>$<?= number_format($totalDeduccion, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">PROYECCIÓN TRIMESTRAL:</td>
                        <td>$<?= number_format($totalSalarios * 3, 2) ?></td>
                        <td>$<?= number_format($totalISR * 3, 2) ?></td>
                        <td>$<?= number_format($totalDeduccion * 3, 2) ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="footer">
                <p>Este documento es un estimado para fines informativos. Consulte con su contador para la declaración oficial.</p>
                <p>Generado por Alianza Inclusiva Tech - Plataforma de Vinculación Laboral para PcD</p>
            </div>
        </body>
        </html>
        <?php
        $html = ob_get_clean();

        // Por simplicidad, servimos como HTML que puede imprimirse como PDF
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: inline; filename="reporte_fiscal_' . date('Y-m') . '.html"');
        echo $html;
        exit;
    }

    private function getEmpresaActual() {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
}
