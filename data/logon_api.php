<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

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

require('logon_config.php');
header('Content-Type: text/javascript');

// convert API config to Ext.Direct spec
// This is a straight forward code.
// Suggestions the foreach can be replaced by LINQ.
$actions = array();
foreach ($API as $aname=>&$a)
{
	$methods = array();
	foreach ($a['methods'] as $mname=>&$m)
    {
	    if (isset($m['len']))
        {
		    $md = array(
			    'name'=>$mname,
			    'len'=>$m['len']
		    );
		}
        else
        {
		    $md = array(
		        'name'=>$mname,
		        'params'=>$m['params']
		    );
		}
		if (isset($m['formHandler']) && $m['formHandler'])
        {
			$md['formHandler'] = true;
		}
		$methods[] = $md;
	}
	$actions[$aname] = $methods;
}

$cfg = array(
    'url'=>'data/logon_router.php',
    'type'=>'remoting',
	'actions'=>$actions
);

echo 'Ext.ns("App.data"); App.data.REMOTING_API = ';

echo json_encode($cfg);
echo ';';