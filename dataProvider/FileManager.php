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
class FileManager
{
	public $tempDir;
	public $fileName;
	public $fileExtension;
	public  $src;

	function __construct()
	{
		$this->db = new dbHelper();
		$this->setTempDir();
		$this->setTempDirAvailableName();
		return;
	}

	public function moveUploadedFileToTempDir($file)
	{
		$this->setFileExtensionFromFile($file['filePath']['name']);
		$this->setSrc();
		if(move_uploaded_file($file['filePath']['tmp_name'], $this->src)) {
			return true;
		} else {
			return false;
		}
	}

	public function moveUploadedFileToDir($file, $dir)
	{
		if(move_uploaded_file($file['filePath']['tmp_name'], $dir.$this->setFileExtensionFromFile($file['filePath']['name']))) {
			return true;
		} else {
			return false;
		}
	}

	public function extractUploadedFileToTempDir($file)
	{
		$this->setSrc();
		if($this->extractFileToTemp($file['filePath']['tmp_name'])) {
			return true;
		} else {
			return false;
		}
	}

	public function extractFileToTemp($fileSrc)
	{
		$zip = new ZipArchive();
		if ($zip->open($fileSrc) === TRUE) {
			$zip->extractTo($this->tempDir);
			$zip->close();
			$this->deleteFileBySrc($this->src);
			return true;
		}else{
			return false;
		}
	}

	public function extractFileToDir($fileSrc, $dir)
	{
		$zip = new ZipArchive();
		if ($zip->open($fileSrc) === TRUE) {
			$zip->extractTo($dir);
			$zip->close();
			$this->deleteFileBySrc($fileSrc);
			return true;
		}else{
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

	private function setTempDirAvailableName()
	{
		$name = time();
		while(file_exists($this->tempDir . $name)) {
			$name = time();
		}
		$this->fileName = $name;
		return;
	}

	private function setFileExtensionFromFile($fileName)
	{
		$foo = explode('.',$fileName);
		return $this->fileExtension = '.'.end($foo);
	}

	private function setSrc()
	{
		$this->src = $this->tempDir . $this->fileName . $this->fileExtension;
		return;
	}

	private function deleteTempFile()
	{
		return unlink($this->src);
	}

	public function deleteFileBySrc($src)
	{
		return unlink($src);
	}


}





