=== Plugin Name ===
Contributors: flashpixx
Tags: wikipedia, reference, link, wiki
Requires at least: 3.2
Tested up to: 3.4.2
Stable tag: 0.21
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WCRMFYTNCJRAU
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html


Creates links to Wikipedia with different languages


== Description ==

The plugin is a Wikipedia plugin, for setting links to Wikipedia articles. The language and the description for every link can be set individually.


== Installation ==

1.  Upload the folder to the "/wp-content/plugins/" directory
2.  Activate the plugin through the 'Plugins' menu in WordPress


== Requirements ==

* Wordpress 3.2 or newer
* PHP 5.3.0 or newer 


== Shortcode ==
Add to your content of a page or article <pre>[[search tag]]</pre>
Another way for using the Wikipedia call, is to use the additional syntax
<pre>[[ article | view ]]</pre> creates a link to the article and within the content the view text is shown
<pre>[[ language | article | view ]]</pre> creates a link to the article with the language and within the content the view text is shown


== Frequently Asked Questions ==

= Which language code is supported ? =
All languages, which are show on the <a href="http://www.wikipedia.org/">Wikipedia</a> site, are supported.


== Upgrade Notice ==

= 0.2 =
On this version the underlaying object-orientated structure of the plugin uses the PHP namespaces, which added in the PHP version
5.3.0. So the plugin needs a PHP version equal or newer than PHP 5.3.0


== Changelog == 

= 0.22 =

* change language domain to "reference-2-wiki"

= 0.21 =

* fixing language bugs

= 0.2 =

* full redesign with PHP namespaces and classes (supports with PHP 5.3.0)
* change source structure to the full Wordpress Codex
* renaming language namespace


= 0.1 =

* add multilanguage support (english, german)
* fix some structures for the wordpress codex
