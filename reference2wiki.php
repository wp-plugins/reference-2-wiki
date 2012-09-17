<?php
/*
Plugin Name: Reference 2 Wiki
Plugin URI: http://wordpress.org/extend/plugins/reference-2-wiki/
Author URI: http://flashpixx.de/2010/02/wordpress-plugin-reference-2-wiki/
Description: The plugin allows to add references to Wikipedia
Author: flashpixx
Version: 0.21


#########################################################################
# GPL License                                                           #
#                                                                       #
# This file is part of the Wordpress Reference-2-Wiki plugin.           #
# Copyright (c) 2012, Philipp Kraus, <philipp.kraus@flashpixx.de>       #
# This program is free software: you can redistribute it and/or modify  #
# it under the terms of the GNU General Public License as published by  #
# the Free Software Foundation, either version 3 of the License, or     #
# (at your option) any later version.                                   #
#                                                                       #
# This program is distributed in the hope that it will be useful,       #
# but WITHOUT ANY WARRANTY; without even the implied warranty of        #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         #
# GNU General Public License for more details.                          #
#                                                                       #
# You should have received a copy of the GNU General Public License     #
# along with this program.  If not, see <http://www.gnu.org/licenses/>. #
#########################################################################
*/
    
namespace de\flashpixx\reference2wiki;
    
// ==== constant for developing with the correct path of the plugin ================================================================================
//define(__NAMESPACE__."\LOCALPLUGINFILE", __FILE__);
define(__NAMESPACE__."\LOCALPLUGINFILE", WP_PLUGIN_DIR."/reference-2-wiki/".basename(__FILE__));
// =================================================================================================================================================
    
    
    
// ==== plugin initialization ======================================================================================================================
@require_once("filter.class.php");
@require_once("menu.class.php");
    
// stop direct call
if (preg_match("#" . basename(LOCALPLUGINFILE) . "#", $_SERVER["PHP_SELF"])) { die("You are not allowed to call this page directly."); }
    
// translation
if (function_exists("load_plugin_textdomain"))
    load_plugin_textdomain("reference-2-wiki", false, dirname(plugin_basename(LOCALPLUGINFILE))."/lang");
// =================================================================================================================================================  
    
    
    
// ==== create Wordpress Hooks =====================================================================================================================
add_filter("the_content", "de\\flashpixx\\reference2wiki\\filter::run");
add_action("admin_init", "de\\flashpixx\\reference2wiki\\menu::settings");
add_action("admin_menu", "de\\flashpixx\\reference2wiki\\menu");

register_activation_hook(LOCALPLUGINFILE, "de\\flashpixx\\reference2wiki\\install");
register_uninstall_hook(LOCALPLUGINFILE, "de\\flashpixx\\reference2wiki\\uninstall");
// =================================================================================================================================================
    


// ==== administration function ====================================================================================================================

/** create the default options **/
function install() {
    $lxConfig = get_option("fpx_reference2wiki_option");
    if (empty($lxConfig))
        update_option("fpx_reference2wiki_option",
            array(
                    "defaultlang"     =>  "en",
                    "target"          =>  "_blank",
                    "cssclass"        =>  null,
                    "beforetext"      =>  null,
                    "aftertext"       =>  null
            )
        );
}

/** uninstall functions **/
function uninstall() {
	unregister_setting("fpx_reference2wiki_option", "fpx_reference2wiki_option");
	delete_option("fpx_reference2wiki_option");
}

/** creates admin menu **/
function menu() {
    if (!is_admin())
        return;
    
    add_options_page("Reference-2-Wiki", "Reference-2-Wiki", "administrator", "reference2wiki_option", "de\\flashpixx\\reference2wiki\\menu::render");
}
    
// =================================================================================================================================================

?>