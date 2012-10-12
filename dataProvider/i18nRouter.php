<?php
/**
 * Created by Aptana IDE
 * Author: Gino Rivera Falú (GI Technologies)
 * File: Localization.php
 * Date: 8/23/12
 * 
 * Description:
 * dataProvider for Localization
 * 
 */
if(!isset($_SESSION))
{
    session_name ('GaiaEHR');
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
		// This language file is need by default.
		include($_SESSION['root'] . '/langs/en_US.php');
		$en_US = $LANG;
		
		// This file will be called when the user or the administrator select 
		// a diferent language. But the primary language will be called first.
		// So if some words are not translated by the selected language it can be 
		// displayed by the original language. 
		include($_SESSION['root'] . '/langs/' . $_SESSION['site']['localization'] . '.php');
		return array_merge($en_US, $LANG);
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
		if($handle = opendir($_SESSION['root'] . '/langs/'))
		{
			while(false !== ($entry = readdir($handle))) 
			{
				if($entry != '.' && $entry != '..') 
				{
					include_once($_SESSION['root'] . '/langs/' . $entry);
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
		return $_SESSION['site']['lang'];
	}

	// -----------------------------------------------------------------------
	// this function will look for the translation, if none found will return the key
	// -----------------------------------------------------------------------
    public static function t($key)
    {
        $lang = self::getTranslation();
        return (array_key_exists($key,$lang) ? $lang[$key] : $key);
    }
}

//	print i18nRouter::t('patient_home_phone');
