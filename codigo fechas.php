<?php
// Añadir este código en el functions.php de tu tema o en un plugin personalizado
function shortcode_fecha_mes_anio() {
    // Usa la zona horaria configurada en WordPress
    $timestamp = current_time('timestamp');

    // Obtener el año y mes actual
    $anio = date('Y', $timestamp);
    $mes  = date('m', $timestamp);

    // Primer día del mes (siempre 01)
    $primer_dia = "$anio.$mes.01";

    // Último día del mes (usamos 't' para obtener el número de días del mes)
    $ultimo_dia_num = date('t', $timestamp); // Ej: 30, 31, 28...
    $ultimo_dia = sprintf('%s.%s.%02d', $anio, $mes, $ultimo_dia_num);

    // Devolver en el formato deseado: 2025.11.01-2025.11.30
    return "$primer_dia-$ultimo_dia";
}
add_shortcode('fecha_timestamp', 'shortcode_fecha_mes_anio');
?>
