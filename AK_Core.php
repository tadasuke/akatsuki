<?php

require_once 'ak_core/AK_BaseController.php';

// 基本クラス
class AK_Core {

	/**
	 * アクション実行フラグ
	 * @var boolean
	 */
	private static $execActionFlg = TRUE;
	public static function setExecActionFlg( $execActionFlg ) {
		self::$execActionFlg = $execActionFlg;
	}
	
	/**
	 * 後処理実行フラグ
	 * @var boolean
	 */
	private static $execAfterRunFlg = TRUE;
	public static function setExecAfterRunFlg( $execAfterRunFlg ) {
		self::$execAfterRunFlg = $execAfterRunFlg;
	}
	
	/**
	 * 終了処理実行フラグ
	 * @var boolean
	 */
	private static $execTerminalFlg = TRUE;
	public static function setExecTerminalFlg( $execTerminalFlg ) {
		self::$execTerminalFlg = $execTerminalFlg;
	}
	
	/**
	 * インスタンス
	 * @var AK_Core
	 */
	private static $instance = NULL;
	
	/**
	 * コントローラ名
	 * @var string
	 */
	private $controllerName = '';
	public function getControllerName() {
		return $this -> controllerName;
	}
	
	/**
	 * アクション名
	 * @var string
	 */
	private $actionName = '';
	public function getActionName() {
		return $this -> actionName;
	}
	
	/**
	 * ユーザパラメータ配列
	 * @var array
	 */
	private $userParamArray = array();
	
	/**
	 * コントローラディレクトリ
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
		
		// コントローラ読み込み
		require_once $this -> controllerDir . '/' . $this -> controllerName . '.php';
		
		// コントローラオブジェクト作成
		$obj = new $this -> controllerName;
		
		// 初期処理
		if ( call_user_func( array( $obj, 'initial' ), $this -> userParamArray ) === FALSE ) {
			echo( 'exec beforeRun error!!' );
			exit;
		} else {
			;
		}
		
		// 前処理
		if ( call_user_func( array( $obj, 'beforeRun' ) ) === FALSE ) {
			echo( 'exec beforeRun error!!' );
			exit;
		} else {
			;
		}
		
		// 処理実行
		if ( self::$execActionFlg === TRUE ) {
			if ( call_user_func( array( $obj, $this -> actionName ) ) === FALSE ) {
				echo( 'exec action error!!' );
				exit;
			}
		} else {
			;
		}
		
		// 後処理
		if ( self::$execAfterRunFlg === TRUE ) {
			if ( call_user_func( array( $obj, 'afterRun' ) ) === FALSE ) {
				echo( 'exec afterRun error!!' );
				exit;
			} else {
				;
			}
		} else {
			;
		}
		
		// 終了処理
		if ( self::$execTerminalFlg === TRUE ) {
			if ( call_user_func( array( $obj, 'terminal' ) ) === FALSE ) {
				echo( 'exec terminal error!!' );
				exit;
			} else {
				;
			}
		} else {
			;
		}
	
	}
	
	
	//--------------------------------- private -------------------------------
	
	/**
	 * URLを元に呼び出すコントローラとアクションを設定
	 */
	private function _parse(){
		
		$array = explode( '?', $_SERVER['REQUEST_URI'] );
		$array = explode( '/', $array[0] );
		
		// コントローラ名設定
		$this -> controllerName = ucfirst( $array[1] ) . 'Controller';
		
		// アクション名設定
		$actionArray = explode( '-', $array[2] );
		$this -> actionName = $actionArray[0];
		for ( $i = 1; $i < count( $actionArray ); $i++ ) {
			$this -> actionName .= ucfirst( $actionArray[$i] );
		}
		$this -> actionName .= 'Action';
		
		$i = 0;
		foreach ( $array as $data ) {
			$i++;
			if ( $i <= 3 ) {
				continue;
			} else {
				;
			}
			$this -> userParamArray[] = $data;
		}
		
	}
		
}
