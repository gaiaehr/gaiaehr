<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ernesto
 * Date: 3/7/12
 * Time: 11:41 AM
 * To change this template use File | Settings | File Templates.
 */

require_once '../classes/Languages.php';

$lang = new Languages();
echo '<pre>';
print_r($lang->getLanguageFromTransifex('es_PR'));
print_r($lang->getLanguagesFromTransifex());

