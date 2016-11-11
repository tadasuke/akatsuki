<?php

require_once 'ak_service/AK_Slack.class.php';
require_once 'ak_service/AK_ChatWork.class.php';

class AK_Service {
	
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

	/**
	 * チャンネル名
	 * @var string
	 */
	protected $channel = NULL;

	public function setChannel( $channel )
	{
		$this->channel = $channel;
	}

	public function getChannel()
	{
		return $this->channel;
	}

	/**
	 * リクエストヘッダ配列
	 * @var array
	 */
	protected $requestHeaderArray = [];

	/**
	 * @param array $requestHeaderArray
	 */
	public function setRequestHeaderArray( $requestHeaderArray )
	{
		$this->requestHeaderArray = $requestHeaderArray;
	}

	/**
	 * @return array
	 */
	public function getRequestHeaderArray()
	{
		return $this->requestHeaderArray;
	}

	/**
	 * リクエストヘッダ配列を追加
	 * @param string $header
	 */
	public function addRequestHeader( $header ) {

		$this->requestHeaderArray[] = $header;

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