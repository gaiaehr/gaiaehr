<?php
/*
 GaiaEHR (Electronic Health Records)
 dataProvider_unitTest.php
 Unit Test for dataProvider
 Copyright (C) 2012 Gino Rivera Falu

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


session_name('GaiaEHR');
session_start();
session_cache_limiter('private');
define('_GaiaEXEC', 1);

include_once ('registry.php');
include_once ('sites/default/conf.php');

$_SESSION['user']['id'] = 94;

// -- Unit Test for User dataProvider
// ********************************************************
include_once ($_SESSION['root'] . '/dataProvider/User.php');

echo '<pre>';
$u = new User();
//R::debug(true);

// -- 1
print_r( $u->getCurrentUserTitleLastName() );
echo '<br>';
// -- 2
$request = new stdClass;
$request->start = 0;
$request->limit = 10;
print_r( $u->getUsers($request) );
echo '<br>';
// -- 3
print_r( $u->getUserNameById(94) );
echo '<br>';
// -- 4
print_r( $u->getUserFullNameById(94) );
echo '<br>';
// -- 5
print_r( $u->getCurrentUserData() );
echo '<br>';
// -- 6
print_r( $u->getCurrentUserBasicData() );
echo '<br>';
echo '</pre>';