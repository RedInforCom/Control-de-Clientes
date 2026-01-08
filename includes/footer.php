<?php
$year = (int)date('Y');
?>
<footer class="mt-auto border-t border-gray-200 bg-white">
    <div class="px-6 py-[0.5rem] flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div class="text-xs text-gray-600">
            © <?php echo $year; ?> <span class="font-semibold text-gray-800">InforCom Soluciones Web</span>. Sistema de Gestión Profesional
        </div>

        <div class="text-xs text-gray-600 inline-flex items-center gap-2">
            <span class="w-2 h-2 rounded-full accent-dot"></span>
            <span class="font-medium text-gray-800">Sistema en línea</span>
            <span class="text-gray-400">|</span>
            <span>Usuario actual</span>
            <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($usuario_layout); ?></span>
        </div>
    </div>
</footer>