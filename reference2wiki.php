<?php
/*
Plugin Name: Reference 2 Wiki
Plugin URI: http://wordpress.org/extend/plugins/reference-2-wiki/
Author URI: http://flashpixx.de/2010/02/wordpress-plugin-reference-2-wiki/
Description: The plugin allows to add references to Wikipedia
Version: 0.1
Stable tag: trunk
Tested up to: 3.0.1
Author: flashpixx
License: GPLv3
*/


// stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

// translation
if (function_exists('load_plugin_textdomain'))
	load_plugin_textdomain('fpx_ref2wiki', false, dirname(plugin_basename(__FILE__))."/lang");


// ==== create Wordpress Hooks =====================================================================================================================
add_filter('the_content', 'fpx_ref2wiki_filter');
register_activation_hook(__FILE__,'fpx_ref2wiki_install');
register_uninstall_hook(__FILE__, 'fpx_ref2wiki_uninstall');
add_action('admin_menu', 'fpx_ref2wiki_adminmenu');
// =================================================================================================================================================


// ==== filter and other functions =================================================================================================================

/** content filter function for get the tags
  * @param $pcContent Content
**/
function fpx_ref2wiki_filter($pcContent) {
	return preg_replace_callback("!\[\[(.*)\]\]!isU", "fpx_ref2wiki_filteraction", $pcContent);
}


/** create action and the href tag
  * @param $pa Array with founded regular expressions
  * @return replace href tag or null on error
**/
function fpx_ref2wiki_filteraction($pa) {
	if ((empty($pa)) || (count($pa) != 2) || (empty($pa[1])))
		return null;
	
	// layout
	$lcClass = get_option('fpx_ref2wiki_class');
	if (!empty($lcClass))
		$lcClass = " class=\"".$lcClass."\"";

	$lcTarget = get_option('fpx_ref2wiki_linktarget');
	if ($lcTarget == "blank")
		$lcTarget = " target=\"_blank\"";
	else
		$lcTarget = null;

	$lcBefore 	= wp_specialchars(get_option('fpx_ref2wiki_beforetext'));
	$lcAfter	= wp_specialchars(get_option('fpx_ref2wiki_aftertext'));
	
	
	// create link structure
	$lcUrl 			= "http://www.wikipedia.org/search-redirect.php";
	$laLinkParams	= array(
						"language" 	=> get_option('fpx_ref2wiki_defaultsearch'),
						"go"		=> "Go",
						"search"	=> $pa[1]
		);
	$lcShowText		= $laLinkParams["search"];
	
	// seperate content
	$la = explode("|", $laLinkParams["search"]);
	if (count($la) == 2) {
		$laLinkParams["search"] 	= $la[0];
		$lcShowText					= $la[1];
	} elseif (count($la) == 3) {
		$laLinkParams["language"]	= $la[0];
		if (!empty($la[1]))
			$laLinkParams["search"] = $la[1];
		else
			$laLinkParams["search"] = $la[2];
		$lcShowText					= $la[2];
		
	}
	
	if (empty($laLinkParams["search"]))
		return $pa[1];
	if (empty($laLinkParams["language"]))
		$laLinkParams["language"] = get_option('fpx_ref2wiki_defaultsearch');
	if (empty($lcShowText))
		$lcShowText = $laLinkParams["search"];

	// create link
	$laLinkParams["search"] = urlencode($laLinkParams["search"]);
	$lcUrl .= "?".fpx_ref2wiki_implodeassoc("&amp;", $laLinkParams);
	return "<a href=\"".$lcUrl."\"".$lcClass.$lcTarget." ref=\"external, nofollow\">".$lcBefore.$lcShowText.$lcAfter."</a>";
	
}

/** implodes an associative array with keys
  * @param $glue seperator
  * @param $array array
  * @return imploded string
**/
function fpx_ref2wiki_implodeassoc($glue, $array) {
	$output = array();
	foreach( $array as $key => $item )
		array_push($output, $key."=".$item);
	return implode($glue, $output);
}

// =================================================================================================================================================



// ==== administration function ====================================================================================================================

/** create the default options **/
function fpx_ref2wiki_install() {
	$laOptions = array(
		'fpx_ref2wiki_defaultsearch'		=> 'en',
		'fpx_ref2wiki_class'				=> null,
		'fpx_ref2wiki_beforetext'			=> null,
		'fpx_ref2wiki_aftertext'			=> null,
		'fpx_ref2wiki_linktarget'			=> null
		);
		
	// create key and default values
	foreach ($laOptions as $key => $value) {
		register_setting('fpx_ref2wiki_option', $key);
		update_option($key, $value);
	}
}


