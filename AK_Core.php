<?php

class AK_core {

	private static $instance = NULL;
	
	/**
	 * コントローラー名
	 * @var string
	 */
	private $controllerName = NULL;
	public function getControllerName() {
		return $this -> controllerName;
	}
	
	/**
	 * アクション名
	 * @var string
	 */
	private $actionName = NULL;
	public function getActionName() {
		return $this -> actionName;
	}
	
	/**
	 * コントローラーディレクトリ
	 * @var string
	 */
	private $controllerDir = NULL;
	public function setControllerDir( $controllerDir ) {
		$this -> controllerDir = $controllerDir;
	}
	
	/**
	 * インスタンス取得
	 * @return AK_core
	 */
	public static function getInstance() {
		return self::$instance = self::$instance ?: new self();
	}
	
	//------------------------------------------------------------------------
	
	/**
	 * コンストラクタ
	 */
	private function __construct() {
		
		$this -> _parse();
		
	}
	
	//------------------------------ public --------------------------------
	
	
	/**
	 * 実行
	 */
	public function run(){
		
		// コントローラのPHPファイルをインクルード
		require_once $this -> controllerDir . '/' . $this -> controllerName . '.php';
		
		// コントローラオブジェクトを作成
		$obj = new $this -> controllerName;
		
		// 前処理実行
		if ( call_user_func( array( $obj, 'beforeRun' ) ) === FALSE ) {
			echo( 'exec beforeRun error!!' );
			exit;
		} else {
			;
		}
		
		// アクション実行
		if ( call_user_func( array( $obj, $this -> actionName ) ) === FALSE ) {
			echo( 'exec action error!!' );
			exit;
		}
		
		// 後処理
		if ( call_user_func( array( $obj, 'afterRun' ) ) === FALSE ) {
			echo( 'exec afterRun error!!' );
			exit;
		} else {
			;
		}
	
	}
	
	
	//--------------------------------- private -------------------------------
	
	/**
	 * URLを解析して呼び出すクラス、メソッドを設定
	 */
	private function _parse(){
		
		$array = explode( '?', $_SERVER['REQUEST_URI'] );
		$array = explode( '/', $array[0] );
		
		$this -> controllerName = ucfirst( $array[1] ) . 'Controller';
		$this -> actionName     = ucfirst( $array[2] ) . 'Action';
		
	}
	
	
		
		
}