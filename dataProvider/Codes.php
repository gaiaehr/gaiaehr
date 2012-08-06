<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
class Codes
{
	private $db;

	private $tempDir;
	private $fileName;
	private $src;

	private $codeType;

	function __construct()
	{
		$this->db = new dbHelper();
		$this->setTempDir();
		$this->setAvailableName();
		$this->setSrc();
		return;
	}

	public function updateCodesWithUploadFile($params, $file)
	{
		$this->codeType = $params->codeType;

		return array('success' => $this->uploadFile($file), 'params' => $params, 'file' => $file);
	}

	/******************************************************************************************************************/
	/**  Code Database Helper Methods  ********************************************************************************/
	/******************************************************************************************************************/
	private function getCurrentCodeVersion()
	{
		$this->codeType;
		return;
	}

	private function getNewCodeVersion()
	{
		$this->codeType;
		return;
	}

	/******************************************************************************************************************/
	/**  File Helper Methods  *****************************************************************************************/
	/******************************************************************************************************************/
	private function uploadFile($file)
	{
		if(move_uploaded_file($file['filePath']['tmp_name'], $this->src)) {
			return true;
		} else {
			return false;
		}
	}

	private function setTempDir()
	{
		$tempDir = $_SESSION['site']['root'] . '/temp/';
		if(is_dir($tempDir) || mkdir($tempDir, 0777, true)) {
			chmod($tempDir, 0777);
			$this->tempDir = $tempDir;
			return true;
		} else {
			return false;
		}
	}

	private function setAvailableName()
	{
		$name = time();
		while(file_exists($this->tempDir . $name)) {
			$name = time();
		}
		$this->fileName = $name;
		return;
	}

	private function setSrc()
	{
		$this->src = $this->tempDir . $this->fileName;
		return;
	}

	private function deleteTempFile()
	{
		return unlink($this->src);
	}

}





