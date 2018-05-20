<?php

/**
 * Table.php
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

// ロードしていないクラスがあればロードする
spl_autoload_register(function ($class) {
	require_once($class . '.php');
});

// 列数は固定で、最初のコンストラクタでのみ設定が出来ます。
class Table {

	private $table;
	
	private $columnCount;
	
	function __construct($columnCount) {
		$this->table = new ArrayList();
		$this->columnCount = $columnCount;
	}
	
	function __destruct() {
		unset( $this->table );
		unset( $this->columnCount );
	}
	
	public function __toString(){
		$output = "";
		$length = $this->getRowCount();
		for ( $i = 0; $i < $length ; $i++ ) {
			$list = $this->getRow($i);
			for ( $j = 0; $j < $list->size() ; $j++ ) {
				$output .= $list->get($j);
				if ($j != $list->size() - 1) {
					$output .= " , ";
				}
			}
			$output .= "<br />";
		}
		return($output);
	}
	
	private function isRow($rowData) {
		if(get_class($rowData) == "ArrayList") {
			if($rowData->size() == $this->columnCount) {
				return(TRUE);
			}
		}
		return(FALSE);
	}
	
	public function getRowCount() {
		return($this->table->size());
	}
	
	public function getColumnCount() {
		return($this->columnCount);
	}
	
	public function getValueAt($row, $column) {
		$list = $this->table->get($row);
		if($this->isRow($list)) {
			return($list->get($column));
		}
	}
	
	public function setValueAt($value, $row, $column) {
		$list = $this->table->get($row);
		if($this->isRow($list)) {
			$list->set($column, $value);
		}
	}
	
	public function insertRow($row, $rowData) {
		if($this->isRow($rowData)) {
			$this->table->add($row, $rowData);
			return(TRUE);
		}
		else {
			return(FALSE);
		}
	}
	
	public function getRow($row) {
		return($this->table->get($row));
	}
	
	public function setRow($row, $rowData) {
		$this->table->set($row, $rowData);
	}
	
	public function addRow($rowData) {
		if($this->isRow($rowData)) {
			$this->table->add($rowData);
			return(TRUE);
		}
		else {
			return(FALSE);
		}
	}
	
	public function removeRow($row) {
		$this->table->remove($row);
	}
	
	public function getSortTable($column, $type) {
		$table = clone $this;
		$table->table->sort($type, $column);
		return($table);
	}
	
}

/*

sample

$s = 3;
$t = new Table($s);
$x = 0;

for($i = 0;$i < 10;$i++) {
	$v = new ArrayList();
	for($j = 0;$j < $s;$j++) {
		//$v->add($i * 100 + $x++);
		$v->add(100 * rand());
	}
	$t->addRow($v);
}

$t->setValueAt(100,2,1);
$t->removeRow(4);

echo $t->getRowCount() . "  " . $t->getColumnCount();

echo "<br>";

echo $t;

echo "<br>";

echo $t->getSortTable(2, 1);
*/


?>