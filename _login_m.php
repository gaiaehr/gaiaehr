<?php
/**
 * GaiaEHR
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('_GaiaEXEC'))
	die('No direct access allowed.');

?>
<html>
	<head>
		<title>GaiaEHR Logon Screen</title>
		<link rel="stylesheet" href="lib/<?php echo $_SESSION['dir']['touch']; ?>/resources/css/sencha-touch.css" type="text/css">
		<script type="text/javascript" src="lib/<?php echo $_SESSION['dir']['touch']; ?>/sencha-touch-debug.js"></script>
		<script type="text/javascript" src="login/login_mobile.js"></script>
	</head>
	<body></body>
</html>