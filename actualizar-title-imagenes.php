// ALT y TITLE automático en todas las imágenes (versión corregida 2025)
add_filter( 'wp_get_attachment_image_attributes', function( $attr, $attachment, $size ) {

    // Si el alt está vacío o solo tiene el nombre del archivo → lo sustituimos
    if ( empty( trim( $attr['alt'] ) ) || 
         ( ! empty( $attr['alt'] ) && strpos( $attr['alt'], basename( $attachment->guid ) ) !== false ) ) {
        
        // Ponemos el título de la entrada/página actual
        $attr['alt'] = wp_strip_all_tags( get_the_title( get_the_ID() ) );
    }

    // TITLE: si no tiene, lo igualamos al alt
    if ( empty( $attr['title'] ) ) {
        $attr['title'] = $attr['alt'];
    }

    // En homepage, archivo o página de provincia (taxonomía), usamos nombre del sitio o del término
    if ( is_home() || is_front_page() || is_archive() || is_tax('provincia') ) {
        if ( empty( $attr['alt'] ) ) {
            if ( is_tax('provincia') ) {
                $term = get_queried_object();
                $attr['alt'] = 'Empresas y servicios en ' . $term->name;
            } else {
                $attr['alt'] = get_bloginfo('name') . ' – ' . get_bloginfo('description');
            }
            $attr['title'] = $attr['alt'];
        }
    }

    return $attr;
}, 10, 3 );
