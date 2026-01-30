<?php
/**
 * Fix mixed content: convierte SOLO URLs de IMÁGENES internas de http a https.
 * (No toca recursos externos, ni enlaces que no sean imágenes)
 */
add_action('template_redirect', function () {

    if ( is_admin() ) return;

    ob_start(function ($html) {

        $host = preg_quote($_SERVER['HTTP_HOST'] ?? '', '~');
        if (!$host) return $html;

        // Solo extensiones de imagen comunes
        $pattern = '~http://'.$host.'(/[^"\'>\s]+?\.(?:png|jpe?g|gif|webp|svg))~i';

        return preg_replace($pattern, 'https://'.($_SERVER['HTTP_HOST']).'$1', $html);
    });
}, 0);
?>