/** uninstall functions **/
function fpx_ref2wiki_uninstall() {
	$laOptions = array(
		'fpx_ref2wiki_defaultsearch',
		'fpx_ref2wiki_class',
		'fpx_ref2wiki_beforetext',
		'fpx_ref2wiki_aftertext',
		'fpx_ref2wiki_linktarget'
		);
	
	// remove plugin options
	foreach ($laOptions as $lcOption) {
		unregister_setting('fpx_ref2wiki_option', $lcOption);
		delete_option($lcOption);
	}
}


/** creates admin menu **/
function fpx_ref2wiki_adminmenu() {
	if (is_admin()) 
		// adds admin page call
		add_options_page('Reference 2 Wiki Optionen', 'Reference 2 Wiki', 9, __FILE__, 'fpx_ref2wiki_option');
}


/** shows the admin panel with actions **/
function fpx_ref2wiki_option() {
	echo "<div class=\"wrap\"><h2>Reference 2 Wiki ".__("Configuration and Usage", 'fpx_ref2wiki')."</h2>\n";
	echo "<p>".__("If you like the plugin and want to support my work, I'd appreciate a donation through the following link very much. A donation encouraged me to develop my work further and to thank you again and again so the community.", 'fpx_ref2wiki')."\n";
	echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"> \n";
	echo "<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\"/> \n";
	echo "<input type=\"hidden\" name=\"hosted_button_id\" value=\"RMZVY3WHSL6ZY\"/> \n";
	echo "<input type=\"image\" src=\"https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online.\"/></form></p>\n\n";
 
	echo "<hr> \n\n";
	echo "<h3>".__("Usage", 'fpx_ref2wiki')."</h3> \n";
	echo "<p>".__("The call of the search term for Wikipedia to be carried out as described in MediaWiki in double brackets [[keyword]]. Optionally, the text can be specified to display [[search | display text]] or a deviation of the default language in the form of [[language | search | display text]] will be carried out. If the second parameter (Wikipedia keyword) is empty, is used to display text, thus [[language | | display text]] is identical to [[language | display text | display text]]. The language is in the two typical abbreviations such as en, de, fr, etc. specified", 'fpx_ref2wiki')."</p>";
	
	if (isset($_POST["submit"])) {
		update_option('fpx_ref2wiki_class', ((isset($_POST["class"])) ? strtolower($_POST["class"]) : null));
		update_option('fpx_ref2wiki_beforetext', ((isset($_POST["beforetext"])) ? strtolower($_POST["beforetext"]) : null));
		update_option('fpx_ref2wiki_aftertext', ((isset($_POST["aftertext"])) ? strtolower($_POST["aftertext"]) : null));
		update_option('fpx_ref2wiki_linktarget', ((isset($_POST["linktarget"])) ? strtolower($_POST["linktarget"]) : null));
		
		if (isset($_POST["language"]))
			update_option('fpx_ref2wiki_defaultsearch', $_POST["language"]);
	}
	$lcLang = get_option('fpx_ref2wiki_defaultsearch');
	
	echo "<form method=\"post\" action=\"\" enctype=\"multipart/form-data\">\n";
	echo "<fieldset> \n";
	settings_fields('fpx_ref2wiki_option');
	echo "<table> \n";
	echo "<tr><td><label for=\"language\">".__("default search language", 'fpx_ref2wiki').":</label></td><td>\n\n";
	echo "<select id=\"language\" name=\"language\">\n";
	
	echo "<option value=\"ar\" lang=\"ar\" ".(($lcLang == "ar") ? "selected": null).">العربية</option>\n";
	echo "<option value=\"ca\" lang=\"ca\" ".(($lcLang == "ca") ? "selected": null).">Català</option>\n";
	echo "<option value=\"cs\" lang=\"cs\" ".(($lcLang == "cs") ? "selected": null).">Česky</option>\n";

	echo "<option value=\"da\" lang=\"da\" ".(($lcLang == "da") ? "selected": null).">Dansk</option>\n";
	echo "<option value=\"de\" lang=\"de\" ".(($lcLang == "de") ? "selected": null).">Deutsch</option>\n";
	echo "<option value=\"en\" lang=\"en\" ".(($lcLang == "en") ? "selected": null).">English</option>\n";
	echo "<option value=\"es\" lang=\"es\" ".(($lcLang == "es") ? "selected": null).">Español</option>\n";
	echo "<option value=\"eo\" lang=\"eo\" ".(($lcLang == "eo") ? "selected": null).">Esperanto</option>\n";
	echo "<option value=\"fr\" lang=\"fr\" ".(($lcLang == "fr") ? "selected": null).">Français</option>\n";
	echo "<option value=\"ko\" lang=\"ko\" ".(($lcLang == "ko") ? "selected": null).">한국어</option>\n";
	echo "<option value=\"id\" lang=\"id\" ".(($lcLang == "id") ? "selected": null).">Bahasa Indonesia</option>\n";
	echo "<option value=\"it\" lang=\"it\" ".(($lcLang == "it") ? "selected": null).">Italiano</option>\n";

	echo "<option value=\"he\" lang=\"he\" ".(($lcLang == "he") ? "selected": null).">עברית</option>\n";
	echo "<option value=\"lt\" lang=\"lt\" ".(($lcLang == "lt") ? "selected": null).">Lietuvių</option>\n";
	echo "<option value=\"hu\" lang=\"hu\" ".(($lcLang == "hu") ? "selected": null).">Magyar</option>\n";
	echo "<option value=\"nl\" lang=\"nl\" ".(($lcLang == "nl") ? "selected": null).">Nederlands</option>\n";
	echo "<option value=\"ja\" lang=\"ja\" ".(($lcLang == "ja") ? "selected": null).">日本語</option>\n";
	echo "<option value=\"no\" lang=\"nb\" ".(($lcLang == "nb") ? "selected": null).">Norsk (bokmål)</option>\n";
	echo "<option value=\"pl\" lang=\"pl\" ".(($lcLang == "pl") ? "selected": null).">Polski</option>\n";
	echo "<option value=\"pt\" lang=\"pt\" ".(($lcLang == "pt") ? "selected": null).">Português</option>\n";
	echo "<option value=\"ro\" lang=\"ro\" ".(($lcLang == "ro") ? "selected": null).">Română</option>\n";

	echo "<option value=\"ru\" lang=\"ru\" ".(($lcLang == "ru") ? "selected": null).">Русский</option>\n";
	echo "<option value=\"sk\" lang=\"sk\" ".(($lcLang == "sk") ? "selected": null).">Slovenčina</option>\n";
	echo "<option value=\"sr\" lang=\"sr\" ".(($lcLang == "sr") ? "selected": null).">Српски / Srpski</option>\n";
	echo "<option value=\"fi\" lang=\"fi\" ".(($lcLang == "fi") ? "selected": null).">Suomi</option>\n";
	echo "<option value=\"sv\" lang=\"sv\" ".(($lcLang == "sv") ? "selected": null).">Svenska</option>\n";
	echo "<option value=\"tr\" lang=\"tr\" ".(($lcLang == "tr") ? "selected": null).">Türkçe</option>\n";
	echo "<option value=\"uk\" lang=\"uk\" ".(($lcLang == "uk") ? "selected": null).">Українська</option>\n";
	echo "<option value=\"vi\" lang=\"vi\" ".(($lcLang == "vi") ? "selected": null).">Tiếng Việt</option>\n";
	echo "<option value=\"vo\" lang=\"vo\" ".(($lcLang == "vo") ? "selected": null).">Volapük</option>\n";

	echo "<option value=\"zh\" lang=\"zh\" ".(($lcLang == "zh") ? "selected": null).">中文</option>\n";
	
	echo "</select>\n";	
	echo "</td></tr>\n"; 

	echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";

	echo "<tr><td><label for=\"class\">".__("css class for link", 'fpx_ref2wiki').":</label></td><td><input type=\"text\" id=\"class\" name=\"class\" value=\"".get_option('fpx_ref2wiki_class')."\" /></td></tr> \n";
	echo "<tr><td><label for=\"beforetext\">".__("text show before link", 'fpx_ref2wiki').":</label></td><td><input type=\"text\" id=\"beforetext\" name=\"beforetext\" value=\"".get_option('fpx_ref2wiki_beforetext')."\" /></td></tr> \n";
	echo "<tr><td><label for=\"aftertext\">".__("text show after link", 'fpx_ref2wiki').":</label></td><td><input type=\"text\" id=\"aftertext\" name=\"aftertext\" value=\"".get_option('fpx_ref2wiki_aftertext')."\" /></td></tr> \n";
	echo "<tr><td><label for=\"linktarget\">".__("link target",'fpx_ref2wiki').":</label></td><td>";

	echo "<select id=\"linktarget\" name=\"linktarget\">\n";
	echo "<option value=\"\">".__("same window", 'fpx_ref2wiki')."</option>\n";
	echo "<option value=\"blank\" ".((get_option('fpx_ref2wiki_linktarget') == "blank") ? "selected": null).">".__("new window / tab", 'fpx_ref2wiki')."</option>\n";
	echo "</select>\n";	
	echo "</td></tr> \n";
	echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
	
	echo "<tr><td><input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"".__('Save Changes')."\"/></td></tr>\n";
	
	echo "</table> \n";
	echo "</fieldset> \n";
	echo "</form></div>\n";
	
}
// =================================================================================================================================================

?>