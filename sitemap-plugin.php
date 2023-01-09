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

define('SITEMAP_PLUGIN_OPTIONS', 'sitemap_plugin_options');

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
    $options = get_option(SITEMAP_PLUGIN_OPTIONS);
    if ($options['ping_search_engines']) {
        $sitemap_url = home_url('sitemap.xml');
        $search_engines = array(
            'http://www.google.com/webmasters/sitemaps/ping?sitemap=',
            'http://www.bing.com/webmaster/ping.aspx?siteMap=',
        );
        foreach ($search_engines as $search_engine) {
            wp_remote_get($search_engine . $sitemap_url);
        }
    }
}

function sitemap_plugin_footer_link() {
    $options = get_option(SITEMAP_PLUGIN_OPTIONS);
    if ($options['show_footer_link']) {
        $sitemap_url = home_url('sitemap.xml');
        echo '<a href="' . $sitemap_url . '">Sitemap</a>';
    }
}
add_action('wp_footer', 'sitemap_plugin_footer_link');

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

function sitemap_plugin_settings_page_html() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    $options = get_option(SITEMAP_PLUGIN_OPTIONS);
    if (isset($_POST['submit'])) {
        $options['ping_search_engines'] = isset($_POST['ping_search_engines']);
        $options['show_footer_link'] = isset($_POST['show_footer_link']);
        update_option(SITEMAP_PLUGIN_OPTIONS, $options);
    }
    ?>
    <div class="wrap">
        <h1>Sitemap Plugin Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="ping_search_engines">
Ping search engines</label></th>
                    <td>
                        <input type="checkbox" id="ping_search_engines" name="ping_search_engines" value="1" <?php checked($options['ping_search_engines']) ?> />
                        <p class="description">Ping search engines (e.g. Google and Bing) when a post or page is published or updated, to let them know that the sitemap has been updated.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_footer_link">Show footer link</label></th>
                    <td>
                        <input type="checkbox" id="show_footer_link" name="show_footer_link" value="1" <?php checked($options['show_footer_link']) ?> />
                        <p class="description">Show a link to the sitemap in the footer of the page.</p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </p>
        </form>
    </div>
    <?php
}

register_activation_hook(__FILE__, 'sitemap_plugin_activate');
function sitemap_plugin_activate() {
    add_option(SITEMAP_PLUGIN_OPTIONS, array(
        'ping_search_engines' => 1,
        'show_footer_link' => 1,
    ));
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'sitemap_plugin_deactivate');
function sitemap_plugin_deactivate() {
    flush_rewrite_rules();
}
