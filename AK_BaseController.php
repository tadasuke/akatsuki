<?php

class AK_BaseController {
	
	/**
	 * GETパラメータ配列
	 * @var array
	 */
	private $getParam = array();
	
	/**
	 * ポストパラメータ配列
	 */
	private $postParam = array();
	
	//---------------------------------- public ----------------------------------
	
	/**
	 * 前処理
	 */
	public function beforeRun() {
		
		$this -> getParam  = $_GET;
		$this -> postParam = $_POST;
		unset( $_GET );
		unset( $_POST );
		
	}
	
	/**
	 * 後処理
	 */
	public function afterRun() {
		;
	}
	
	//--------------------------------- protected ----------------------------------
	
	/**
	 * ゲットパラメータ取得
	 * @param string $key
	 * @return string
	 */
	protected function getGetParam( $key ) {
		return ( array_key_exists( $key, $this -> getParam ) === TRUE) ? $this -> getParam[$key] : NULL;
	}
	
	/**
	 * ポストパラメータ取得
	 * @param string $key
	 * @return string
	 */
	protected function getPostParam( $key ) {
		return ( array_key_exists( $key, $this -> postParam ) === TRUE) ? $this -> postParam[$key] : NULL; 
	}
	
	/**
	 * パラメータ取得
	 * 同一のキーがあった場合はGET優先
	 * @param string $key
	 * @return string
	 */
	protected function getParam( $key ) {
		$value = $this -> getGetParam( $key );
		$value = $value ?: $this -> getPostParam( $key );
		return $value;
	}
	
	
}