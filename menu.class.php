<?php
/**
 * #########################################################################
 * # GPL License                                                           #
 * #                                                                       #
 * # This file is part of the Wordpress Reference-2-Wiki plugin.           #
 * # Copyright (c) 2012, Philipp Kraus, <philipp.kraus@flashpixx.de>       #
 * # This program is free software: you can redistribute it and/or modify  #
 * # it under the terms of the GNU General Public License as published by  #
 * # the Free Software Foundation, either version 3 of the License, or     #
 * # (at your option) any later version.                                   #
 * #                                                                       #
 * # This program is distributed in the hope that it will be useful,       #
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of        #
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         #
 * # GNU General Public License for more details.                          #
 * #                                                                       #
 * # You should have received a copy of the GNU General Public License     #
 * # along with this program.  If not, see <http://www.gnu.org/licenses/>. #
 * #########################################################################
 **/

namespace de\flashpixx\reference2wiki;

    
/** class for creating the menu panel **/
class menu {
    
    /** creates the setting fields **/
    static function settings() {
        register_setting( "fpx_reference2wiki_option",      "fpx_reference2wiki_option",                        get_class()."::validate" );
        
        
        add_settings_section("fpx_reference2wiki_option",           __("usage",  "reference-2-wiki"),               get_class()."::renderInfo",               "fpx_reference2wiki_option");
		
        add_settings_field("fpx_reference2wiki_defaultlanguage",    __("default language",  "reference-2-wiki"),    get_class()."::render_defaultlanguage",   "fpx_reference2wiki_option",    "fpx_reference2wiki_option");
       add_settings_field("fpx_reference2wiki_htmltarget",         __("html target (open a new browser window)",  "reference-2-wiki"),         get_class()."::render_htmltarget",        "fpx_reference2wiki_option",    "fpx_reference2wiki_option");
        add_settings_field("fpx_reference2wiki_cssclass",           __("css class (is added to the href-tag)",  "reference-2-wiki"),           get_class()."::render_cssclass",          "fpx_reference2wiki_option",    "fpx_reference2wiki_option");
        add_settings_field("fpx_reference2wiki_beforetext",         __("text before link (text that is added in front of the href-tag)",  "reference-2-wiki"),    get_class()."::render_beforetext",        "fpx_reference2wiki_option",    "fpx_reference2wiki_option");
        add_settings_field("fpx_reference2wiki_aftertext",          __("text after link (text that is added behind the href-tag)",  "reference-2-wiki"),     get_class()."::render_aftertext",         "fpx_reference2wiki_option",    "fpx_reference2wiki_option");
    }
    
    /** renders the option panel **/
    static function render() {
        echo "<div class=\"wrap\" id=\"fpx_reference2wiki\"><h2>Reference-2-Wiki ".__("Configuration", "reference-2-wiki")."</h2>\n";
        
        echo "<form method=\"post\" action=\"options.php\">";
        settings_fields("fpx_reference2wiki_option");
        do_settings_sections("fpx_reference2wiki_option");
        echo "<p class=\"submit\"><input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"".__("Save Changes")."\"/></p>\n";
        echo "</form></div>\n";
    }
    
    /** info text of the option page **/
    static function renderInfo() {
        echo __("You can setup the reference to Wikipedia with the commands:", "reference-2-wiki");
        echo "<ul>";
        echo "<li><strong>[[</strong> ".__("article", "reference-2-wiki")." <strong>]]</strong> ".__("creates a link to the article with the default options", "reference-2-wiki")."</li>";
        echo "<li><strong>[[</strong> ".__("article", "reference-2-wiki")."<strong> | </strong>".__("view", "reference-2-wiki")." <strong>]]</strong> ".__("creates a link to the article and within the content the view text is shown", "reference-2-wiki")."</li>";
                echo "<li><strong>[[</strong> ".__("language", "reference-2-wiki")." <strong> | </strong> ".__("article", "reference-2-wiki")."<strong> | </strong>".__("view", "reference-2-wiki")." <strong>]]</strong> ".__("creates a link to the article with the language and within the content the view text is shown", "reference-2-wiki")."</li>";
        echo "</ul>".__("The language is in the two typical abbreviations such as en, de, fr, etc. specified. All options are optional except the article.", "reference-2-wiki");
        echo "<hr width=\"100%\" /><br/>";
    }
    
    /** validates the options **/
    static function validate( $pa ) {
        
        $pa["cssclass"]   = wp_specialchars($pa["cssclass"]);
        $pa["aftertext"]  = wp_specialchars($pa["aftertext"]);
        $pa["beforetext"] = wp_specialchars($pa["beforetext"]);
        
        return $pa;
    }
    
    
    
