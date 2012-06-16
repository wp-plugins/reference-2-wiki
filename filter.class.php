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
    

/** class for filtering the content and add the Wikipedia link **/
class filter {
    
    static function run( $pcContent )
    {
        return preg_replace_callback("!\[\[(.*)\]\]!isU", "self::action", $pcContent);
    }
    
    static function action( $pa ) 
    {
        if ((empty($pa)) || (count($pa) != 2) || (empty($pa[1])))
            return null;
        
        // read options
        $laOption   = get_option("fpx_reference2wiki_option");
        $lcLanguage = $laOption["defaultlang"];

        
        // extract tag data (tag data within the second element)
        $lcSearch   = $pa[1];
        $lcShowText = $pa[1];
        
        $laData     = explode("|", $pa[1]);
        if (count($laData) == 2) {
            
            $lcSearch       = trim($laData[0]);
            $lcShowText		= trim($laData[1]);
            
        } elseif (count($laData) == 3) {
            $lcLanguage	= $laData[0];
            
            if (!empty($laData[1]))
                $lcSearch = $laData[1];
            else
                $lcSearch = $laData[2];
            $lcShowText	= $laData[2];
            
        }
        
        
        // create link
        $lcLink   = empty($laOption["beforetext"]) ? null : $laOption["beforetext"];
        $lcLink  .= "<a href=\"http://www.wikipedia.org/search-redirect.php?".http_build_query(array( "language" => $lcLanguage, "go" => "Go", "search" => $lcSearch ))."\"";
        if (!empty($laOption["cssclass"]))
            $lcLink .= " class=\"".$laOption["cssclass"]."\"";
        if (!empty($laOption["target"]))
            $lcLink .= " target=\"".$laOption["target"]."\"";
        
        $lcLink .= ">".$lcShowText."</a>";
        if (!empty($laOption["aftertext"]))
            $lcLink .= $laOption["aftertext"];

        return $lcLink;
    }
    
}

?>