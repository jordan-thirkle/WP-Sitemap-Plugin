<?php

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
                    <th scope="row"><label for="ping_search_engines">Ping search engines</label></th>
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

function sitemap_plugin_activate() {
    add_option(SITEMAP_PLUGIN_OPTIONS, array('ping_search_engines' => 1,
        'show_footer_link' => 1,
    ));
    flush_rewrite_rules();
}

function sitemap_plugin_deactivate() {
    flush_rewrite_rules();
}
