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

$db->setTable('acl_roles');
$db->setField(array( 'NAME' => 'role_name', 'TYPE' => 'VARCHAR', 'LENGTH' => 20, 'NULL' => false  ) );
$db->setField(array( 'NAME' => 'role_key', 'TYPE' => 'VARCHAR', 'LENGTH' => 40, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'seq', 'TYPE' => 'VARCHAR', 'LENGTH' => 5, 'NULL' => false ) );
$db->executeORM();
		
$db->setTable('acl_permissions');
$db->setField(array( 'NAME' => 'perm_key', 'TYPE' => 'VARCHAR', 'LENGTH' => 100, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'perm_name', 'TYPE' => 'VARCHAR', 'LENGTH' => 100, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'perm_cat', 'TYPE' => 'VARCHAR', 'LENGTH' => 100, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'seq', 'TYPE' => 'VARCHAR', 'LENGTH' => 5, 'NULL' => false ) );
$db->executeORM();
		
$db->setTable('acl_role_perms');
$db->setField(array( 'NAME' => 'role_key', 'TYPE' => 'VARCHAR', 'LENGTH' => 50, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'perm_key', 'TYPE' => 'VARCHAR', 'LENGTH' => 50, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'value', 'TYPE' => 'INT', 'LENGTH' => 5, 'NULL' => false, 'DEFAULT' => '0' ) );
$db->setField(array( 'NAME' => 'add_date', 'TYPE' => 'DATETIME', 'NULL' => false ) );
$db->executeORM();
		
$db->setTable('acl_user_roles');
$db->setField(array( 'NAME' => 'user_id', 'TYPE' => 'BIGINT', 'LENGTH' => 20, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'role_id', 'TYPE' => 'BIGINT', 'LENGTH' => 20, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'add_date', 'TYPE' => 'TIMESTAMP', 'NULL' => false, 'DEFAULT' => 'CURRENT_TIMESTAMP' ) );
$db->executeORM();
		
$db->setTable('acl_user_perms');
$db->setField(array( 'NAME' => 'user_id', 'TYPE' => 'BIGINT', 'LENGTH' => 20, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'perm_key', 'TYPE' => 'VARCHAR', 'LENGTH' => 50, 'NULL' => false ) );
$db->setField(array( 'NAME' => 'value', 'TYPE' => 'TINYINT', 'LENGTH' => 1, 'NULL' => false , 'DEFAULT' => '0') );
$db->setField(array( 'NAME' => 'add_date', 'TYPE' => 'TIMESTAMP', 'NULL' => false, 'DEFAULT' => 'CURRENT_TIMESTAMP' ) );
$db->executeORM();
 
?>