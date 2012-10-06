<?php

/**
 * @file
 * 
 * Functions to support the documentation and examples.
 *   
 */

/**
 * Computes base root, base path, and base url.
 * 
 * This code is adapted from Drupal function conf_init, see:
 * http://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/conf_init/6
 * 
 */
function htmltodocx_paths() {
  
  if (!isset($_SERVER['SERVER_PROTOCOL']) || ($_SERVER['SERVER_PROTOCOL'] != 'HTTP/1.0' && $_SERVER['SERVER_PROTOCOL'] != 'HTTP/1.1')) {
    $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
  }

  if (isset($_SERVER['HTTP_HOST'])) {
    // As HTTP_HOST is user input, ensure it only contains characters allowed
    // in hostnames. See RFC 952 (and RFC 2181).
    // $_SERVER['HTTP_HOST'] is lowercased here per specifications.
    $_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);
    if (!htmltodocx_valid_http_host($_SERVER['HTTP_HOST'])) {
      // HTTP_HOST is invalid, e.g. if containing slashes it may be an attack.
      header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
      exit;
    }
  }
  else {
    // Some pre-HTTP/1.1 clients will not send a Host header. Ensure the key is
    // defined for E_ALL compliance.
    $_SERVER['HTTP_HOST'] = '';
  }

  // Create base URL
  $base_root = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

  $base_url = $base_root .= '://' . $_SERVER['HTTP_HOST'];

  // $_SERVER['SCRIPT_NAME'] can, in contrast to $_SERVER['PHP_SELF'], not
  // be modified by a visitor.
  if ($dir = trim(dirname($_SERVER['SCRIPT_NAME']), '\,/')) {
    $base_path = "/$dir";
    $base_url .= $base_path;
    $base_path .= '/';
  }
  else {
    $base_path = '/';
  }
  
  return array(
    'base_path' => $base_path,
    'base_url' => $base_url,
    'base_root' => $base_root,
  );
  
}

/**
 * Check for valid http host.
 * 
 * This code is adapted from function drupal_valid_http_host, see:
 * http://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/drupal_valid_http_host/6
 * 
 * @param mixed $host
 * @return int
 */
function htmltodocx_valid_http_host($host) {
  return preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $host);
}