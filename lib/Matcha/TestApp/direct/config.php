<?php

function getDirActions($dir, $exclude = array()){
	$output = array();

	if ($handle = opendir($dir)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != '.' && $entry != '..') {
				if(preg_match('/.*\.php/', $entry) && !in_array($entry, $exclude)){
					try{
						include_once ("$dir/$entry");
						$cls = str_replace('.php', '', $entry);
						$class = new ReflectionClass($cls);
						$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
						$buffer = array();
						foreach ($methods as $method) {
							if(!preg_match('/^__/', $method->getName())){

								$buffer[$method->getName()] =  array(
									'len' => $method->getNumberOfParameters(),
									'formHandler' => preg_match('/Form$/', $method->getName())
								);
							}
						}
						unset($class);
						$output[$cls]['methods'] = $buffer;
					}catch (Exception $e){

					}
				}
			}
		}
		closedir($handle);
	}
	return $output;
}

$dir = dirname(dirname(__FILE__)) . '/data';
$exclude = array(

);

$API = getDirActions($dir, $exclude);