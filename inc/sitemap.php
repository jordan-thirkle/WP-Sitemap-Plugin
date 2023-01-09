<?php

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
    $sitemap .= "<?xml-stylesheet type='text/xsl' href='/sitemap.xsl'?>\n";
    $sitemap .= $posts;
    $sitemap .= $pages;
    $sitemap .= "</urlset>\n";
    return $sitemap;
}
