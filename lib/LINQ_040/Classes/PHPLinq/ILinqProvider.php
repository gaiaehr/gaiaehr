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


/**
 * PHPLinq_ILinqProvider
 *
 * @category   PHPLinq
 * @package    PHPLinq
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 */
interface PHPLinq_ILinqProvider {
	/**
	 * Can this provider type handle data in $source?
	 *
	 * @param mixed $source
	 * @return bool
	 */
	public static function handles($source);
	
	/**
	 * Create a new class instance
	 *
	 * @param string $name
	 * @param PHPLinq_ILinqProvider $parentProvider Optional parent PHPLinq_ILinqProvider instance, used with join conditions
	 * @return PHPLinq_ILinqProvider
	 */
	public function __construct($name, PHPLinq_ILinqProvider $parentProvider = null);
	
	/**
	 * Class destructor
	 */
	public function __destruct();

	/**
	 * Is object destructing?
	 *
	 * @return bool
	 */
	public function __isDestructing();

	/**
	 * Get join condition
	 *
	 * @return PHPLinq_Expression
	 */
	public function getJoinCondition();
	
	/**
	 * Add child provider, used with joins
	 *
	 * @param PHPLinq_ILinqProvider $provider
	 */
	public function addChildProvider(PHPLinq_ILinqProvider $provider);
	
	/**
	 * Retrieve "from" name
	 *
	 * @return string
	 */
	public function getFromName();
	
	/**
	 * Retrieve data in data source
	 *
	 * @return mixed
	 */
	public function getSource();
	
	/**
	 * Set source of data
	 *
	 * @param mixed $source
	 * @return PHPLinq_ILinqProvider
	 */
	public function in($source);
	
	/**
	 * Select
	 *
	 * @param  string	$expression	Expression which creates a resulting element
	 * @return mixed
	 */
	public function select($expression = null);
	
	/*
	public function selectMany();
	*/
	
	/**
	 * Where
	 *
	 * @param  string	$expression	Expression checking if an element should be contained
	 * @return PHPLinq_ILinqProvider
	 */
	public function where($expression);
	
	/**
	 * Join
	 *
	 * @param string $name
	 * @return PHPLinq_Initiator
	 */
	public function join($name);
	
	/**
	 * On
	 *
	 * @param  string	$expression	Expression representing join condition
	 * @return PHPLinq_ILinqProvider
	 */
	public function on($expression);
	
	/*
	public function groupJoin();
	*/
	
	/**
	 * Take $n elements
	 *
	 * @param int $n
	 * @return PHPLinq_ILinqProvider
	 */
	public function take($n);
	
	/**
	 * Take elements while $expression evaluates to true
	 *
	 * @param  string	$expression	Expression to evaluate
	 * @return PHPLinq_ILinqProvider
	 */
	public function takeWhile($expression);
	
	/**
	 * Skip $n elements
	 *
	 * @param int $n
	 * @return PHPLinq_ILinqProvider
	 */
	public function skip($n);
	
	/**
	 * Skip elements while $expression evaluates to true
	 *
	 * @param  string	$expression	Expression to evaluate
	 * @return PHPLinq_ILinqProvider
	 */
	public function skipWhile($expression);
	
	/**
	 * Select the elements of a certain type
	 *
	 * @param string $type	Type name
	 */
	public function ofType($type);
	
	/**
	 * Concatenate data
	 *
	 * @param mixed $source
	 * @return PHPLinq_ILinqProvider
	 */
	public function concat($source);
	
	/**
	 * OrderBy
	 *
	 * @param  string	$expression	Expression to order elements by
	 * @param  string	$comparer	Comparer function (taking 2 arguments, returning -1, 0, 1)
	 * @return PHPLinq_ILinqProvider
	 */
	public function orderBy($expression, $comparer = null);
	
	/**
	 * OrderByDescending
	 *
	 * @param  string	$expression	Expression to order elements by
	 * @param  string	$comparer	Comparer function (taking 2 arguments, returning -1, 0, 1)
	 * @return PHPLinq_ILinqProvider
	 */
	public function orderByDescending($expression, $comparer = null);
	