    static function render_defaultlanguage() {
        $laOptions   = get_option("fpx_reference2wiki_option");
        
        echo "<select name=\"fpx_reference2wiki_option[defaultlang]\">\n";
        
        echo "<option value=\"ar\" lang=\"ar\" ".(($laOptions["defaultlang"] == "ar") ? "selected": null).">العربية</option>\n";
        echo "<option value=\"ca\" lang=\"ca\" ".(($laOptions["defaultlang"] == "ca") ? "selected": null).">Català</option>\n";
        echo "<option value=\"cs\" lang=\"cs\" ".(($laOptions["defaultlang"] == "cs") ? "selected": null).">Česky</option>\n";
        
        echo "<option value=\"da\" lang=\"da\" ".(($laOptions["defaultlang"] == "da") ? "selected": null).">Dansk</option>\n";
        echo "<option value=\"de\" lang=\"de\" ".(($laOptions["defaultlang"] == "de") ? "selected": null).">Deutsch</option>\n";
        echo "<option value=\"en\" lang=\"en\" ".(($laOptions["defaultlang"] == "en") ? "selected": null).">English</option>\n";
        echo "<option value=\"es\" lang=\"es\" ".(($laOptions["defaultlang"] == "es") ? "selected": null).">Español</option>\n";
        echo "<option value=\"eo\" lang=\"eo\" ".(($laOptions["defaultlang"] == "eo") ? "selected": null).">Esperanto</option>\n";
        echo "<option value=\"fr\" lang=\"fr\" ".(($laOptions["defaultlang"] == "fr") ? "selected": null).">Français</option>\n";
        echo "<option value=\"ko\" lang=\"ko\" ".(($laOptions["defaultlang"] == "ko") ? "selected": null).">한국어</option>\n";
        echo "<option value=\"id\" lang=\"id\" ".(($laOptions["defaultlang"] == "id") ? "selected": null).">Bahasa Indonesia</option>\n";
        echo "<option value=\"it\" lang=\"it\" ".(($laOptions["defaultlang"] == "it") ? "selected": null).">Italiano</option>\n";
        
        echo "<option value=\"he\" lang=\"he\" ".(($laOptions["defaultlang"] == "he") ? "selected": null).">עברית</option>\n";
        echo "<option value=\"lt\" lang=\"lt\" ".(($laOptions["defaultlang"] == "lt") ? "selected": null).">Lietuvių</option>\n";
        echo "<option value=\"hu\" lang=\"hu\" ".(($laOptions["defaultlang"] == "hu") ? "selected": null).">Magyar</option>\n";
        echo "<option value=\"nl\" lang=\"nl\" ".(($laOptions["defaultlang"] == "nl") ? "selected": null).">Nederlands</option>\n";
        echo "<option value=\"ja\" lang=\"ja\" ".(($laOptions["defaultlang"] == "ja") ? "selected": null).">日本語</option>\n";
        echo "<option value=\"no\" lang=\"nb\" ".(($laOptions["defaultlang"] == "nb") ? "selected": null).">Norsk (bokmål)</option>\n";
        echo "<option value=\"pl\" lang=\"pl\" ".(($laOptions["defaultlang"] == "pl") ? "selected": null).">Polski</option>\n";
        echo "<option value=\"pt\" lang=\"pt\" ".(($laOptions["defaultlang"] == "pt") ? "selected": null).">Português</option>\n";
        echo "<option value=\"ro\" lang=\"ro\" ".(($laOptions["defaultlang"] == "ro") ? "selected": null).">Română</option>\n";
        
        echo "<option value=\"ru\" lang=\"ru\" ".(($laOptions["defaultlang"] == "ru") ? "selected": null).">Русский</option>\n";
        echo "<option value=\"sk\" lang=\"sk\" ".(($laOptions["defaultlang"] == "sk") ? "selected": null).">Slovenčina</option>\n";
        echo "<option value=\"sr\" lang=\"sr\" ".(($laOptions["defaultlang"] == "sr") ? "selected": null).">Српски / Srpski</option>\n";
        echo "<option value=\"fi\" lang=\"fi\" ".(($laOptions["defaultlang"] == "fi") ? "selected": null).">Suomi</option>\n";
        echo "<option value=\"sv\" lang=\"sv\" ".(($laOptions["defaultlang"] == "sv") ? "selected": null).">Svenska</option>\n";
        echo "<option value=\"tr\" lang=\"tr\" ".(($laOptions["defaultlang"] == "tr") ? "selected": null).">Türkçe</option>\n";
        echo "<option value=\"uk\" lang=\"uk\" ".(($laOptions["defaultlang"] == "uk") ? "selected": null).">Українська</option>\n";
        echo "<option value=\"vi\" lang=\"vi\" ".(($laOptions["defaultlang"] == "vi") ? "selected": null).">Tiếng Việt</option>\n";
        echo "<option value=\"vo\" lang=\"vo\" ".(($laOptions["defaultlang"] == "vo") ? "selected": null).">Volapük</option>\n";
        
        echo "<option value=\"zh\" lang=\"zh\" ".(($laOptions["defaultlang"] == "zh") ? "selected": null).">中文</option>\n";
        
        echo "</select>\n";	        
    }
    
    static function render_cssclass() {
        $laOptions = get_option("fpx_reference2wiki_option");
        echo "<input name=\"fpx_reference2wiki_option[cssclass]\" type=\"text\" value=\"".$laOptions["cssclass"]."\" size=\"15\" />";
    }
    
    static function render_htmltarget() {
        $laOptions = get_option("fpx_reference2wiki_option");
        
        echo "<select name=\"fpx_reference2wiki_option[target]\">\n";
        echo "<option value=\"\">".__("same window", "reference-2-wiki")."</option>\n";
        echo "<option value=\"_blank\" ".(($laOptions["target"] == "_blank") ? "selected": null).">".__("new window / tab", "reference-2-wiki")."</option>\n";
        echo "</select>\n";	
    }
    
    static function render_beforetext() {
        $laOptions = get_option("fpx_reference2wiki_option");
        echo "<input name=\"fpx_reference2wiki_option[beforetext]\" type=\"text\" value=\"".$laOptions["beforetext"]."\" size=\"15\" />";
    }
    
    static function render_aftertext() {
        $laOptions = get_option("fpx_reference2wiki_option");
        echo "<input name=\"fpx_reference2wiki_option[aftertext]\" type=\"text\" value=\"".$laOptions["aftertext"]."\" size=\"15\" />";
    } 
    
}

?>