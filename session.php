<?php
/**
 * This file is just use for debugging
 * access this file from yoou browser to see
 * all session array and values
 */
session_name ( 'GaiaEHR' );
session_start();
session_cache_limiter('private');

echo '<pre>';
print_r($_SESSION);