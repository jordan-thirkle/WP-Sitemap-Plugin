<?php

function sitemap_plugin_footer_link() {
    $options = get_option(SITEMAP_PLUGIN_OPTIONS);
    if ($options['show_footer_link']) {
        $sitemap_url = home_url('sitemap.xml');
        echo '<a href="' . $sitemap_url . '">Sitemap</a>';
    }
}
add_action('wp_footer', 'sitemap_plugin_footer_link');
