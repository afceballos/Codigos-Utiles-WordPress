<?php
add_action('wp_head', function () {

    if ( ! is_front_page() ) return;

    $pages = [
        'menu-calcotada-a-sant-llorenc-savall',
        'menu-sant-esteve'
    ];

    foreach ($pages as $slug) {
        $page = get_page_by_path($slug);
        if ($page) {
            echo '<link rel="prefetch" href="' . esc_url(get_permalink($page)) . '" />' . "\n";
        }
    }
});
