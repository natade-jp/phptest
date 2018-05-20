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

// 排他処理を管理
class ExclusiveControl {

	private $id;
	
	private $lock;
	
	function __construct($id) {
		$this->id		= $id;
		$this->lock		= FALSE;
	}
	
	function __destruct() {
		$this->EI();	//終了時にここを呼ばれる。多分EIをしていないとエラー発生
		unset( $this->id );
		unset( $this->lock );
	}
	
	function DI() {
		$bakayoke = 0;
		$lock = FALSE;
		while($lock == FALSE) {
			$lock = fopen($this->id, 'w+');
			if($lock != FALSE) {
				if(flock($lock, LOCK_EX | LOCK_NB) == FALSE) {
					fclose($lock);
					$lock = FALSE;
				}
			}
			if($lock == FALSE) {
				$bakayoke++;
				if($bakayoke > 6) {	// 3sec
					return(FALSE);
				}
				usleep(500 * 1000); // 500ms
			}
		}
		$this->lock = $lock;
		return(TRUE);
	}
	
	function EI() {
		if($this->lock == FALSE) {
			return(FALSE);
		}
		flock($this->lock, LOCK_UN);
		fclose($this->lock);
		unlink($this->id);
		$this->lock = FALSE;
		return(TRUE);
	}
	
}

/*
//sample

$lock = new ExclusiveControl("lock");
if($lock->DI()) {
	
	echo "test";

	$lock->EI();
}
*/


?>