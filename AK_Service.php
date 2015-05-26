<?php

require_once 'ak_service/AK_Slack.class.php';

class AK_Servcie {
	
	/**
	 * 通信結果
	 * @var string
	 */
	protected $result = NULL;
	public function getResult() {
		return $this -> result;
	}
	
	/**
	 * エラー
	 * @var string
	 */
	protected $error = NULL;
	public function getError() {
		return $this -> error;
	}
	
	/**
	 * ピンポンダッシュフラグ
	 * @var boolean
	 */
	protected $pinponDashFlg = FALSE;
	public function setPinponDashFlg( $pinponDashFlg ) {
		$this -> pinponDashFlg = $pinponDashFlg;
	}
	
	
	//------------------------------------------- static --------------------------------------------
	
	/**
	 * インスタンス配列
	 * @var array
	 */
	private static $instanceArray = array();
	
	public static function getInstance() {
		
		$class = static::class;
		
		if ( array_key_exists( $class, self::$instanceArray ) === FALSE ) {
			self::$instanceArray[$class] = new $class();
		} else {
			;
		}
		
		return self::$instanceArray[$class];
		
	}
	
}