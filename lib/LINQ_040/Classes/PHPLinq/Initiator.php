<?php
/**
 * PHPLinq
 *
 * Copyright (c) 2008 - 2009 PHPLinq
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPLinq
 * @package    PHPLinq
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 * @license    http://www.gnu.org/licenses/lgpl.txt	LGPL
 * @version    0.4.0, 2009-01-27
 */


/** PHPLinq */
require_once('PHPLinq.php');

/** PHPLinq_ILinqProvider */
require_once('PHPLinq/ILinqProvider.php');


/**
 * PHPLinq_Initiator
 *
 * @category   PHPLinq
 * @package    PHPLinq
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 */
class PHPLinq_Initiator {
	/**
	 * Registered PHPLinq_ILinqProvider classes
	 *
	 * @var string[]
	 */
	private static $_registeredProviders = array();
	
	/**
	 * Register PHPLinq_ILinqProvider class
	 *
	 * @param string $class Class name implementing PHPLinq_ILinqProvider
	 * @param string $reference Path reference (for inclusion)
	 */
	public static function registerProvider($class = null, $reference = null) {
		if (!is_null($class) && $class != '') {
			// Require code
			if (is_null($reference) || $reference == '') {
				$reference = str_replace('_', '/', $class);
				$reference .= '.php';
			}
			require_once($reference);
			
			// Add registered PHPLinq_ILinqProvider
			self::$_registeredProviders[] = $class;
		}
	}
	
	/**
	 * Default variable name
	 *
	 * @var string
	 */
	private $_from = '';
	
	/**
	 * Parent PHPLinq_ILinqProvider instance, used with join conditions
	 *
	 * @var PHPLinq_ILinqProvider
	 */
	private $_parentProvider = null;
	
	/**
	 * Create a new class instance
	 *
	 * @param string $name
	 * @param PHPLinq_ILinqProvider $parentProvider Optional parent PHPLinq_ILinqProvider instance, used with join conditions
	 * @return PHPLinq_Initiator
	 */
	public function __construct($name, PHPLinq_ILinqProvider $parentProvider = null) {
		$this->_from = $name;
		$this->_parentProvider = $parentProvider;
		return $this;
	}
	
	/**
	 * Set source of data
	 *
	 * @param mixed $source
	 * @return PHPLinq_ILinqProvider
	 */
	public function in($source) {
		// Search correct provider
		foreach (self::$_registeredProviders as $provider) {
			if (call_user_func(array($provider, 'handles'), $source)) {
				$returnValue = new $provider($this->_from, $this->_parentProvider);
				return $returnValue->in($source);
			}
		}
		
		// No provider found...
		throw new PHPLinq_Exception("No valid PHPLinq_ILinqProvider found for the specified data source.");
	}
}
