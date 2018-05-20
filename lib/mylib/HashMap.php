<?php

/**
 * HashMap.php
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

class HashMap {

	private $size;
	
	private $map;
	
	function __construct() {
		$this->clear();
	}
	
	function __destruct() {
		unset( $this->size );
		unset( $this->map );
	}
	
	public function __toString(){
		$output = "{";
		reset($this->map);
		for($i = 0; $i < $this->size() ; $i++ ) {
			$key = key($this->map);
			$output .= "\"" . $key . "\":" . $this->get($key);
			next($this->map);
			if($i != $this->size() - 1) {
				$output .= " , ";
			}
		}
		$output .= "}<br />\n";
		return($output);
	}

	public function size() {
		return( $this->size );
	}
	
	public function isEmpty() {
		return( $this->size == 0 );
	}
	
	public function containsKey( $key ) {
		// NULLが入っているとfalseになってしまう
		//return(isset($this->map[$key]));
		return(array_key_exists($key, $this->map));
	}
	
	public function containsValue( $value ) {
		return(in_array($value, $this->map));
	}
	
	public function get( $key ) {
		return( $this->map[$key] );
	}

	public function put( $key, $value ) {
		if($this->containsKey($key) == false) {
			$this->size++;
			$this->map[$key] = $value;
			return(null);
		}
		else {
			$output = $this->map[$key];
			$this->map[$key] = $value;
			return($output);
		}
	}
	
	public function remove( $key ) {
		if($this->containsKey($key) == false) {
			return(null);
		}
		else {
			$this->size--;
			$output = $this->map[$key];
			unset($this->map[$key]);
			return($output);
		}
	}
	
	public function putAll( $map ) {
		$this->map = array_merge($this->map, $map->map);
		$this->size = count($this->map);
	}
	
	public function clear() {
		$this->map = array();
		$this->size   = 0;
	}
	
}


/*
//sample 1
$map = new HashMap();
$map->put("test", 3);
echo $map;
$map->put("test",  300);
$map->put("test1", 4);
$map->put("test2", 5);
echo $map;
$map->remove("test1");
$map->remove("test42");
echo $map;
echo $map->get("test") . "<br />";

$map2 = new HashMap();
$map2->put("test4", 1234);
$map->putAll($map2);
echo $map;
*/

?>