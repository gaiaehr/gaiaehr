<?php
/*
 GaiaEHR (Electronic Health Records)
 WebCamImgHandler.php
 Web Camera Image Handler dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
$img = $_SESSION['site']['path'] . '/patients/' . $_SESSION['patient']['pid'] . '/patientPhotoId.jpg';
$result = file_put_contents($img, file_get_contents('php://input'));

if (!$result)
{
	print '{"success":false}';
	exit();
}
else
{
	print '{"success":true, "url":"' . $img . '"}';
}
