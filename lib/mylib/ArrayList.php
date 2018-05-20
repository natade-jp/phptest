<?php

/**
 * ArrayList.php
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

class ArrayList {

	private $size;
	
	private $element;
	
	function __construct() {
		$this->clear();
	}
	
	function __destruct() {
		unset($this->size);
		unset($this->element);
	}
	
	public function __toString(){
		$output = "[";
		for($i = 0; $i < $this->size() ; $i++ ) {
			$output .= $this->get($i);
			if($i != $this->size() - 1) {
				$output .= ", ";
			}
		}
		$output .= "]<br />\n";
		return($output);
	}
	
	public function isEmpty() {
		return($this->size == 0);
	}
	
	public function contains( $object ) {
		return(in_array($object, $this->element));
	}

	public function size() {
		return( $this->size );
		// count() に置き換えても良い
	}
	
	public function clear() {
		$this->element = array();
		$this->size   = 0;
	}
	
	public function get( $index ) {
		return($this->element[$index]);
	}
	
	private function addOverLoad1( $object ) {
		$this->element[$this->size] = $object;
		$this->size++;
		// array_push()  に置き換えても良い
	}
	
	private function addOverLoad2( $index, $object ) {
		for($i = $this->size; $i > $index; $i-- ) {
			$this->element[$i] = $this->element[$i - 1];
		}
		$this->element[$index] = $object;
		$this->size++;
		// array_splice()  に置き換えても良い
	}
	
	public function add() {
		$arguments = func_get_args();
		$arguments_size = count($arguments);
		if($arguments_size == 1) {
			$this->addOverLoad1($arguments[0]);
		}
		else {
			$this->addOverLoad2($arguments[0], $arguments[1]);
		}
	}
	
	public function set( $index, $object ) {
		unset( $this->element[$index] );
		$this->element[$index] = $object;
		// array_splice()  に置き換えても良い
	}
	
	public function remove( $index ) {
		$this->size--;
		for ($i = $index; $i < $this->size; $i++ ) {
			$this->element[$i] = $this->element[$i + 1];
		}
		// 上の部分で参照しているため最初にunsetをすると、再び作成される。
		// 従って、最後にunsetする。
		unset( $this->element[$this->size] );
		// array_splice()  に置き換えても良い
	}
	
	public function addAllOverLoad1( $list ) {
		$size = $list->size;
		for($i = 0; $i < $size; $i++) {
			$this->element[$this->size++] = $list->element[$i];
		}
	}
	
	public function addAllOverLoad2( $index, $list ) {
		if($this == $list) {
			$list = clone $list;
		}
		$size = $this->size - $index;
		$target_i = $this->size + $list->size - 1;
		$source_i = $this->size - 1;
		for($i = 0; $i < $size ; $i++ ) {
			$this->element[$target_i--] = $this->element[$source_i--];
		}
		$size = $list->size;
		for($i = 0; $i < $size; $i++) {
			$this->element[$index++] = $list->element[$i];
		}
		$this->size += $list->size;
	}
	
	public function addAll() {
		$arguments = func_get_args();
		$arguments_size = count($arguments);
		if($arguments_size == 1) {
			$this->addAllOverLoad1($arguments[0]);
		}
		else {
			$this->addAllOverLoad2($arguments[0], $arguments[1]);
		}
	}
	
	// マージソート(安定ソート)
	private function usort_merge(&$array, $first, $last ,$cmp_function) {
		if($first < $last) {
			$middle = (int)(($first + $last) / 2);
			$this->usort_merge($array, $first, $middle, $cmp_function);
			$this->usort_merge($array, $middle + 1, $last, $cmp_function);
			$p = 0;
			for($i = $first; $i <= $middle; $i++) {
				$temp[$p++] = $array[$i];
			}
			$i = $middle + 1;
			$j = 0;
			$k = $first;
			while(($i <= $last) && ($j < $p)) {
				if(call_user_func($cmp_function, $array[$i], $temp[$j])) {
					$array[$k++] = $temp[$j++];
				}
				else {
					$array[$k++] = $array[$i++];
				}
			}
			while($j < $p) {
				$array[$k++] = $temp[$j++];
			}
		}
		return(true);
	}
	
	// 安定ソート
	private function usort_stable(&$array, $cmp_function) {
		$this->usort_merge($array, 0, count($array) - 1, $cmp_function);
		return(true);
	}
	
	/*
		type      ... 0 ... 昇順, 1 ... 降順
		point     ... どの位置を基準にソートするか
		pointは通常気にする必要がない。
		Arrayを何重にも繋げて、2次元配列にした場合などにどの列を基準にソートするか。
		指定するときに利用する。詳しくは、Tableクラスの中のgetSortTable()を参照
	*/
	public function sort() {
		$arguments = func_get_args();
		$arguments_size = count($arguments);
		// デフォルトは昇順
		if($arguments_size == 0) {
			$type = 0;
		}
		else {
			$type = $arguments[0];
		}
		// デフォルトは1次元
		$point = array();
		if($arguments_size > 1) {
			
			for($i = 1,$j = 0;$i < $arguments_size;) {
				$point[$j++] = $arguments[$i++];
			}
		}
		$pointsize = $arguments_size - 1;
		// 比較用無名関数
		// ソートへ
		$this->usort_stable($this->element, function ( $a, $b ) use( $type, $point, $pointsize ) {
			for($i = 0;$i < $pointsize;$i++) {
				$a = $a->get($point[$i]);
				$b = $b->get($point[$i]);
			}
			if($type == 0) {
				return($a >= $b);
			}
			else {
				return($b >= $a);
			}
		});
	}
	
}

/*
//sample 1

$list1 = new ArrayList();
for($i = 0;$i < 3; $i++) {
	$list1->add(rand());
}
echo $list1;


$list2 = new ArrayList();
for($i = 0;$i < 3; $i++) {
	$list2->add(rand());
}
echo $list2;

$list1->addAll($list2);
echo $list1;
*/

/*
//sample 2
$list = new ArrayList();

for($i = 0;$i < 10000; $i++) {
	$list->add(rand());
}

//echo $list;
$startTime = microtime(true);
$list->sort();
$endTime = microtime(true);
echo number_format($endTime - $startTime, 7) . "ms<hr />";
//echo $list;
*/

/*

//sample 3

$list = new ArrayList();

$list->add(1);
$list->add(2);
$list->add(3);
$list->add(4);

$list->add(0, 100);
$list->remove(3);


echo $list;

$list->sort();

echo "<br>" . $list;

*/

?>