	/**
	 * ThenBy
	 *
	 * @param  string	$expression	Expression to order elements by
	 * @param  string	$comparer	Comparer function (taking 2 arguments, returning -1, 0, 1)
	 * @return PHPLinq_ILinqProvider
	 */
	public function thenBy($expression, $comparer = null);
	
	/**
	 * ThenByDescending
	 *
	 * @param  string	$expression	Expression to order elements by
	 * @param  string	$comparer	Comparer function (taking 2 arguments, returning -1, 0, 1)
	 * @return PHPLinq_ILinqProvider
	 */
	public function thenByDescending($expression, $comparer = null);
	
	/**
	 * Reverse elements
	 *
	 * @param bool $preserveKeys Preserve keys?
	 * @return PHPLinq_ILinqProvider
	 */
	public function reverse($preserveKeys = null);
	
	/*
	public function groupBy(); 
	*/
	
	/**
	 * Distinct
	 *
	 * @param  string	$expression	Expression to retrieve the key value. 
	 * @return PHPLinq_ILinqProvider
	 */
	public function distinct($expression); 
	
	/*
	public function union();
	public function intersect();
	public function except(); 
	public function equalAll();
	*/
	
	/**
	 * First
	 *
	 * @param  string	$expression	Expression which creates a resulting element
	 * @return mixed
	 */
	public function first($expression = null);
	
	/**
	 * FirstOrDefault 
	 *
	 * @param  string	$expression	Expression which creates a resulting element
	 * @param  mixed	$defaultValue Default value to return if nothing is found
	 * @return mixed
	 */
	public function firstOrDefault ($expression = null, $defaultValue = null);
	
	/**
	 * Last
	 *
	 * @param  string	$expression	Expression which creates a resulting element
	 * @return mixed
	 */
	public function last($expression = null);
	
	/**
	 * LastOrDefault 
	 *
	 * @param  string	$expression	Expression which creates a resulting element
	 * @param  mixed	$defaultValue Default value to return if nothing is found
	 * @return mixed
	 */
	public function lastOrDefault ($expression = null, $defaultValue = null);
	
	/**
	 * Single
	 *
	 * @param  string	$expression	Expression which creates a resulting element
	 * @return mixed
	 */
	public function single($expression = null);
	
	/**
	 * SingleOrDefault 
	 *
	 * @param  string	$expression	Expression which creates a resulting element
	 * @param  mixed	$defaultValue Default value to return if nothing is found
	 * @return mixed
	 */
	public function singleOrDefault ($expression = null, $defaultValue = null);
	
	/**
	 * Element at index
	 *
	 * @param mixed $index Index
	 * @return mixed Element at $index
	 */
	public function elementAt($index = null);
	
	/**
	 * Element at index or default
	 *
	 * @param mixed $index Index
	 * @param  mixed $defaultValue Default value to return if nothing is found
	 * @return mixed Element at $index
	 */
	public function elementAtOrDefault($index = null, $defaultValue = null);
	
	/**
	 * Any
	 *
	 * @param  string	$expression	Expression checking if an element is contained
	 * @return boolean
	 */
	public function any($expression);
	
	/**
	 * All
	 *
	 * @param  string	$expression	Expression checking if an all elements are contained
	 * @return boolean
	 */
	public function all($expression);

	/**
	 * Contains
	 *
	 * @param mixed $element Is the $element contained?
	 * @return boolean
	 */
	public function contains($element);
	
	/**
	 * Count elements
	 *
	 * @return int Element count
	 */
	public function count();
	
	/**
	 * Sum elements
	 *
	 * @return mixed Sum of elements
	 */
	public function sum();
	
	/**
	 * Minimum of elements
	 *
	 * @return mixed Minimum of elements
	 */
	public function min();
	
	/**
	 * Maximum of elements
	 *
	 * @return mixed Maximum of elements
	 */
	public function max();
	
	/**
	 * Average of elements
	 *
	 * @return mixed Average of elements
	 */
	public function average();

	/**
	 * Aggregate
	 *
	 * @param int $seed	Seed
	 * @param string $expression	Expression defining the aggregate
	 * @return mixed aggregate
	 */
	public function aggregate($seed = 0, $expression);
}
