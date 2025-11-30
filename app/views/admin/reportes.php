<?php $pageTitle = 'Reportes y Estadísticas'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Reportes y Estadísticas</h1>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Candidatos por Mes -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Candidatos Registrados por Mes</h2>
                <canvas id="chartCandidatos"></canvas>
            </div>
            
            <!-- Contrataciones por Mes -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Contrataciones por Mes</h2>
                <canvas id="chartContrataciones"></canvas>
            </div>
            
            <!-- Por Tipo de Discapacidad -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Candidatos por Tipo de Discapacidad</h2>
                <canvas id="chartTipoDiscapacidad"></canvas>
            </div>
            
            <!-- Por Municipio -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Candidatos por Municipio</h2>
                <canvas id="chartMunicipio"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Candidatos por Mes
new Chart(document.getElementById('chartCandidatos'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($candidatosPorMes, 'mes')) ?>,
        datasets: [{
            label: 'Candidatos',
            data: <?= json_encode(array_column($candidatosPorMes, 'total')) ?>,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: { responsive: true }
});

// Contrataciones por Mes
new Chart(document.getElementById('chartContrataciones'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($contratacionesPorMes, 'mes')) ?>,
        datasets: [{
            label: 'Contrataciones',
            data: <?= json_encode(array_column($contratacionesPorMes, 'total')) ?>,
            backgroundColor: '#22c55e'
        }]
    },
    options: { responsive: true }
});

// Por Tipo de Discapacidad
new Chart(document.getElementById('chartTipoDiscapacidad'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(function($t) { return ucfirst($t['tipo_discapacidad']); }, $porTipoDiscapacidad)) ?>,
        datasets: [{
            data: <?= json_encode(array_column($porTipoDiscapacidad, 'total')) ?>,
            backgroundColor: ['#2563eb', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6']
        }]
    },
    options: { responsive: true }
});

// Por Municipio
new Chart(document.getElementById('chartMunicipio'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($porMunicipio, 'municipio')) ?>,
        datasets: [{
            label: 'Candidatos',
            data: <?= json_encode(array_column($porMunicipio, 'total')) ?>,
            backgroundColor: '#8b5cf6'
        }]
    },
    options: { responsive: true, indexAxis: 'y' }
});
</script>
