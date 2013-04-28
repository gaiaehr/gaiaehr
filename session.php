<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 4/27/13
 * Time: 6:49 PM
 * To change this template use File | Settings | File Templates.
 */

if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
print '<pre>';
print_r($_SESSION);