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
    session_cache_limiter('private');
    session_cache_expire(1);
    session_regenerate_id(false);
    session_name('GaiaEHR');
    session_start();
    setcookie(session_name(),session_id(),time()+60, '/', null, false, true);
}
print '<pre>';
print_r($_SESSION);