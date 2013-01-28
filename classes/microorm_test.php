<?php
error_reporting(E_ALL);
session_name('GaiaEHR');
session_start();
session_cache_limiter('private');
define('_GaiaEXEC', 1);

include_once('../registry.php');
include_once('../sites/default/conf.php');
include_once('dbHelper.php');

$db = new dbHelper();

$db->setTable('soap_snippets');

        $db->setField(
            array(
                'NAME' => 'parentId',
                'TYPE' => 'VARCHAR',
                'LENGTH' => 30,
                'NULL' => true,
                'DEFAULT' => ''
            )
        );
        $db->setField(
            array(
                'NAME' => 'text',
                'TYPE' => 'TEXT',
                'NULL' => true,
                'DEFAULT' => ''
            )
        );
        $db->setField(
            array(
                'NAME' => 'index',
                'TYPE' => 'INT',
                'LENGTH' => 11,
                'NULL' => true,
                'DEFAULT' => ''
            )
        );
        $db->setField(
            array(
                'NAME' => 'category',
                'TYPE' => 'VARCHAR',
                'LENGTH' => 50,
                'NULL' => true,
                'DEFAULT' => ''
            )
        );
        $db->setField(
            array(
                'NAME' => 'leaf',
                'TYPE' => 'TINYINT',
                'LENGTH' => 1,
                'NULL' => true,
                'DEFAULT' => ''
            )
        );
        $db->executeORM();
 
?>