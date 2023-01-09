# WP-Sitemap-Plugin
  This plugin has the following features:
<ul>
Generates a sitemap and adds a link to it in the footer of the page.

Automatically updates the sitemap.xml file and pings search engines like Google and Bing when changes are made to the blog.

Provides a settings page that allows the user to turn on or off the search engine pinging and footer link features.

Adds a link to the settings page in the plugin list.
  </ul>
  
To use this plugin, simply upload the sitemap-plugin folder to the wp-content/plugins directory of your WordPress installation, and then activate the plugin from the Plugins menu in the WordPress dashboard. The plugin will then automatically handle the generation and updating of the sitemap.xml file, as well as pinging search engines when changes are made to the blog.

The settings for the plugin can be accessed from the plugin list or from the Settings menu in the WordPress dashboard.



<b><h2>
  sitemap-plugin.php:
  </h2></b> 
  
  This is the main plugin file. It defines the plugin name, version, and other metadata, and includes the other PHP files that make up the plugin. It also registers the plugin's activation and deactivation hooks, which are functions that run when the plugin is activated or deactivated.
<b><h2>
inc/init.php:
  </b></h2>
  This file contains code that runs when the plugin is initialized. It registers a new rewrite rule for the sitemap.xml file, adds a custom query variable for the sitemap, generates the sitemap when the custom query variable is set, adds a settings link to the plugin list, and registers the settings page.
<b><h2>
inc/sitemap.php:
  </b></h2>
  This file contains the generate_sitemap() function, which generates the sitemap as an XML string.

<b><h2>
inc/search-engines.php: 
  </b></h2>
  This file contains the sitemap_plugin_ping_search_engines() function, which pings search engines like Google and Bing when a post or page is published or updated. It also registers the function to run when a post or page is published or updated.

<b><h2>
inc/footer-link.php: 
  </b></h2>
This file contains the sitemap_plugin_footer_link() function, which adds a link to the sitemap in the footer of the page. It also registers the function to run in the footer of the page.

<b><h2>
inc/settings.php:
 </b></h2> 
 This file contains the sitemap_plugin_settings_page_html() function, which generates the HTML for the plugin's settings page. It also contains the sitemap_plugin_activate() and sitemap_plugin_deactivate() functions, which run when the plugin is activated or deactivated. The sitemap_plugin_activate() function adds the plugin's options to the database and flushes the rewrite rules, while the sitemap_plugin_deactivate() function flushes the rewrite rules.
