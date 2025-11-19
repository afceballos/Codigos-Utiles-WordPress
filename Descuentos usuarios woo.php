<?php
/**
 * Descuento para SOCIOS RSF solo en la categoría "cursos"
 * - Cambia $cat_slug y $discount_rate según necesites.
 */
add_action( 'woocommerce_cart_calculate_fees', 'rsf_descuento_socios_categoria_cursos' );
function rsf_descuento_socios_categoria_cursos() {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    if ( ! WC()->cart ) return;
	
	// ID del producto de suscripción
	$producto_suscripcion_id = 45187;
	$campo_descuento = 'descuento_para_productos';

	// Obtener el valor del campo (ej: 15 = 15%)
	$descuento_valor = get_post_meta($producto_suscripcion_id, $campo_descuento, true);

	// Convertir a decimal (15 → 0.15) y validar
	$discount_rate = 0;
	if (is_numeric($descuento_valor) && $descuento_valor > 0) {
		$discount_rate = $descuento_valor / 100;
	}
	
    // Configuración
    $cat_slug      = 'cursos';   // <-- slug de la categoría
//     $discount_rate = 0.10;       // 10% de descuento

    // Verifica rol
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'socios_rsf', (array) $user->roles, true ) ) {
        return;
    }

    // Sumar solo el subtotal (sin impuestos) de los productos en la categoría
    $eligible_subtotal = 0;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product_id   = $cart_item['product_id'];
        $variation_id = $cart_item['variation_id'];

        // Para variaciones, comprobamos el padre (las categorías suelen estar en el padre)
        $check_id = $variation_id ? wp_get_post_parent_id( $variation_id ) : $product_id;
        if ( ! $check_id ) $check_id = $product_id;

        if ( has_term( $cat_slug, 'product_cat', $check_id ) ) {
            // line_subtotal ya incluye la cantidad
            $eligible_subtotal += (float) $cart_item['line_subtotal'];
        }
    }

    if ( $eligible_subtotal > 0 ) {
        $discount = $eligible_subtotal * $discount_rate;
        WC()->cart->add_fee(
            sprintf( 'Descuento Socios RSF (%d%% - %s)', (int) round( $discount_rate * 100 ), ucfirst( $cat_slug ) ),
            -$discount
        );
    }
}
?>
