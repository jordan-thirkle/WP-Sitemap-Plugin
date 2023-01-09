<?php
/*
 * Plugin Name: Sitemap Plugin
 * Plugin URI: https://jordanthirkle.com/wp-sitemap-plugin
 * Description: Generates a sitemap and adds a link to it in the footer of the page. Automatically updates the sitemap.xml file and pings search engines when changes are made to the blog.
 * Version: 1.0
 * Author: Jordan Thirkle
 * Author URI: https://jordanthirkle.com
 * License: GPL2
 */

function sitemap_plugin_init() {
    // Register a new rewrite rule for the sitemap.xml file
    add_rewrite_rule('sitemap\.xml$', 'index.php?sitemap=1', 'top');

    // Add a custom query variable for the sitemap
    add_filter('query_vars', 'sitemap_plugin_query_vars');

    // Generate the sitemap when the custom query variable is set
    add_action('template_redirect', 'sitemap_plugin_template_redirect');

    // Ping search engines when a post is published or updated
    add_action('publish_post', 'sitemap_plugin_ping_search_engines');
    add_action('publish_page', 'sitemap_plugin_ping_search_engines');
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

function generate_sitemap() {
    $posts = wp_get_archives(array(
        'type' => 'postbypost',
        'limit' => -1,
        'echo' => 0
    ));
    $pages = wp_get_archives(array(
        'type' => 'page',
        'limit' => -1,
        'echo' => 0
    ));
    $sitemap = "<?xml version='1.0' encoding='UTF-8'?>\n";
    $sitemap .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";
    $sitemap .= $posts;
    $sitemap .= $pages;
    $sitemap .= "</urlset>\n";
    return $sitemap;
}

function sitemap_plugin_ping_search_engines() {
    $sitemap_url = home_url('sitemap.xml');
    $search_engines = array(
        'http://www.google.com/webmasters/sitemaps/ping?sitemap=',
        'http://www.bing.com/webmaster/ping.aspx?siteMap=',
    );
    foreach ($search_engines as $search_engine) {
        wp_remote_get($search_engine . $sitemap_url);
    }
}

function sitemap_plugin_footer_link() {
    $sitemap_url = home_url('sitemap.xml');
    echo '<a href="' . $sitemap_url . '">Sitemap</a>';
}
add_action('wp_footer', 'sitemap_plugin_footer_link');
