<?php

abstract class AK_BaseController {
	
	const RESPONSE_TYPE_JSON    = 1;
	const RESPONSE_TYPE_JSONP   = 2;
	const RESPONSE_TYPE_DATA    = 3;
	const RESPONSE_TYPE_MSGPACK = 4;
	
	const DEFAULT_JSON_CONTENT_TYPE    = 'Content-type: application/json; charset=UTF-8';
	const DEFAULT_JSONP_CONTENT_TYPE   = 'Content-type: application/javascript; charset=UTF-8';
	const DEFALUT_DATA_CONTENT_TYPE    = 'Content-type: image/*';
	const DEFAULT_MSGPACK_CONTENT_TYPE = 'Content-type: application/x-msgpack; charset=x-user-defined';
	
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
	 * リクエストボディパラメータ配列
	 * @var array
	 */
	private $requestBodyParam = array();
	
	/**
	 * ユーザパラメータ配列
	 * @var array
	 */
	private $userParam = array();
	
	/**
	 * リクエストパラメータ配列
	 * @var array
	 */
	private $requestParamArray = NULL;
	public function getRequestParam( $key = NULL ) {
		if ( is_null( $this -> requestParamArray ) === TRUE ) {
			$this -> setRequestParamArray();
		} else {
			;
		}
		
		if ( is_null( $key ) === TRUE ) {
			$param = $this -> requestParamArray;
		} else {
			if ( array_key_exists( $key, $this -> requestParamArray ) === TRUE ) {
				$param = $this -> requestParamArray[$key];
			} else {
				$param = NULL;
			}
		}
		
		return $param;
		
	}
	
	
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
	protected function setResponseParam( $array ) {
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
	 * レスポンスを返したUNIXタイム
	 * @var int
	 */
	private $responseTime = NULL;
	protected function setResponseTime( $responseTime ) {
		$this -> responseTime = $responseTime;
	}
	protected function getResponseTime() {
		return $this -> responseTime;
	}
	
	/**
	 * レスポンス返却フラグ
	 * @var boolean
	 */
	private $responseFlg = TRUE;
	public function setResponseFlg( $responseFlg ) {
		$this -> responseFlg = $responseFlg;
	}
	public function getResponseFlg() {
		return $this -> responseFlg;
	}
	
	/**
	 * JSONエンコードフラグ
	 * @var boolean
	 */
	private $jsonEncodeFlg = TRUE;
	protected function setJsonEncodeFlg( $jsonEncodeFlg ) {
		$this -> jsonEncodeFlg = $jsonEncodeFlg;
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
		$this -> setRequestBodyParam();
		// callbackパラメータが設定されていた場合
		$callback = $this -> getRequestParam( 'callback' );
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
					if ( $this -> jsonEncodeFlg === TRUE ) {
						$response = json_encode( $this -> responseParam );
					} else {
						$response = $this -> responseParam;
					}
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
				// レスポンスタイプがMessagePackの場合
				} else if ( $this -> responseType == self::RESPONSE_TYPE_MSGPACK ) {
					$response = msgpack_serialize( $this -> responseParam );
					$contentType = $this -> contentType ?: self::DEFAULT_MSGPACK_CONTENT_TYPE;
					header( 'X-Content-Type-Options: nosniff' );
					header( $contentType );
					echo( $response );
				} else {
					;
				}
				$this -> setResponseTime( microtime( TRUE ) );
			} else {
				;
			}
			
		} else {
			;
		}
		
	}
	
	
	/**
	 * レスポンス返却後処理
	 */
	public function afterResponse() {
		;
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
	
	
	//-------------------------------------------- private function ---------------------------------------
	
	/**
	 * リクエストボディパラメータ配列設定
	 */
	private function setRequestBodyParam() {
		
		$requestParamArray = array();
		
		$requestBody = file_get_contents( 'php://input' );
		
		AK_Log::getLogClass() -> log( AK_Log::INFO, __METHOD__, __LINE__, Zend_Debug::dump( $requestBody, '', FALSE ) );
			
		if ( strlen( $requestBody ) > 0 ) {
		
			$array = json_decode( $requestBody, TRUE );
			if ( is_null( $array ) === FALSE ) {
				foreach ( $array as $key => $value ) {
					$requestParamArray[$key] = $value;
				}
			} else {
				;
			}
		} else {
			;
		}
		
		$this -> requestBodyParam = $requestParamArray;
		
	}
	
	
	/**
	 * リクエストパラメータ設定
	 */
	private function setRequestParamArray() {
		
		$requestParamArray = array();
		
		// GET対応
		if ( AK_Core::getGetParamValidFlg() === TRUE ) {
			$requestParamArray = $this -> getParam;
		} else {
			;
		}
		
		// POST対応
		if ( AK_Core::getPostParamValidFlg() === TRUE ) {
			foreach ( $this -> postParam as $key => $value ) {
				$requestParamArray[$key] = $value;
			}
		} else {
			;
		}
		
		// リクエストボディ対応
		if ( AK_Core::getRequestBodyParamValidFlg() === TRUE ) {
			foreach ( $this -> requestBodyParam as $key => $value ) {
				$requestParamArray[$key] = $value;
			}
		} else {
			;
		}
		
		// ユーザパラメータ対応
		if ( AK_Core::getUserParamValidFlg() === TRUE ) {
			foreach ( $this -> userParam as $key => $value ) {
				$requestParamArray[$key] = $value;
			}
		} else {
			;
		}
		
		$this -> requestParamArray = $requestParamArray;
		
	}
	
}