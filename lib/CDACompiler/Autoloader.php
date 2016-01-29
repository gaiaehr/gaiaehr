<?php
/**
 * Class Autoloader
 * This Class will register an AutoLoad Class procedure, this to save time in code, also load the
 * classes dynamically this saves processing power and time.
 *
 */
class Autoloader
{
    /**
     * @param $className
     * @return bool
     */
    static public function loader($className) {
        $filename = "./".str_replace("\\", "/", $className) . ".php";
        if (file_exists($filename)) {
            require_once $filename;
            if (class_exists($className)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
spl_autoload_register('Autoloader::loader');
