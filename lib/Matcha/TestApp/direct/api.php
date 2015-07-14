<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 12/10/13
 * Time: 9:33 AM
 */

ob_start();

function getDirActions($dir, $exclude = array()){
	$output = array();

	if ($handle = opendir($dir)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != '.' && $entry != '..') {
				if(preg_match('/.*\.php/', $entry) && !in_array($entry, $exclude)){
					try{
						include_once ($dir . '/' . $entry);
						$cls = str_replace('.php', '', $entry);
                                        
						$class = new ReflectionClass($cls);                
						$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
						$buffer = array();
                        
						foreach ($methods as $method) {
							if(!preg_match('/^__/', $method->getName())){

								$buffer[] =  array(
									'name' => $method->getName(),
									'len' => $method->getNumberOfParameters() > 0 ? 1 : 0,
									'formHandler' => preg_match('/Form$/', $method->getName())
								);
							}
						}
						unset($class);
						$output[$cls] = $buffer;
					}catch (Exception $e){

					}
				}
			}
		}
		closedir($handle);
	}
    
	return $output;
}

function getRemotingAPI($module = null, $exclude = array()){

	$url = (!isset($module) ? 'direct/router.php' : "direct/router.php?module=$module");
	$dir = (!isset($module) ? '../data' : "../modules/$module/data");

	return json_encode(array(
		'url' => $url,
		'type' => 'remoting',
		'actions' => getDirActions($dir, $exclude),
        'namespace' => 'Remote',
		'timeout' => 3600
	));
}
ob_end_clean();

// array of classes to exclude
$exclude = array(

);

header('Content-Type: text/javascript');
print 'Ext.app.REMOTING_API = ' . getRemotingAPI(null, $exclude) . ';';