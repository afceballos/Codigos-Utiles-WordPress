<?php
add_filter('rank_math/frontend/title', function ($title) {

    // Solo frontend
    if ( is_admin() ) {
        return $title;
    }

    // Solo productos WooCommerce
    if ( ! is_singular('product') ) {
        return $title;
    }

    // Detectar idioma por URL
    $uri = $_SERVER['REQUEST_URI'] ?? '';

    // Español
    if ( preg_match('~^/es(/|$)~', $uri) ) {
        if ( stripos($title, '| ES') === false ) {
            $title .= ' | ES';
        }
        return $title;
    }

    // Inglés
    if ( preg_match('~^/en(/|$)~', $uri) ) {
        if ( stripos($title, '| EN') === false ) {
            $title .= ' | EN';
        }
        return $title;
    }

    return $title;
}, 99);
