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
 * Coding Style: C# 
 */
if(!isset($_SESSION))
{
    session_name ('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

class i18n 
{
	// Get the translation file
	// An array made by http://transifex.net/
	// GaiaEHR Project
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

	// TODO: This procedure have to make a lookup in the directory of language, to
	// look for available language files, and then precent the available languages
	// on the dropdown list.
	public static function getAvailableLanguages(){
		$langTexts = array(
			'en_US' => 'English (United States)',
			'es_PR' => 'Spanish (Puerto Rico)',
			'en_ES' => 'Spanish (Spain)',
			'fr' => 'French',
			'nl' => 'Dutch'
		);
		$languages = array();
		if($handle = opendir($_SESSION['site']['root'] . '/langs/')){
			while(false !== ($entry = readdir($handle))) {
				if($entry != '.' && $entry != '..') {
					$foo['code'] = basename($entry,'.php');
					$foo['text'] = (array_key_exists($foo['code'], $langTexts)) ? $langTexts[$foo['code']] : '';
					$foo['file'] = $entry;
					$languages[] = $foo;
				}
			}
			closedir($handle);
		}
		return $languages;
	}

	public static function getDefaultLanguage(){
		return $_SESSION['site']['lang'];
	}
}
//print '<pre>';
//$foo = new i18n();
//print_r($foo->getAvailableLanguages());
//print_r(i18n::getAvailableLanguages(false));
