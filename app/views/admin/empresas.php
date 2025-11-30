<?php $pageTitle = 'Gestión de Empresas'; ?>
<div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Gestión de Empresas</h1>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">RFC</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Vacantes</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Contrataciones</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Verificada</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($empresas as $e): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($e['nombre_comercial'] ?? $e['razon_social']) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($e['email']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm"><?= htmlspecialchars($e['rfc']) ?></td>
                        <td class="px-6 py-4 text-center"><?= $e['total_vacantes'] ?></td>
                        <td class="px-6 py-4 text-center"><?= $e['total_contrataciones'] ?></td>
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="<?= BASE_URL ?>/admin/empresa/<?= $e['id'] ?>/verificar" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                <input type="hidden" name="verificada" value="<?= $e['verificada'] ? '0' : '1' ?>">
                                <button type="submit" class="<?= $e['verificada'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' ?> px-3 py-1 rounded text-xs font-medium hover:opacity-80">
                                    <?= $e['verificada'] ? 'Verificada' : 'Sin verificar' ?>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-gray-400">-</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
