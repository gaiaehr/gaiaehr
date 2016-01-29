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

if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

require_once 'HTTP/Request2.php';

class Languages
{
	public function getLanguageFromTransifex($lang)
	{
		$request = new HTTP_Request2('http://vela1606:pass@www.transifex.net/api/2/project/gaiaehr/resource/All/translation/' . $lang . '/?file', HTTP_Request2::METHOD_GET);
		$r = $request -> send() -> getBody();
		$r = str_replace('<?php $LANG = ', '', $r);
		$r = str_replace('array(', '', $r);
		$r = str_replace('=>', ':', $r);
		$r = str_replace(');', '', $r);
		$r = str_replace('\'', '"', $r);
		$r = '{"lang":{' . $r . "}}";

		return json_decode($r, true);
	}

	public function getLanguagesFromTransifex()
	{
		$request = new HTTP_Request2('http://vela1606:pass@www.transifex.net/api/2/project/gaiaehr/resource/All/?details', HTTP_Request2::METHOD_GET);
		$r = $request -> send() -> getBody();
		$r = json_decode($r, true);
		return array('langs' => $r['available_languages']);
	}

}
