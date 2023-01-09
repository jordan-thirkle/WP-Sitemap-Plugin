<?php

function sitemap_plugin_init() {
    // Register a new rewrite rule for the sitemap.xml file
    add_rewrite_rule('sitemap\.xml$', 'index.php?sitemap=1', 'top');

    // Add a custom query variable for the sitemap
    add_filter('query_vars', 'sitemap_plugin_query_vars');

    // Generate the sitemap when the custom query variable is set
    add_action('template_redirect', 'sitemap_plugin_template_redirect');

    // Add a settings link to the plugin list
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sitemap_plugin_settings_link');

    // Register the settings page
    add_action('admin_menu', 'sitemap_plugin_settings_page');
}
add_action('init', 'sitemap_plugin_init');

function sitemap_plugin_query_vars($vars) {
    $vars[] = 'sitemap';
    return $vars;
}

function sitemap_plugin_template_redirect() {
    global $wp_query;
    if (isset($wp_query->query_vars['sitemap'])) {
        header('Content-Type: text/xml; charset=utf-8');
        echo generate_sitemap();
        exit;
    }
}

function sitemap_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=sitemap-plugin">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

function sitemap_plugin_settings_page() {
    add_options_page(
        'Sitemap Plugin Settings',
        'Sitemap Plugin',
        'manage_options',
        'sitemap-plugin',
        'sitemap_plugin_settings_page_html'
    );
}
