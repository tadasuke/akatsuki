<?php

class AK_BaseController {
	
	/**
	 * GETパラメータ配列
	 * @var array
	 */
	private $getParam = array();
	
	/**
	 * POSTパラメータ配列
	 */
	private $postParam = array();
	
	//---------------------------------- public ----------------------------------
	
	/**
	 * 初期処理
	 */
	final public function initial() {
		// パラメータを内部変数に保存
		$this -> getParam  = $_GET;
		$this -> postParam = $_POST;
		unset( $_GET );
		unset( $_POST );
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
	 * POSTパラメータ取得
	 * @param string $key
	 * @return string
	 */
	protected function getPostParam( $key ) {
		return ( array_key_exists( $key, $this -> postParam ) === TRUE) ? $this -> postParam[$key] : NULL; 
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
	
	
}