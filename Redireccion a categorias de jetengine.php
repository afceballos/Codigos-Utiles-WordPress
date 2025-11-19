<?php
// Redirect JetSmartFilters taxonomy param to term archive (para provincias y categoria-de-empresa)
add_action('template_redirect', 'redirect_jsf_tax_to_archive');
function redirect_jsf_tax_to_archive() {
    // Solo en páginas no-admin y si hay params de filtro
    if (is_admin() || !isset($_GET['jsf']) || $_GET['jsf'] !== 'jet-engine') {
        return;
    }

    // Detecta tax=provincias:ID o tax=categoria-de-empresa:ID
    if (isset($_GET['tax'])) {
        $tax_value = $_GET['tax'];
        
        // Para provincias
        if (preg_match('/^provincias:(\d+)$/', $tax_value, $matches)) {
            $term_id = intval($matches[1]);
            $taxonomy = 'provincias';
        }
        // Para categoria-de-empresa
        elseif (preg_match('/^categoria-de-empresa:(\d+)$/', $tax_value, $matches)) {
            $term_id = intval($matches[1]);
            $taxonomy = 'categoria-de-empresa';
        } else {
            return; // No es ninguna de las taxonomías esperadas
        }

        $term = get_term($term_id, $taxonomy);

        if ($term && !is_wp_error($term)) {
            $archive_url = get_term_link($term);
            if (!is_wp_error($archive_url)) {
                // Redirect permanente al archive del término (usa slug, no ID)
                wp_redirect($archive_url, 301);
                exit;
            }
        }
    }
}

?>
