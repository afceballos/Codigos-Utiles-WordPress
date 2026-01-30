<?php
add_filter('rank_math/frontend/title', function ($title) {

    // Solo frontend
    if ( is_admin() ) {
        return $title;
    }

    // Detectar idioma por URL (/es/)
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $is_es = preg_match('~^/es(/|$)~', $uri);

    if ( ! $is_es ) {
        return $title;
    }

    // PRODUCTOS
    if ( is_singular('product') ) {
        if ( stripos($title, '| ES') === false ) {
            $title .= ' | ES';
        }
        return $title;
    }

    // CATEGORÍAS DE PRODUCTO (product_cat)
    if ( is_tax('product_cat') ) {
        if ( stripos($title, '| ES') === false ) {
            $title .= ' | ES';
        }
        return $title;
    }

    // ARCHIVO DE TIENDA (shop)
    if ( function_exists('is_shop') && is_shop() ) {
        if ( stripos($title, '| ES') === false ) {
            $title .= ' | ES';
        }
        return $title;
    }

    return $title;
}, 99);
