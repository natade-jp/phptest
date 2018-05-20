<?php

/**
 * ExclusiveControl.php
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
 
class AutoClassLoader {

	private $classpath;
	private $autoload;
	
	function __construct() {
		$this->classpath = [];
		$this->autoload = function($classname) {
			for($i = 0; $i < count($this->classpath) ; $i++ ) {
				$url = $this->classpath[$i] . $classname . ".php";
				if(file_exists($url)) {
					require_once($url);
					break;
				}
			}
		};
		spl_autoload_register($this->autoload);
	}
	
	function __destruct() {
		spl_autoload_unregister($this->autoload);
		unset($this->classpath);
		unset($this->autoload);
	}
	
	public function add( $path ) {
		array_push($this->classpath, $path);
	}
}

?>