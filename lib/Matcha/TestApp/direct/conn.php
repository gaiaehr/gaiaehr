<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 12/10/13
 * Time: 1:17 PM
 */
date_default_timezone_set('America/Puerto_Rico');
include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/Matcha/Matcha.php');

Matcha::connect(array(
	'host' => 'localhost',
	'name' => 'matchatest',
	'user' => 'matchatest',
	'pass' => '123456',
	'app' => dirname(dirname(__FILE__)) . '/app'
));
