<?php
/**
 * Matcha::connect microORM v0.0.1
 * This set of classes will help Sencha ExtJS and PHP developers deliver fast and powerful application fast and easy to develop.
 * If Sencha ExtJS is a GUI Framework of the future, think Matcha micrORM as the bridge between the Client-Server
 * GAP.
 *
 * Matcha will read and parse a Sencha Model .js file and then connect to the database and produce a compatible database-table
 * from your model. Also will provide the basic functions for the CRUD. If you are familiar with Sencha ExtJS, and know
 * about Sencha Models, you will need this PHP Class. You can use it in any way you want, in MVC like pattern, your own pattern,
 * or just playing simple. It's compatible with all your coding style.
 *
 * Taking some ideas from diferent microORM's and full featured ORM's we bring you this cool Class.
 *
 * History:
 * Born in the fields of GaiaEHR we needed a way to develop the application more faster, Gino Rivera suggested the use of an
 * microORM for fast development and the development began. We tried to use some already developed and well known ORM's on the
 * space of PHP, but none satisfied our needs. So Gino Rivera sugested the development of our own microORM (a long way to run).
 *
 * But despite the long run, it returned to be more logical to get ideas from the well known ORM's and how Sensha manage their models
 * so this is the result.
 *
 */
include_once('../lib/Matcha.php');
include_once('../lib/matchacup.php');

//print '<pre>';
class CupTest{

	function __construct(){
		Matcha::connect(array(
			'host'=>(string)'localhost',
			'port'=>(int)'3306',
			'name'=>(string)'gaiadb',
			'user'=>(string)'gaiadb',
			'pass'=>(string)'pass',
			'root'=>(string)'C:/inetpub/wwwroot/gaiaehr'
		));


	}

	function cuptest($params){
		$t = new MatchaCUP();
		$t->setModel(Array(
			'extend' => 'Ext.data.Model',
			'table' => Array(
				'name' => 'accvoucher',
				'engine' => 'InnoDB',
				'autoIncrement' => 1,
				'charset' => 'utf8',
				'collate' => 'utf8_bin',
				'comment' => 'Voucher / Receipt'
			),
			'fields' => Array(
				Array(
					'name' => 'id',
					'type' => 'int'
				),
				Array(
					'name' => 'voucherId',
					'type' => 'int',
					'comment' => 'Voucher'
				),
				Array(
					'name' => 'accountId',
					'type' => 'int',
					'comment' => 'Account'
				)
			),
			'associations' => Array(
				Array(
					'type' => 'belongsTo',
					'model' => 'App.model.account.Voucher',
					'foreignKey' => 'voucherId',
					'setterName' => 'setVoucher',
					'getterName' => 'getVoucher'
				)
			)
		));


		//$array = array(
		////	'id' => 4,
		//	'date' => date('Y-m-d'),
		//	'encounterId' => 2,
		//	'accountId' => 4,
		//	'journalId' => 10
		//);
		//print $t::store($array);
		//print '<br>';
		//print $t::$rowsAffected;
		//print '<br>';
		//print $t::$lastInsertId;
//		print '<br>';
//		print_r($t::load($params)->fetch());    					// fetch all
//		print '<br>';
//		print_r($t::load(5)->fetchAll());    				// fetch all
		//print '<br>';
		//print '<br>';
		//$t::load(5);    						            // fetch all columns where id = 5
		//print '<br>';
		//print '<br>';
		//$t::load(5,array('id','name'));    			    // fetch id and name where id = 5
		//print '<br>';
		//print '<br>';
		//$t::load(array('voucherId'=>3));    			    // fetch all columns where voucherId = 5
		//print '<br>';
		//print '<br>';
		//$t::load(array('voucherId'=>3),array('id','name'));	// fetch id and name where voucherId = 5
		//print '<br>';
		//print '<br>';
		//$t::load(array('voucherId' =>3,'userId' =>5),array('id','name'));	// fetch id and name where voucherId = 5
		//print '<br>';
		//print '<br>';
		//$t::load(array('voucherId'=>3, 'OR', 'userId'=>7),array('id','name'));	// fetch id and name where voucherId = 5


		//
		//print '<br>';
		//print_r($t::$model->table->name);


		//	SELECT `id`,`name` FROM `accvoucher` WHERE `voucherId`='3' AND `userId`='7' OR (`hello`='4' AND `hello2`='5' )

		return array();
	}
}

