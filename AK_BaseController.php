<?php

class AK_BaseController {
	
	const RESPONSE_TYPE_JSON = 1;
	
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
	 * レスポンスパラメータ
	 * @var array
	 */
	private $responseParam = array();
	public function setResponseParam( array $array ) {
		$this -> responseParam = $array;
	}
	
	/**
	 * レスポンスタイプ
	 * @var int
	 */
	private $responseType = self::RESPONSE_TYPE_JSON;
	public function setResponseType( $responseType ) {
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
		//unset( $_GET );
		//unset( $_POST );
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
			// レスポンスがJSON形式の場合
			if ( $this -> responseType == self::RESPONSE_TYPE_JSON ) {
				$response = json_encode( $this -> responseParam );
				header( "X-Content-Type-Options: nosniff" );
				header( "Content-type: application/json" );
				echo( $response );
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
	 * JSON形式でレスポンス
	 */
	protected function returnJsonResponse( array $returnResponse ) {
	
		// JSON設定
		$json = json_encode( $returnResponse );
	
		header( "Content-type:text/plain" );
		echo( $json );
	
	}
	
}