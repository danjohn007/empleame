<?php $pageTitle = 'Calculadora Fiscal'; ?>
<div class="py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Calculadora de Deducción ISR</h1>
        <p class="text-gray-600 mb-8">Estima tu ahorro fiscal por contratar personas con discapacidad (Art. 186 Ley ISR)</p>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Datos de Cálculo</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Salario Bruto Mensual (MXN)</label>
                        <input type="number" id="salario_bruto" value="20000" min="0" step="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Porcentaje de Discapacidad</label>
                        <select id="porcentaje_discapacidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="30">30% o más (Deducción 100%)</option>
                            <option value="20">Menos de 30% (Deducción 25%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Empleados PcD</label>
                        <input type="number" id="num_empleados" value="1" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <button type="button" onclick="calcular()" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                        <i class="fas fa-calculator mr-2"></i>Calcular Ahorro
                    </button>
                </div>
            </div>
            
            <!-- Results -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Resultado</h2>
                <div id="resultado" class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">Salario Bruto Mensual</p>
                        <p class="text-xl font-bold text-gray-800" id="res_salario">$20,000.00</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">ISR Retenido Mensual</p>
                        <p class="text-xl font-bold text-gray-800" id="res_isr">$0.00</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-600">Deducción Aplicable Mensual</p>
                        <p class="text-2xl font-bold text-green-600" id="res_deduccion">$0.00</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-600">Ahorro Anual Estimado</p>
                        <p class="text-2xl font-bold text-blue-600" id="res_anual">$0.00</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Costo Real vs Ahorro Fiscal</h2>
            <canvas id="chartComparativo" height="100"></canvas>
        </div>
        
        <div class="mt-8 text-center">
            <a href="<?= BASE_URL ?>/fiscal/reporte" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold inline-block">
                <i class="fas fa-file-pdf mr-2"></i>Generar Reporte SAT
            </a>
        </div>
    </div>
</div>

<script>
let chart = null;

function calcular() {
    const salario = parseFloat(document.getElementById('salario_bruto').value) || 0;
    const porcentaje = parseInt(document.getElementById('porcentaje_discapacidad').value) || 30;
    const numEmpleados = parseInt(document.getElementById('num_empleados').value) || 1;
    
    fetch('<?= BASE_URL ?>/fiscal/calcular', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `salario_bruto=${salario}&porcentaje_discapacidad=${porcentaje}&num_empleados=${numEmpleados}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const calc = data.calculo_mensual;
            const proy = data.proyeccion_anual;
            
            document.getElementById('res_salario').textContent = '$' + formatNumber(calc.salario_bruto);
            document.getElementById('res_isr').textContent = '$' + formatNumber(calc.isr_retenido);
            document.getElementById('res_deduccion').textContent = '$' + formatNumber(calc.deduccion);
            document.getElementById('res_anual').textContent = '$' + formatNumber(proy.ahorro_total);
            
            actualizarGrafica(calc.salario_bruto, calc.deduccion, calc.costo_real);
        }
    });
}

function formatNumber(num) {
    return num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function actualizarGrafica(salario, deduccion, costoReal) {
    const ctx = document.getElementById('chartComparativo').getContext('2d');
    if (chart) chart.destroy();
    
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sin Beneficio', 'Con Beneficio ISR'],
            datasets: [{
                label: 'Costo Mensual (MXN)',
                data: [salario, costoReal],
                backgroundColor: ['#ef4444', '#22c55e']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

// Calcular al cargar
calcular();
</script>
