<?php
/*
GaiaEHR (Electronic Health Records)
i18nRouter.php
i18n dataProvider
Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if (!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

class i18nRouter
{
    // -----------------------------------------------------------------------
    // Get the translation file
    // An array made by http://transifex.net/
    // GaiaEHR Project
    // -----------------------------------------------------------------------
    public static function getTranslation()
    {
        if (isset($_SESSION['site']['localization'])) {
            $loc = $_SESSION['site']['localization'];
        } elseif (isset($_SESSION['site']['default_localization'])) {
            $loc = $_SESSION['site']['default_localization'];
        } else {
            $loc = false;
        }
        // This language file is need by default.
        include ($_SESSION['root'] . '/langs/en_US.php');
        $en_US = $LANG;
        // This file will be called when the user or the administrator select
        // a different language. But the primary language will be called first.
        // So if some words are not translated by the selected language it can be
        // displayed by the original language.
        if ($loc !== false) {
            include ($_SESSION['root'] . '/langs/' . $loc . '.php');
            return array_merge($en_US, $LANG);
        } else {
            return $en_US;
        }
    }

    // -----------------------------------------------------------------------
    // This will loop through the langs directory and get
    // all the available languages for GaiaEHR
    // This function is consumed by the dropdown list.
    // Need more translations go to: https://www.transifex.com/projects/p/gaiaehr/
    // -----------------------------------------------------------------------
    public static function getAvailableLanguages()
    {
        $availableLanguages = array();
        if ($handle = opendir($_SESSION['root'] . '/langs/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..') {
                    include_once ($_SESSION['root'] . '/langs/' . $entry);
                    $languageContent['code'] = key($LANG);
                    $languageContent['description'] = current($LANG);
                    $availableLanguages[] = $languageContent;
                    $LANG = NULL;
                }
            }
            closedir($handle);
        }
        return $availableLanguages;
    }

    // -----------------------------------------------------------------------
    // Get the default language
    // -----------------------------------------------------------------------
    public static function getDefaultLanguage()
    {
        if(isset($_SESSION['site']['default_localization'])){
            return $_SESSION['site']['default_localization'];
        }else{
            return 'en_US';
        }
    }

    // -----------------------------------------------------------------------
    // this function will look for the translation, if none found will return the key
    // -----------------------------------------------------------------------
    public static function t($key)
    {
        $lang = self::getTranslation();
        return (array_key_exists($key, $lang) ? $lang[$key] : $key);
    }

}

//	print_r(i18nRouter::getTranslation());
