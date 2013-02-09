<?php

class AK_BaseController {
	
	const RESPONSE_TYPE_JSON = 1;
	const RESPONSE_TYPE_JSONP = 2;
	
	/**
	 * GETパラメータ配列
	 * @var array
	 */
	private $getParam = array();
	
	/**
	 * POSTパラメータ配列
	 * @var array
	 */
	private $postParam = array();
	
	/**
	 * ユーザパラメータ配列
	 * @var array
	 */
	private $userParam = array();
	
	/**
	 * コールバック名
	 * @var string
	 */
	private $callback = NULL;
	protected function setCallback( $callback ) {
		$this -> callback = $callback;
	}
	
	/**
	 * レスポンスパラメータ
	 * @var array
	 */
	private $responseParam = array();
	protected function setResponseParam( array $array ) {
		$this -> responseParam = $array;
	}
	protected function getResponseParam() {
		return $this -> responseParam;
	}
	
	/**
	 * レスポンスタイプ
	 * @var int
	 */
	private $responseType = self::RESPONSE_TYPE_JSON;
	protected function setResponseType( $responseType ) {
		$this -> responseType = $responseType;
	}
	
	//---------------------------------- public ----------------------------------
	
	/**
	 * 初期処理
	 */
	final public function initial( $userParamArray ) {
		// パラメータを内部変数に保存
		$this -> getParam  = $_GET;
		$this -> postParam = $_POST;
		$this -> userParam = $userParamArray;
	}
	
	/**
	 * 前処理
	 */
	public function beforeRun() {
		;
	}
	
	/**
	 * 後処理
	 */
	public function afterRun() {
		;
	}
	
	/**
	 * 終了処理
	 */
	final public function terminal() {
		
		// レスポンスパラメータが存在した場合
		if ( count( $this -> responseParam ) > 0 ) {
			// レスポンスタイプがJSON形式の場合
			if ( $this -> responseType == self::RESPONSE_TYPE_JSON ) {
				$response = json_encode( $this -> responseParam );
				header( "X-Content-Type-Options: nosniff" );
				header( "Content-type: application/json; charset=UTF-8" );
				echo( $response );
			// レスポンスタイプがJSONP形式の場合
			} else if ( $this -> responseType == self::RESPONSE_TYPE_JSONP ) {
				$response = json_encode( $this -> responseParam );
				echo( $this -> callback . '(' . $response . ')' );
				header( "X-Content-Type-Options: nosniff" );
				header( "Content-type: application/javascript; charset=UTF-8" );
			} else {
				;
			}
		} else {
			;
		}
		
	}
	
	//--------------------------------- protected ----------------------------------
	
	/**
	 * GETパラメータ取得
	 * @param string $key
	 * @return string
	 */
	protected function getGetParam( $key ) {
		return ( array_key_exists( $key, $this -> getParam ) === TRUE) ? $this -> getParam[$key] : NULL;
	}
	
	/**
	 * 全GETパラメータ取得
	 */
	protected function getAllGetParam() {
		return $this -> getParam;
	}
	
	/**
	 * POSTパラメータ取得
	 * @param string $key
	 * @return string
	 */
	protected function getPostParam( $key ) {
		return ( array_key_exists( $key, $this -> postParam ) === TRUE) ? $this -> postParam[$key] : NULL; 
	}
	
	/**
	 * 全POSTパラメータ取得
	 * @return array
	 */
	protected function getAllPostParam() {
		return $this -> postParam;
	}
	
	/**
	 * パラメータ取得
	 * 同一のキー名が存在する場合はPOSTを優先
	 * @param string $key
	 * @return string
	 */
	protected function getParam( $key ) {
		$value = $this -> getGetParam( $key );
		$value = $value ?: $this -> getPostParam( $key );
		return $value;
	}
	
	/**
	 * 全パラメータ取得
	 * @return array
	 */
	protected function getAllParam() {
		$postParamArray = $this -> getAllPostParam();
		$getParamArray  = $this -> getAllGetParam();
		foreach ( $getParamArray as $key => $value ) {
			if ( !array_key_exists( $key, $postParamArray ) ) $postParamArray[$key] = $value;
		}
		return $postParamArray;
	}
	
	/**
	 * 全ユーザパラメータ取得
	 * @return array
	 */
	protected function getAllUserParam() {
		return $this -> userParam;
	}
	
	
	/**
	 * レスポンスパラメータ数返却
	 */
	protected function getResponseParamCount() {
		return count( $this -> responseParam );
	}
	
	
	/**
	 * レスポンスパラメータ追加
	 * @param string $key
	 * @param mixed $value
	 */
	protected function addResponseParam( $key, $value ) {
		$this -> responseParam[$key] = $value;
	}
	
}