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
	public function getTranslation()
	{
		// This language file is need by default.
		include_once($_SESSION['site']['root'] . '/langs/en_US.php');
		$en_US = $LANG;
		
		// This file will be called when the user or the administrator select 
		// a diferent language.
		include_once($_SESSION['site']['root'] . '/langs/' . $_SESSION['site']['localization'] . '.php');
		return array_merge($en_US, $LANG);
	}

	// -----------------------------------------------------------------------
	// Will loop through the lang directory and get
	// the available languages for GaiaEHR
	// This function is consumed by the dropdown list.
	// -----------------------------------------------------------------------
	public static function getAvailableLanguages()
	{
		$availableLanguages = array();
		if($handle = opendir($_SESSION['site']['root'] . '/langs/'))
		{
			while(false !== ($entry = readdir($handle))) 
			{
				if($entry != '.' && $entry != '..') 
				{
					include_once($_SESSION['site']['root'] . '/langs/' . $entry);
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
	
}

