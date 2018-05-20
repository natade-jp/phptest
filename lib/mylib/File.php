<?php

/**
 * File.php
 * 
 * AUTHOR:
 *  natade (http://twitter.com/natadea)
 * 
 * LICENSE:
 *  The zlib/libpng License https://opensource.org/licenses/Zlib
 *
 * DEPENDENT LIBRARIES:
 *  なし
 */

class File {

	private $url;
	
	private $isweb;
	
	function __construct($url) {
		$this->url		= str_replace("\\", "/", $url);
		$this->isweb	= preg_match('/^http[s]?:/', $url) == 1;
	}
	
	function __destruct() {
		unset( $this->url );
		unset( $this->isweb );
	}
	
	public function __toString(){
		return $this->url;
	}
	
	public function getPath() {
		return $this->url;
	}
	
	public function length() {
		return filesize($this->url);
	}
	
	public function exists() {
		return file_exists($this->url);
	}
	
	public function canRead() {
		return is_readable($this->url);
	}
	
	public function canWrite() {
		return is_writable($this->url);
	}
	
	public function delete() {
		return unlink($this->url);
	}
	
	public function isDirectory() {
		if($this->isweb) {
			// 最後が/で終わっている
			return preg_match('/[\/]$/', $this->url) == 1;
		}
		else {
			if($this->exists()) {
				return is_dir($this->url);
			}
			else {
				return false;
			}
		}
	}
	
	public function isFile() {
		if($this->isweb) {
			// 最後が/で終わってない
			return preg_match('/[^\/]$/', $this->url) == 1;
		}
		else {
			if($this->exists()) {
				return !is_dir($this->url);
			}
			else {
				return false;
			}
		}
	}
	
	public function getName() {
		// 最後が/で終わっている
		if(preg_match('/[\/]$/', $this->url) == 1) {
			return "";
		}
		$array = explode("/", $this->url);
		return $array[count($array) - 1];
	}
	
	public function getText() {
		if($this->exists() == false) {
			return false;
		}
		$text = file_get_contents($this->url, false);
		$text = preg_replace('/\r\n|\r/', '\n', $text);
		return $text;
	}
	
	public function setText($text) {
		$text = preg_replace('/\r\n|\r/', '\n', $text);
		file_put_contents($this->url, $text, false);
		return;
	}
	
	public function getCSV() {
		$file = new SplFileObject($this->url, "r");
		$file->setFlags(
			SplFileObject::READ_CSV |
			SplFileObject::SKIP_EMPTY |
			SplFileObject::READ_AHEAD
		);
		$records = [];
		foreach($file as $i => $row) {
			$line = [];
			foreach($row as $j => $col) {
				$line[$j] = $col;
			}
			$records[$i] = $line;
		}
		return $records;
	}
	
	public function setCSV($arraydata) {
		$file = new SplFileObject($this->url, "w");
		$records = [];
		foreach($arraydata as $i => $row) {
			$file->fputcsv($row);
		}
		$file->fflush();
		return;
	}
	
	public function getFileExtension() {
		$extension = strrchr($this->getName(), '.');
		if($extension == false) {
			// フォルダなど
			return false;
		}
		// 拡張子が存在
		$extension = substr($extension, 1);
		return strtolower($extension);
	}
	
}


/*
//sample

//$file = new File("http://aaa/test1.a");
$file1 = new File("abc.csv");
echo json_encode($file1->getCSV());

$file2 = new File("abc2.csv");
$file2->setCSV($file1->getCSV());
*/



?>