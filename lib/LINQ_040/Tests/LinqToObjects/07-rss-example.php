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

/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../Classes/' . PATH_SEPARATOR . './resources/');

/** PHPLinq_LinqToObjects */
require_once 'PHPLinq/LinqToObjects.php';

// Create data source
$rssFeed = simplexml_load_string(file_get_contents('http://blog.maartenballiauw.be/syndication.axd'));
$result = from('$item')->in($rssFeed->xpath('//channel/item'))
			->orderByDescending('$item => strtotime((string)$item->pubDate)')
			->take(2)
			->select('new {
							"PostTitle" => (string)$item->title,
							"PostAuthor" => (string)$item->author,
							"MetaData" => new {
												"Url" => (string)$item->link,
												"Guid" => (string)$item->guid,
												"PostDate" => strtotime((string)$item->pubDate)
										  }
					  }');
				
print_r($result);