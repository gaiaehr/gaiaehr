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
    session_name ("GaiaEHR" );
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
		include_once($_SESSION['site']['root'] . '/langs/' . $_SESSION['site']['localization'] . '.php');
		return $LANG;
	}
}

?>