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
$_SESSION['site']['flops'] = 0;
class FileManager
{
	public $workingDir;
	public $workingDirName;
	public $tempDir;
	public $fileName;
	public $fileExtension;
	public $fileError = '';
	public  $src;

	function __construct()
	{
		$this->db = new dbHelper();
		$this->tempDir = $_SESSION['site']['root'] . '/temp/';

		//$this->setTempDir();
		//$this->setTempDirAvailableName();
		return;
	}

	public function cleanUp()
	{

		if(is_dir($this->workingDir)){
			$this->deleteWorkingDir();
		}
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
		if($this->extractFileToTempDir($file['filePath']['tmp_name'])) {
			return true;
		} else {
			return false;
		}
	}

	public function extractFileToTempDir($fileSrc)
	{
		$zip = new ZipArchive();
		if($this->setWorkingDir()){
			if ($zip->open($fileSrc) === TRUE) {
				if (!($zip->extractTo($this->workingDir))) {
			        return false;
			    }
				$zip->close();
				return $this->workingDir;
			}else{
				return false;
			}
		}else{
			$this->fileError = 'Could not create working directory';
			return false;
		}

	}

	public function extractFileToDir($fileSrc, $dir, $deleteSrcFile = false)
	{
		$zip = new ZipArchive();
		if ($zip->open($fileSrc) === TRUE) {
			$zip->extractTo($dir);
			$zip->close();
			if($deleteSrcFile){
				$this->deleteFileBySrc($fileSrc);
			}
			return true;
		}else{
			return false;
		}
	}



	private function setWorkingDir()
	{
		$workingDir = $_SESSION['site']['root'] . '/temp/'. $this->getTempDirAvailableName();
		if(is_dir($workingDir) || mkdir($workingDir, 0777, true)) {
			chmod($workingDir, 0777);
			$this->workingDir = $workingDir;
			return true;
		} else {
			return false;
		}
	}

	private function getTempDirAvailableName()
	{
		$name = time();
		while(file_exists($this->tempDir . $name)) {
			$name = time();
		}
		$this->workingDirName = $name;
		return $this->workingDirName;
	}

	public function getWorkingDirName()
	{
		return $this->workingDirName;
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

	private function deleteWorkingDir()
	{
		return $this->rmdir_recursive($this->workingDir);
	}

	public function deleteFileBySrc($src)
	{
		return $this->rmdir_recursive($src);
	}

	public function rmdir_recursive($dir) {
	    $files = scandir($dir);
	    array_shift($files);    // remove '.' from array
	    array_shift($files);    // remove '..' from array
	    foreach ($files as $file) {
	        $file = $dir . '/' . $file;
	        if (is_dir($file)) {
	            $this->rmdir_recursive($file);
		    continue;
	        }
	        unlink($file);
	    }
	    rmdir($dir);
		return true;
	}
}





