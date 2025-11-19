<?php
add_filter( 'rank_math/frontend/breadcrumb/items', function( $crumbs, $class ) {
    // Solo cuando estemos en un término de la taxonomía "provincia"
    
    if ( is_tax('provincias') || is_tax('categoria-de-empresa') ) {
        
        // URL fija de la página padre (la que creaste: https://topmejor.com/provincias/)
        $provincias_url = home_url( '/provincias/' );
		    $tipoempresa_url = home_url( '/categoria-de-empresa/' );
        
        // Recorremos los crumbs buscando el que dice "Provincias"
        foreach ( $crumbs as &$crumb ) {
			
            if ( isset( $crumb[0] ) && trim( $crumb[0] ) === 'Provincias' ) {
                $crumb[1] = $provincias_url; // ¡le pone el enlace!
                break;
            }
			if ( isset( $crumb[0] ) && trim( $crumb[0] ) === 'Categorías de empresas' ) {
                $crumb[1] = $tipoempresa_url; // ¡le pone el enlace!
                break;
            }
        }
        unset( $crumb );
    }
    
    return $crumbs;
}, 10, 2 );
?>
