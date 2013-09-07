<?php

class AK_BaseController {
	
	const RESPONSE_TYPE_JSON  = 1;
	const RESPONSE_TYPE_JSONP = 2;
	const RESPONSE_TYPE_DATA  = 3;
	
	const DEFAULT_JSON_CONTENT_TYPE  = 'Content-type: application/json; charset=UTF-8';
	const DEFAULT_JSONP_CONTENT_TYPE = 'Content-type: application/javascript; charset=UTF-8';
	const DEFALUT_DATA_CONTENT_TYPE  = 'Content-type: image/*';
	
	/**
	 * コントローラ名
	 * @var string
	 */
	private $controllerName = NULL;
	public function setControllerName( $controllerName ) {
		$this -> controllerName = $controllerName;
	}
	public function getControllerName() {
		return $this -> controllerName;
	}
	
	/**
	 * アクション名
	 * @var string
	 */
	private $actionName = NULL;
	public function setActionName( $actionName ) {
		$this -> actionName = $actionName;
	}
	public function getActionName() {
		return $this -> actionName;
	}
	
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
	protected function getResponseType() {
		return $this -> responseType;
	}
	
	/**
	 * コンテントタイプ
	 * @var string
	 */
	private $contentType = NULL;
	protected function setContentType( $contentType ) {
		$this -> contentType = $contentType;
	}
	protected function getContentType() {
		return $this -> contentType;
	}
	
	/**
	 * レスポンス返却フラグ
	 * @var boolean
	 */
	private $responseFlg = TRUE;
	protected function setResponseFlg( $responseFlg ) {
		$this -> responseFlg = $responseFlg;
	}
	protected function getResponseFlg() {
		return $this -> responseFlg;
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
		// callbackパラメータが設定されていた場合
		$callback = $this -> getGetAndPostParam( 'callback' );
		if ( strlen( $callback ) > 0 ) {
			$this -> setResponseType( self::RESPONSE_TYPE_JSONP );
			$this -> setCallback( $callback );
		} else {
			;
		}
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
		
		if ( $this -> responseFlg === TRUE ) {
		
			// レスポンスパラメータが存在した場合
			if ( count( $this -> responseParam ) > 0 ) {
				// レスポンスタイプがJSON形式の場合
				if ( $this -> responseType == self::RESPONSE_TYPE_JSON ) {
					$response = json_encode( $this -> responseParam );
					$contentType = $this -> contentType ?: self::DEFAULT_JSON_CONTENT_TYPE;
					header( 'X-Content-Type-Options: nosniff' );
					header( $contentType );
					echo( $response );
				// レスポンスタイプがJSONP形式の場合
				} else if ( $this -> responseType == self::RESPONSE_TYPE_JSONP ) {
					$response = json_encode( $this -> responseParam );
					$contentType = $this -> contentType ?: self::DEFAULT_JSONP_CONTENT_TYPE;
					header( 'X-Content-Type-Options: nosniff' );
					header( $contentType );
					echo( $this -> callback . '(' . $response . ')' );
				// レスポンスタイプがDATAの場合
				} else if ( $this -> responseType == self::RESPONSE_TYPE_DATA ) {
					$response = $this -> responseParam[0];
					$contentType = $this -> contentType ?: self::DEFALUT_DATA_CONTENT_TYPE;
					header( $contentType );
					echo( $response );
				} else {
					;
				}
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
	protected function getGetAndPostParam( $key ) {
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